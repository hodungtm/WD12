<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function show(Order $order)
    {
        $this->authorizeOrder($order);

        $items = $order->orderItems;

        return view('client.order.show', compact('order', 'items'));
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'cancel_reason' => 'required|string|max:255',
        ]);

        $order = Order::with('orderItems.productVariant')->findOrFail($request->order_id);

        // Kiểm tra quyền
        $this->authorizeOrder($order);

        // ✅ Cho phép hủy khi trạng thái là "Đang chờ" hoặc "Xác nhận đơn hàng"
        if (!in_array($order->status, ['Đang chờ', 'Xác nhận đơn hàng'])) {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        // ✅ Trả lại số lượng sản phẩm
        foreach ($order->orderItems as $item) {
            $variant = $item->productVariant;
            if ($variant) {
                $variant->increment('quantity', $item->quantity);
            }
        }

        // ✅ Hoàn lại lượt dùng mã giảm giá
        if ($order->discount_code) {
            $discount = Discount::where('code', $order->discount_code)->first();
            if ($discount) {
                $discount->increment('max_usage');
            }
        }

        // ✅ Cập nhật trạng thái & lý do
        $order->update([
            'status' => 'Đã hủy',
            'cancel_reason' => $request->cancel_reason,
        ]);

        return back()->with('success', 'Đơn hàng đã được hủy thành công.');
    }



    protected function authorizeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
    }
}

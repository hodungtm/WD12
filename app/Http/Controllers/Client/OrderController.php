<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderCancelledMail;
use App\Models\Discount;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', '');

        $query = Order::where('user_id', Auth::id());
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10);

        return view('client.order.index', compact('orders', 'status'));
    }

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
        'cancel_reason' => 'required|string|max:255'
    ], [
        'order_id.required' => 'Trường order id là bắt buộc.',
        'cancel_reason.required' => 'Vui lòng nhập lý do hủy đơn.',
        'cancel_reason.max' => 'Lý do hủy đơn không được vượt quá 255 ký tự.'
    ]);

    $order = Order::with('orderItems.productVariant')
        ->where('user_id', Auth::id())
        ->findOrFail($request->order_id);

    // ✅ Cho phép hủy ở cả hai trạng thái
    if (!in_array($order->status, ['Đang chờ', 'Xác nhận đơn'])) {
        return back()->with('error', 'Chỉ có thể hủy đơn hàng ở trạng thái Đang chờ hoặc Đã xác nhận.');
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

    // ✅ (Tuỳ chọn) Gửi email thông báo cho khách
    Mail::to($order->receiver_email)->send(new OrderCancelledMail($order));

    return back()->with('success', 'Đơn hàng của bạn đã được hủy thành công.');
}



    protected function authorizeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
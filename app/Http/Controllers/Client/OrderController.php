<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function cancel(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->status !== 'Đang chờ') {
            return back()->with('error', 'Không thể hủy đơn hàng này.');
        }
foreach ($order->orderItems as $item) {
        $variant = $item->productVariant;
        $variant->increment('quantity', $item->quantity);
    }
        $order->update(['status' => 'Đã hủy']);

        return back()->with('success', 'Đơn hàng đã được hủy.');
    }

    protected function authorizeOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_items;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;      

class OrderController extends Controller
{
public function index()
{
    $orders = Order::with(['user', 'orderItems.product'])->latest()->get();
    return view('admin.orders.index', compact('orders'));
}

public function show($id)
{
    $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);
    return view('admin.orders.show', compact('order'));
}

public function create()
{
    $users = User::all();
    $products = Product::with([
        'variants' => function($query) {
            $query->select('id', 'product_id', 'size_id', 'color_id', 'quantity', 'variant_price', 'variant_sale_price');
        },
        'variants.size',
        'variants.color'
    ])->get();

    return view('admin.orders.create', compact('users', 'products'));
}


    /**
     * Xử lý lưu đơn hàng mới vào cơ sở dữ liệu.
     * Bao gồm tạo đơn hàng chính và các mục sản phẩm trong đơn hàng.
     */
   public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'payment_method' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.variant_id' => 'required|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::transaction(function () use ($request) {
            // Tạo mã đơn hàng duy nhất
            // Bạn có thể tùy chỉnh định dạng mã đơn hàng ở đây
            $orderCode = 'DH' . date('Ymd') . '-' . Str::random(6); // Ví dụ: DH20250531-ABCDEF

            // Đảm bảo mã đơn hàng là duy nhất
            while (Order::where('order_code', $orderCode)->exists()) {
                $orderCode = 'DH' . date('Ymd') . '-' . Str::random(6);
            }

            // Tạo đơn hàng chính
            $order = Order::create([
                'user_id' => $request->user_id,
                'order_date' => $request->order_date,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'order_code' => $orderCode, // <--- Gán mã đơn hàng vào đây
                // Các trường khác của bảng orders nếu có (ví dụ: total_amount)
            ]);

            // Lặp qua từng sản phẩm trong đơn hàng để tạo các mục đơn hàng (order_items)
            foreach ($request->products as $item) {
                $variant = ProductVariant::find($item['variant_id']);

                if ($variant) {
                    $priceToStore = ($variant->variant_sale_price > 0) ? $variant->variant_sale_price : $variant->variant_price;

                    Order_items::create([ // Đảm bảo đây là tên model đúng của bạn (Order_items hoặc OrderItem)
                        'order_id'      => $order->id,
                        'product_id'    => $variant->product_id,
                        'quantity'      => $item['quantity'],
                        'price'         => $priceToStore,
                        'total_price'   => $item['quantity'] * $priceToStore,
                    ]);
                }
            }
        });

        return redirect()->route('admin.orders.index')->with('success', 'Tạo đơn hàng thành công!');
    }




public function edit($id)
{
    $order = Order::findOrFail($id);
    return view('admin.orders.edit', compact('order'));
}
public function update(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $order->update([
        'status' => $request->status,
        'payment_status' => $request->payment_status,
    ]);
    return redirect()->route('admin.orders.index')->with('success', 'Cập nhật đơn hàng thành công');
}

public function destroy($id)
{
    // Tìm đơn hàng
    $order = Order::findOrFail($id);
    
    // Xóa tất cả các mục đơn hàng (order_items) liên quan trước
    Order_items::where('order_id', $id)->delete();
    
    // Sau đó xóa đơn hàng chính
    $order->delete();
    
    return redirect()->route('admin.orders.index')
        ->with('success', 'Đơn hàng đã được xóa thành công!');
}


}
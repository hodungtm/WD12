<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Order_items;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Receiver;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;      

class OrderController extends Controller
{
public function index(Request $request) // Thêm Request $request vào đây
    {
        $query = Order::with([
            'user',
            'receiver',
            'shippingMethod',
            'discount',
            'orderItems'
        ]);

        // Thêm logic tìm kiếm theo mã đơn hàng
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('order_code', 'LIKE', '%' . $search . '%');
        }

        $orders = $query->latest()->get(); // Sắp xếp theo ngày mới nhất

        return view('admin.orders.index', compact('orders'));
    }

public function show($id)
{
    $order = Order::with(['user', 'orderItems.product','shippingMethod',])->findOrFail($id);
    return view('admin.orders.show', compact('order'));
}

public function create()
{
    $users = User::all();
    $receivers = Receiver::all();
    $products = Product::with([
        'variants' => function ($query) {
            $query->select('id', 'product_id', 'size_id', 'color_id', 'quantity', 'variant_price', 'variant_sale_price');
        },
        'variants.size',
        'variants.color'
    ])->get();

    $shippingMethods = ShippingMethod::all();

    return view('admin.orders.create', compact('users','receivers', 'products', 'shippingMethods'));
}



    /**
     * Xử lý lưu đơn hàng mới vào cơ sở dữ liệu.
     * Bao gồm tạo đơn hàng chính và các mục sản phẩm trong đơn hàng.
     */
   public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'receiver_id' => 'nullable|exists:receivers,id', // Đã sửa tên trường trong form, có thể là required tùy logic của bạn
            'order_date' => 'required|date',
            'payment_method' => 'required|string',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'discount_id' => 'nullable|exists:discounts,id', // Thêm validation cho discount_id
            'products' => 'required|array|min:1',
            'products.*.variant_id' => 'required|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // Tạo mã đơn hàng duy nhất
            $orderCode = 'DH' . date('Ymd') . '-' . Str::upper(Str::random(6));
            while (Order::where('order_code', $orderCode)->exists()) {
                $orderCode = 'DH' . date('Ymd') . '-' . Str::upper(Str::random(6));
            }

            // Tạo đơn hàng chính (chưa có final_amount, total_price, discount_amount)
            // Các trường này sẽ được cập nhật sau khi tính toán xong
            $order = Order::create([
                'user_id'            => $request->user_id,
                'receiver_id'        => $request->receiver_id,
                'order_date'         => $request->order_date,
                'payment_method'     => $request->payment_method,
                'payment_status'     => 'Chờ thanh toán',
                'status'             => 'Đang chờ',
                'note'               => $request->note,
                'shipping_method_id' => $request->shipping_method_id,
                'discount_id'        => $request->discount_id, // Lưu discount_id nếu có
                'order_code'         => $orderCode,
                // 'total_price', 'discount_amount', 'final_amount' sẽ được cập nhật sau
            ]);

            $subtotalAmount = 0; // Tổng tiền sản phẩm ban đầu
            $discountAmount = 0; // Số tiền giảm giá áp dụng cho đơn hàng

            foreach ($request->products as $item) {
                $variant = ProductVariant::with('product')->find($item['variant_id']);
                if (!$variant) continue;

                $product        = $variant->product;
                $basePrice      = $variant->variant_price;
                $discountPrice  = $variant->variant_sale_price > 0 ? $variant->variant_sale_price : null;
                $finalPrice     = $discountPrice ?? $basePrice;
                $quantity       = $item['quantity'];
                $totalPrice     = $finalPrice * $quantity;

                // Lưu vào bảng order_items
                Order_items::create([
                    'order_id'       => $order->id,
                    'product_id'     => $variant->product_id,
                    'discount_id'    => $product->discount_id ?? null, // Nếu sản phẩm có discount riêng
                    'quantity'       => $quantity,
                    'price'          => $basePrice,
                    'discount_price' => $discountPrice,
                    'final_price'    => $finalPrice,
                    'total_price'    => $totalPrice,
                ]);

                $subtotalAmount += $totalPrice; // Cộng vào tổng tiền sản phẩm
            }

            // Lấy phí vận chuyển từ model ShippingMethod
            $shippingMethod = ShippingMethod::find($request->shipping_method_id);
            $shippingFee = $shippingMethod->fee ?? 0;

            // Lấy số tiền giảm giá từ discount_id của đơn hàng (nếu có)
            if ($order->discount_id) {
                $discount = Discount::find($order->discount_id);
                if ($discount) {
                    // Đây là nơi bạn cần logic để tính toán số tiền giảm giá thực tế
                    // Ví dụ: nếu discount là % thì phải tính dựa trên subtotalAmount
                    // Nếu là số tiền cố định thì lấy fee/amount của discount
                    // Giả sử discount->amount là số tiền giảm giá cố định
                    $discountAmount = $discount->amount ?? 0; // Hoặc logic phức tạp hơn
                }
            }


            // Tính toán tổng tiền cuối cùng (final_amount)
            $finalAmount = $subtotalAmount + $shippingFee - $discountAmount;

            // Cập nhật các trường tổng tiền vào đơn hàng
            $order->update([
                'total_price'     => $subtotalAmount, // Tổng tiền sản phẩm
                'discount_amount' => $discountAmount, // Số tiền giảm giá đã áp dụng
                'final_amount'    => $finalAmount,    // Tổng tiền cuối cùng
            ]);
        });

        return redirect()->route('admin.orders.index')->with('success', 'Tạo đơn hàng thành công!');
    }

    // Các phương thức edit, update, destroy giữ nguyên
    public function edit($id)
    {
        $order = Order::with(['orderItems.product', 'user', 'shippingMethod', 'discount', 'receiver'])->findOrFail($id); // Eager load discount và receiver
        $users = User::all();
        $receivers = Receiver::all();
        $products = Product::with([
            'variants' => function ($query) {
                $query->select('id', 'product_id', 'size_id', 'color_id', 'quantity', 'variant_price', 'variant_sale_price');
            },
            'variants.size',
            'variants.color'
        ])->get();
        $shippingMethods = ShippingMethod::all();
        $discounts = Discount::all(); // Lấy tất cả các discount để hiển thị trong form edit

        return view('admin.orders.edit', compact('order', 'users', 'receivers', 'products', 'shippingMethods', 'discounts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'receiver_id' => 'nullable|exists:receivers,id',
            'order_date' => 'required|date',
            'payment_method' => 'required|string',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'discount_id' => 'nullable|exists:discounts,id',
            // Nếu bạn muốn cập nhật sản phẩm trong đơn hàng, bạn cần thêm validation cho 'products' ở đây
        ]);

        $order = Order::findOrFail($id);

        DB::transaction(function () use ($request, $order) {
            // Cập nhật các thông tin cơ bản của đơn hàng
            $order->update([
                'user_id' => $request->user_id,
                'receiver_id' => $request->receiver_id,
                'order_date' => $request->order_date,
                'payment_method' => $request->payment_method,
                'shipping_method_id' => $request->shipping_method_id,
                'discount_id' => $request->discount_id,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'note' => $request->note, // Đảm bảo trường note cũng được cập nhật nếu có
            ]);

            // Logic cập nhật sản phẩm trong order_items (nếu bạn cho phép cập nhật)
            // Phần này sẽ phức tạp hơn, có thể cần xóa các order_items cũ và tạo lại
            // hoặc so sánh và cập nhật/xóa từng mục. Tôi sẽ bỏ qua phần này ở đây
            // vì nó không phải trọng tâm chính, nhưng bạn nên xem xét nó.

            // Tính toán lại final_amount và discount_amount sau khi cập nhật
            $subtotalAmount = 0;
            foreach ($order->orderItems as $item) { // Duyệt qua orderItems hiện có để tính tổng tiền sản phẩm
                $subtotalAmount += $item->total_price;
            }

            $shippingMethod = ShippingMethod::find($order->shipping_method_id);
            $shippingFee = $shippingMethod->fee ?? 0;

            $discountAmount = 0;
            if ($order->discount_id) {
                $discount = Discount::find($order->discount_id);
                if ($discount) {
                    $discountAmount = $discount->amount ?? 0;
                }
            }

            $finalAmount = $subtotalAmount + $shippingFee - $discountAmount;

            $order->update([
                'total_price' => $subtotalAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ]);
        });


        return redirect()->route('admin.orders.index')
            ->with('success', 'Cập nhật đơn hàng thành công!');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        try {
            DB::transaction(function () use ($order) {
                // Xóa toàn bộ mục đơn hàng
                Order_items::where('order_id', $order->id)->delete();

                // Xóa đơn hàng chính
                $order->delete();
            });

            return redirect()->route('admin.orders.index')
                ->with('success', 'Đơn hàng đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Có lỗi xảy ra khi xóa đơn hàng: ' . $e->getMessage());
        }
    }
    }
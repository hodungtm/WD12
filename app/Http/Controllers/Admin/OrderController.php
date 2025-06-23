<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Order_items; // Đổi tên thành OrderItem nếu đúng chuẩn PSR-4
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Receiver;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Models\ArchivedOrderItem; // Thêm Model ArchivedOrderItem
use App\Models\Size; // Thêm Model Size
use App\Models\Color; // Thêm Model Color
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
     public function index(Request $request)
{
    $query = Order::with([
        'user',
        'shippingMethod',
        'orderItems',
    ]);

    // Tìm kiếm theo mã đơn hàng
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where('order_code', 'LIKE', '%' . $search . '%');
    }

    // Lấy danh sách đơn hàng mới nhất
    $orders = $query->latest()->get();

    return view('admin.orders.index', compact('orders'));
}

  public function show($id)
{
    $order = Order::with([
        'user',
        'shippingMethod',
        'orderItems.product',
        'orderItems.productVariant.attributeValues.attribute',
    ])->findOrFail($id);

    return view('admin.orders.show', compact('order'));
}
   public function create()
{
    $users = User::all();

    // Eager load variants + size & color của từng variant
    $products = Product::with([
        'variants.size',
        'variants.color',
    ])->get();

    $shippingMethods = ShippingMethod::all();

    $discounts = Discount::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->where(function ($query) {
            $query->whereNull('max_usage')
                ->orWhere('max_usage', '>', 0);
        })
        ->get();

    return view('admin.orders.create', compact('users', 'products', 'shippingMethods', 'discounts'));
}


    /**
     * Xử lý lưu đơn hàng mới vào cơ sở dữ liệu.
     * Bao gồm tạo đơn hàng chính và các mục sản phẩm trong đơn hàng.
     */
public function store(Request $request)
{
    $request->validate([
        'user_id'            => 'required|exists:users,id',
        'receiver_name'      => 'required|string|max:100',
        'receiver_phone'     => 'required|string|max:20',
        'receiver_email'     => 'nullable|email',
        'receiver_address'   => 'required|string|max:255',
        'order_date'         => 'required|date',
        'payment_method'     => 'required|string|max:50',
        'shipping_method_id' => 'required|exists:shipping_methods,id',
        'discount_code'      => 'nullable|string|max:50',

        'products'                  => 'required|array|min:1',
        'products.*.variant_id'     => 'required|exists:product_variants,id',
        'products.*.quantity'       => 'required|integer|min:1',

        'note' => 'nullable|string|max:1000',
    ]);

    DB::transaction(function () use ($request) {
        // Tạo mã đơn hàng duy nhất
        do {
            $orderCode = 'DH' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_code', $orderCode)->exists());

        // Tạo đơn hàng ban đầu
        $order = Order::create([
            'user_id'            => $request->user_id,
            'order_date'         => $request->order_date,
            'payment_method'     => $request->payment_method,
            'payment_status'     => 'Chờ thanh toán',
            'status'             => 'Đang chờ',
            'note'               => $request->note,
            'shipping_method_id' => $request->shipping_method_id,
            'order_code'         => $orderCode,

            // Snapshot người nhận
            'receiver_name'    => $request->receiver_name,
            'receiver_phone'   => $request->receiver_phone,
            'receiver_email'   => $request->receiver_email,
            'receiver_address' => $request->receiver_address,
        ]);

        $subtotal = 0;

        foreach ($request->products as $item) {
            $variant =ProductVariant::with(['product.brand', 'size', 'color'])->findOrFail($item['variant_id']);
            $product = $variant->product;

            $quantity = $item['quantity'];
            $price = $variant->price;
            $totalPrice = $price * $quantity;

            // Snapshot thông tin sản phẩm
          Order_items::create([
                'order_id'           => $order->id,
                'product_id'         => $product->id,
                'product_variant_id' => $variant->id,
                'quantity'           => $quantity,
                'price'              => $price,
                'total_price'        => $totalPrice,

                // Snapshot bổ sung
                'product_name'    => $product->name,
                'variant_name'    => 'Size ' . ($variant->size->name ?? '-') . ' - Màu ' . ($variant->color->name ?? '-'),
                'product_image'   => $product->image, // giả sử cột 'image' chứa tên file ảnh
                'sku'             => $variant->sku ?? '',
                'brand_name'      => $product->brand->name ?? '',
            ]);

            $subtotal += $totalPrice;
        }

        // Tính phí vận chuyển
        $shippingFee = ShippingMethod::find($request->shipping_method_id)->fee ?? 0;

        // Tính giảm giá nếu có mã hợp lệ
        $discountAmount = 0;
        $appliedDiscountCode = null;

        if ($request->filled('discount_code')) {
            $discount = Discount::where('code', $request->discount_code)->first();

            if ($discount && $discount->start_date <= now() && $discount->end_date >= now()) {
                if (!$discount->min_order_amount || $subtotal >= $discount->min_order_amount) {
                    if ($discount->discount_percent > 0) {
                        $discountAmount = $subtotal * ($discount->discount_percent / 100);
                    } else {
                        $discountAmount = $discount->discount_amount;
                    }

                    if ($discount->max_discount_amount && $discountAmount > $discount->max_discount_amount) {
                        $discountAmount = $discount->max_discount_amount;
                    }

                    if ($discount->max_usage !== null && $discount->max_usage > 0) {
                        $discount->decrement('max_usage');
                    }

                    $appliedDiscountCode = $discount->code;
                }
            }
        }

        $finalAmount = max($subtotal + $shippingFee - $discountAmount, 0);

        // Cập nhật lại đơn hàng
        $order->update([
            'total_price'     => $subtotal,
            'discount_code'   => $appliedDiscountCode,
            'discount_amount' => $discountAmount,
            'final_amount'    => $finalAmount,
        ]);
    });

    return redirect()->route('admin.orders.index')->with('success', 'Tạo đơn hàng thành công!');
}

    public function edit($id)
    {
        $order = Order::with([
            'user',
            'receiver',
            'shippingMethod',
            'discount',
            'orderItems.product',
            'orderItems.productVariant.attributeValues.attribute', // Load thuộc tính qua attributeValues
            'archivedOrderItems.product',
            'archivedOrderItems.productVariant.attributeValues.attribute', // Load thuộc tính qua attributeValues cho archived items
        ])->findOrFail($id);

        $users = User::all();
        $receivers = Receiver::all();
        $products = Product::with([
            'variants' => function ($query) {
                $query->with('attributeValues.attribute');
            }
        ])->get();
        $shippingMethods = ShippingMethod::all();
        $discounts = Discount::all();

        return view('admin.orders.edit', compact('order', 'users', 'receivers', 'products', 'shippingMethods', 'discounts'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $originalStatus = $order->status;
        $originalPaymentStatus = $order->payment_status;

        $rules = [
            'status' => [
                'required',
                'string',
                Rule::in(['Đang chờ', 'Đang xử lý', 'Đang giao hàng', 'Đã giao hàng', 'Hoàn thành', 'Đã hủy']),
            ],
            'payment_status' => [
                'required',
                'string',
                Rule::in(['Chờ thanh toán', 'Đã thanh toán']),
            ],
            'note' => 'nullable|string|max:1000',
        ];

        $validatedData = $request->validate($rules);

        if ($validatedData['payment_status'] === 'Chờ thanh toán' &&
            in_array($validatedData['status'], ['Đang giao hàng', 'Hoàn thành'])) {
            return redirect()->back()->withErrors([
                'status' => 'Không thể chuyển trạng thái đơn hàng sang "' . $validatedData['status'] . '" khi trạng thái thanh toán là "Chờ thanh toán".'
            ])->withInput();
        }
        if ($originalPaymentStatus === 'Đã thanh toán' && $validatedData['payment_status'] === 'Chờ thanh toán') {
            return redirect()->back()->withErrors([
                'payment_status' => 'Không thể chuyển trạng thái thanh toán từ "Đã thanh toán" về "Chờ thanh toán".'
            ])->withInput();
        }
        if ($originalStatus === 'Đã hủy' && $validatedData['status'] !== 'Đã hủy') {
            return redirect()->back()->withErrors([
                'status' => 'Không thể thay đổi trạng thái của đơn hàng đã bị hủy.'
            ])->withInput();
        }
        if ($originalStatus === 'Đang giao hàng' && !in_array($validatedData['status'], ['Hoàn thành', 'Đã hủy', 'Đang giao hàng'])) {
            return redirect()->back()->withErrors([
                'status' => 'Đơn hàng đang giao chỉ có thể chuyển sang "Hoàn thành", "Đã hủy" hoặc giữ nguyên "Đang giao hàng".'
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            $order->update([
                'status'         => $validatedData['status'],
                'payment_status' => $validatedData['payment_status'],
                'note'           => $validatedData['note'] ?? null,
            ]);

            $newStatus = $validatedData['status'];
            if ($newStatus === 'Hoàn thành' && $originalStatus !== 'Hoàn thành') {
                $order->load(['orderItems.product', 'orderItems.productVariant.attributeValues.attribute']);

                foreach ($order->orderItems as $item) {
                    $productName = $item->product->name ?? null;
                    $productSku = $item->productVariant->sku ?? null;

                    $sizeName = null;
                    $colorName = null;

                    if ($item->productVariant && $item->productVariant->attributeValues) {
                        foreach ($item->productVariant->attributeValues as $attrValue) {
                            if ($attrValue->attribute) {
                                if (strtolower($attrValue->attribute->name) === 'size') {
                                    $sizeName = $attrValue->value;
                                } elseif (strtolower($attrValue->attribute->name) === 'màu' || strtolower($attrValue->attribute->name) === 'color') {
                                    $colorName = $attrValue->value;
                                }
                            }
                        }
                    }

                    $productVariantId = $item->product_variant_id;

                    ArchivedOrderItem::create([
                        'order_id'           => $order->id,
                        'product_id'         => $item->product_id,
                        'product_variant_id' => $productVariantId,
                        'discount_id'        => $item->discount_id,
                        'quantity'           => $item->quantity,
                        'price'              => $item->price,
                        'discount_price'     => $item->discount_price,
                        'final_price'        => $item->final_price,
                        'total_price'        => $item->total_price,
                        'product_name'       => $productName,
                        'product_sku'        => $productSku,
                        'size_name'          => $sizeName,
                        'color_name'         => $colorName,
                        'created_at'         => $item->created_at,
                        'updated_at'         => $item->updated_at,
                        'archived_at'        => now(),
                    ]);
                }

                $order->orderItems()->delete();
            }

            DB::commit();
            return redirect()->route('admin.orders.edit', $order->id)
                             ->with('success', 'Cập nhật đơn hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi cập nhật đơn hàng: " . $e->getMessage(), ['order_id' => $order->id, 'request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage())
                                     ->withInput();
        }
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        try {
            DB::transaction(function () use ($order) {
                Order_items::where('order_id', $order->id)->delete();

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
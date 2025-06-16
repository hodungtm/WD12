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
        $order = Order::with([
            'user',
            'receiver',
            'shippingMethod',
            'discount',
            'orderItems.product',
            'orderItems.productVariant.attributeValues.attribute', // Đã sửa: Load thuộc tính qua attributeValues
            'archivedOrderItems.product', // Tải sản phẩm cho archived items
            'archivedOrderItems.productVariant.attributeValues.attribute', // Đã sửa: Load thuộc tính qua attributeValues cho archived items
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $users = User::all();
        $receivers = Receiver::all();

        // PHẦN CẦN SỬA ĐỔI ĐỂ KHẮC PHỤC LỖI 'size_id' VÀ 'color_id'
        $products = Product::with([
            'variants' => function ($query) {
                // KHÔNG SELECT TRỰC TIẾP size_id, color_id nữa.
                // Thay vào đó, eager load mối quan hệ attributeValues và attribute của chúng.
                $query->with('attributeValues.attribute');
            }
            // Bỏ các dòng 'variants.size' và 'variants.color' vì chúng không còn trực tiếp nữa.
        ])->get();


        $shippingMethods = ShippingMethod::all();
        $discounts = Discount::where('start_date', '<=', now())
                             ->where('end_date', '>=', now())
                             ->where(function ($query) {
                                 $query->whereNull('max_usage')
                                       ->orWhere('max_usage', '>', 0);
                             })
                             ->get();

        return view('admin.orders.create', compact('users', 'receivers', 'products', 'shippingMethods', 'discounts'));
    }

    /**
     * Xử lý lưu đơn hàng mới vào cơ sở dữ liệu.
     * Bao gồm tạo đơn hàng chính và các mục sản phẩm trong đơn hàng.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'receiver_id' => 'nullable|exists:receivers,id',
            'order_date' => 'required|date',
            'payment_method' => 'required|string',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'discount_id' => 'nullable|exists:discounts,id',
            'products' => 'required|array|min:1',
            'products.*.variant_id' => 'required|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            $orderCode = 'DH' . date('Ymd') . '-' . substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 6)), 0, 6);
            while (Order::where('order_code', $orderCode)->exists()) {
                $orderCode = 'DH' . date('Ymd') . '-' . substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 6)), 0, 6);
            }

            $order = Order::create([
                'user_id'            => $request->user_id,
                'receiver_id'        => $request->receiver_id,
                'order_date'         => $request->order_date,
                'payment_method'     => $request->payment_method,
                'payment_status'     => 'Chờ thanh toán',
                'status'             => 'Đang chờ',
                'note'               => $request->note,
                'shipping_method_id' => $request->shipping_method_id,
                'order_code'         => $orderCode,
            ]);

            $subtotalAmount = 0;

            foreach ($request->products as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);

                // Đảm bảo giá không bao giờ là NULL
                $basePrice = $variant->price ?? 0;
                $discountPrice = ($variant->sale_price > 0 && $variant->sale_price < $basePrice) ? $variant->sale_price : null;

                $finalPrice = $discountPrice ?? $basePrice;
                $quantity = $item['quantity'];
                $totalPrice = $finalPrice * $quantity;

                Order_items::create([
                    'order_id'           => $order->id,
                    'product_id'         => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'quantity'           => $quantity,
                    'price'              => $basePrice,
                    'discount_price'     => $discountPrice,
                    'final_price'        => $finalPrice,
                    'total_price'        => $totalPrice,
                ]);

                $subtotalAmount += $totalPrice;
            }

            $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);
            $shippingFee = $shippingMethod->fee ?? 0;

             // --- Logic tính toán giảm giá từ discount_id của đơn hàng ---
            $discountAmountApplied = 0; // Số tiền giảm giá thực tế áp dụng cho đơn hàng
            $appliedDiscountId = null; // ID của mã giảm giá được áp dụng (sẽ là null nếu không áp dụng)

            if ($request->discount_id) {
                $discount = Discount::find($request->discount_id);

                if ($discount) {
                    $isDiscountValid = true;
                    $errorMessage = '';

                    // 1. Kiểm tra ngày hết hạn
                    if ($discount->end_date && $discount->end_date < now()) {
                        $isDiscountValid = false;
                        $errorMessage = 'Mã giảm giá đã hết hạn.';
                    }

                    // 2. Kiểm tra số lần sử dụng tối đa
                    if ($discount->max_usage !== null && $discount->max_usage <= 0) {
                        $isDiscountValid = false;
                        $errorMessage = 'Mã giảm giá đã hết lượt sử dụng.';
                    }

                    // 3. Kiểm tra giá trị đơn hàng tối thiểu
                    if ($discount->min_order_amount && $subtotalAmount < $discount->min_order_amount) {
                        $isDiscountValid = false;
                        $errorMessage = 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã giảm giá.';
                    }

                    if ($isDiscountValid) {
                        // Tính toán số tiền giảm giá dựa trên loại mã
                        if ($discount->type === 'order') { // Giả sử 'order' là loại giảm giá cho tổng đơn hàng
                            if ($discount->discount_percent > 0) {
                                $calculatedDiscount = $subtotalAmount * ($discount->discount_percent / 100);
                            } else { // Nếu là giảm giá cố định (fixed amount)
                                $calculatedDiscount = $discount->discount_amount;
                            }

                            // Áp dụng giới hạn giảm giá tối đa (max_discount_amount)
                            if ($discount->max_discount_amount && $calculatedDiscount > $discount->max_discount_amount) {
                                $discountAmountApplied = $discount->max_discount_amount;
                            } else {
                                $discountAmountApplied = $calculatedDiscount;
                            }
                        }
                        // Thêm logic cho các loại discount khác nếu có (ví dụ: product, shipping)
                        // else if ($discount->type === 'product') { ... }
                        // else if ($discount->type === 'shipping') { ... }

                        // Cập nhật số lần sử dụng mã giảm giá nếu có và mã hợp lệ
                        if ($discount->max_usage !== null) {
                            $discount->decrement('max_usage');
                        }
                        $appliedDiscountId = $discount->id; // Chỉ gán ID nếu mã giảm giá thực sự được áp dụng
                    } else {
                        // Nếu mã giảm giá không hợp lệ, có thể ghi log hoặc gửi thông báo
                        // Ví dụ: session()->flash('warning', 'Mã giảm giá "' . $discount->code . '" không hợp lệ: ' . $errorMessage);
                        Log::warning('Attempted to apply invalid discount: ' . ($discount->code ?? 'N/A') . ' - ' . $errorMessage);
                    }
                } else {
                    // Mã giảm giá không tồn tại trong DB mặc dù ID đã được cung cấp
                    // Ví dụ: session()->flash('warning', 'Mã giảm giá không tồn tại.');
                    Log::warning('Discount ID ' . $request->discount_id . ' not found in database.');
                }
            }

            // Tính toán tổng tiền cuối cùng của đơn hàng (final_amount)
            $finalAmount = $subtotalAmount + $shippingFee - $discountAmountApplied;
            // Đảm bảo tổng tiền không âm
            if ($finalAmount < 0) {
                $finalAmount = 0;
            }

            // Cập nhật các trường tổng tiền và discount_id vào đơn hàng chính
            $order->update([
                'total_price'     => $subtotalAmount,         // Tổng tiền các sản phẩm (chưa bao gồm phí ship/giảm giá)
                'discount_amount' => $discountAmountApplied,   // Số tiền giảm giá thực tế áp dụng
                'final_amount'    => $finalAmount,             // Tổng tiền cuối cùng khách phải trả
                'discount_id'     => $appliedDiscountId,       // ID của mã giảm giá đã áp dụng (null nếu không có/không hợp lệ)
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
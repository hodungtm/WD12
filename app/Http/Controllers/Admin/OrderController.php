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
            'orderItems.productVariant.color', // Đảm bảo tải mối quan hệ color
            'orderItems.productVariant.size',  // Đảm bảo tải mối quan hệ size
            'archivedOrderItems.product', // Tải sản phẩm cho archived items
            'archivedOrderItems.productVariant.color', // Tải màu sắc
            'archivedOrderItems.productVariant.size', // Tải kích thước
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
            'note' => 'nullable|string|max:1000', // Đảm bảo note cũng được validate
        ]);

        DB::transaction(function () use ($request) {
            // Tạo mã đơn hàng duy nhất
            $orderCode = 'DH' . date('Ymd') . '-' . Str::upper(Str::random(6));
            while (Order::where('order_code', $orderCode)->exists()) {
                $orderCode = 'DH' . date('Ymd') . '-' . Str::upper(Str::random(6));
            }

            // Tạo đơn hàng chính với các thông tin cơ bản
            // total_price, discount_amount, final_amount, và discount_id sẽ được cập nhật sau
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
                // Các trường tổng tiền và discount_id sẽ được cập nhật sau tính toán
            ]);

            $subtotalAmount = 0; // Tổng tiền sản phẩm ban đầu

            foreach ($request->products as $item) {
                $variant = ProductVariant::with('product')->find($item['variant_id']);
                if (!$variant) {
                    // Xử lý trường hợp không tìm thấy biến thể sản phẩm (ví dụ: throw exception, log, skip)
                    // Hiện tại chỉ bỏ qua, nhưng có thể cần xử lý lỗi mạnh hơn
                    continue;
                }

                $product = $variant->product;
                $basePrice = $variant->variant_price;
                // Nếu variant_sale_price > 0 thì đó là giá khuyến mãi, nếu không thì dùng giá gốc
                $discountPrice = $variant->variant_sale_price > 0 ? $variant->variant_sale_price : null;
                $finalPrice = $discountPrice ?? $basePrice; // Giá cuối cùng của 1 đơn vị sản phẩm
                $quantity = $item['quantity'];
                $totalPrice = $finalPrice * $quantity; // Tổng tiền cho từng dòng sản phẩm

                // Lưu vào bảng order_items
                // KHUYẾN NGHỊ: Đổi tên Model Order_items thành OrderItem để tuân thủ PSR-4 của Laravel
                Order_items::create([
                    'order_id'           => $order->id,
                    'product_id'         => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'quantity'           => $quantity,
                    'price'              => $basePrice,
                    'discount_price'     => $discountPrice, // Giá khuyến mãi (nếu có)
                    'final_price'        => $finalPrice,    // Giá cuối cùng sau khi áp dụng khuyến mãi biến thể
                    'total_price'        => $totalPrice,    // Tổng tiền của dòng sản phẩm này
                ]);

                $subtotalAmount += $totalPrice; // Cộng vào tổng tiền sản phẩm của đơn hàng
            }

            // Lấy phí vận chuyển
            $shippingMethod = ShippingMethod::find($request->shipping_method_id);
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
            'user', // Thông tin người đặt
            'receiver', // Thông tin người nhận
            'shippingMethod', // Phương thức vận chuyển
            'discount', // Mã giảm giá

            // Tải eager loading cho Order Items (đơn hàng CHƯA hoàn thành)
            'orderItems.product',
            'orderItems.productVariant.attributeValues.attribute', // SỬA Ở ĐÂY

            // Tải eager loading cho Archived Order Items (đơn hàng ĐÃ hoàn thành)
            'archivedOrderItems.product',
            'archivedOrderItems.productVariant.attributeValues.attribute', // SỬA Ở ĐÂY
        ])->findOrFail($id);

        $users = User::all();
        $receivers = Receiver::all();

        // Phần này đã đúng từ lần sửa trước cho create()
        $products = Product::with([
            'variants' => function ($query) {
                $query->with('attributeValues.attribute');
                // Nếu bạn muốn giới hạn các cột được chọn từ bảng product_variants, hãy liệt kê chúng ở đây,
                // NHƯNG KHÔNG BAO GỒM 'size_id' và 'color_id'.
                // $query->select('id', 'product_id', 'sku', 'price', 'sale_price', 'stock_status', 'description', 'attribute_text', 'image');
            }
        ])->get();

        $shippingMethods = ShippingMethod::all();
        $discounts = Discount::all(); // Bạn có thể thêm các điều kiện lọc tương tự như trong create() nếu cần

        return view('admin.orders.edit', compact('order', 'users', 'receivers', 'products', 'shippingMethods', 'discounts'));
    }
   public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Lấy trạng thái ban đầu của đơn hàng và trạng thái thanh toán để kiểm tra sự thay đổi
        $originalStatus = $order->status;
        $originalPaymentStatus = $order->payment_status;

        // Định nghĩa các quy tắc validation - CHỈ GIỮ NHỮNG GÌ BẠN MUỐN CẬP NHẬT
        $rules = [
            'status' => [
                'required',
                'string',
                Rule::in(['Đang chờ', 'Đã giao hàng', 'Hoàn thành', 'Đã hủy']),
            ],
            'payment_status' => [
                'required',
                'string',
                Rule::in(['Chờ thanh toán', 'Đã thanh toán']),
            ],
            // Bỏ các validation cho user_id, receiver_id, order_date, payment_method,
            // shipping_method_id, discount_id vì chúng không được cập nhật nữa
            'note' => 'nullable|string|max:1000', // Giữ lại note nếu bạn muốn cập nhật nó
        ];

        // Thực hiện validation cơ bản
        $validatedData = $request->validate($rules);

        // --- Các Validate Logic Bổ Sung (Nghiệp vụ) ---

        // 1. Logic: Nếu trạng thái thanh toán là 'Chờ thanh toán'
        // VÀ muốn chuyển trạng thái đơn hàng thành 'Đã giao hàng' HOẶC 'Hoàn thành'
        if ($validatedData['payment_status'] === 'Chờ thanh toán' &&
            in_array($validatedData['status'], ['Đã giao hàng', 'Hoàn thành'])) { // Đã sửa 'Đang giao hàng' thành 'Đã giao hàng' cho phù hợp
            return redirect()->back()->withErrors([
                'status' => 'Không thể chuyển trạng thái đơn hàng sang "' . $validatedData['status'] . '" khi trạng thái thanh toán là "Chờ thanh toán".'
            ])->withInput();
        }

        // Logic: Không thể chuyển trạng thái thanh toán từ "Đã thanh toán" về "Chờ thanh toán"
        if ($originalPaymentStatus === 'Đã thanh toán' && $validatedData['payment_status'] === 'Chờ thanh toán') {
            return redirect()->back()->withErrors([
                'payment_status' => 'Không thể chuyển trạng thái thanh toán từ "Đã thanh toán" về "Chờ thanh toán".'
            ])->withInput();
        }

        // 2. Logic: Không cho phép chuyển từ 'Đã hủy' sang trạng thái khác
        if ($originalStatus === 'Đã hủy' && $validatedData['status'] !== 'Đã hủy') {
            return redirect()->back()->withErrors([
                'status' => 'Không thể thay đổi trạng thái của đơn hàng đã bị hủy.'
            ])->withInput();
        }

        // 3. Logic: Không cho phép chuyển từ 'Đã giao hàng' nếu không phải 'Hoàn thành', 'Đã hủy', hoặc giữ nguyên 'Đã giao hàng'
        // Tôi thấy có vẻ bạn đã thay đổi 'Đang giao hàng' trong một số ngữ cảnh trước đó thành 'Đã giao hàng',
        // nên tôi giữ nguyên 'Đang giao hàng' ở đây nếu đó là trạng thái hiện tại.
        // Tuy nhiên, trong Rule::in, bạn dùng 'Đã giao hàng'. Hãy thống nhất!
        // Nếu 'Đã giao hàng' là trạng thái bạn đang dùng sau 'Đang giao hàng', thì dòng này nên là:
        // Nếu trạng thái trong DB thực sự là 'Đang giao hàng' khi nó đang trên đường, và sau đó mới là 'Đã giao hàng' (delivered), thì bạn cần sửa Rule::in và logic này cho phù hợp.
        // Tôi tạm thời dùng 'Đã giao hàng' theo Rule::in của bạn.

        // --- Bắt đầu Transaction để đảm bảo tính nhất quán dữ liệu ---
        DB::beginTransaction();
        try {
            // **Chỉ cập nhật trạng thái và ghi chú**
            $order->update([
                'status'         => $validatedData['status'],
                'payment_status' => $validatedData['payment_status'],
                'note'           => $validatedData['note'] ?? null, // Cập nhật note nếu có
                // KHÔNG CẬP NHẬT: user_id, receiver_id, order_date, payment_method,
                // shipping_method_id, total_price, discount_amount, final_amount, discount_id
                // vì chúng được coi là cố định khi tạo đơn.
            ]);

            // **3. Kiểm tra và thực hiện logic lưu trữ nếu trạng thái thay đổi sang 'Hoàn thành'**
            $newStatus = $validatedData['status'];
            if ($newStatus === 'Hoàn thành' && $originalStatus !== 'Hoàn thành') {
                // Tải lại orderItems với attributeValues.attribute để có thông tin tên thuộc tính
                $order->load(['orderItems.product', 'orderItems.productVariant.attributeValues.attribute']); // Load lại để có đủ thông tin

                foreach ($order->orderItems as $item) { // Duyệt qua orderItems đã được load lại
                    $productName = $item->product->name ?? null;
                    $productSku = $item->productVariant->sku ?? null;

                    // Lấy tên size và color từ attributeValues
                    $sizeName = null;
                    $colorName = null;
                    if ($item->productVariant && $item->productVariant->attributeValues) {
                        foreach ($item->productVariant->attributeValues as $attrValue) {
                            if ($attrValue->attribute) {
                                if (strtolower($attrValue->attribute->name) === 'size') {
                                    $sizeName = $attrValue->value;
                                } elseif (strtolower($attrValue->attribute->name) === 'màu') { // Hoặc 'color' tùy tên thuộc tính
                                    $colorName = $attrValue->value;
                                }
                            }
                        }
                    }

                    $productVariantId = $item->product_variant_id; // Đảm bảo luôn có

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
                        'archived_at'        => now(), // Thời điểm lưu trữ
                    ]);
                }

                // Xóa các order_items gốc sau khi đã lưu trữ thành công
                $order->orderItems()->delete();

                // Nếu bạn có logic giảm số lượng sản phẩm trong kho khi hoàn thành, hãy thêm vào đây
                // Ví dụ:
                // foreach ($order->orderItems()->withTrashed()->get() as $item) { // Dùng withTrashed để lấy item vừa xóa
                //    $variant = $item->productVariant;
                //    if ($variant) {
                //        $variant->decrement('stock', $item->quantity);
                //    }
                // }

                // Nếu bạn có logic giảm số lượng của mã giảm giá khi đơn hàng hoàn thành
                // if ($order->discount_id && $order->discount) {
                //     $order->discount->decrement('max_usage'); // Giảm số lượt sử dụng
                // }
            }

            DB::commit(); // Hoàn tất transaction
            return redirect()->route('admin.orders.edit', $order->id)
                             ->with('success', 'Cập nhật đơn hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack(); // Hoàn tác transaction nếu có lỗi
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
                // Xóa toàn bộ mục đơn hàng
                // Lưu ý: Nếu bạn chọn xóa order_items gốc sau khi archive,
                // thì khi destroy một đơn hàng đã hoàn thành, các order_items của nó
                // sẽ không còn trong bảng gốc để xóa nữa.
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
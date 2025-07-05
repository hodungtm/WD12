<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderCompletedMail;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Order_items; // Đổi tên thành OrderItem nếu đúng chuẩn PSR-4

use App\Models\Products;
use App\Models\ProductVariant;
use App\Models\Receiver;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Models\Size; // Thêm Model Size
use App\Models\Color; // Thêm Model Color

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use \Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
{
    $query = Order::with(['user', 'shippingMethod', 'orderItems']);

    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where('order_code', 'LIKE', '%' . $search . '%');
    }

    $perPage = $request->input('per_page', 10);  // lấy từ request hoặc mặc định 10

    $orders = $query->latest()->paginate($perPage);

    return view('admin.orders.index', compact('orders'));
}

    public function show($id)
    {
        $order = Order::with([
            'user',
            'shippingMethod',
            'orderItems.product',
            'orderItems.productVariant.color', // Đảm bảo tải mối quan hệ color
            'orderItems.productVariant.size',  // Đảm bảo tải mối quan hệ size
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }
    public function create()
    {
        $users = User::all();

        // Eager load variants + size & color của từng variant
        $products = Products::with([
            'variants.size',
            'variants.color',
        ])->get();

        $shippingMethods = ShippingMethod::all();

        $discounts = Discount::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function ($query) {
                $query->whereNull('max_usage')->orWhere('max_usage', '>', 0);
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

            // Tạo đơn hàng cơ bản
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
                $variant = ProductVariant::with(['product', 'size', 'color',])->findOrFail($item['variant_id']);
                $product = $variant->product;

                $quantity = $item['quantity'];
                $price = $variant->sale_price > 0 ? $variant->sale_price : $variant->price;
                $totalPrice = $price * $quantity;

                Order_items::create([
                    'order_id'           => $order->id,
                    'product_id'         => $product->id,
                    'product_variant_id' => $variant->id,
                    'quantity'           => $quantity,
                    'price'              => $price,
                    'total_price'        => $totalPrice,

                    // Snapshot
                    'product_name'    => $product->name,
                    'variant_name'    => 'Màu ' . ($variant->color->name ?? '-') . ' / Size ' . ($variant->size->name ?? '-'),
                    'product_image'   => $product->image ?? $variant->image ?? null,
                    'sku'             => $variant->sku ?? '',
                   
                ]);

                // Tăng sold cho sản phẩm cha
                Products::where('id', $product->id)->increment('sold', $quantity);

                $subtotal += $totalPrice;
            }

            // Phí vận chuyển
            $shippingFee = ShippingMethod::find($request->shipping_method_id)->fee ?? 0;

            // Áp dụng mã giảm giá nếu có
            $discountAmount = 0;
            $appliedDiscountCode = null;

            if ($request->filled('discount_code')) {
                $discount = Discount::whereRaw('LOWER(code) = ?', [strtolower($request->discount_code)])->first();

                if ($discount && $discount->start_date <= now() && $discount->end_date >= now()) {
                    // 🔒 Kiểm tra nếu user đã từng dùng mã này
                    $usedBefore = Order::where('user_id', $request->user_id)
                        ->where('discount_code', $discount->code)
                        ->exists();

                    if ($usedBefore) {
                        throw ValidationException::withMessages([
                            'discount_code' => 'Bạn đã sử dụng mã khuyến mãi này rồi.',
                        ]);
                    }

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


            // Tính tổng tiền cần thanh toán
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
            'user',               // Người đặt
            'orderItems',         // Danh sách sản phẩm snapshot
            'shippingMethod',     // Phương thức vận chuyển
            // 'discount',           // Mã giảm giá (nếu có)
        ])->findOrFail($id);

        $users = User::all(); // Lấy danh sách người dùng

        // $products = Product::select('id', 'name')->get(); // Nếu dùng khi cập nhật đơn hàng

        $shippingMethods = ShippingMethod::all(); // Lấy tất cả phương thức vận chuyển

        // $discounts = Discount::select('id', 'code', 'discount_type', 'discount_value')->get();

        return view('admin.orders.edit', compact(
            'order',
            'users',
            // 'products',
            'shippingMethods',
            // 'discounts'
        ));
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

        // Ràng buộc logic trạng thái
        if (
            $validatedData['payment_status'] === 'Chờ thanh toán' &&
            in_array($validatedData['status'], ['Đang giao hàng', 'Hoàn thành'])
        ) {
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
            // Gửi email nếu đơn hàng chuyển sang "Hoàn thành"
            if (
                $originalStatus !== 'Hoàn thành' &&
                $validatedData['status'] === 'Hoàn thành' &&
                $order->user && $order->user->email
            ) {
                Mail::to($order->user->email)->send(new OrderCompletedMail($order));
            }

            DB::commit();
            return redirect()->route('admin.orders.edit', $order->id)
                ->with('success', 'Cập nhật đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi cập nhật đơn hàng: " . $e->getMessage(), [
                'order_id' => $order->id,
                'request_data' => $request->all()
            ]);

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

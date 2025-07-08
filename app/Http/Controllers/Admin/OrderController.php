<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderCompletedMail;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Order_items;
use App\Models\Products;
use App\Models\ProductVariant;
use App\Models\Receiver;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        $perPage = $request->input('per_page', 10);
        $orders = $query->latest()->paginate($perPage);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'shippingMethod',
            'orderItems.product',
            'orderItems.productVariant.color',
            'orderItems.productVariant.size',
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $users = User::all();
        $products = Products::with(['variants.size', 'variants.color'])->get();
        $shippingMethods = ShippingMethod::all();
        $discounts = Discount::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function ($query) {
                $query->whereNull('max_usage')->orWhere('max_usage', '>', 0);
            })->get();

        return view('admin.orders.create', compact('users', 'products', 'shippingMethods', 'discounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'receiver_name' => 'required|string|max:100',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'nullable|email',
            'receiver_address' => 'required|string|max:255',
            'order_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'discount_code' => 'nullable|string|max:50',
            'products' => 'required|array|min:1',
            'products.*.variant_id' => 'required|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            do {
                $orderCode = 'DH' . date('Ymd') . '-' . strtoupper(Str::random(6));
            } while (Order::where('order_code', $orderCode)->exists());

            $order = Order::create([
                'user_id' => $request->user_id,
                'order_date' => $request->order_date,
                'payment_method' => $request->payment_method,
                'payment_status' => 'Chờ thanh toán',
                'status' => 'Đang chờ',
                'note' => $request->note,
                'shipping_method_id' => $request->shipping_method_id,
                'order_code' => $orderCode,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_email' => $request->receiver_email,
                'receiver_address' => $request->receiver_address,
            ]);

            $subtotal = 0;

            foreach ($request->products as $item) {
                $variant = ProductVariant::with(['product', 'size', 'color'])->findOrFail($item['variant_id']);
                $product = $variant->product;
                $quantity = $item['quantity'];
                $price = $variant->sale_price > 0 ? $variant->sale_price : $variant->price;
                $totalPrice = $price * $quantity;

                Order_items::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'product_name' => $product->name,
                    'variant_name' => 'Màu ' . ($variant->color->name ?? '-') . ' / Size ' . ($variant->size->name ?? '-'),
                    'product_image' => $product->image ?? $variant->image ?? null,
                    'sku' => $variant->sku ?? '',
                ]);

                Products::where('id', $product->id)->increment('sold', $quantity);
                $subtotal += $totalPrice;
            }

            $shippingFee = ShippingMethod::find($request->shipping_method_id)->fee ?? 0;
            $discountAmount = 0;
            $appliedDiscountCode = null;

            if ($request->filled('discount_code')) {
                $discount = Discount::whereRaw('LOWER(code) = ?', [strtolower($request->discount_code)])->first();

                if ($discount && $discount->start_date <= now() && $discount->end_date >= now()) {
                    $usedBefore = Order::where('user_id', $request->user_id)
                        ->where('discount_code', $discount->code)->exists();

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

            $finalAmount = max($subtotal + $shippingFee - $discountAmount, 0);

            $order->update([
                'total_price' => $subtotal,
                'discount_code' => $appliedDiscountCode,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ]);
        });

        return redirect()->route('admin.orders.index')->with('success', 'Tạo đơn hàng thành công!');
    }

    public function edit($id)
    {
        $order = Order::with([
            'user',
            'orderItems',
            'shippingMethod',
        ])->findOrFail($id);

        $users = User::all();
        $shippingMethods = ShippingMethod::all();

        return view('admin.orders.edit', compact(
            'order',
            'users',
            'shippingMethods'
        ));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $originalStatus = $order->status;
        $originalPaymentStatus = $order->payment_status;

        $validatedData = $request->validate([
            'status' => ['required', 'string', Rule::in(['Đang chờ', 'Đang xử lý', 'Đang giao hàng', 'Đã giao hàng', 'Hoàn thành', 'Đã hủy'])],
            'payment_status' => ['required', 'string', Rule::in(['Chờ thanh toán', 'Đã thanh toán'])],
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validatedData['payment_status'] === 'Chờ thanh toán' && in_array($validatedData['status'], ['Đang giao hàng', 'Hoàn thành'])) {
            return back()->withErrors(['status' => 'Không thể chuyển trạng thái đơn hàng sang "' . $validatedData['status'] . '" khi trạng thái thanh toán là "Chờ thanh toán".'])->withInput();
        }

        if ($originalPaymentStatus === 'Đã thanh toán' && $validatedData['payment_status'] === 'Chờ thanh toán') {
            return back()->withErrors(['payment_status' => 'Không thể chuyển trạng thái thanh toán từ "Đã thanh toán" về "Chờ thanh toán".'])->withInput();
        }

        if ($originalStatus === 'Đã hủy' && $validatedData['status'] !== 'Đã hủy') {
            return back()->withErrors(['status' => 'Không thể thay đổi trạng thái của đơn hàng đã bị hủy.'])->withInput();
        }

        if ($originalStatus === 'Đang giao hàng' && !in_array($validatedData['status'], ['Hoàn thành', 'Đã hủy', 'Đang giao hàng'])) {
            return back()->withErrors(['status' => 'Đơn hàng đang giao chỉ có thể chuyển sang "Hoàn thành", "Đã hủy" hoặc giữ nguyên "Đang giao hàng".'])->withInput();
        }

        DB::beginTransaction();
        try {
            $order->update([
                'status' => $validatedData['status'],
                'payment_status' => $validatedData['payment_status'],
                'note' => $validatedData['note'] ?? null,
            ]);

            if ($originalStatus !== 'Hoàn thành' && $validatedData['status'] === 'Hoàn thành' && $order->user && $order->user->email) {
                Mail::to($order->user->email)->send(new OrderCompletedMail($order));
            }

            DB::commit();
            return redirect()->route('admin.orders.edit', $order->id)->with('success', 'Cập nhật đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi cập nhật đơn hàng: " . $e->getMessage(), ['order_id' => $order->id, 'request_data' => $request->all()]);
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage())->withInput();
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

            return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Có lỗi xảy ra khi xóa đơn hàng: ' . $e->getMessage());
        }
    }
}

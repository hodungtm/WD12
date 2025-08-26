<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderCreatedMail;
use App\Models\Order_items;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\ShippingMethod;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $userId = Auth::id();
        $selectedIds = $request->input('selected_items', []);

        $cartQuery = Cart::with(['product.images', 'variant.color', 'variant.size'])
            ->where('user_id', $userId);

        if (!empty($selectedIds)) {
            $cartQuery->whereIn('id', $selectedIds);
        }

        $cartItems = Cart::with([
            'product.images',
            'variant.color',
            'variant.size'
        ])->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán.');
        }

        $shippingMethods = ShippingMethod::all();
        $subtotal = $cartItems->sum(fn($item) => $item->variant->sale_price * $item->quantity);

        $discounts = Discount::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where('min_order_amount', '<=', $subtotal)
            ->get();

        return view('client.cart.checkout', compact('cartItems', 'shippingMethods', 'discounts'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $selectedIds = $request->input('selected_items', []);
        if (empty($selectedIds)) {
            $selectedIds = Cart::where('user_id', $user->id)->pluck('id')->toArray();
        }

        if (empty($selectedIds)) {
            return back()->with('error', 'Vui lòng chọn sản phẩm để thanh toán.');
        }
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', $user->id)
            ->when(!empty($selectedIds), function ($query) use ($selectedIds) {
                return $query->whereIn('id', $selectedIds);
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Không tìm thấy sản phẩm được chọn.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->variant->sale_price * $item->quantity);

        $discountCode = $request->discount_code;
        $discountAmount = 0;
        $discount = null;

        if ($discountCode) {
            $discount = Discount::where('code', $discountCode)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if ($discount && $subtotal >= $discount->min_order_amount) {
                if ($discount->type === 'percent') {
                    $discountAmount = round($subtotal * $discount->discount_percent / 100);
                    if ($discount->max_discount_amount) {
                        $discountAmount = min($discountAmount, $discount->max_discount_amount);
                    }
                } elseif ($discount->type === 'fixed') {
                    $discountAmount = min($discount->discount_amount, $subtotal);
                }
            }
        }

        $shippingMethod = ShippingMethod::find($request->shipping_method_id);
        $shippingFee = $shippingMethod ? $shippingMethod->fee : 0;

        // Nếu đơn hàng trên 300k thì freeship
        if ($subtotal >= 300000) {
            $shippingFee = 0;
        }

        $finalAmount = $subtotal + $shippingFee - $discountAmount;

        // TẠO ĐƠN HÀNG NGAY LẬP TỨC KHI CHỌN MOMO
        if ($request->payment_method === 'Momo') {
            DB::beginTransaction();
            try {
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_code' => 'DH' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                    'order_date' => now(),
                    'shipping_method_id' => $request->shipping_method_id,
                    'receiver_name' => $request->receiver_name,
                    'receiver_phone' => $request->receiver_phone,
                    'receiver_email' => $request->receiver_email,
                    'receiver_address' => $request->receiver_address,
                    'payment_method' => 'Momo',
                    'payment_status' => 'Chờ thanh toán',
                    'total_price' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'discount_code' => $discountCode,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                ]);

                foreach ($cartItems as $item) {
                    // Tìm ảnh theo màu của variant
                    $productImage = $item->product->images
                        ->where('color_id', $item->variant->color_id)
                        ->first();
                    
                    // Nếu không có ảnh theo màu thì lấy ảnh đầu tiên của sản phẩm
                    $imagePath = $productImage ? $productImage->image : 
                        ($item->product->images->first()->image ?? 'no-image.jpg');

                    Order_items::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product->id,
                        'product_variant_id' => $item->variant->id,
                        'quantity' => $item->quantity,
                        'price' => $item->variant->sale_price,
                        'total_price' => $item->variant->sale_price * $item->quantity,
                        'product_name' => $item->product->name,
                        'variant_name' => ($item->variant->color->name ?? '') . ' / ' . ($item->variant->size->name ?? ''),
                        'product_image' => $imagePath, // Lưu đúng ảnh theo màu của biến thể
                    ]);
                }

                session([
                    'checkout_data' => [
                        'order_id' => $order->id,
                        'final_amount' => $finalAmount,
                    ]
                ]);

                DB::commit();

                return redirect()->route('momo.payment');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('❌ Lỗi khi tạo đơn hàng MoMo: ' . $e->getMessage());
                return back()->with('error', 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.');
            }
        }
        
        // LOGIC CHO CÁC PHƯƠNG THỨC THANH TOÁN KHÁC (NHƯ COD)
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_code' => 'DH' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                'order_date' => now(),
                'shipping_method_id' => $request->shipping_method_id,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_email' => $request->receiver_email,
                'receiver_address' => $request->receiver_address ?? $request->address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'Chờ thanh toán',
                'total_price' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_code' => $discountCode,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ]);

            foreach ($cartItems as $item) {
                // Tìm ảnh theo màu của variant
                $productImage = $item->product->images
                    ->where('color_id', $item->variant->color_id)
                    ->first();
                
                // Nếu không có ảnh theo màu thì lấy ảnh đầu tiên của sản phẩm
                $imagePath = $productImage ? $productImage->image : 
                    ($item->product->images->first()->image ?? 'no-image.jpg');

                Order_items::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_variant_id' => $item->variant->id,
                    'quantity' => $item->quantity,
                    'price' => $item->variant->sale_price,
                    'total_price' => $item->variant->sale_price * $item->quantity,
                    'product_name' => $item->product->name,
                    'variant_name' => ($item->variant->color->name ?? '') . ' / ' . ($item->variant->size->name ?? ''),
                    'product_image' => $imagePath, // Lưu đúng ảnh theo màu của biến thể
                ]);

                // Trừ kho ngay lập tức
                if ($item->variant) {
                    $variant = ProductVariant::find($item->variant->id);
                    if ($variant) {
                        $variant->quantity = max(0, $variant->quantity - $item->quantity);
                        $variant->save();
                    }
                } else {
                    $product = Product::find($item->product->id);
                    if ($product) {
                        $product->quantity = max(0, $product->quantity - $item->quantity);
                        $product->save();
                    }
                }
            }

            // Trừ lượt sử dụng của mã giảm giá
            if ($discountCode) {
                $discount = Discount::where('code', $discountCode)->first();
                if ($discount && $discount->max_usage > 0) {
                    $discount->decrement('max_usage');
                }
            }
            
            // ✅ Bắt lỗi cụ thể khi gửi email để dễ dàng debug
            try {
                // Xoá giỏ hàng và gửi email
                Cart::whereIn('id', $cartItems->pluck('id'))->delete();
                if ($order->receiver_email) {
                    Mail::to($order->receiver_email)->send(new OrderCreatedMail($order));
                }
            } catch (Exception $e) {
                // Chỉ ghi log lỗi email và tiếp tục
                Log::error('❌ Lỗi khi gửi email xác nhận đơn hàng: ' . $e->getMessage());
                // Không rollback transaction, vẫn cho phép đơn hàng được tạo
            }
            
            DB::commit();
            return redirect()->route('client.order.success', $order->id)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Lỗi khi tạo đơn hàng (COD): ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.');
        }
    }
    
    public function momoReturn(Request $request)
    {
        $data = session('checkout_data');
        if (!$data || !isset($data['order_id'])) {
            return redirect()->route('client.checkout.show')->with('error', 'Dữ liệu đơn hàng không hợp lệ hoặc đã hết hạn.');
        }

        $order = Order::find($data['order_id']);
        if (!$order) {
            return redirect()->route('client.checkout.show')->with('error', 'Đơn hàng không tồn tại.');
        }

        if ($request->resultCode == 0) {
            DB::beginTransaction();
            try {
                // CẬP NHẬT TRẠNG THÁI THANH TOÁN THÀNH CÔNG
                $order->payment_status = 'Đã thanh toán';
                $order->save();

                // Trừ kho
                $selectedIds = Cart::where('user_id', $order->user_id)->pluck('id')->toArray();
                $cartItems = Cart::with(['product', 'variant'])->whereIn('id', $selectedIds)->get();

                foreach ($cartItems as $item) {
                    if ($item->variant) {
                        $variant = ProductVariant::find($item->variant->id);
                        if ($variant) {
                            $variant->quantity = max(0, $variant->quantity - $item->quantity);
                            $variant->save();
                        }
                    } else {
                        $product = Product::find($item->product->id);
                        if ($product) {
                            $product->quantity = max(0, $product->quantity - $item->quantity);
                            $product->save();
                        }
                    }
                }

                // Kiểm tra và trừ mã giảm giá (nếu có)
                if ($order->discount_code) {
                    $discount = Discount::where('code', $order->discount_code)->first();
                    if ($discount && $discount->max_usage > 0) {
                        $discount->decrement('max_usage');
                    }
                }

                // Xoá giỏ hàng và session
                Cart::whereIn('id', $cartItems->pluck('id'))->delete();
                session()->forget('checkout_data');
                
                // Gửi email xác nhận đơn hàng
                if ($order->receiver_email) {
                    Mail::to($order->receiver_email)->send(new OrderCreatedMail($order));
                }

                DB::commit();
                return redirect()->route('client.order.success', $order->id)->with('success', 'Thanh toán MoMo thành công và đơn hàng đã được cập nhật!');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('❌ Lỗi khi cập nhật đơn hàng MoMo: ' . $e->getMessage());
                return redirect()->route('client.checkout.show')->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng sau thanh toán. Vui lòng liên hệ hỗ trợ.');
            }
        }

        // LOGIC NẾU THANH TOÁN BỊ HỦY HOẶC THẤT BẠI
        session()->forget('checkout_data');
        return redirect()->route('client.order.detail', $order->id)->with('error', 'Thanh toán thất bại hoặc bị hủy. Vui lòng thanh toán lại.');
    }


    public function momoPayment()
    {
        $data = session('checkout_data');

        if (!$data || !isset($data['order_id'])) {
            return redirect()->route('client.checkout')->with('error', 'Không có dữ liệu đơn hàng');
        }

        $order = Order::find($data['order_id']);
        if (!$order) {
            return redirect()->route('client.checkout')->with('error', 'Không tìm thấy đơn hàng đã tạo.');
        }

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toan don hang " . $order->order_code;

        $amount = (int) $order->final_amount;
        
        $orderId = $order->id . '_' . time(); 

        $requestId = time();
        $redirectUrl = route('momo.return');
        $ipnUrl = route('momo.return');
        $requestType = "payWithMethod";
        $extraData = "";

        $rawData = [
            'accessKey' => $accessKey,
            'amount' => $amount,
            'extraData' => $extraData,
            'ipnUrl' => $ipnUrl,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'partnerCode' => $partnerCode,
            'redirectUrl' => $redirectUrl,
            'requestId' => $requestId,
            'requestType' => $requestType,
        ];

        ksort($rawData);
        $rawHash = urldecode(http_build_query($rawData));
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $body = [
            'partnerCode' => $partnerCode,
            'partnerName' => "MoMoTest",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $result = $this->execPostRequest($endpoint, json_encode($body));
        Log::info('🔁 Phản hồi thô từ MoMo: ' . $result);

        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            return redirect($jsonResult['payUrl']);
        }

        // Ghi log chi tiết khi có lỗi từ MoMo
        if (isset($jsonResult['message'])) {
            Log::error('❌ Lỗi MoMo: ' . $jsonResult['message']);
            return back()->with('error', 'Lỗi MoMo: ' . $jsonResult['message']);
        }
        
        return back()->with('error', 'Không thể kết nối với cổng thanh toán MoMo.');
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            Log::error("❌ Lỗi cURL MoMo: $error");
            curl_close($ch);
            return json_encode(['curl_error' => $error]);
        }

        curl_close($ch);
        return $result;
    }

    public function success($orderId)
    {
        $order = Order::with(['orderItems.product', 'orderItems.productVariant'])->findOrFail($orderId);
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Không có quyền xem đơn hàng này');
        }

        return view('client.order.success', compact('order'));
    }

    public function detail($orderId)
    {
        $order = Order::with(['orderItems.product', 'orderItems.productVariant'])->findOrFail($orderId);
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Không có quyền xem đơn hàng này');
        }

        return view('client.order.detail', compact('order'));
    }
}
<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderCreatedMail;
use App\Models\Order_items;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\ShippingMethod;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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

        $cartItems = $cartQuery->get();
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
            ->when(!empty($selectedIds), fn($q) => $q->whereIn('id', $selectedIds))
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Không tìm thấy sản phẩm được chọn.');
        }

        // Tính toán giá
        $subtotal = $cartItems->sum(fn($item) => $item->variant->sale_price * $item->quantity);

        $discountCode = $request->discount_code;
        $discountAmount = 0;

        if ($discountCode) {
            $discount = Discount::where('code', $discountCode)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if ($discount && $subtotal >= $discount->min_order_amount) {
                $discountAmount = min(
                    round($subtotal * $discount->discount_percent / 100),
                    $discount->max_discount_amount
                );
            }
        }

        $shippingMethod = ShippingMethod::find($request->shipping_method_id);
        $shippingFee = $shippingMethod ? $shippingMethod->fee : 0;
        $finalAmount = $subtotal + $shippingFee - $discountAmount;

        // ✅ Lưu session checkout
        session([
            'checkout_data' => [
                'user_id'            => $user->id,
                'receiver_name'      => $request->receiver_name,
                'receiver_phone'     => $request->receiver_phone,
                'receiver_email'     => $request->receiver_email,
                'receiver_address'   => $request->receiver_address,
                'payment_method'     => $request->payment_method,
                'shipping_method_id' => $request->shipping_method_id,
                'discount_code'      => $discountCode,
                'discount_amount'    => $discountAmount,
                'shipping_fee'       => $shippingFee,
                'subtotal'           => $subtotal,
                'final_amount'       => $finalAmount,
                'selected_items'     => $selectedIds,
            ]
        ]);

        // ✅ Điều hướng theo cổng thanh toán
        switch ($request->payment_method) {
            case 'Momo':
                return redirect()->route('momo.payment');
            case 'Vnpay':
                return redirect()->route('vnpay.payment');
            default: // COD, Bank
                return $this->storeOrder($request, $cartItems, $discountCode, $discountAmount, $shippingFee, $subtotal, $finalAmount);
        }
    }

    // ================= MoMo =================
    public function momoPayment()
    {
        $data = session('checkout_data');
        if (!$data) return redirect()->route('client.checkout.show')->with('error', 'Không có dữ liệu đơn hàng');

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toan don hang qua MoMo";
        $amount = $data['final_amount'];
        $orderId = time();
        $requestId = time();
        $redirectUrl = route('momo.return');
        $ipnUrl = route('momo.return');
        $requestType = "payWithMethod";
        $extraData = "";

        $rawData = [
            'accessKey'    => $accessKey,
            'amount'       => $amount,
            'extraData'    => $extraData,
            'ipnUrl'       => $ipnUrl,
            'orderId'      => $orderId,
            'orderInfo'    => $orderInfo,
            'partnerCode'  => $partnerCode,
            'redirectUrl'  => $redirectUrl,
            'requestId'    => $requestId,
            'requestType'  => $requestType,
        ];

        ksort($rawData);
        $rawHash = urldecode(http_build_query($rawData));
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $body = array_merge($rawData, [
            'partnerName' => "MoMoTest",
            'storeId'     => "MomoTestStore",
            'lang'        => 'vi',
            'signature'   => $signature,
        ]);

        $result = $this->execPostRequest($endpoint, json_encode($body));
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            return redirect($jsonResult['payUrl']);
        }

        return back()->with('error', 'Không thể kết nối với cổng thanh toán MoMo.');
    }

    public function momoReturn(Request $request)
    {
        if ($request->resultCode == 0) {
            return $this->handlePaymentSuccess('Momo');
        }
        return redirect()->route('client.checkout.show')->with('error', 'Thanh toán MoMo thất bại hoặc bị hủy.');
    }

    // ================= VNPAY =================
    public function vnpayPayment()
    {
        $data = session('checkout_data');
        if (!$data) {
            return redirect()->route('client.checkout.show')->with('error', 'Không có dữ liệu đơn hàng');
        }

        $vnp_Url        = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl  = route('vnpay.return');
        $vnp_TmnCode    = "VJPOALBE"; // ✅ Mã website của bạn
        $vnp_HashSecret = "F9I2WB7YOFY2XJOQC08EW5K62L5GKU0T"; // ✅ Chuỗi bí mật

        $vnp_TxnRef     = time();
        $vnp_OrderInfo  = "Thanh toán đơn hàng qua VNPAY";
        $vnp_OrderType  = "billpayment";
        $vnp_Amount     = $data['final_amount'] * 100; // nhân 100 theo yêu cầu VNPAY
        $vnp_Locale     = "vn";
        $vnp_IpAddr     = request()->ip();

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => $vnp_Amount,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $vnp_IpAddr,
            "vnp_Locale"     => $vnp_Locale,
            "vnp_OrderInfo"  => $vnp_OrderInfo,
            "vnp_OrderType"  => $vnp_OrderType,
            "vnp_ReturnUrl"  => $vnp_Returnurl,
            "vnp_TxnRef"     => $vnp_TxnRef,
        ];

        // ✅ Sắp xếp theo key
        ksort($inputData);

        // ✅ Tạo chuỗi hashdata đúng chuẩn
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&';
            }
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }

        // ✅ Tạo chữ ký
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        // ✅ Tạo query string
        $query = http_build_query($inputData);
        $vnp_Url = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect()->away($vnp_Url);
    }



    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "F9I2WB7YOFY2XJOQC08EW5K62L5GKU0T"; // ✅ đúng với config của bạn
        $vnp_SecureHash = $request->get('vnp_SecureHash');

        // Lấy toàn bộ dữ liệu trả về trừ vnp_SecureHash và vnp_SecureHashType
        $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType');

        // Sắp xếp key theo thứ tự tăng dần
        ksort($inputData);

        // Build lại chuỗi hashData đúng chuẩn
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&';
            }
            $hashData .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }

        // Tạo secure hash để đối chiếu
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash && $request->get('vnp_ResponseCode') == '00') {
            // ✅ Thành công
            return $this->handlePaymentSuccess('VNPAY');
        }

        // ❌ Thất bại
        return redirect()->route('client.checkout.show')->with('error', 'Thanh toán VNPAY thất bại hoặc bị hủy.');
    }


    // ================== Dùng chung ==================
    private function handlePaymentSuccess($gateway)
    {
        $data = session('checkout_data');
        $userId = $data['user_id'] ?? null;
        if (!$data || !$userId) {
            return redirect()->route('client.checkout.show')->with('error', 'Dữ liệu không hợp lệ hoặc đã hết hạn.');
        }

        $selectedIds = $data['selected_items'] ?? [];
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', $userId)
            ->when(!empty($selectedIds), fn($q) => $q->whereIn('id', $selectedIds))
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Giỏ hàng trống hoặc đã được xử lý.');
        }

        $data['payment_method'] = $gateway;
        return $this->storeOrder((object)$data, $cartItems, $data['discount_code'], $data['discount_amount'], $data['shipping_fee'], $data['subtotal'], $data['final_amount']);
    }

public function storeOrder($request, $cartItems, $discountCode, $discountAmount, $shippingFee, $subtotal, $finalAmount)
{
    // Ép dữ liệu checkout_data thành array
    $data = (array) session('checkout_data');

    // ✅ Kiểm tra mã giảm giá
    if ($discountCode) {
        $discount = Discount::where('code', $discountCode)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$discount) return back()->with('error', 'Mã giảm giá không hợp lệ.');
        if ($discount->max_usage <= 0) return back()->with('error', 'Mã giảm giá đã hết lượt.');
        $discount->decrement('max_usage');
    }

    // ✅ Xác định trạng thái đơn hàng
    $payment_status = in_array($data['payment_method'], ['Momo', 'Vnpay'])
        ? 'Đã thanh toán'
        : 'Chờ thanh toán';

    // ✅ Tạo đơn hàng
    $order = Order::create([
        'user_id'            => Auth::id(),
        'order_code'         => 'DH' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
        'order_date'         => now(),
        'shipping_method_id' => $data['shipping_method_id'] ?? null,
        'receiver_name'      => $data['receiver_name'] ?? '',
        'receiver_phone'     => $data['receiver_phone'] ?? '',
        'receiver_email'     => $data['receiver_email'] ?? null,
        'receiver_address'   => $data['receiver_address'] ?? $data['address'] ?? '',
        'payment_method'     => $data['payment_method'] ?? 'COD',
        'total_price'        => $subtotal,
        'shipping_fee'       => $shippingFee,
        'discount_code'      => $discountCode,
        'discount_amount'    => $discountAmount,
        'final_amount'       => $finalAmount,
        'payment_status'     => $payment_status,
    ]);

    // ✅ Lưu chi tiết đơn hàng
    foreach ($cartItems as $item) {
        Order_items::create([
            'order_id'           => $order->id,
            'product_id'         => $item->product->id,
            'product_variant_id' => $item->variant->id,
            'quantity'           => $item->quantity,
            'price'              => $item->variant->sale_price,
            'total_price'        => $item->variant->sale_price * $item->quantity,
            'product_name'       => $item->product->name,
            'variant_name'       => ($item->variant->color->name ?? '') . ' / ' . ($item->variant->size->name ?? ''),
            'product_image'      => optional($item->product->images->first())->image,
        ]);

        // ✅ Trừ kho
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

    // ✅ Xoá giỏ hàng và session
    Cart::whereIn('id', $cartItems->pluck('id'))->delete();
    session()->forget('checkout_data');

    // ✅ Gửi email xác nhận
    if ($order->receiver_email) {
        Mail::to($order->receiver_email)->send(new OrderCreatedMail($order));
    }

    return redirect()->route('client.order.success', $order->id)->with('success', 'Đặt hàng thành công!');
}



    private function execPostRequest($url, $data)
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
            Log::error("❌ cURL Error: $error");
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
}

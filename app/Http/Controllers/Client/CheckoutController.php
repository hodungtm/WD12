<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order_items;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\ShippingMethod;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        ->where(function ($q) {
            $q->whereNull('max_usage')
              ->orWhere('max_usage', '>', 0); // max_usage > 0 nghĩa là vẫn còn lượt
        })
        ->first();

    if (!$discount) {
        return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng.');
    }

    if ($subtotal >= $discount->min_order_amount) {
        $discountAmount = min(
            round($subtotal * $discount->discount_percent / 100),
            $discount->max_discount_amount
        );
    } else {
        return back()->with('error', 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã giảm giá.');
    }
}

        $shippingMethod = ShippingMethod::find($request->shipping_method_id);
        $shippingFee = $shippingMethod ? $shippingMethod->fee : 0;

        $finalAmount = $subtotal + $shippingFee - $discountAmount;

        if ($request->payment_method === 'Momo') {
            session([
                'checkout_data' => [
                    'user_id'            => $user->id,
                    'receiver_name'      => $request->receiver_name,
                    'receiver_phone'     => $request->receiver_phone,
                    'receiver_email'     => $user->email,
                    'receiver_address'   => $request->receiver_address,
                    'payment_method'     => 'Momo',
                    'shipping_method_id' => $request->shipping_method_id,
                    'discount_code'      => $discountCode,
                    'discount_amount'    => $discountAmount,
                    'shipping_fee'       => $shippingFee,
                    'subtotal'           => $subtotal,
                    'final_amount'       => $finalAmount,
                    'selected_items'     => $selectedIds,
                ]
            ]);

            return redirect()->route('momo.payment');
        }

        return $this->storeOrder($request, $cartItems, $discountCode, $discountAmount, $shippingFee, $subtotal, $finalAmount);
    }

public function momoReturn(Request $request)
{
    // Kiểm tra resultCode từ MoMo
    if ($request->resultCode == 0) {
        $data = session('checkout_data');
        $userId = $data['user_id'] ?? null;

        if (!$data || !$userId) {
            return redirect()->route('client.checkout.show')
                ->with('error', 'Dữ liệu không hợp lệ hoặc đã hết hạn.');
        }

        $selectedIds = $data['selected_items'] ?? [];

        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', $userId)
            ->when(!empty($selectedIds), function ($query) use ($selectedIds) {
                return $query->whereIn('id', $selectedIds);
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Giỏ hàng trống hoặc đã được xử lý.');
        }

        // Thêm trạng thái thanh toán đã trả tiền
        $data['payment_status'] = 'Đã thanh toán';

        return $this->storeOrder(
            (object)$data,
            $cartItems,
            $data['discount_code'],
            $data['discount_amount'],
            $data['shipping_fee'],
            $data['subtotal'],
            $data['final_amount']
        );
    }

    return redirect()->route('client.checkout.show')
        ->with('error', 'Thanh toán thất bại hoặc bị hủy.');
}



public function storeOrder($request, $cartItems, $discountCode, $discountAmount, $shippingFee, $subtotal, $finalAmount)
{
    // Kiểm tra và trừ lượt mã giảm giá
    if ($discountCode) {
        $discount = Discount::where('code', $discountCode)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$discount) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        if ($discount->max_usage <= 0) {
            return back()->with('error', 'Mã giảm giá đã được sử dụng hết.');
        }

        $discount->decrement('max_usage');
    }

    // Tạo đơn hàng
    $order = Order::create([
        'user_id'            => Auth::id(),
        'order_code'         => 'DH' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
        'order_date'         => now(),
        'shipping_method_id' => $request->shipping_method_id,
        'receiver_name'      => $request->receiver_name,
        'receiver_phone'     => $request->receiver_phone,
        'receiver_email'     => $request->receiver_email,
        'receiver_address'   => $request->receiver_address ?? $request->address,
        'payment_method'     => $request->payment_method,
        'payment_status'     => $request->payment_status ?? 'Chờ thanh toán', 
        'total_price'        => $subtotal,
        'shipping_fee'       => $shippingFee,
        'discount_code'      => $discountCode,
'discount_amount'    => $discountAmount,
        'final_amount'       => $finalAmount,
    ]);

    // Lưu sản phẩm vào bảng order_items
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

        // Trừ kho
        if ($item->variant) {
            $item->variant->decrement('quantity', $item->quantity);
        } else {
            $item->product->decrement('quantity', $item->quantity);
        }
    }

    // Xóa giỏ hàng và session
    Cart::whereIn('id', $cartItems->pluck('id'))->delete();
    session()->forget('checkout_data');

    return redirect()->route('client.order.success', $order->id)
        ->with('success', 'Đặt hàng thành công!');
}

    public function momoPayment()
    {
        $data = session('checkout_data');

        if (!$data) {
            return redirect()->route('client.checkout')->with('error', 'Không có dữ liệu đơn hàng');
        }

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
        Log::info('🔁 MoMo Raw Response: ' . $result);

        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            return redirect($jsonResult['payUrl']);
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
            Log::error("❌ cURL MoMo Error: $error");
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
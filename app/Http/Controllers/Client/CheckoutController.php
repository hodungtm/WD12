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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    public function show()
{
    $userId = Auth::id();

    $cartItems = Cart::with(['product.images', 'variant.color', 'variant.size'])
        ->where('user_id', $userId)
        ->get();

    $shippingMethods = ShippingMethod::all();
    $discounts = Discount::whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->get();

    return view('client.cart.checkout', compact('cartItems', 'shippingMethods', 'discounts'));
}


public function process(Request $request)
{
    $user = Auth::user();

    $cartItems = Cart::with(['product', 'variant'])
        ->where('user_id', $user->id)
        ->get();

    if ($cartItems->isEmpty()) {
        return back()->with('error', 'Giỏ hàng trống!');
    }

    // Tính tổng tiền hàng (subtotal)
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item->variant->price * $item->quantity;
    }

    // Xử lý mã giảm giá
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

    // Phí ship từ phương thức giao hàng
    $shippingMethod = ShippingMethod::find($request->shipping_method_id);
    $shippingFee = $shippingMethod ? $shippingMethod->fee : 0;

    // Tổng cuối cùng
    $finalAmount = $subtotal + $shippingFee - $discountAmount;

    // Lưu đơn hàng
    $order = Order::create([
        'user_id'            => $user->id,
        'order_code'         => 'DH' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
        'order_date'         => now(),
        'shipping_method_id' => $request->shipping_method_id,
        'receiver_name'      => $request->receiver_name,
        'receiver_phone'     => $request->receiver_phone,
        'receiver_email'     => $user->email,
        'receiver_address'   => $request->receiver_address,

        'payment_method'     => $request->payment_method,

        'total_price'        => $subtotal, // Giá trị đơn hàng chưa giảm, chưa ship
        'discount_code'      => $discountCode,
        'discount_amount'    => $discountAmount,
        'final_amount'       => $finalAmount,
    ]);

    // Lưu từng sản phẩm
    foreach ($cartItems as $item) {
        Order_items::create([
            'order_id'           => $order->id,
            'product_id'         => $item->product->id,
            'product_variant_id' => $item->variant->id,
            'quantity'           => $item->quantity,
            'price'              => $item->variant->price,
            'total_price'        => $item->variant->price * $item->quantity,
            'product_name'       => $item->product->name,
            'variant_name'       => ($item->variant->color->name ?? '') . ' / ' . ($item->variant->size->name ?? ''),
            'product_image'      =>  optional($item->product->images->first())->image,
        ]);
    }

    // Xoá giỏ hàng của user
    Cart::where('user_id', $user->id)->delete();

    return redirect()->route('client.order.success', $order->id)
        ->with('success', 'Đặt hàng thành công!');
}

public function success($orderId)
{
    $order = Order::with(['orderItems.product', 'orderItems.productVariant'])->findOrFail($orderId);

    // Kiểm tra đơn có thuộc về người dùng hiện tại không (bảo mật)
    if ($order->user_id !== Auth::id()) {
        abort(403, 'Không có quyền xem đơn hàng này');
    }

    return view('client.order.success', compact('order'));
}

    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

     public function momo_payment(Request $request)
  {

    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

    $partnerCode = 'MOMOBKUN20180529';
    $accessKey = 'klm05TvNBzhg7h7j';
    $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    $orderInfo = "Thanh toán qua ATM MoMo";
    $amount = $_POST['total_momo'];
    $orderId = time() . "";
    $redirectUrl = "http://localhost:8080/weblinhkienmaytinh/checkout";
    $ipnUrl = "http://localhost:8080/weblinhkienmaytinh/checkout";
    $extraData = "";

    $requestId = time() . "";
    $requestType = "payWithATM";
    // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
    //before sign HMAC SHA256 signature
    $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
    $signature = hash_hmac("sha256", $rawHash, $secretKey);
    $data = array(
      'partnerCode' => $partnerCode,
      'partnerName' => "Test",
      "storeId" => "MomoTestStore",
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
    );
    $result = $this->execPostRequest($endpoint, json_encode($data));
    $jsonResult = json_decode($result, true);  // decode json

    //Just a example, please check more in there
    return redirect()->to($jsonResult['payUrl']);

  }

}

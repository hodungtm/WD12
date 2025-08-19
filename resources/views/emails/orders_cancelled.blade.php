<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng đã bị hủy</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 30px;">

    <div style="max-width: 650px; margin: auto; background: #fff; border-radius: 10px; padding: 25px 30px; box-shadow: 0 2px 12px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <h2 style="color: #2c3e50; margin-bottom: 10px;">Xin chào {{ $order->user->name }},</h2>
        <p style="font-size: 15px; color: #555; margin-bottom: 25px;">
            Rất tiếc 😔 đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã bị 
            <span style="color: red; font-weight: bold;">hủy</span>.
        </p>

        <!-- Order Items -->
        <h3 style="font-size: 16px; color: #333; margin-bottom: 15px; border-bottom: 2px solid #eee; padding-bottom: 8px;">
            Chi tiết đơn hàng đã hủy
        </h3>

        @foreach ($order->orderItems as $item)
            <div style="display: flex; align-items: center; margin-bottom: 15px; border-bottom: 1px dashed #eee; padding-bottom: 12px;">
                <img src="{{ $item->product->thumbnail_url }}" 
                     alt="{{ $item->product->name }}" 
                     width="70" height="70" 
                     style="border-radius: 6px; object-fit: cover; margin-right: 15px; border: 1px solid #ddd;">
                <div>
                    <p style="margin: 0; font-weight: bold; font-size: 15px; color: #333;">{{ $item->product->name }}</p>
                    <p style="margin: 3px 0 0; font-size: 14px; color: #555;">Số lượng: {{ $item->quantity }}</p>
                </div>
            </div>
        @endforeach

        <!-- Thank You -->
        <p style="margin-top: 25px; font-size: 15px; color: #333;">
            Nếu đây là sự nhầm lẫn, bạn có thể đặt lại đơn hàng trên website của chúng tôi.  
        </p>

        <!-- Footer -->
        <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #eee; text-align: center;">
            <p style="font-size: 13px; color: #777; margin: 0;">
                Nếu bạn cần hỗ trợ, vui lòng liên hệ CSKH
            </p>
            <p style="font-size: 14px; font-weight: bold; color: #0069d9; margin-top: 5px;">
                📞 0989 315 020
            </p>
        </div>
    </div>

</body>
</html>

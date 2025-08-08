<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cập nhật đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: white; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Chào {{ $order->user->name }},</h2>
        <p>Đơn hàng mã số <strong>#{{ $order->order_code }}</strong> của bạn đã được 
            <span style="color: green; font-weight: bold;">{{ $statusText }}</span>.
        </p>

        <h4 style="margin-top: 30px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Chi tiết đơn hàng</h4>
        @foreach ($order->orderItems as $item)
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <img src="{{ $item->product->thumbnail_url }}" alt="{{ $item->product->name }}" width="60" height="60" style="border-radius: 5px; object-fit: cover; margin-right: 15px;">
                <div>
                    <strong>{{ $item->product->name }}</strong><br>
                    Số lượng: {{ $item->quantity }}
                </div>
            </div>
        @endforeach

        <p style="margin-top: 30px;">Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi!</p>

        <p style="font-size: 13px; color: #999;">Nếu bạn có bất kỳ câu hỏi nào, xin vui lòng liên hệ đội ngũ chăm sóc khách hàng.</p>
        <p style="font-size: 13px; color: #1c0ee5; text-align: center;">0989315020</p>
    </div>
</body>
</html>

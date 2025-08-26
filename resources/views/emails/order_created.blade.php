<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: white; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

        <h2 style="color: #333;">Chào {{ $order->receiver_name }},</h2>
        <p>Đơn hàng mã số <strong>#{{ $order->order_code }}</strong> của bạn đã được tiếp nhận và hiện đang 
            <span style="color: orange; font-weight: bold;">chờ xác nhận</span>.
        </p>

        <h4 style="margin-top: 30px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Chi tiết đơn hàng</h4>
        @foreach ($order->orderItems as $item)
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <img src="{{ $item->product_image }}" 
                     alt="{{ $item->product_name }}" 
                     width="60" height="60" 
                     style="border-radius: 5px; object-fit: cover; margin-right: 15px;">
                <div>
                    <strong>{{ $item->product_name }}</strong><br>
                    Phân loại: {{ $item->variant_name ?? 'Không có' }}<br>
                    Số lượng: {{ $item->quantity }}<br>
                    Giá: {{ number_format($item->price, 0, ',', '.') }}₫
                </div>
            </div>
        @endforeach

        <hr style="margin: 20px 0;">

        <p><strong>Tạm tính:</strong> {{ number_format($order->total_price, 0, ',', '.') }}₫</p>
        <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }}₫</p>
        @if ($order->discount_amount > 0)
            <p><strong>Giảm giá:</strong> -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</p>
        @endif
        <p style="font-size: 16px; font-weight: bold;">Tổng thanh toán: {{ number_format($order->final_amount, 0, ',', '.') }}₫</p>

        <p style="margin-top: 30px;">Chúng tôi sẽ thông báo cho bạn khi đơn hàng được xác nhận và giao cho đơn vị vận chuyển.</p>

        <p style="font-size: 13px; color: #999;">Nếu bạn có bất kỳ câu hỏi nào, xin vui lòng liên hệ đội ngũ chăm sóc khách hàng.</p>
        <p style="font-size: 13px; color: #1c0ee5; text-align: center;">0989 315 020</p>

    </div>
</body>
</html>

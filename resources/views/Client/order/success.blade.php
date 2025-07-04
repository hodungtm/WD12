@extends('Client.Layouts.ClientLayout')
@section('main')

<section class="thank-you-section py-5">
    <div class="container text-center">
        <h2 class="mb-4 text-success">🎉 Đặt hàng thành công!</h2>
        <p class="mb-3">Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi.</p>

        <div class="card shadow-sm p-4 mt-4">
            <h5>Mã đơn hàng: <strong>{{ $order->order_code }}</strong></h5>
            <p>Ngày đặt: {{ $order->order_date->format('d/m/Y H:i') }}</p>
            <p>Người nhận: {{ $order->receiver_name }}</p>
            <p>Địa chỉ: {{ $order->receiver_address }}</p>
            <p>Số điện thoại: {{ $order->receiver_phone }}</p>
            <p>Phương thức giao hàng: {{ $order->shippingMethod->name ?? 'Không rõ' }}</p>
            <p class="text-danger fw-bold">Tổng thanh toán: {{ number_format($order->total_price, 0, ',', '.') }}₫</p>
        </div>

        <a href="{{ route('home') }}" class="btn btn-primary mt-4">Về trang chủ</a>
        {{-- <a href="{{ route('client.orders.list') }}" class="btn btn-outline-secondary mt-4">Xem lịch sử đơn hàng</a> --}}
    </div>
</section>

@endsection

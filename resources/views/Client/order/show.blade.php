@extends('Client.Layouts.ClientLayout')

@section('main')
<main class="main container my-5">
    <h3>Chi tiết đơn hàng: {{ $order->order_code }}</h3>

    <p><strong>Ngày đặt:</strong> {{ $order->order_date }}</p>
    <p><strong>Trạng thái:</strong> {{ $order->status }}</p>
    <p><strong>Thanh toán:</strong> {{ $order->payment_status }}</p>

    <h5>Thông tin người nhận</h5>
    <p>{{ $order->receiver_name }} - {{ $order->receiver_phone }}</p>
    <p>{{ $order->receiver_address }}</p>

    <h5>Danh sách sản phẩm</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Biến thể</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>
                    <img src="{{ asset('storage/' . $item->product_image) }}" alt="" width="50">
                    {{ $item->product_name }}
                </td>
                <td>{{ $item->variant_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Tổng cộng:</strong> {{ number_format($order->final_amount, 0, ',', '.') }}₫</p>

    <a href="{{ route('client.orders.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
</main>
@endsection

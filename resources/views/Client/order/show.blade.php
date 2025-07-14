@extends('Client.Layouts.ClientLayout')

@section('main')
<div class="container py-4">
    <div class="card mb-4">
        <div class="card-header">
            <strong>Chi tiết đơn hàng #{{ $order->code ?? $order->id }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái:</strong> <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span></p>
            <hr>
            <h5>Sản phẩm trong đơn hàng:</h5>
            <table class="table table-bordered">
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
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? $item->product_name ?? 'Sản phẩm đã xóa' }}</td>
                        <td>{{ $item->variant_name ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price) }}₫</td>
                        <td>{{ number_format($item->total_price) }}₫</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price ?? $order->orderItems->sum('total_price')) }}₫</p>
        </div>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection 
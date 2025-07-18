@extends('Client.Layouts.ClientLayout')

@section('main')
<main class="main container my-5">
    <h3>Theo dõi đơn hàng</h3>

    @php
        $statuses = [
            '' => 'Tất cả',
            'Đang chờ' => 'Đang chờ',
            'Đang giao hàng' => 'Đang giao hàng',
            'Hoàn thành' => 'Hoàn thành',
            'Đã hủy' => 'Đã hủy',
        ];
    @endphp

    <ul class="nav nav-tabs mb-4">
        @foreach($statuses as $key => $label)
            <li class="nav-item">
                <a class="nav-link {{ $status == $key ? 'active' : '' }}"
                   href="{{ route('client.orders.index', ['status' => $key]) }}">
                   {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>

    @if($orders->count())
        @foreach($orders as $order)
        <div class="card mb-3 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>Mã đơn:</strong> {{ $order->order_code }}<br>
                    <small>Ngày đặt: {{ $order->order_date }}</small>
                </div>
                <div>
                    <span class="badge bg-primary">{{ $order->status }}</span>
                </div>
            </div>
            <div class="card-body">
                @php
                    $firstItem = $order->orderItems->first();
                @endphp
                @if($firstItem)
                    <div class="d-flex">
                        <a href="{{ route('client.product.detail', $firstItem->product_id) }}">
                            <img src="{{ asset('storage/' . $firstItem->product_image) }}" alt="" width="80" height="80" class="me-3 rounded border">
                        </a>
                        <div class="flex-grow-1">
                            <h6><a href="{{ route('client.product.detail', $firstItem->product_id) }}" class="text-dark text-decoration-none">
                                {{ $firstItem->product_name }}
                            </a></h6>
                            <small>Phân loại: {{ $firstItem->variant_name ?? '-' }}</small><br>
                            <small>x{{ $firstItem->quantity }}</small>
                        </div>
                        <div class="text-end">
                            <del>{{ number_format($firstItem->price, 0, ',', '.') }}₫</del><br>
                            <strong>{{ number_format($firstItem->total_price, 0, ',', '.') }}₫</strong>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        Đơn hàng này không có sản phẩm nào.
                    </div>
                @endif
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <strong>Thành tiền: {{ number_format($order->final_amount, 0, ',', '.') }}₫</strong>
                <div>
                    @if($order->status === 'Đang chờ')
                    <form action="{{ route('client.orders.cancel', $order) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hủy đơn hàng này?')">Hủy</button>
                    </form>
                    @endif
                    @if($order->status === 'Hoàn thành' && $firstItem)
    <a href="{{ route('client.product.detail', [$firstItem->product_id, 'review' => 1]) }}" class="btn btn-sm btn-primary">
        Đánh giá
    </a>
@endif

                    <a href="{{ route('client.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">Xem chi tiết</a>
                </div>
            </div>
        </div>
        @endforeach

        {{ $orders->links() }}
    @else
        <p>Không có đơn hàng nào.</p>
    @endif
</main>
@endsection

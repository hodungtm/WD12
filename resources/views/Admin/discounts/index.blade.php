@extends('Admin.Layouts.AdminLayout')
@section('main')
<div class="container mt-4">
    <h2>Danh sách mã giảm giá</h2>
    <a href="{{ route('discounts.create') }}" class="btn btn-primary mb-3">+ Thêm mã</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Mô tả</th>
                <th>Giảm</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Lượt tối đa</th>
                <th>Đơn tối thiểu</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($discounts as $discount)
                <tr>
                    <td>{{ $discount->code }}</td>
                    <td>{{ $discount->description }}</td>
                    <td>
                        @if ($discount->discount_amount)
                            {{ number_format($discount->discount_amount) }} VNĐ
                        @else
                            {{ $discount->discount_percent }}%
                        @endif
                    </td>
                    <td>{{ $discount->start_date }}</td>
                    <td>{{ $discount->end_date }}</td>
                    <td>{{ $discount->max_usage }}</td>
                    <td>{{ number_format($discount->min_order_amount) }} VNĐ</td>
                    <td>
                        <a href="{{ route('discounts.edit', $discount) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('discounts.destroy', $discount) }}" method="POST" class="d-inline-block"
                              onsubmit="return confirm('Xóa mã này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

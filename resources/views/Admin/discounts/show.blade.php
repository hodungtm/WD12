@extends('Admin.Layouts.AdminLayout')

@section('title', 'Chi tiết mã giảm giá')

@section('main')
<div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Danh sách mã</a></li>
        <li class="breadcrumb-item active">Chi tiết mã giảm giá</li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Chi tiết mã giảm giá</h3>
            <div class="tile-body">
                <table class="table table-bordered">
                    <tr><th>ID</th><td>{{ $discount->id }}</td></tr>
                    <tr><th>Mã</th><td>{{ $discount->code }}</td></tr>
                    <tr><th>Mô tả</th><td>{{ $discount->description }}</td></tr>
                    <tr><th>Loại</th><td>{{ ucfirst($discount->type) }}</td></tr>
                    <tr><th>Giảm theo %</th><td>{{ $discount->discount_percent ? $discount->discount_percent . '%' : '-' }}</td></tr>
                    <tr><th>Giá trị giảm tối đa</th><td>{{ $discount->max_discount_amount ? number_format($discount->max_discount_amount) . ' VNĐ' : 'Không giới hạn' }}</td></tr>
                    <tr><th>Đơn hàng tối thiểu</th><td>{{ $discount->min_order_amount ? number_format($discount->min_order_amount) . ' VNĐ' : '-' }}</td></tr>
                    <tr><th>Số lượt sử dụng</th><td>{{ $discount->max_usage ?? '-' }}</td></tr>
                    <tr><th>Bắt đầu</th><td>{{ \Carbon\Carbon::parse($discount->start_date)->format('d/m/Y') }}</td></tr>
                    <tr><th>Kết thúc</th><td>{{ \Carbon\Carbon::parse($discount->end_date)->format('d/m/Y') }}</td></tr>
                </table>
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
            </div>
        </div>
    </div>
</div>
@endsection

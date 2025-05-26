@extends('Admin.Layouts.AdminLayout')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('main')
<div class="container">
    <h2>Chỉnh sửa mã giảm giá</h2>

    <form action="{{ route('discounts.update', $discount->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="code" class="form-label">Mã giảm giá</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $discount->code) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $discount->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="discount_amount" class="form-label">Số tiền giảm (VNĐ)</label>
            <input type="number" name="discount_amount" id="discount_amount" class="form-control" value="{{ old('discount_amount', $discount->discount_amount) }}">
        </div>

        <div class="mb-3">
            <label for="discount_percent" class="form-label">Phần trăm giảm (%)</label>
            <input type="number" name="discount_percent" id="discount_percent" class="form-control" value="{{ old('discount_percent', $discount->discount_percent) }}">
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label for="max_usage" class="form-label">Số lượt sử dụng tối đa</label>
            <input type="number" name="max_usage" id="max_usage" class="form-control" value="{{ old('max_usage', $discount->max_usage) }}">
        </div>

        <div class="mb-3">
            <label for="min_order_amount" class="form-label">Giá trị đơn hàng tối thiểu (VNĐ)</label>
            <input type="number" name="min_order_amount" id="min_order_amount" class="form-control" value="{{ old('min_order_amount', $discount->min_order_amount) }}">
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('discounts.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection

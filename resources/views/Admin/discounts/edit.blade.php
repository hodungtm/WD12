@extends('Admin.Layouts.AdminLayout')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('main')
<div class="container">
    <h2>Chỉnh sửa mã giảm giá</h2>

    <form action="{{ isset($discount) ? route('discounts.update', $discount->id) : route('discounts.store') }}" method="POST">
    @csrf
    @if(isset($discount)) @method('PUT') @endif

    <div class="mb-3">
        <label for="code" class="form-label">Mã giảm giá</label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $discount->code ?? '') }}">
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $discount->description ?? '') }}">
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="type" class="form-label">Phân loại mã</label>
        <select name="type" class="form-control @error('type') is-invalid @enderror">
            <option value="order" {{ old('type', $discount->type ?? '') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
            <option value="shipping" {{ old('type', $discount->type ?? '') == 'shipping' ? 'selected' : '' }}>Giao hàng</option>
            <option value="product" {{ old('type', $discount->type ?? '') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
        </select>
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="discount_amount" class="form-label">Giảm theo tiền</label>
        <input type="number" step="0.01" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" value="{{ old('discount_amount', $discount->discount_amount ?? '') }}">
        @error('discount_amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="discount_percent" class="form-label">Giảm theo %</label>
        <input type="number" step="0.01" name="discount_percent" class="form-control @error('discount_percent') is-invalid @enderror" value="{{ old('discount_percent', $discount->discount_percent ?? '') }}">
        @error('discount_percent')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="start_date" class="form-label">Ngày bắt đầu</label>
        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($discount->start_date) ? $discount->start_date->format('Y-m-d') : '') }}">
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="end_date" class="form-label">Ngày kết thúc</label>
        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($discount->end_date) ? $discount->end_date->format('Y-m-d') : '') }}">
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="max_usage" class="form-label">Số lượt tối đa</label>
        <input type="number" name="max_usage" class="form-control @error('max_usage') is-invalid @enderror" value="{{ old('max_usage', $discount->max_usage ?? '') }}">
        @error('max_usage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="min_order_amount" class="form-label">Giá trị đơn tối thiểu</label>
        <input type="number" step="0.01" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}">
        @error('min_order_amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Lưu</button>
</form>

</div>
@endsection

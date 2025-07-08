@extends('Admin.Layouts.AdminLayout')

@section('title', isset($discount) ? 'Chỉnh sửa mã giảm giá' : 'Thêm mã giảm giá')

@section('main')
<div class="container py-4">
    <div class="rounded shadow-sm p-4" style="background: #ffffff; color: #000; border: 50px solid #fefefe;">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h2 class="fw-bold mb-0" style="color: #45B8AC">
                {{ isset($discount) ? 'Chỉnh sửa mã giảm giá' : 'Thêm mã giảm giá' }}
            </h2>
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Danh sách
            </a>
        </div>

        <form action="{{ isset($discount) ? route('admin.discounts.update', $discount->id) : route('admin.discounts.store') }}" method="POST">
            @csrf
            @if(isset($discount)) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Mã giảm giá</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" placeholder="VD: SALE2025" value="{{ old('code', $discount->code ?? '') }}">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mô tả</label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Mô tả ngắn" value="{{ old('description', $discount->description ?? '') }}">
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phân loại mã</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="order" {{ old('type', $discount->type ?? '') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
                        <option value="shipping" {{ old('type', $discount->type ?? '') == 'shipping' ? 'selected' : '' }}>Giao hàng</option>
                        <option value="product" {{ old('type', $discount->type ?? '') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phần trăm giảm (%)</label>
                    <input type="number" name="discount_percent" step="0.01" min="0" max="100" class="form-control @error('discount_percent') is-invalid @enderror" placeholder="VD: 10" value="{{ old('discount_percent', $discount->discount_percent ?? '') }}">
                    @error('discount_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ngày bắt đầu</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($discount->start_date) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '') }}">
                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ngày kết thúc</label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($discount->end_date) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '') }}">
                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Số lượt sử dụng tối đa</label>
                    <input type="number" name="max_usage" class="form-control @error('max_usage') is-invalid @enderror" placeholder="VD: 100" value="{{ old('max_usage', $discount->max_usage ?? '') }}">
                    @error('max_usage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá trị đơn tối thiểu (VNĐ)</label>
                    <input type="number" step="0.01" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" placeholder="VD: 200000" value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}">
                    @error('min_order_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Giá trị giảm tối đa (VNĐ)</label>
                    <input type="number" name="max_discount_amount" step="1000" min="0" class="form-control @error('max_discount_amount') is-invalid @enderror" placeholder="VD: 50000" value="{{ old('max_discount_amount', $discount->max_discount_amount ?? '') }}">
                    @error('max_discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-times-circle me-1"></i> Hủy bỏ
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Lưu lại
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

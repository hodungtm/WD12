@extends('Admin.Layouts.AdminLayout')

@section('title', isset($discount) ? 'Chỉnh sửa mã giảm giá' : 'Thêm mã giảm giá')

@section('main')
<div class="container py-4">
    <div class="rounded shadow-sm p-4" style="background: #ffffff; color: #000; border: 2px solid #f1f1f1;">
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

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã giảm giá <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" placeholder="Nhập mã giảm giá" value="{{ old('code', $discount->code ?? '') }}">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Loại mã <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="">-- Chọn loại mã --</option>
                        <option value="order" {{ old('type', $discount->type ?? '') == 'order' ? 'selected' : '' }}>Theo đơn hàng</option>
                        <option value="shipping" {{ old('type', $discount->type ?? '') == 'shipping' ? 'selected' : '' }}>Giảm phí vận chuyển</option>
                        <option value="product" {{ old('type', $discount->type ?? '') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Nhập mô tả">{{ old('description', $discount->description ?? '') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($discount->start_date) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '') }}">
                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($discount->end_date) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '') }}">
                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Phần trăm giảm giá (%) <span class="text-danger">*</span></label>
                    <input type="number" name="discount_percent" step="0.01" min="1" max="100" class="form-control @error('discount_percent') is-invalid @enderror" value="{{ old('discount_percent', $discount->discount_percent ?? '') }}" placeholder="Nhập phần trăm giảm (ví dụ: 10)">
                    @error('discount_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="form-text text-muted">Giá trị từ 1 đến 100 (%)</small>
                </div>

                <div class="col-md-6">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Số lượt sử dụng</label>
                            <input type="number" name="max_usage" min="0" class="form-control @error('max_usage') is-invalid @enderror" value="{{ old('max_usage', $discount->max_usage ?? '') }}" placeholder="Không giới hạn">
                            @error('max_usage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Đơn hàng tối thiểu</label>
                            <input type="number" step="1000" min="0" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}" placeholder="0">
                            @error('min_order_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Giảm tối đa</label>
                            <input type="number" step="1000" min="0" name="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror" value="{{ old('max_discount_amount', $discount->max_discount_amount ?? '') }}" placeholder="0">
                            @error('max_discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="mb-2 text-start">
                        <small class="text-muted">Hoàn tất chỉnh sửa trước khi lưu</small>
                    </div>
                    <div class="text-start">
                        <button class="btn px-5 py-2 fw-bold text-white" type="submit" style="background-color: #41BFBF; font-size: 1.1rem;">
                            <i class="fas fa-save me-1"></i> Lưu mã giảm giá
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

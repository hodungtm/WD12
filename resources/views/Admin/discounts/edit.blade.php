@extends('Admin.Layouts.AdminLayout')

@section('title', isset($discount) ? 'Chỉnh sửa mã giảm giá' : 'Thêm mã giảm giá')

@section('main')
<div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Danh sách mã giảm giá</a></li>
        <li class="breadcrumb-item active">{{ isset($discount) ? 'Chỉnh sửa' : 'Thêm mới' }}</li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">{{ isset($discount) ? 'Chỉnh sửa mã giảm giá' : 'Thêm mã giảm giá' }}</h3>
            <div class="tile-body">
                <form action="{{ isset($discount) ? route('admin.discounts.update', $discount->id) : route('admin.discounts.store') }}" method="POST">
                    @csrf
                    @if(isset($discount)) @method('PUT') @endif

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Mã giảm giá</label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $discount->code ?? '') }}">
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label class="control-label">Mô tả</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $discount->description ?? '') }}">
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label class="control-label">Phân loại mã</label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="order" {{ old('type', $discount->type ?? '') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
                                <option value="shipping" {{ old('type', $discount->type ?? '') == 'shipping' ? 'selected' : '' }}>Giao hàng</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Giảm theo phần trăm (%)</label>
                            <input type="number" name="discount_percent" step="0.01" min="0" max="100" class="form-control @error('discount_percent') is-invalid @enderror" value="{{ old('discount_percent', $discount->discount_percent ?? '') }}">
                            @error('discount_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">Ngày bắt đầu</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($discount->start_date) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '') }}">
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">Ngày kết thúc</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($discount->end_date) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '') }}">
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Số lượt sử dụng tối đa</label>
                            <input type="number" name="max_usage" class="form-control @error('max_usage') is-invalid @enderror" value="{{ old('max_usage', $discount->max_usage ?? '') }}">
                            @error('max_usage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Giá trị đơn tối thiểu</label>
                            <input type="number" step="0.01" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}">
                            @error('min_order_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label>Giá trị giảm tối đa (VNĐ)</label>
                            <input type="number" name="max_discount_amount" step="1000" min="0" class="form-control @error('max_discount_amount') is-invalid @enderror" value="{{ old('max_discount_amount', $discount->max_discount_amount ?? '') }}">
                            @error('max_discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-save">Lưu lại</button>
                    <a href="{{ route('admin.discounts.index') }}" class="btn btn-cancel">Hủy bỏ</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

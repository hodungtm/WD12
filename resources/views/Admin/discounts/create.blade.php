@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="container mt-4">
    <h2>Thêm mã giảm giá</h2>

    <form action="{{ route('discounts.store') }}" method="POST">
        @csrf

        @include('admin.discounts.form')

        <div class="mb-3">
    <label for="type" class="form-label">Loại mã</label>
    <select name="type" id="type" class="form-control">
        <option value="order" {{ old('type', $discount->type ?? '') == 'order' ? 'selected' : '' }}>Theo đơn hàng</option>
        <option value="product" {{ old('type', $discount->type ?? '') == 'product' ? 'selected' : '' }}>Theo sản phẩm</option>
        <option value="shipping" {{ old('type', $discount->type ?? '') == 'shipping' ? 'selected' : '' }}>Giảm phí ship</option>
    </select>
</div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('discounts.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection

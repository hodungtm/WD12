@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="container mt-4">
    <h2>Thêm mã giảm giá</h2>

    <form action="{{ route('discounts.store') }}" method="POST">
        @csrf

        @include('admin.discounts.form')

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('discounts.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection

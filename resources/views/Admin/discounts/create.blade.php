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

                    @include('admin.discounts.form')

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

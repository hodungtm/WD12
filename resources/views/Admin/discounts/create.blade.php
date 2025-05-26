@extends('Admin.Layouts.AdminLayout')

@section('title', 'Thêm mã giảm giá')

@section('main')
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('discounts.index') }}">Danh sách mã giảm giá</a></li>
            <li class="breadcrumb-item active">Thêm mã giảm giá</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Thêm mã giảm giá</h3>
                <div class="tile-body">
                    <form action="{{ route('discounts.store') }}" method="POST">
                        @csrf

                        {{-- Gọi file form chung --}}
                        @include('admin.discounts.form')

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('discounts.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

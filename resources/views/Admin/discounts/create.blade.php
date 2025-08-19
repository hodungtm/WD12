@extends('admin.layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Thêm mã giảm giá</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.discounts.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.discounts.index') }}">
                            <div class="text-tiny">Ecommerce</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Thêm mã giảm giá</div>
                    </li>
                </ul>
            </div>
            <form action="{{ route('admin.discounts.store') }}" method="POST">
                @csrf
                @include('admin.discounts.form')
            </form>
        </div>
    </div>
@endsection

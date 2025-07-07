@extends('Admin.Layouts.AdminLayout')

@section('title', 'Thùng rác mã giảm giá')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Thùng rác mã giảm giá</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.discounts.index') }}">
                        <div class="text-tiny">Mã giảm giá</div>
                    </a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Thùng rác</div>
                </li>
            </ul>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-3" id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="wg-box mt-3">
            <div class="flex items-center justify-between gap10 flex-wrap mb-3">
                <h5 class="fw-bold text-primary m-0">Danh sách mã giảm giá đã xóa</h5>
                <a href="{{ route('admin.discounts.index') }}" class="tf-button style-1">
                    <i class="icon-arrow-left"></i> Quay lại danh sách
                </a>
            </div>

            <div class="wg-table table-all-category mt-3">
                <ul class="table-title flex mb-14">
                    <li style="width: 20%">Mã giảm giá</li>
                    <li style="width: 50%">Mô tả</li>
                    <li style="width: 30%">Hành động</li>
                </ul>

                <ul class="flex flex-column">
                    @forelse ($discounts as $discount)
                        <li class="product-item flex mb-10 align-items-center">
                            <div style="width: 20%" class="fw-semibold">{{ $discount->code }}</div>
                            <div style="width: 50%">{{ $discount->description }}</div>
                            <div style="width: 30%">
                                <form action="{{ route('admin.discounts.restore', $discount->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="tf-button style-1 btn-sm me-2">
                                        <i class="icon-rotate-ccw"></i> Khôi phục
                                    </button>
                                </form>
                                <form action="{{ route('admin.discounts.forceDelete', $discount->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn chắc chắn muốn xóa vĩnh viễn mã giảm giá này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tf-button style-2 btn-sm">
                                        <i class="icon-trash-2"></i> Xóa vĩnh viễn
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-muted py-3">Không có mã giảm giá nào đã xóa.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.opacity = 0;
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }
    });
</script>
@endsection

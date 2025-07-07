@extends('Admin.Layouts.AdminLayout')

@section('title', 'Chi tiết mã giảm giá')

@section('main')
<div class="container-fluid px-0">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm border-start border-4" style="border-color: #41BFBF;">
            <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Danh sách mã</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết mã giảm giá</li>
        </ol>
    </nav>

    <!-- Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header text-white" style="background-color: #41BFBF;">
            <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Chi tiết mã giảm giá</h5>
        </div>

        <div class="card-body px-4 py-3">
            @php
                function field($label, $value) {
                    return "
                    <div class='row mb-3'>
                        <label class='col-sm-4 col-form-label text-muted fw-semibold'>$label:</label>
                        <div class='col-sm-8'>$value</div>
                    </div>
                    ";
                }
            @endphp

            {!! field('Mã giảm giá', $discount->code) !!}
            {!! field('Loại mã', match($discount->type) {
                'order' => '<span class="badge bg-primary">Theo đơn hàng</span>',
                'shipping' => '<span class="badge bg-success">Phí vận chuyển</span>',
                'product' => '<span class="badge bg-warning text-dark">Sản phẩm</span>',
                default => '<span class="badge bg-secondary">Khác</span>',
            }) !!}
            {!! field('Mô tả', $discount->description ?? 'Không có mô tả') !!}
            {!! field('Giảm (%)', $discount->discount_percent ? $discount->discount_percent . '%' : '-') !!}
            {!! field('Giảm tối đa', $discount->max_discount_amount ? number_format($discount->max_discount_amount) . ' VNĐ' : 'Không giới hạn') !!}
            {!! field('Đơn hàng tối thiểu', $discount->min_order_amount ? number_format($discount->min_order_amount) . ' VNĐ' : '-') !!}
            {!! field('Số lượt sử dụng tối đa', $discount->max_usage ?? 'Không giới hạn') !!}
            {!! field('Ngày bắt đầu', \Carbon\Carbon::parse($discount->start_date)->format('d/m/Y')) !!}
            {!! field('Ngày kết thúc', \Carbon\Carbon::parse($discount->end_date)->format('d/m/Y')) !!}
        </div>

        <div class="card-footer bg-light text-end">
            <a href="{{ route('admin.discounts.index') }}" class="btn" style="background-color: #41BFBF; color: white;">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>
</div>
@endsection

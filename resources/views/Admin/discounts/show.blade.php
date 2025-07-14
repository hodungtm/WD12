@extends('admin.layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chi tiết mã giảm giá: {{ $discount->code }}</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.discounts.index') }}">
                            <div class="text-tiny">Mã giảm giá</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chi tiết</div>
                    </li>
                </ul>
            </div>
            {{-- Box: Thông tin mã giảm giá --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-percent"></i>
                    <div class="body-text">Thông tin mã giảm giá</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Mã giảm giá:</label>
                        <p>{{ $discount->code }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Loại mã:</label>
                        <p>
                            @if($discount->type === 'order')
                                <span class="badge-status badge-instock">Đơn hàng</span>
                            @elseif($discount->type === 'shipping')
                                <span class="badge-status badge-outstock">Vận chuyển</span>
                            @elseif($discount->type === 'product')
                                <span class="badge-status badge-instock">Sản phẩm</span>
                            @else
                                <span class="badge-status badge-outstock">Khác</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            {{-- Box: Điều kiện áp dụng --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-settings"></i>
                    <div class="body-text">Điều kiện áp dụng</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Ngày bắt đầu:</label>
                        <p>{{ $discount->start_date ? \Carbon\Carbon::parse($discount->start_date)->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Ngày kết thúc:</label>
                        <p>{{ $discount->end_date ? \Carbon\Carbon::parse($discount->end_date)->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Phần trăm giảm giá:</label>
                        <p>{{ $discount->discount_percent ? $discount->discount_percent . '%' : '-' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Giảm tối đa:</label>
                        <p>{{ $discount->max_discount_amount ? number_format($discount->max_discount_amount) . ' VNĐ' : 'Không giới hạn' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Đơn hàng tối thiểu:</label>
                        <p>{{ $discount->min_order_amount ? number_format($discount->min_order_amount) . ' VNĐ' : '-' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Số lượt sử dụng tối đa:</label>
                        <p>{{ $discount->max_usage ?? 'Không giới hạn' }}</p>
                    </div>
                </div>
            </div>
            {{-- Box: Mô tả --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-file-text"></i>
                    <div class="body-text">Mô tả</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full">
                        <label class="body-title">Mô tả:</label>
                        <p>{!! nl2br(e($discount->description)) !!}</p>
                    </div>
                </div>
            </div>
                <div class="flex gap10 mt-4">
                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="tf-button"><i class="icon-edit-3 me-1"></i> Chỉnh sửa</a>
                    <a href="{{ route('admin.discounts.index') }}" class="tf-button style-1"><i class="icon-arrow-left me-1"></i> Quay lại</a>
                </div>
            </div>
        </div>
    </div>
@endsection

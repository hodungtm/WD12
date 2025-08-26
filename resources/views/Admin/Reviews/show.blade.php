@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chi tiết đánh giá</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.reviews.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.reviews.index') }}">
                            <div class="text-tiny">Đánh giá</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chi tiết đánh giá</div>
                    </li>
                </ul>
            </div>

            {{-- Box: Thông tin người dùng & sản phẩm --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-user"></i>
                    <div class="body-text">Thông tin người dùng & sản phẩm</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Người dùng:</label>
                        <div class="body-text" style="font-size: 16px; color: #333; font-weight: 500;">{{ $review->user->name ?? 'N/A' }}</div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Sản phẩm:</label>
                        <div class="body-text" style="font-size: 16px; color: #333; font-weight: 500;">{{ $review->product->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            {{-- Box: Nội dung đánh giá --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-star"></i>
                    <div class="body-text">Nội dung đánh giá</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Số sao:</label>
                        <div class="rating-display" style="display: inline-block;">
                            <span style="color: #f39c12; font-size: 24px; font-weight: 600;">{{ $review->so_sao }} ⭐</span>
                            <span style="color: #666; margin-left: 8px;">/ 5 sao</span>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Trạng thái:</label>
                        <div class="status-badge" style="display: inline-block;">
                            <div class="{{ $review->trang_thai ? 'block-available' : 'block-stock' }} bg-1 fw-7" style="display: inline-block; min-width: 80px; text-align: center; border-radius: 8px; padding: 6px 18px; font-size: 15px; font-weight: 600; background: #f3f7f6; color: {{ $review->trang_thai ? '#1abc9c' : '#e67e22' }}; letter-spacing: 0.5px;">
                                {{ $review->trang_thai ? 'Hiển thị' : 'Ẩn' }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <label class="body-title">Nội dung đánh giá:</label>
                        <div class="body-text" style="font-size: 15px; color: #555; line-height: 1.6; background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #f39c12;">{!! nl2br(e($review->noi_dung ?? 'Không có nội dung')) !!}</div>
                    </div>
                </div>
            </div>
            {{-- Box: Thông tin hệ thống --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-info"></i>
                    <div class="body-text">Thông tin hệ thống</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Ngày tạo:</label>
                        <span class="body-text" style="font-weight: 500;">{{ $review->created_at ? $review->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Cập nhật lần cuối:</label>
                        <span class="body-text" style="font-weight: 500;">{{ $review->updated_at ? $review->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex justify-start gap-3 mt-4">
                <a href="{{ route('admin.reviews.edit', $review->id) }}" class="tf-button btn-sm w-auto px-3 py-2">
                    <i class="icon-edit-3"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                    <i class="icon-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
@endsection

@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chi tiết bình luận</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('Admin.comments.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('Admin.comments.index') }}">
                            <div class="text-tiny">Bình luận</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chi tiết bình luận</div>
                    </li>
                </ul>
            </div>

            {{-- Box: Thông tin sản phẩm --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-package"></i>
                    <div class="body-text">Thông tin sản phẩm</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full">
                        <label class="body-title">Sản phẩm:</label>
                        <div class="body-text" style="font-size: 16px; color: #333; font-weight: 500;">{{ $comment->product->name ?? '[Sản phẩm đã xóa]' }}</div>
                    </div>
                </div>
            </div>
            {{-- Box: Thông tin bình luận --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-message-circle"></i>
                    <div class="body-text">Thông tin bình luận</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Tác giả:</label>
                        <div class="body-text" style="font-size: 16px; color: #333; font-weight: 500;">{{ $comment->user->name ?? 'N/A' }}</div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Trạng thái:</label>
                        <div class="status-badge" style="display: inline-block;">
                            <div class="{{ $comment->trang_thai ? 'block-available' : 'block-stock' }} bg-1 fw-7" style="display: inline-block; min-width: 80px; text-align: center; border-radius: 8px; padding: 6px 18px; font-size: 15px; font-weight: 600; background: #f3f7f6; color: {{ $comment->trang_thai ? '#1abc9c' : '#e67e22' }}; letter-spacing: 0.5px;">
                                {{ $comment->trang_thai ? 'Hiển thị' : 'Ẩn' }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <label class="body-title">Nội dung bình luận:</label>
                        <div class="body-text" style="font-size: 15px; color: #555; line-height: 1.6; background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #1abc9c;">{!! nl2br(e($comment->noi_dung)) !!}</div>
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
                        <span class="body-text" style="font-weight: 500;">{{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Cập nhật lần cuối:</label>
                        <span class="body-text" style="font-weight: 500;">{{ $comment->updated_at ? $comment->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex justify-start gap-3 mt-4">
                <a href="{{ route('Admin.comments.edit', $comment->id) }}" class="tf-button btn-sm w-auto px-3 py-2">
                    <i class="icon-edit-3"></i> Chỉnh sửa
                </a>
                <a href="{{ route('Admin.comments.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                    <i class="icon-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
@endsection

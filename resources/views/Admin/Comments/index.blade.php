@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Quản lý bình luận</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('Admin.comments.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Bình luận</div>
                    </li>
                </ul>
            </div>

    

    <!-- Thông báo -->
    @if (session('success'))
                <div class="alert"
                    style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
                    <i class="icon-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert"
                    style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
                    <i class="icon-alert-triangle" style="margin-right: 6px;"></i> {{ session('error') }}
                </div>
            @endif

    <!-- Bộ lọc + chọn số dòng -->
   

            <div class="wg-box">
                <div class="title-box">
                    <i class="icon-message-circle"></i>
                    <div class="body-text">Tìm kiếm và quản lý bình luận của khách hàng.</div>
                </div>
                <div class="flex flex-column gap10 mb-3">
                    <form method="GET" action="{{ route('Admin.comments.index') }}" class="form-search w-100" style="margin-bottom: 10px;">
                        <div class="search-input" style="width: 100%; position: relative;">
                            <input type="text" placeholder="Tìm kiếm bình luận..." name="keyword" value="{{ request('keyword') }}" style="width: 100%; min-width: 200px;">
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff; position: absolute; right: 5px; top: 50%; transform: translateY(-50%);">
                                <i class="icon-search" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </div>
                    </form>
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <div class="flex gap10 flex-wrap align-items-center">
                            <form method="GET" action="{{ route('Admin.comments.index') }}" class="flex gap10 flex-wrap align-items-center" style="margin-bottom: 0;">
                                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                                <select name="trang_thai" class="form-select" style="width: 140px;">
                                    <option value="">-- Trạng thái --</option>
                                    <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Đã duyệt</option>
                                    <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Chưa duyệt</option>
                                </select>
                                <select name="sort" class="form-select" style="width: 120px;">
                                    <option value="">-- Sắp xếp --</option>
                                    <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                                </select>
                                <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff;">
                                    <i class="icon-filter" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                      <div class="wg-table table-product-list mt-3">
                    <ul class="table-title flex mb-14" style="gap: 2px;">
                        <li style="flex-basis: 60px;"><div class="body-title">ID</div></li>
                        <li style="flex-basis: 200px;"><div class="body-title">Sản phẩm</div></li>
                        <li style="flex-basis: 150px;"><div class="body-title">Tác giả</div></li>
                        <li style="flex-basis: 300px;"><div class="body-title">Nội dung</div></li>
                        <li style="flex-basis: 120px;"><div class="body-title">Trạng thái</div></li>
                        <li style="flex-basis: 120px;"><div class="body-title">Ngày tạo</div></li>
                        <li style="flex-basis: 120px;"><div class="body-title">Hành động</div></li>
                    </ul>
                    <ul class="flex flex-column">
                        @forelse ($comments as $comment)
                            <li class="wg-product item-row" style="gap: 2px;">
                                <div class="body-text mt-4" style="flex-basis: 60px;">#{{ $comment->id }}</div>
                                <div class="body-text mt-4" style="flex-basis: 200px;">
                                    <span title="{{ $comment->product?->name ?? '[Sản phẩm đã xóa]' }}">
                                        {{ \Illuminate\Support\Str::limit($comment->product?->name ?? '[Sản phẩm đã xóa]', 30) }}
                                    </span>
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 150px;">{{ $comment->user->name ?? 'N/A' }}</div>
                                <div class="body-text mt-4" style="flex-basis: 300px;">
                                    <span title="{{ $comment->noi_dung }}">
                                        {{ \Illuminate\Support\Str::limit($comment->noi_dung, 80) }}
                                    </span>
                                </div>
                                <div style="flex-basis: 120px;">
                                    <div class="{{ $comment->trang_thai ? 'block-available' : 'block-stock' }} bg-1 fw-7" style="display: inline-block; min-width: 80px; text-align: center; border-radius: 8px; padding: 6px 18px; font-size: 15px; font-weight: 600; background: #f3f7f6; color: {{ $comment->trang_thai ? '#1abc9c' : '#e67e22' }}; letter-spacing: 0.5px; vertical-align: middle; margin-top: 2px;">
                                        {{ $comment->trang_thai ? 'Hiển thị' : 'Ẩn' }}
                                    </div>
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 120px;">{{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                                <div class="list-icon-function" style="flex-basis: 120px;">
                                    <a href="{{ route('Admin.comments.show', $comment->id) }}" class="item eye"><i class="icon-eye"></i></a>
                                    <a href="{{ route('Admin.comments.edit', $comment->id) }}" class="item edit"><i class="icon-edit-3"></i></a>
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-muted py-3">Không tìm thấy bình luận nào.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">Hiển thị từ {{ $comments->firstItem() }} đến {{ $comments->lastItem() }} trong tổng số {{ $comments->total() }} bình luận</div>
                    {{ $comments->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

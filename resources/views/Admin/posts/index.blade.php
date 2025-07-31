@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Danh sách bài viết</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">Bài viết</div></li>
                </ul>
            </div>
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
            <div class="wg-box">
                <div class="title-box">
                    <i class="icon-book-open"></i>
                    <div class="body-text">Tìm kiếm bài viết theo tiêu đề hoặc lọc theo trạng thái.</div>
                </div>
                <div class="flex flex-column gap10 mb-3">
                    <form method="GET" action="{{ route('posts.index') }}" class="form-search w-100" style="margin-bottom: 10px;">
                        <div class="search-input" style="width: 100%; position: relative;">
                            <input type="text" placeholder="Tìm kiếm bài viết..." name="keyword" value="{{ request('keyword') }}" style="width: 100%; min-width: 200px;">
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff; position: absolute; right: 5px; top: 50%; transform: translateY(-50%);">
                                <i class="icon-search" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </div>
                    </form>
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <form method="GET" action="{{ route('posts.index') }}" class="flex gap10 flex-wrap align-items-center" style="margin-bottom: 0;">
                            <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                            <select name="status" class="form-select" style="width: 120px;">
                                <option value="">-- Trạng thái --</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã đăng</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                                <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                            <input type="date" name="created_from" class="form-control" style="width: 140px;" value="{{ request('created_from') }}" placeholder="Từ ngày">
                            <input type="date" name="created_to" class="form-control" style="width: 140px;" value="{{ request('created_to') }}" placeholder="Đến ngày">
                            <select name="per_page" class="form-select" style="width: 110px;">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 dòng</option>
                                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25 dòng</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 dòng</option>
                            </select>
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff;">
                                <i class="icon-filter" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </form>
                        <div class="flex gap10">
                            <a href="{{ route('posts.create') }}" class="tf-button style-1 w200">
                                <i class="icon-plus"></i> Thêm bài viết
                            </a>
                        </div>
                    </div>
                </div>
                
                    <div class="wg-table table-product-list mt-3">
                        <ul class="table-title flex mb-14" style="gap: 2px;">
                            <li style="flex-basis: 40px;"><div class="body-title">ID</div></li>
                            <li style="flex-basis: 250px;"><div class="body-title">Tiêu đề</div></li>
                            <li style="flex-basis: 80px;"><div class="body-title">Ảnh</div></li>
                            <li style="flex-basis: 80px;"><div class="body-title">Trạng thái</div></li>
                            <li style="flex-basis: 100px;"><div class="body-title">Ngày tạo</div></li>
                            <li style="flex-basis: 120px;"><div class="body-title">Hành động</div></li>
                        </ul>
                        <ul class="flex flex-column">
                            @forelse ($posts as $post)
                                <li class="wg-product item-row" style="gap: 2px; align-items:center; border-bottom:1px solid #f0f0f0;">
                                    <div class="body-text mt-4" style="flex-basis: 40px;">#{{ $post->id }}</div>
                                    <div class="title line-clamp-2 mb-0" style="flex-basis: 250px;">
                                        <a href="{{ route('posts.show', $post) }}" class="body-text">{{ $post->title }}</a>
                                    </div>
                                    <div class="image" style="flex-basis: 80px;">
                                        @if ($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" width="50" class="rounded" alt="Ảnh">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </div>
                                    <div style="flex-basis: 80px;">
                                        @if ($post->status === 'published')
                                            <div class="block-available bg-1 fw-7">Đã đăng</div>
                                        @elseif($post->status === 'draft')
                                            <div class="block-stock bg-1 fw-7">Nháp</div>
                                        @else
                                            <div class="block-stock bg-warning fw-7">Ẩn</div>
                                        @endif
                                    </div>
                                    <div class="body-text mt-4" style="flex-basis: 100px;">{{ $post->created_at ? $post->created_at->format('d/m/Y') : 'N/A' }}</div>
                                    <div class="list-icon-function flex justify-center gap10" style="flex-basis: 120px;">
                                        <a href="{{ route('posts.show', $post) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
                                        <a href="{{ route('posts.edit', $post) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="color: red" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                <i class="icon-trash" style="color: red; font-size: 20px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-muted py-3">Chưa có bài viết nào.</li>
                            @endforelse
                        </ul>
                    </div>
                
                @if ($posts->hasPages())
                    <div class="mt-3">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
@endsection

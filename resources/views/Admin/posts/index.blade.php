@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Danh sách bài viết</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#">
                        <div class="text-tiny">Dashboard</div>
                    </a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Danh sách bài viết</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form method="GET" action="{{ route('posts.index') }}" class="form-search flex items-center gap10">
                        <div class="select">
                            <select name="status" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã đăng</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                                <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                        <div class="select">
                            <select name="per_page" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <fieldset class="name">
                            <input type="text" name="keyword" placeholder="Tìm kiếm..." value="{{ request('keyword') }}">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="flex gap10">
                    <a href="{{ route('posts.create') }}" class="tf-button style-1">
                        <i class="icon-plus"></i> Tạo mới bài viết
                    </a>
                </div>
            </div>

            @if (session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif

            <form action="{{ route('posts.delete.selected') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa các bài viết đã chọn?');">
                @csrf
                @method('DELETE')
                <div class="wg-table table-all-category mt-3">
                    <ul class="table-title flex mb-14">
                        <li style="width: 3%"><input type="checkbox" id="check_all"></li>
                        <li style="width: 5%">ID</li>
                        <li style="width: 25%">Tiêu đề</li>
                        <li style="width: 15%">Ảnh</li>
                        <li style="width: 10%">Trạng thái</li>
                        <li style="width: 15%">Ngày tạo</li>
                        <li style="width: 25%">Hành động</li>
                    </ul>
                    <ul class="flex flex-column">
                        @forelse ($posts as $post)
                            <li class="product-item flex mb-10">
                                <div style="width: 3%"><input type="checkbox" name="selected_posts[]" value="{{ $post->id }}" class="check_item"></div>
                                <div style="width: 5%">{{ $post->id }}</div>
                                <div style="width: 25%">{{ $post->title }}</div>
                                <div style="width: 15%">
                                    @if ($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Ảnh" style="max-width: 80px; max-height: 80px;">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </div>
                                <div style="width: 10%">
                                    @if ($post->status === 'published')
                                        <span class="badge bg-success">Đã đăng</span>
                                    @elseif($post->status === 'draft')
                                        <span class="badge bg-secondary">Nháp</span>
                                    @else
                                        <span class="badge bg-warning">Ẩn</span>
                                    @endif
                                </div>
                                <div style="width: 15%">{{ $post->created_at->format('d/m/Y') }}</div>
                                <div class="col-action list-icon-function" style="width: 25%">
                                    <a href="{{ route('posts.show', $post) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
                                    <a href="{{ route('posts.edit', $post) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
<form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="item trash" title="Xóa" style="background: none; border: none;">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-muted">Chưa có bài viết nào.</li>
                        @endforelse
                    </ul>
                </div>

                <button type="submit" class="tf-button style-1 mt-3">
                    <i class="icon-trash"></i> Xóa các bài viết đã chọn
                </button>
            </form>

            <div class="divider mt-3"></div>
            <div class="flex items-center justify-between flex-wrap gap10">
                <div class="text-tiny">Tổng: {{ $posts->total() }} bài viết</div>
                {{ $posts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('check_all').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('.check_item');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>

    <script>
        document.getElementById('check_all').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('.check_item');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>

@endsection
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

            <div class="wg-box">
                <div class="title-box">
                    <i class="icon-book-open"></i>
                    <div class="body-text">Tìm kiếm bài viết theo tiêu đề hoặc lọc theo trạng thái.</div>
                </div>

                {{-- Thanh filter --}}
                <div class="flex items-center justify-between gap10 flex-wrap mb-3">
                    <div class="wg-filter flex-grow">
                        {{-- Lọc số lượng --}}
                        <div class="show">
                            <div class="text-tiny">Hiển thị</div>
                            <div class="select">
                                <form method="GET" action="{{ route('posts.index') }}">
                                    <select name="per_page" onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                </form>
                            </div>
                            <div class="text-tiny">bài viết</div>
                        </div>

                        {{-- Form tìm kiếm --}}
                        <form method="GET" action="{{ route('posts.index') }}" class="form-search mt-2">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm bài viết..." name="keyword"
                                    value="{{ request('keyword') }}">
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>

                    {{-- Nút tạo mới --}}
                    <a href="{{ route('posts.create') }}" class="tf-button style-1 w200">
                        <i class="icon-plus"></i> Thêm bài viết
                    </a>
                </div>

                {{-- Bảng danh sách --}}
              <form action="{{ route('posts.delete.selected') }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn xóa các bài viết đã chọn?');">
                    @csrf
                    @method('DELETE')

                   <div class="wg-table table-product-list mt-3">
                        <ul class="table-title flex mb-14" style="gap: 2px;"> {{-- Reduced gap for header --}}
                            <li style="flex-basis: 25px;"><div class="body-title"><input type="checkbox" id="check_all"></div></li>
                            <li style="flex-basis: 40px;"><div class="body-title">ID</div></li>
                            <li style="flex-basis: 250px;"><div class="body-title">Tiêu đề</div></li> {{-- Adjust title width --}}
                            <li style="flex-basis: 80px;"><div class="body-title">Ảnh</div></li>
                            <li style="flex-basis: 80px;"><div class="body-title">Trạng thái</div></li>
                            <li style="flex-basis: 100px;"><div class="body-title">Ngày tạo</div></li>
                            <li style="flex-basis: 120px;"><div class="body-title">Hành động</div></li>
                        </ul>

                        <ul class="flex flex-column">
                            @forelse ($posts as $post)
                                <li class="wg-product item-row" style="gap: 2px;"> {{-- Reduced gap for rows --}}
                                    <div style="flex-basis: 25px;"><input type="checkbox" name="selected_posts[]" value="{{ $post->id }}" class="check_item"></div>
                                    <div class="body-text mt-4" style="flex-basis: 40px;">#{{ $post->id }}</div>
                                    <div class="title line-clamp-2 mb-0" style="flex-basis: 250px;">
                                        <a href="{{ route('posts.show', $post) }}" class="body-text">{{ $post->title }}</a>
                                    </div>
                                    <div class="image" style="flex-basis: 80px;">
                                        @if ($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" width="50" class="rounded" alt="Ảnh"> {{-- Smaller image width --}}
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
                                    <div class="body-text mt-4" style="flex-basis: 100px;">{{ $post->created_at->format('d/m/Y') }}</div>
                                    <div class="list-icon-function" style="flex-basis: 120px;">
                                        <a href="{{ route('posts.show', $post) }}" class="item eye"><i class="icon-eye"></i></a>
                                        <a href="{{ route('posts.edit', $post) }}" class="item edit"><i class="icon-edit-3"></i></a>
                                        <button type="submit" formaction="{{ route('posts.destroy', $post) }}"
                                            formmethod="POST" class="item trash" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            @csrf @method('DELETE') <i class="icon-trash-2"></i>
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center text-muted py-3">Chưa có bài viết nào.</li>
                            @endforelse
                        </ul>
                    </div>

                    <button type="submit" class="tf-button style-1 w120" style="margin-top: 20px;">
                        <i class="fas fa-trash-alt"></i> Xóa các bài viết đã chọn
                    </button>
                </form>

                {{-- Phân trang --}}
                @if ($posts->hasPages())
                    <div class="mt-3">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>


<script>
    document.getElementById('check_all').addEventListener('change', function () {
        document.querySelectorAll('.check_item').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection

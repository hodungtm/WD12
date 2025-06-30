@extends('admin.layouts.Adminlayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề và breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Danh sách bài viết</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Bài viết</div></li>
      </ul>
    </div>

    <!-- Lọc + Thêm mới -->
    <div class="wg-box">
      <div class="flex items-center justify-between gap10 flex-wrap mb-4">
        <div class="wg-filter flex-grow">
          <form class="form-search" method="GET" action="{{ route('posts.index') }}">
            <fieldset class="name">
              <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm theo tiêu đề...">
            </fieldset>
            <div class="button-submit">
              <button type="submit"><i class="icon-search"></i></button>
            </div>
          </form>
        </div>
        <a class="tf-button style-1 w208" href="{{ route('posts.create') }}"><i class="icon-plus"></i> Thêm mới</a>
      </div>

      <!-- Bảng bài viết -->
      <div class="wg-table table-all-user">
        <!-- Tiêu đề cột -->
        <ul class="table-title flex gap20 mb-14">
          <li style="flex: 2"><div class="body-title">Tiêu đề</div></li>
          <li style="flex: 1"><div class="body-title">Ảnh</div></li>
          <li style="flex: 1"><div class="body-title">Trạng thái</div></li>
          <li style="flex: 1"><div class="body-title">Ngày tạo</div></li>
          <li style="flex: 1"><div class="body-title">Hành động</div></li>
        </ul>

        <!-- Danh sách -->
        <ul class="flex flex-column">
          @forelse ($posts as $post)
          <li class="user-item gap14">
            <div class="flex items-center justify-between gap20 flex-grow">
              <!-- Tiêu đề -->
              <div style="flex: 2">
                <a href="#" class="body-title-2">{{ $post->title }}</a>
                <div class="text-tiny mt-1">#{{ $post->id }}</div>
              </div>

              <!-- Ảnh -->
              <div style="flex: 1">
                @if ($post->image)
                  <img src="{{ asset('storage/' . $post->image) }}" alt="Ảnh" style="width: 80px; height: 80px; object-fit: cover;" class="rounded shadow-sm">
                @else
                  <span class="text-muted">Không có ảnh</span>
                @endif
              </div>

              <!-- Trạng thái -->
              <div style="flex: 1">
                @if ($post->status === 'published')
                  <span class="badge bg-success">Đã đăng</span>
                @elseif ($post->status === 'draft')
                  <span class="badge bg-secondary">Nháp</span>
                @else
                  <span class="badge bg-warning">Ẩn</span>
                @endif
              </div>

              <!-- Ngày tạo -->
              <div style="flex: 1">
                <div class="body-text">{{ $post->created_at->format('d/m/Y') }}</div>
              </div>

              <!-- Hành động -->
              <div class="list-icon-function" style="flex: 1">
                <a href="{{ route('posts.show', $post) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
                <a href="{{ route('posts.edit', $post) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa bài viết này?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="item trash" title="Xóa" style="background: none; border: none;"><i class="icon-trash-2"></i></button>
                </form>
              </div>
            </div>
          </li>
          @empty
          <li class="text-muted p-4">Không có bài viết nào</li>
          @endforelse
        </ul>
      </div>

      <!-- Phân trang -->
      <div class="divider my-4"></div>
      <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Tổng cộng {{ $posts->total() }} bài viết</div>
        <div>
          {{ $posts->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

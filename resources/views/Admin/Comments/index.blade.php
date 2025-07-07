@extends('Admin.Layouts.AdminLayout')
@section('main')

<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề + breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Quản lý bình luận</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Bình luận</div></li>
      </ul>
    </div>

    <!-- Thông báo -->
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Bộ lọc -->
    <form method="GET" action="{{ route('Admin.comments.index') }}" class="flex flex-wrap gap-3 mb-4 align-items-center">
      <input type="text" name="keyword" class="form-control" placeholder="Tìm sản phẩm, tác giả..."
        value="{{ request('keyword') }}" style="min-width: 200px; max-width: 300px;">

      <select name="trang_thai" class="form-select" style="width: 150px;">
        <option value="">Trạng thái </option>
        <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Đã duyệt</option>
        <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Chưa duyệt</option>
      </select>

      <select name="sort" class="form-select " style="width: 150px;">
        <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>Mới nhất</option>
        <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>Cũ nhất</option>
      </select>

      <button class="tf-button style-1" type="submit">
        <i class="icon-search me-1"></i> Tìm kiếm
      </button>
    </form>

    <!-- Danh sách bình luận -->
    <!-- Danh sách bình luận -->
    <div class="wg-box">
      <div class="wg-table table-all-user">

        <!-- Tiêu đề bảng -->
        <ul class="table-title flex gap20 mb-14">
          <li class="col-id"><div class="body-title">ID</div></li>
          <li class="col-product"><div class="body-title">Sản phẩm</div></li>
          <li class="col-author"><div class="body-title">Tác giả</div></li>
          <li class="col-content"><div class="body-title">Nội dung</div></li>
          <li class="col-status"><div class="body-title">Trạng thái</div></li>
          <li class="col-date"><div class="body-title">Ngày tạo</div></li>
          <li class="col-action"><div class="body-title">Hành động</div></li>
        </ul>

        @forelse ($comments as $comment)
        <ul class="user-item flex gap20 mb-2">
          <li class="col-id">{{ $comment->id }}</li>
          <li class="col-product">{{ $comment->product?->name ?? '[Sản phẩm đã xóa]' }}</li>
          <li class="col-author">{{ $comment->tac_gia }}</li>
          <li class="col-content">{{ \Illuminate\Support\Str::limit($comment->noi_dung, 40) }}</li>
          <li class="col-status">
            @if(!$comment->trang_thai)
              <form method="POST" action="{{ route('Admin.comments.approve', $comment->id) }}">
                @csrf
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                <input type="hidden" name="page" value="{{ request('page') }}">
                <button type="submit" class="tf-button style-2 small">Duyệt</button>
              </form>
            @else
              <span class="badge bg-success">Đã duyệt</span>
            @endif
          </li>
          <li class="col-date">{{ $comment->created_at->format('d/m/Y') }}</li>
          <li class="col-action list-icon-function">
            <a href="{{ route('Admin.comments.show', $comment->id) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
            <a href="{{ route('Admin.comments.edit', $comment->id) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
          </li>
        </ul>
        @empty
          <div class="text-muted px-3">Không có bình luận nào.</div>
        @endforelse
      </div>

      <!-- Phân trang -->
      <div class="divider"></div>
      <div class="flex justify-between align-items-center mt-3">
        <div class="text-tiny">Tổng: {{ $comments->total() }} bình luận</div>
        <div>
          {{ $comments->links('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ CSS fix lệch cột -->
<style>
  .table-title li,
  .user-item li {
    display: flex;
    align-items: center;
    padding: 6px 5px;
  }

  .col-id      { flex: 0.5; min-width: 40px; }
  .col-product { flex: 1.8; min-width: 140px; }
  .col-author  { flex: 1.5; min-width: 120px; }
  .col-content { flex: 2.5; min-width: 200px; }
  .col-status  { flex: 1.2; min-width: 100px; }
  .col-date    { flex: 1.2; min-width: 100px; }
  .col-action  { flex: 1.2; min-width: 120px; display: flex; gap: 10px; }
</style>

@endsection

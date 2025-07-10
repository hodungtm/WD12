@extends('admin.layouts.AdminLayout')
@section('main')

<div class="main-content-inner" style="padding-top: 10px; margin-top: 0;">
  <div class="main-content-wrap" style="padding-top: 0; margin-top: 0;">
    <!-- Tiêu đề + breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-30">
      <h3>Quản lý bình luận</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng điều khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Bình luận</div></li>
      </ul>
    </div>

    

    <!-- Thông báo -->
    

    <!-- Bộ lọc + chọn số dòng -->
   

    <!-- Danh sách bình luận -->
    <div class="wg-box">
      <!-- Dòng hướng dẫn tìm kiếm -->
    <div class="flex items-center gap10 mb-3" style="color:#1abc9c; font-size:16px;">
      <i class="icon-coffee" style="font-size:20px;"></i>
      <span>Tip: Bạn có thể tìm kiếm theo <b>ID</b> hoặc <b>tên sản phẩm</b> để lọc nhanh bình luận.</span>
    </div>
    <form method="GET" action="{{ route('Admin.comments.index') }}" class="flex flex-wrap gap-3 mb-4 align-items-center">
      <div class="flex items-center gap10">
        <label for="per_page" class="text-tiny" style="color:#222;">Hiển thị</label>
        <select name="per_page" id="per_page" class="form-select" style="width: 70px;" onchange="this.form.submit()">
          @foreach([10, 20, 50, 100] as $num)
            <option value="{{ $num }}" {{ request('per_page', 10) == $num ? 'selected' : '' }}>{{ $num }}</option>
          @endforeach
        </select>
        <span class="text-tiny" style="color:#222;">dòng</span>
      </div>
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
      <div class="wg-table table-all-user">
        <!-- Tiêu đề bảng -->
        <ul class="table-title flex gap0 mb-14 table-row-align">
          <li class="col-id"><div class="body-title">ID</div></li>
          <li class="col-product"><div class="body-title">Sản phẩm</div></li>
          <li class="col-author"><div class="body-title">Tác giả</div></li>
          <li class="col-content"><div class="body-title">Nội dung</div></li>
          <li class="col-status"><div class="body-title">Trạng thái</div></li>
          <li class="col-date"><div class="body-title">Ngày tạo</div></li>
          <li class="col-action"><div class="body-title">Hành động</div></li>
        </ul>
        @forelse ($comments as $comment)
        <ul class="user-item flex gap0 mb-0 table-row-align" style="border-bottom:1px solid #f2f2f2;">
          <li class="col-id">{{ $comment->id }}</li>
          <li class="col-product" title="{{ $comment->product?->name ?? '[Sản phẩm đã xóa]' }}"><span class="ellipsis">{{ $comment->product?->name ?? '[Sản phẩm đã xóa]' }}</span></li>
          <li class="col-author">{{ $comment->user->name ?? 'N/A' }}</li>
          <li class="col-content" title="{{ $comment->noi_dung }}"><span class="ellipsis">{{ \Illuminate\Support\Str::limit($comment->noi_dung, 60) }}</span></li>
          <li class="col-status">
            @if($comment->trang_thai)
              <span class="badge-status badge-instock">Hiện</span>
            @else
              <span class="badge-status badge-outstock">Ẩn</span>
            @endif
          </li>
          <li class="col-date">{{ $comment->created_at->format('d/m/Y') }}</li>
          <li class="col-action">
            <a href="{{ route('Admin.comments.show', $comment->id) }}" title="Xem" class="action-icon"><i class="icon-eye"></i></a>
            <a href="{{ route('Admin.comments.edit', $comment->id) }}" title="Sửa" class="action-icon edit"><i class="icon-edit-3"></i></a>
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

<!-- ✅ CSS căn đều cột, badge trạng thái stock, ellipsis cho text dài -->
<style>
  .table-title, .user-item { width: 100%; }
  .table-title li, .user-item li {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 14px 10px;
    font-size: 16px;
    box-sizing: border-box;
    word-break: break-word;
    background: none;
    border: none;
    min-height: 48px;
  }
  .table-row-align { align-items: stretch !important; }
  .col-id      { flex: 0.5 0 40px; min-width: 40px; justify-content: flex-start; }
  .col-product { flex: 1.8 0 140px; min-width: 140px; justify-content: flex-start; }
  .col-author  { flex: 1.5 0 120px; min-width: 120px; justify-content: flex-start; }
  .col-content { flex: 2.5 0 200px; min-width: 200px; justify-content: flex-start; }
  .col-status  { flex: 1.2 0 140px; min-width: 140px; justify-content: flex-start; }
  .col-date    { flex: 1.2 0 100px; min-width: 100px; justify-content: flex-start; }
  .col-action  { flex: 1.2 0 120px; min-width: 120px; display: flex; gap: 10px; align-items: center; justify-content: flex-start; }
  .badge-status {
    display: inline-block;
    padding: 6px 18px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    background: #f3f7f6;
    color: #1abc9c;
    letter-spacing: 0.5px;
    vertical-align: middle;
    margin-top: 2px;
  }
  .badge-instock {
    background: #f3f7f6;
    color: #1abc9c;
  }
  .badge-outstock {
    background: #fbeee7;
    color: #e67e22;
  }
  .action-icon {
    color: #2d9cdb;
    font-size: 20px;
    margin-right: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
  }
  .action-icon.edit { color: #f7b731; margin-right: 0; }
  .ellipsis {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 320px;
  }
  @media (max-width: 1200px) {
    .ellipsis { max-width: 180px; }
  }
  @media (max-width: 900px) {
    .table-title li, .user-item li { font-size:15px; }
    .col-content { min-width:120px; }
    .ellipsis { max-width: 100px; }
  }
</style>

@endsection

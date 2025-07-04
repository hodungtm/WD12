@extends('Admin.Layouts.AdminLayout')

@section('main')
<main class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề + breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Danh sách đánh giá</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Đánh giá</div></li>
      </ul>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Bộ lọc -->
    <form action="{{ route('Admin.reviews.index') }}" method="GET" class="flex flex-wrap gap-3 mb-4 align-items-center">
      <input type="text" name="product_name" class="form-control" placeholder="Tìm theo sản phẩm..."
        value="{{ request('product_name') }}" style="min-width: 200px; max-width: 300px;">

      <select name="so_sao" class="form-select" style="width: 150px;">
        <option value="">Số sao</option>
        @for ($i = 1; $i <= 5; $i++)
          <option value="{{ $i }}" {{ request('so_sao') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
        @endfor
      </select>

      <select name="trang_thai" class="form-select" style="width: 150px;">
        <option value="">Trạng thái</option>
        <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Hiển thị</option>
        <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Ẩn</option>
      </select>

      <select name="sort" class="form-select" style="width: 150px;">
        <option value="">Sắp xếp</option>
        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
      </select>

      <button class="tf-button style-1" type="submit">
        <i class="icon-search me-1"></i> Tìm kiếm
      </button>
    </form>

    <!-- Danh sách đánh giá -->
    <!-- Danh sách đánh giá -->
    <div class="wg-box">
      <div class="wg-table table-all-user">

        <!-- Tiêu đề bảng -->
        <ul class="table-title flex gap20 mb-14">
          <li class="col-id"><div class="body-title">ID</div></li>
          <li class="col-user"><div class="body-title">Người dùng</div></li>
          <li class="col-product"><div class="body-title">Sản phẩm</div></li>
          <li class="col-star"><div class="body-title">Số sao</div></li>
          <li class="col-content"><div class="body-title">Nội dung</div></li>
          <li class="col-status"><div class="body-title">Trạng thái</div></li>
          <li class="col-date"><div class="body-title">Ngày tạo</div></li>
          <li class="col-action"><div class="body-title">Hành động</div></li>
        </ul>

        <!-- Dữ liệu -->
        @forelse ($reviews as $review)
        <ul class="user-item flex gap20 mb-2">
          <li class="col-id">{{ $review->id }}</li>
          <li class="col-user">{{ $review->user->name ?? 'N/A' }}</li>
          <li class="col-product">{{ $review->product->name ?? 'N/A' }}</li>
          <li class="col-star">{{ $review->so_sao }} ⭐</li>
          <li class="col-content">{{ Str::limit($review->noi_dung, 60) }}</li>
          <li class="col-status">
            {!! $review->trang_thai
              ? '<span class="badge bg-success">Hiển thị</span>'
              : '<span class="badge bg-secondary">Ẩn</span>' !!}
          </li>
          <li class="col-date">{{ $review->created_at->format('d/m/Y') }}</li>
          <li class="col-action list-icon-function">
            <a href="{{ route('Admin.reviews.show', $review->id) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
            <a href="{{ route('Admin.reviews.edit', $review->id) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
            <form action="{{ route('Admin.reviews.destroy', $review->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="item trash" style="background: transparent; border: none;" title="Xóa">
                <i class="icon-trash-2"></i>
              </button>
            </form>
          </li>
        </ul>
        @empty
          <div class="text-muted px-3">Không có đánh giá nào.</div>
        @endforelse
      </div>

      <!-- Phân trang -->
      <div class="divider"></div>
      <div class="flex justify-between align-items-center mt-3">
        <div class="text-tiny">Tổng: {{ $reviews->total() }} đánh giá</div>
        <div>
          {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
</main>
<!-- CSS fix lệch cột -->
<style>
  .table-title li,
  .user-item li {
    display: flex;
    align-items: center;
    padding: 6px 5px;
  }

  .col-id       { flex: 0.5; min-width: 40px; }
  .col-user     { flex: 1.5; min-width: 120px; }
  .col-product  { flex: 1.5; min-width: 120px; }
  .col-star     { flex: 1; min-width: 80px; }
  .col-content  { flex: 2.5; min-width: 200px; }
  .col-status   { flex: 1; min-width: 100px; }
  .col-date     { flex: 1; min-width: 100px; }
  .col-action   { flex: 1.2; min-width: 120px; display: flex; gap: 10px; }
</style>
@endsection

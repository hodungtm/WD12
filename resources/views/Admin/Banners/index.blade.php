@extends('Admin.Layouts.AdminLayout')
@section('title', 'Danh sách banner | Quản trị Admin')

@section('main')

<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề + breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Danh sách banner</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Banner</div></li>
      </ul>
    </div>


                <!-- Phân trang -->
                <div class="divider"></div>
                <div class="flex justify-between align-items-center mt-3">
                    <div class="text-tiny">Tổng: {{ $banners->total() }} banner</div>
                    <div>
                        {{ $banners->links('pagination::bootstrap-4') }}
                    </div>
                </div>

    <div class="wg-box">
      <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
          <form method="GET" action="{{ route('admin.banners.index') }}" class="form-search flex items-center gap10">
            <fieldset class="name">
              <input type="text" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </fieldset>
            <div class="button-submit">
              <button type="submit"><i class="icon-search"></i></button>

            </div>
          </form>
        </div>
        <div class="flex gap10">
          <a class="tf-button style-1 w208" href="{{ route('admin.banners.create') }}">
            <i class="icon-plus"></i> Thêm mới
          </a>
        </div>
      </div>

      <div class="wg-table table-all-category mt-3">
        <ul class="table-title flex mb-14">
          <li class="col-id"><div class="body-title">ID</div></li>
          <li class="col-title"><div class="body-title">Tiêu đề</div></li>
          <li class="col-images"><div class="body-title">Ảnh</div></li>
          <li class="col-type"><div class="body-title">Loại banner</div></li>
          <li class="col-status"><div class="body-title">Trạng thái</div></li>
          <li class="col-action"><div class="body-title">Hành động</div></li>
        </ul>

        <ul class="flex flex-column">
          @forelse ($banners as $banner)
          <li class="product-item flex mb-10">
            <div class="col-id">{{ $loop->iteration }}</div>
            <div class="col-title">{{ $banner->tieu_de }}</div>
            <div class="col-images">
              @if ($banner->hinhAnhBanner && $banner->hinhAnhBanner->count())
                @foreach ($banner->hinhAnhBanner as $img)
                  <img src="{{ asset('storage/' . $img->hinh_anh) }}" alt="ảnh banner" style="max-width: 80px; max-height: 80px;" class="me-1 mb-1">
                @endforeach
              @else
                <span class="text-muted">Chưa có ảnh</span>
              @endif
            </div>
            <div class="col-type">{{ $banner->loai_banner }}</div>
            <div class="col-status">
              <span class="badge {{ $banner->trang_thai === 'hien' ? 'bg-success' : 'bg-warning' }}">
                {{ $banner->trang_thai === 'hien' ? 'Hiện' : 'Ẩn' }}
              </span>
            </div>
            <div class="col-action list-icon-function">
              <a href="{{ route('admin.banners.show', $banner->id) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
              <a href="{{ route('admin.banners.edit', $banner->id) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
              <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Bạn có chắc muốn xóa banner này không?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="item trash" title="Xóa" style="background: none; border: none;">
                  <i class="icon-trash-2"></i>
                </button>
              </form>
            </div>
          </li>
          @empty
          <div class="text-muted px-3">Chưa có banner nào.</div>
          @endforelse
        </ul>
      </div>

      <div class="divider mt-3"></div>
      <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Tổng: {{ $banners->total() }} banner</div>
        {{ $banners->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('fade');
                alertBox.style.transition = "opacity 0.5s";
                alertBox.style.opacity = 0;
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }
    });
  document.addEventListener('DOMContentLoaded', function () {
    // XÓA ĐOẠN HIỂN THỊ LỖI/THÔNG BÁO Ở ĐÂY
  });
</script>

<style>
  .table-title li,
  .product-item > div {
    display: flex;
    align-items: center;
    padding: 6px 5px;
  }

  .col-id      { flex: 0.3; min-width: 40px; }
  .col-title   { flex: 1.5; min-width: 120px; }
  .col-images  { flex: 2; min-width: 150px; flex-wrap: wrap; gap: 3px; }
  .col-type    { flex: 1; min-width: 80px; }
  .col-status  { flex: 1; min-width: 80px; }
  .col-action  { flex: 1.2; min-width: 120px; display: flex; gap: 10px; }
</style>

@endsection

@extends('Admin.Layouts.AdminLayout')

@section('main')
  <div class="main-content-inner">
    <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Danh sách danh mục</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Danh mục</div></li>
        </ul>
      </div>
      <div class="wg-box">
        <div class="title-box">
          <i class="icon-book-open"></i>
          <div class="body-text">Tìm kiếm danh mục theo tên hoặc lọc theo trạng thái.</div>
        </div>
        <div class="flex flex-column gap10 mb-3">
          <form method="GET" action="{{ route('Admin.categories.index') }}" class="form-search w-100" style="margin-bottom: 10px;">
            <fieldset class="name" style="width: 100%;">
              <input type="text" placeholder="Tìm kiếm danh mục..." name="keyword" value="{{ request('keyword') }}" style="width: 100%; min-width: 200px;">
              <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff; position: absolute; right: 5px; top: 50%; transform: translateY(-50%);">
                <i class="icon-search" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
              </button>
            </fieldset>
          </form>
          <div class="flex items-center justify-between gap10 flex-wrap">
            <div class="flex gap10 flex-wrap align-items-center">
              <form method="GET" action="{{ route('Admin.categories.index') }}" class="flex gap10 flex-wrap align-items-center" style="margin-bottom: 0;">
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                <select name="status" class="form-select" style="width: 120px;">
                  <option value="">-- Trạng thái --</option>
                  <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                  <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
                <select name="sort_created" class="form-select" style="width: 120px;">
                  <option value="">-- Ngày tạo --</option>
                  <option value="desc" {{ request('sort_created') === 'desc' ? 'selected' : '' }}>Mới nhất</option>
                  <option value="asc" {{ request('sort_created') === 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                </select>
                <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff;">
                  <i class="icon-filter" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                </button>
              </form>
            </div>
            <div class="flex gap10">
              <a href="{{ route('Admin.categories.create') }}" class="tf-button style-1 w200">
                <i class="icon-plus"></i> Thêm danh mục
              </a>
            </div>
          </div>
        </div>
        <div class="wg-table table-product-list mt-3">
          <ul class="table-title flex mb-14" style="gap: 2px;">
            <li style="flex-basis: 40px;"><div class="body-title">ID</div></li>
            <li style="flex-basis: 200px;"><div class="body-title">Tên danh mục</div></li>
            <li style="flex-basis: 80px;"><div class="body-title">Ảnh</div></li>
            <li style="flex-basis: 120px;"><div class="body-title">Trạng thái</div></li>
            <li style="flex-basis: 180px;"><div class="body-title">Mô tả</div></li>
            <li style="flex-basis: 120px;"><div class="body-title">Hành động</div></li>
          </ul>
          <ul class="flex flex-column">
            @foreach($categories as $cat)
              <li class="wg-product item-row" style="gap: 2px;">
                <div class="body-text mt-4" style="flex-basis: 40px;">#{{ $cat->id }}</div>
                <div class="title line-clamp-2 mb-0" style="flex-basis: 200px;">{{ $cat->ten_danh_muc }}</div>
                <div class="image" style="flex-basis: 80px;">
                  @if($cat->anh)
                    <img src="{{ asset('storage/' . $cat->anh) }}" width="50" class="rounded" alt="Ảnh">
                  @else
                    <span class="text-muted">Không có ảnh</span>
                  @endif
                </div>
                <div style="flex-basis: 120px;">
                  <div class="{{ $cat->tinh_trang == 1 ? 'block-available' : 'block-stock' }} bg-1 fw-7">
                    {{ $cat->tinh_trang == 1 ? 'Hiển thị' : 'Ẩn' }}
                  </div>
                </div>
                <div class="body-text mt-4" style="flex-basis: 180px;">{{ $cat->mo_ta }}</div>
                <div class="list-icon-function" style="flex-basis: 120px;">
                  <a href="{{ route('Admin.categories.show', $cat->id) }}" class="item eye"><i class="icon-eye"></i></a>
                  <a href="{{ route('Admin.categories.edit', $cat->id) }}" class="item edit"><i class="icon-edit-3"></i></a>
                  <form action="{{ route('Admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: red" title="Xóa danh mục">
                      <i class="icon-trash" style="color: red; font-size: 20px;"></i>
                    </button>
                  </form>
                </div>
              </li>
            @endforeach
          </ul>
        </div>
        <div class="divider"></div>
        <div class="flex items-center justify-between flex-wrap gap10">
          <div class="text-tiny">Hiển thị từ {{ $categories->firstItem() }} đến {{ $categories->lastItem() }} trong tổng số {{ $categories->total() }} danh mục</div>
          {{ $categories->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
@endsection
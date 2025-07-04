@extends('admin.layouts.AdminLayout')

@section('main')

  <div class="main-content-inner">
    <div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Danh sách danh mục</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
      <li><a href="#">
        <div class="text-tiny">Dashboard</div>
        </a></li>
      <li><i class="icon-chevron-right"></i></li>
      <li><a href="#">
        <div class="text-tiny">Danh mục</div>
        </a></li>
      <li><i class="icon-chevron-right"></i></li>
      <li>
        <div class="text-tiny">Danh sách danh mục</div>
      </li>
      </ul>
    </div>

    <div class="wg-box">
      <div class="flex items-center justify-between gap10 flex-wrap">
      <div class="wg-filter flex-grow">
        <form action="{{ route('Admin.categories.index') }}" method="GET" class="form-search flex items-center gap10">
        <div class="select">
          <select name="tinh_trang" onchange="this.form.submit()">
          <option value=""> Tình trạng </option>
          <option value="1" {{ request('tinh_trang') == '1' ? 'selected' : '' }}>Hiện</option>
          <option value="0" {{ request('tinh_trang') == '0' ? 'selected' : '' }}>Ẩn</option>
          </select>
        </div>
        <div class="select">
          <select name="sort" onchange="this.form.submit()">
          <option value=""> Sắp xếp </option>
          <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
          <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
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
        <a href="{{ route('Admin.categories.trash') }}" class="tf-button style-1">
        <i class="icon-trash"></i> Thùng rác
        </a>
        <a class="tf-button style-1 w208" href="{{ route('Admin.categories.create') }}">
        <i class="icon-plus"></i> Thêm mới
        </a>
      </div>
      </div>

      <div class="wg-table table-all-category mt-3">
      <ul class="table-title flex mb-14">
        <li class="col-name">
        <div class="body-title">Tên danh mục</div>
        </li>
        <li class="col-image">
        <div class="body-title">Ảnh</div>
        </li>
        <li class="col-status">
        <div class="body-title">Tình trạng</div>
        </li>
        <li class="col-description">
        <div class="body-title">Mô tả</div>
        </li>
        <li class="col-action">
        <div class="body-title">Hành động</div>
        </li>
      </ul>

      <ul class="flex flex-column">
        @foreach($categories as $cat)
      <li class="product-item flex mb-10">
      <div class="col-name">
        <a class="body-title-2">{{ $cat->ten_danh_muc }}</a>
      </div>
      <div class="col-image">
        @if($cat->anh)
      <img src="{{ asset('storage/' . $cat->anh) }}" alt="" style="max-width: 80px; max-height: 80px;">
      @else
      <span class="text-muted">Không có ảnh</span>
      @endif
      </div>
      <div class="col-status body-text">{{ $cat->tinh_trang ? 'Hiện' : 'Ẩn' }}</div>
      <div class="col-description body-text">{{ $cat->mo_ta }}</div>
      <div class="col-action list-icon-function">
        <a href="{{ route('Admin.categories.show', $cat->id) }}" class="item eye" title="Xem"><i
        class="icon-eye"></i></a>
        <a href="{{ route('Admin.categories.edit', $cat->id) }}" class="item edit" title="Sửa"><i
        class="icon-edit-3"></i></a>
        <form action="{{ route('Admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline"
        onsubmit="return confirm('Bạn có chắc muốn xóa?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="item trash" title="Xóa" style="background: none; border: none;">
        <i class="icon-trash-2"></i>
        </button>
        </form>
      </div>
      </li>
      @endforeach
      </ul>

      </div>

      <div class="divider mt-3"></div>
      <div class="flex items-center justify-between flex-wrap gap10">
      <div class="text-tiny">Tổng: {{ $categories->total() }} danh mục</div>
      {{ $categories->links('pagination::bootstrap-5') }}
      </div>
    </div>
    </div>
  </div>

@endsection
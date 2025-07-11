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
        <div class="flex items-center justify-between gap10 flex-wrap mb-3">
          <div class="wg-filter flex-grow">
            <form method="GET" action="{{ route('Admin.categories.index') }}" class="form-search mt-2">
              <fieldset class="name">
                <input type="text" placeholder="Tìm kiếm danh mục..." name="keyword" value="{{ request('keyword') }}">
              </fieldset>
              <div class="button-submit">
                <button type="submit"><i class="icon-search"></i></button>
              </div>
            </form>
          </div>
          <a href="{{ route('Admin.categories.create') }}" class="tf-button style-1 w200">
            <i class="icon-plus"></i> Thêm danh mục
          </a>
        </div>
        <div class="wg-table table-product-list mt-3">
          <ul class="table-title flex mb-14" style="gap: 2px;">
            <li style="flex-basis: 40px;"><div class="body-title">ID</div></li>
            <li style="flex-basis: 200px;"><div class="body-title">Tên danh mục</div></li>
            <li style="flex-basis: 80px;"><div class="body-title">Ảnh</div></li>
            <li style="flex-basis: 120px;"><div class="body-title">Tình trạng</div></li>
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
                <div class="body-text mt-4" style="flex-basis: 120px;">
                  @if($cat->tinh_trang)
                    <span class="badge-status badge-instock">Hiện</span>
                  @else
                    <span class="badge-status badge-outstock">Ẩn</span>
                  @endif
                </div>
                <div class="body-text mt-4" style="flex-basis: 180px;">{{ $cat->mo_ta }}</div>
                <div class="list-icon-function" style="flex-basis: 120px;">
                  <a href="{{ route('Admin.categories.show', $cat->id) }}" class="item eye"><i class="icon-eye"></i></a>
                  <a href="{{ route('Admin.categories.edit', $cat->id) }}" class="item edit"><i class="icon-edit-3"></i></a>
                  <form action="{{ route('Admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?');" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Xóa" style="background: none; border: none;"><i class="icon-trash-2" style="color: red; font-size: 22px;"></i></button>
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

  <style>
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
  </style>
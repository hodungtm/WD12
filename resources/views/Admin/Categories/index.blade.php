@extends('admin.layouts.AdminLayout')

@section('main')
  <div class="main-content-inner" style="padding-top: 10px; margin-top: 0;">
    <div class="main-content-wrap" style="padding-top: 0; margin-top: 0;">
      <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Danh sách danh mục</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#">
            <div class="text-tiny">Bảng điều khiển</div>
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
        <!-- Dòng hướng dẫn tìm kiếm -->
      <div class="flex items-center gap10 mb-3" style="color:#1abc9c; font-size:16px;">
        <i class="icon-coffee" style="font-size:20px;"></i>
        <span>Tip: Bạn có thể tìm kiếm theo <b>ID</b> hoặc <b>tên danh mục</b> để lọc nhanh.</span>
      </div>
        <div class="flex items-center justify-between gap10 flex-wrap">
          <div class="wg-filter flex-grow">
            <form action="{{ route('Admin.categories.index') }}" method="GET" class="form-search flex items-center gap10">
              <div class="flex items-center gap10">
                <label for="per_page" class="text-tiny" style="color:#222;">Hiển thị</label>
                <select name="per_page" id="per_page" class="form-select" style="width: 70px;" onchange="this.form.submit()">
                  @foreach([10, 20, 50, 100] as $num)
                    <option value="{{ $num }}" {{ request('per_page', 10) == $num ? 'selected' : '' }}>{{ $num }}</option>
                  @endforeach
                </select>
                <span class="text-tiny" style="color:#222;">dòng</span> <!-- Dropdown chọn số dòng -->
              </div>
              <div class="select">
                <select name="tinh_trang" class="form-control" style="height: 40px; min-width: 110px;"
                onchange="this.form.submit()">
                  <option value=""> Tình trạng </option>
                  <option value="1" {{ request('tinh_trang') == '1' ? 'selected' : '' }}>Hiện</option>
                  <option value="0" {{ request('tinh_trang') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
              </div>
              <div class="select">
                <select name="sort" class="form-control" style="height: 40px; min-width: 100px;"
                onchange="this.form.submit()">
                  <option value=""> Sắp xếp </option>
                  <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                  <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                </select>
              </div>
             
              
              <div class="flex" style="align-items:center;">
                <input type="text" name="keyword" class="form-control"
                style="height: 40px; border-top-right-radius:0; border-bottom-right-radius:0; min-width:200px;"
                placeholder="Tìm kiếm..." value="{{ request('keyword') }}">
                <button type="submit"
                style="height: 40px; width: 40px; border-top-left-radius:0; border-bottom-left-radius:0; border:1px solid #ccc; border-left:0; background:#fff; display: flex; align-items: center; justify-content: center;">
                <i class="icon-search"></i>
                </button>
              </div>
            </form>
          </div>
          <div class="flex gap10">
            <a class="tf-button style-1 w208" href="{{ route('Admin.categories.create') }}">
            <i class="icon-plus"></i> Thêm mới
            </a>
          </div>
        </div>

        <div class="wg-table table-all-category mt-3"
        style="display: inline-block; min-width: unset; width: auto; max-width: 100%;">
        <ul class="table-title flex mb-14" style="gap:0;">
          <li class="col-name" style="width:180px; text-align:center;">
          <div class="body-title">Tên danh mục</div>
          </li>
          <li class="col-status" style="width:100px; text-align:center;">
          <div class="body-title">Tình trạng</div>
          </li>
          <li class="col-description" style="width:180px; text-align:center;">
          <div class="body-title">Mô tả</div>
          </li>
          <li class="col-action" style="width:100px; text-align:center;">
          <div class="body-title">Hành động</div>
          </li>
        </ul>

        <ul class="flex flex-column">
          @foreach($categories as $cat)
        <li class="wg-product item-row flex align-items-center mb-10" style="gap:0;">
        <div class="col-name flex items-center" style="width:180px; text-align:center; justify-content:center;">
          @if($cat->anh)
        <img src="{{ asset('storage/' . $cat->anh) }}" alt=""
        style="max-width: 32px; max-height: 32px; border-radius: 6px; margin-right:8px;">
        @else
        <span class="text-muted">Không có ảnh</span>
        @endif
          <span class="body-text">{{ $cat->ten_danh_muc }}</span>
        </div>
        <div class="col-status body-text" style="width:100px; text-align:center;">
          @if($cat->tinh_trang)
            <span class="badge-status badge-instock">Hiện</span>
          @else
            <span class="badge-status badge-outstock">Ẩn</span>
          @endif
        </div>
        <div class="col-description body-text" style="width:180px; text-align:center;">{{ $cat->mo_ta }}</div>
        <div class="col-action list-icon-function" style="width:100px; text-align:center;">
          <a href="{{ route('Admin.categories.show', $cat->id) }}" class="item eye" title="Xem"><i
          class="icon-eye"></i></a>
          <a href="{{ route('Admin.categories.edit', $cat->id) }}" class="item edit" title="Sửa"><i
          class="icon-edit-3"></i></a>
          <form action="{{ route('Admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Bạn có chắc muốn xóa?');" style="display:inline">
          @csrf
          @method('DELETE')
          <button type="submit" title="Xóa" style="background: none; border: none;">
            <i class="icon-trash-2" style="color: red; font-size: 22px;"></i>
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
@endsection
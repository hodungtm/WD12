@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item active"><a href="#"><b>Danh sách danh mục</b></a></li>
    </ul>
    <div id="clock"></div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="row element-button">
            <div class="col-sm-2">
              <a class="btn btn-add btn-sm" href="{{ route('Admin.categories.create') }}" title="Thêm">
                <i class="fas fa-plus"></i> Tạo mới danh mục
              </a>
              <a href="{{ route('Admin.categories.trash') }}" class="btn btn-warning">
                <i class="fas fa-trash-restore"></i> Thùng rác
              </a>
              
            </div>
            
          </div>
          <form method="GET" action="{{ route('Admin.categories.index') }}" class="row g-3 align-items-center mb-3">
            <div class="col-auto">
    <label class="btn btn-primary">
      <i class="fas fa-filter"></i> Lọc
    </label>
  </div>

            <div class="col-auto">
              <select name="tinh_trang" class="form-control" onchange="this.form.submit()">
                <option value="">-- Tất cả tình trạng --</option>
                <option value="1" {{ request('tinh_trang') == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('tinh_trang') == '0' ? 'selected' : '' }}>Không hoạt động</option>
              </select>
            </div>

            <div class="col-auto">
              <select name="sort" class="form-control" onchange="this.form.submit()">
                <option value="">-- Sắp xếp theo --</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
              </select>
            </div>

            
          </form>
          <form method="GET" action="{{ route('Admin.categories.index') }}" class="input-group mb-3"
            style="max-width: 300px;">
            <input type="text" name="keyword" placeholder="Tìm kiếm..." value="{{ $search ?? '' }}" class="form-control"
              aria-label="Tìm kiếm">
            <button class="btn btn-outline" type="submit" style="height: calc(2.7rem + 2px);">
              <i class="bi bi-search"></i>
            </button>
          </form>

          <table class="table table-hover table-bordered" id="sampleTable">
            <thead>
              <tr>
                <th width="10"><input type="checkbox" id="all"></th>
                <th>Tên danh mục</th>
                <th>Ảnh</th>
                <th>Tình trạng</th>
                <th>Mô tả</th> {{-- Cột mô tả mới --}}
                <th>Chức năng</th>
              </tr>
            </thead>
            <tbody>
              @foreach($categories as $cat)
            <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->ten_danh_muc }}</td>
            <td>
              @if($cat->anh)
          <img src="{{ asset('storage/' . $cat->anh) }}" width="80" alt="Ảnh danh mục">
          @else
          Không có ảnh
          @endif
            </td>
            <td>{{ $cat->tinh_trang ? 'Hiện' : 'Ẩn' }}</td>
            <td>{{ $cat->mo_ta }}</td> {{-- Hiển thị mô tả --}}
            <td>
              <a class="btn btn-primary btn-sm edit" href="{{ route('Admin.categories.edit', $cat->id) }}">
              <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('Admin.categories.destroy', $cat->id) }}" method="POST" style="display:inline"
              onsubmit="return confirm('Bạn có chắc muốn xóa?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-primary btn-sm trash">
                <i class="fas fa-trash-alt"></i>
              </button>
              </form>
              <a href="{{ route('Admin.categories.show', $cat->id) }}" class="btn btn-info btn-sm"> <i
                class="fas fa-eye"></i></a>
            </td>
            </tr>
        @endforeach
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</main>
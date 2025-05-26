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

              <a class="btn btn-add btn-sm" href="{{ route('Admin.categories.create') }}" title="Thêm"><i
                  class="fas fa-plus"></i>
                Tạo mới danh mục</a>
              <a class="btn btn-primary btn-sm trashed" href="{{ route('Admin.categories.trashed') }}">
                <i class="fa fa-trash"></i> Danh mục đã xóa</a>
            </div>
          </div>
              <form method="GET" action="{{ route('Admin.categories.index') }}" class="d-flex">
                <input type="text" name="keyword" placeholder="Tìm kiếm..." value="{{ $search ?? '' }}"  class="form-control">
                <button class="btn btn-add btn-sm" type="submit">  Tìm</button>
              </form>
          <table class="table table-hover table-bordered" id="sampleTable">
            <thead>
              <tr>
                <th width="10"><input type="checkbox" id="all"></th>
                <th>Mã danh mục</th>
                <th>Tên danh mục</th>
                <th>Ảnh</th>
                <th>Tình trạng</th>
                <th>Chức năng</th>
              </tr>
            </thead>
            <tbody>
              @foreach($categories as $cat)
            <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->ma_danh_muc}}</td>
            <td>{{ $cat->ten_danh_muc }}</td>
            <td>
              @if($cat->anh)
          <img src="{{ asset('storage/' . $cat->anh) }}" width="80" alt="Ảnh danh mục">
          @else
          Không có ảnh
          @endif
            </td>
            <td>{{ $cat->tinh_trang ? 'Hiện' : 'Ẩn' }}</td>
            <td>
              <a class="btn btn-primary btn-sm edit" href="{{ route('Admin.categories.edit', $cat->id) }}"  ><i class="fas fa-edit"></i></a>
              <form action="{{ route('Admin.categories.destroy', $cat->id) }}" method="POST" style="display:inline"
              onsubmit="return confirm('Bạn có chắc muốn xóa?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-primary btn-sm trash"><i class="fas fa-trash-alt"></i> </button>
              </form>
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
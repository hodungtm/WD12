@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item active"><a href="#"><b>Danh mục đã xóa mềm</b></a></li>
    </ul>
    <div>
      <a href="{{ route('Admin.categories.index') }}" class="btn btn-outline-primary btn-sm mb-3">
        ← Quay lại danh sách
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($trashed->count() > 0)

  <form method="GET" action="{{ route('Admin.categories.trashed') }}" class="input-group mb-3" style="max-width: 300px;">
    <input type="text" name="keyword" placeholder="Tìm kiếm..." value="{{ $search ?? '' }}" class="form-control" aria-label="Tìm kiếm">
    <button class="btn btn-outline-secondary" type="submit" style="height: calc(2.25rem + 2px);">
      <i class="bi bi-search"></i> Tìm
    </button>
  </form>

  <div class="tile">
    <div class="tile-body p-0">
      <table class="table table-hover table-bordered mb-0">
        <thead>
          <tr>
            <th>ID</th>
            {{-- Bỏ cột Mã danh mục --}}
            <th>Tên danh mục</th>
            <th>Mô tả</th> {{-- Thêm cột Mô tả --}}
            <th>Ảnh</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($trashed as $cat)
          <tr>
            <td>{{ $cat->id }}</td>
            {{-- Bỏ hiển thị mã danh mục --}}
            <td>{{ $cat->ten_danh_muc }}</td>
            <td>{{ $cat->mo_ta }}</td> {{-- Hiển thị mô tả --}}
            <td>
              @if($cat->anh)
                <img src="{{ asset('storage/' . $cat->anh) }}" width="60" alt="Ảnh danh mục">
              @else
                Không có ảnh
              @endif
            </td>
            <td>
              <a class="btn btn-success btn-sm" href="{{ route('Admin.categories.restore', $cat->id) }}">
                <i class="fas fa-undo"></i> Khôi phục
              </a>
              <form action="{{ route('Admin.categories.forceDelete', $cat->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xóa vĩnh viễn?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                  <i class="fas fa-trash-alt"></i> Xóa vĩnh viễn
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  @else
    <p>Không có danh mục nào đã bị xóa.</p>
  @endif

</main>



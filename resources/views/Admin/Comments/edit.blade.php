@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item active"><a href="#"><b>Sửa Bình Luận</b></a></li>
    </ul>
    <div>
      <a href="{{ route('Admin.comments.index') }}" class="btn btn-outline-primary btn-sm">
        ← Quay lại danh sách bình luận
      </a>
    </div>
  </div>

  <div class="tile">
    <div class="tile-body">
      <form action="{{ route('Admin.comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="noi_dung">Nội Dung</label>
          <textarea
            name="noi_dung"
            id="noi_dung"
            class="form-control @error('noi_dung') is-invalid @enderror"
            rows="4"
          >{{ old('noi_dung', $comment->noi_dung) }}</textarea>
          @error('noi_dung')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="trang_thai">Trạng Thái</label>
          <select name="trang_thai" id="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror">
            <option value="0" {{ old('trang_thai', $comment->trang_thai) == 0 ? 'selected' : '' }}>Chưa duyệt</option>
            <option value="1" {{ old('trang_thai', $comment->trang_thai) == 1 ? 'selected' : '' }}>Đã duyệt</option>
          </select>
          @error('trang_thai')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary me-2">Cập Nhật Bình Luận</button>
        <a href="{{ route('Admin.comments.index') }}" class="btn btn-secondary">Hủy</a>
      </form>
    </div>
  </div>
</main>


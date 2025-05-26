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

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="tile">
    <div class="tile-body">
      <form action="{{ route('Admin.comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="tac_gia">Tác Giả</label>
          <input type="text" name="tac_gia" id="tac_gia" class="form-control" value="{{ old('tac_gia', $comment->tac_gia) }}" required>
        </div>

        <div class="form-group mb-3">
          <label for="noi_dung">Nội Dung</label>
          <textarea name="noi_dung" id="noi_dung" class="form-control" rows="4" required>{{ old('noi_dung', $comment->noi_dung) }}</textarea>
        </div>

        <div class="form-check mb-3">
          <input type="checkbox" name="trang_thai" id="trang_thai" class="form-check-input" {{ old('trang_thai', $comment->trang_thai) ? 'checked' : '' }}>
          <label for="trang_thai" class="form-check-label">Hiển Thị</label>
        </div>

        <button type="submit" class="btn btn-primary me-2">Cập Nhật Bình Luận</button>
        <a href="{{ route('Admin.comments.index') }}" class="btn btn-secondary">Hủy</a>
      </form>
    </div>
  </div>
</main>



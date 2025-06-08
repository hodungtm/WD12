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

  {{-- Hiển thị lỗi chung nếu có --}}
 

  <div class="tile">
    <div class="tile-body">
      <form action="{{ route('Admin.comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="tac_gia">Tác Giả</label>
          <input
            type="text"
            name="tac_gia"
            id="tac_gia"
            class="form-control @error('tac_gia') is-invalid @enderror"
            value="{{ old('tac_gia', $comment->tac_gia) }}"
          
          >
          @error('tac_gia')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

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

        <button type="submit" class="btn btn-primary me-2">Cập Nhật Bình Luận</button>
        <a href="{{ route('Admin.comments.index') }}" class="btn btn-secondary">Hủy</a>
      </form>
    </div>
  </div>
</main>



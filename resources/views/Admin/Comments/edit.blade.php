@extends('Admin.Layouts.AdminLayout')
@section('main')

<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Breadcrumb + tiêu đề -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Sửa bình luận #{{ $comment->id }}</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('Admin.comments.index') }}"><div class="text-tiny">Bình luận</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Sửa bình luận</div></li>
      </ul>
    </div>

    <!-- Form -->
    <div class="wg-box">
      <form action="{{ route('Admin.comments.update', $comment->id) }}" method="POST" class="grid gap-4">
        @csrf
        @method('PUT')

        {{-- Nội dung --}}
        <div>
          <label for="noi_dung" class="form-label">Nội dung bình luận</label>
          <textarea name="noi_dung" id="noi_dung" rows="4"
            class="form-control @error('noi_dung') is-invalid @enderror">{{ old('noi_dung', $comment->noi_dung) }}</textarea>
          @error('noi_dung')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        {{-- Trạng thái --}}
        <div>
          <label for="trang_thai" class="form-label">Trạng thái hiển thị</label>
          <select name="trang_thai" id="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
            <option value="1" {{ old('trang_thai', $comment->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ old('trang_thai', $comment->trang_thai) == 0 ? 'selected' : '' }}>Ẩn</option>
          </select>
          @error('trang_thai')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        {{-- Nút --}}
        <div class="mt-4 flex gap-2">
          <button type="submit" class="tf-button style-1">Cập nhật</button>
          <a href="{{ route('Admin.comments.index') }}" class="tf-button style-2">Hủy</a>
        </div>
      </form>
    </div>

  </div>
</div>

@endsection

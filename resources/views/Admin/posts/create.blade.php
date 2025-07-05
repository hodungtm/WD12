@extends('admin.layouts.Adminlayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề và breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Tạo bài viết mới</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('posts.index') }}"><div class="text-tiny">Bài viết</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Tạo mới</div></li>
      </ul>
    </div>

    <!-- Form -->
    <div class="wg-box">
      <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="grid gap20">
        @csrf

        <div>
          <label class="body-title mb-2">Tiêu đề bài viết</label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}">
          @error('title')
            <div class="text-danger text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="body-title mb-2">Trạng thái</label>
          <select name="status" class="form-control">
            <option value="">-- Chọn trạng thái --</option>
            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Đã đăng</option>
            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
            <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
          </select>
          @error('status')
            <div class="text-danger text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="body-title mb-2">Ảnh đại diện</label>
          <input type="file" name="image" class="form-control">
          @error('image')
            <div class="text-danger text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="body-title mb-2">Nội dung bài viết</label>
          <textarea name="content" id="editor" rows="10" class="form-control">{{ old('content') }}</textarea>
          @error('content')
            <div class="text-danger text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="flex gap10 mt-4">
          <button type="submit" class="tf-button">Lưu bài viết</button>
          <a href="{{ route('posts.index') }}" class="tf-button style-1">Hủy bỏ</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
      console.error(error);
    });
</script>
@endsection

@extends('Admin.Layouts.AdminLayout')

@section('main')

<div class="app-title">
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item">Bài viết</li>
    <li class="breadcrumb-item"><a href="{{ route('posts.create') }}">Tạo bài viết</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Tạo mới bài viết</h3>

      <form class="row" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group col-md-6">
          <label class="control-label">Tiêu đề</label>
          <input class="form-control" type="text" name="title">
          @error('title')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group col-md-6">
          <label class="control-label">Trạng thái</label>
          <select class="form-control" name="status">
            <option value="">-- Chọn trạng thái --</option>
            <option value="published">Đã đăng</option>
            <option value="draft">Nháp</option>
            <option value="hidden">Ẩn</option>
          </select>
          @error('status')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group col-md-12">
          <label class="control-label">Nội dung</label>
          <textarea class="form-control" name="content" id="editor" rows="10" ></textarea>
          @error('content')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group col-md-6">
          <label class="control-label">Ảnh đại diện</label>
          <input class="form-control" type="file" name="image">
          @error('image')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group col-md-12 mt-3">
          <button type="submit" class="btn btn-outline-success"><i class="fas fa-save me-1"></i> Lưu Lại</button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i> Hủy bỏ</a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    ClassicEditor
      .create(document.querySelector('#editor'))
      .catch(error => {
        console.error('CKEditor lỗi:', error);
      });
  });
</script>
@endsection



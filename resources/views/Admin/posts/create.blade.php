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
          <input class="form-control" type="text" name="title" required>
        </div>

        <div class="form-group col-md-6">
          <label class="control-label">Trạng thái</label>
          <select class="form-control" name="status" required>
            <option value="">-- Chọn trạng thái --</option>
            <option value="published">Đã đăng</option>
            <option value="draft">Nháp</option>
            <option value="hidden">Ẩn</option>
          </select>
        </div>

        <div class="form-group col-md-12">
          <label class="control-label">Nội dung</label>
          <textarea class="form-control" name="content" id="editor" rows="10" ></textarea>
        </div>

        <div class="form-group col-md-6">
          <label class="control-label">Ảnh đại diện</label>
          <input class="form-control" type="file" name="image">
        </div>

        <div class="form-group col-md-12 mt-3">
          <button class="btn btn-save" type="submit">Lưu lại</button>
          <a class="btn btn-cancel" href="{{ route('posts.index') }}">Hủy bỏ</a>
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



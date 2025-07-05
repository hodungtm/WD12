@extends('Admin.Layouts.AdminLayout')

@section('main')

<div class="app-title">
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item">Bài viết</li>
    <li class="breadcrumb-item"><a href="{{ route('posts.edit', $post) }}">Chỉnh sửa bài viết</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Chỉnh sửa bài viết</h3>

      {{-- Hiển thị lỗi --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="row" action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group col-md-6">
          <label class="control-label">Tiêu đề</label>
          <input class="form-control" type="text" name="title" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="form-group col-md-6">
          <label class="control-label">Trạng thái</label>
          <select class="form-control" name="status" required>
            <option value="">-- Chọn trạng thái --</option>
            @php
              $statuses = [
                  'published' => 'Đã đăng',
                  'draft' => 'Nháp',
                  'hidden' => 'Ẩn'
              ];
            @endphp
            @foreach($statuses as $value => $label)
              <option value="{{ $value }}" {{ old('status', $post->status) == $value ? 'selected' : '' }}>
                {{ $label }}
              </option>
            @endforeach
          </select>
        </div>

       <div class="form-group col-md-12">
  <label class="control-label">Nội dung</label>
  <textarea class="form-control" name="content" id="editor" rows="10" required>{{ old('content', $post->content ?? '') }}</textarea>
</div>

        <div class="form-group col-md-6">
          <label class="control-label">Ảnh đại diện</label>
          <input class="form-control" type="file" name="image">
          @if ($post->image)
            <div class="mt-2">
              <img src="{{ asset('storage/' . $post->image) }}" width="150" alt="Ảnh hiện tại" onerror="this.style.display='none'">
            </div>
          @endif
        </div>

        <div class="form-group col-md-12 mt-3">
         <button type="submit" class="btn btn-outline-success"><i class="fas fa-save me-1"></i> Cập Nhật</button>
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
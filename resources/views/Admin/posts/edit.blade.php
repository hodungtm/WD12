@extends('admin.layouts.Adminlayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề và breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Chỉnh sửa bài viết</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('posts.index') }}"><div class="text-tiny">Bài viết</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Chỉnh sửa</div></li>
      </ul>
    </div>

    <!-- Hiển thị lỗi -->
    @if ($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Form -->
    <div class="wg-box">
      <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="grid gap20">
        @csrf
        @method('PUT')

        <div>
          <label class="body-title mb-2">Tiêu đề bài viết</label>
          <input type="text" name="title" value="{{ old('title', $post->title) }}" class="form-control">
          @error('title')
            <div class="text-danger text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="body-title mb-2">Trạng thái</label>
          <select name="status" class="form-control">
            <option value="">-- Chọn trạng thái --</option>
            @php
              $statuses = ['published' => 'Đã đăng', 'draft' => 'Nháp', 'hidden' => 'Ẩn'];
            @endphp
            @foreach($statuses as $value => $label)
              <option value="{{ $value }}" {{ old('status', $post->status) == $value ? 'selected' : '' }}>
                {{ $label }}
              </option>
            @endforeach
          </select>
          @error('status')
            <div class="text-danger text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="body-title mb-2">Ảnh đại diện</label>
          <input type="file" name="image" class="form-control">
          @if ($post->image)
            <div class="mt-2">
              <img src="{{ asset('storage/' . $post->image) }}" width="150" class="rounded border" alt="Ảnh hiện tại" onerror="this.style.display='none'">
            </div>
          @endif
          @error('image')
            <div class="text-danger text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <label class="body-title mb-2">Nội dung bài viết</label>
          <textarea name="content" id="editor" rows="10" class="form-control">{{ old('content', $post->content ?? '') }}</textarea>
          @error('content')
            <div class="text-danger text-sm mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="flex gap10 mt-4">
          <button type="submit" class="tf-button">Cập nhật</button>
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

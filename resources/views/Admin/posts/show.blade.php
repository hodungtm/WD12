@extends('Admin.Layouts.AdminLayout')
@section('main')
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item">Bài viết</li>
      <li class="breadcrumb-item active">Chi tiết bài viết</li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Chi tiết bài viết: {{ $post->title }}</h3>

        <div class="row">
          <div class="form-group col-md-6">
            <label class="control-label">Tiêu đề:</label>
            <p>{{ $post->title }}</p>
          </div>

          <div class="form-group col-md-6">
            <label class="control-label">Trạng thái:</label>
            <p>
              @if($post->status === 'published')
                <span class="badge bg-success">Đã đăng</span>
              @elseif($post->status === 'draft')
                <span class="badge bg-secondary">Nháp</span>
              @else
                <span class="badge bg-warning">Ẩn</span>
              @endif
            </p>
          </div>

          <div class="form-group col-md-12">
            <label class="control-label">Nội dung:</label>
            <div class="border p-3" style="background: #f9f9f9;">
              {!! nl2br(e($post->content)) !!}
            </div>
          </div>

          <div class="form-group col-md-6 mt-3">
            <label class="control-label">Ảnh đại diện:</label><br>
            @if ($post->image)
              <img src="{{ asset('storage/' . $post->image) }}" width="300" alt="Ảnh bài viết">
            @else
              <p>Không có ảnh</p>
            @endif
          </div>

          <div class="form-group col-md-6 mt-3">
            <label class="control-label">Ngày tạo:</label>
            <p>{{ $post->created_at->format('d/m/Y H:i') }}</p>
          </div>
        </div>

        <div class="mt-4">
          <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">Chỉnh sửa</a>
          <a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
      </div>
    </div>
  </div>

@endsection
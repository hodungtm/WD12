@extends('admin.layouts.Adminlayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề và breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Chi tiết bài viết</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('posts.index') }}"><div class="text-tiny">Bài viết</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Chi tiết</div></li>
      </ul>
    </div>

    <!-- Nội dung -->
    <div class="wg-box">
      <div class="grid gap20">

        <div>
          <label class="body-title">Tiêu đề:</label>
          <div class="body-text mt-1">{{ $post->title }}</div>
        </div>

        <div>
          <label class="body-title">Trạng thái:</label>
          <div class="mt-1">
            @if($post->status === 'published')
              <span class="badge bg-success">Đã đăng</span>
            @elseif($post->status === 'draft')
              <span class="badge bg-secondary">Nháp</span>
            @else
              <span class="badge bg-warning text-dark">Ẩn</span>
            @endif
          </div>
        </div>

        <div>
          <label class="body-title">Ảnh đại diện:</label>
          <div class="mt-2">
            @if ($post->image)
              <img src="{{ asset('storage/' . $post->image) }}" width="300" class="rounded border" alt="Ảnh bài viết">
            @else
              <div class="text-muted">Không có ảnh</div>
            @endif
          </div>
        </div>

        <div>
          <label class="body-title">Nội dung:</label>
          <div class="p-3 rounded bg-light border mt-2">

            @php
              $content = $post->content;
              if (preg_match('/<h2[^>]*>(.*?)<\/h2>/is', $content, $matches)) {
                  $titleLine = strip_tags(str_replace(['<br>', '<br/>', '&nbsp;'], '', $matches[1]));
              } else {
                  $titleLine = '';
              }
              $content = preg_replace('/<h2[^>]*>.*?<\/h2>/is', '', $content);
            @endphp

            <div class="fw-bold mb-2">{{ $titleLine }}</div>
            {!! $content !!}
          </div>
        </div> 

        <div>
          <label class="body-title">Ngày tạo:</label>
          <div class="mt-1">{{ $post->created_at->format('d/m/Y H:i') }}</div>
        </div>

        <div class="flex gap10 mt-4">
          <a href="{{ route('posts.edit', $post) }}" class="tf-button"><i class="icon-edit-3 me-1"></i> Chỉnh sửa</a>
          <a href="{{ route('posts.index') }}" class="tf-button style-1"><i class="icon-arrow-left me-1"></i> Quay lại</a>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection

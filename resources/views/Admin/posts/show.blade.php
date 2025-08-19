@extends('admin.layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Chi tiết bài viết: {{ $post->title }}</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.posts.index') }}"><div class="text-tiny">Bài viết</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Chi tiết</div></li>
      </ul>
    </div>
    {{-- Box: Thông tin bài viết --}}
    <div class="wg-box mb-30">
      <div class="title-box">
        <i class="icon-book-open"></i>
        <div class="body-text">Thông tin bài viết</div>
      </div>
      <div class="flex flex-wrap gap20 mb-4">
        <div class="w-full md:w-1/2">
          <label class="body-title">Tiêu đề:</label>
          <p>{{ $post->title }}</p>
        </div>
        <div class="w-full md:w-1/2">
          <label class="body-title">Trạng thái:</label>
          <p>
            @if($post->status === 'published')
              <span class="block-available bg-1 fw-7">Đã đăng</span>
            @elseif($post->status === 'draft')
              <span class="block-stock bg-1 fw-7">Nháp</span>
            @else
              <span class="block-stock bg-warning fw-7">Ẩn</span>
            @endif
          </p>
        </div>
        <div class="w-full md:w-1/2">
          <label class="body-title">Ngày tạo:</label>
          <p>{{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
        </div>
      </div>
    </div>
    {{-- Box: Ảnh đại diện --}}
    <div class="wg-box mb-30">
      <div class="title-box">
        <i class="icon-image"></i>
        <div class="body-text">Ảnh đại diện</div>
      </div>
      <div class="mt-2">
        @if ($post->image)
          <img src="{{ asset('storage/' . $post->image) }}" width="200" class="rounded border" alt="Ảnh bài viết">
        @else
          <div class="text-muted">Không có ảnh</div>
        @endif
      </div>
    </div>
    {{-- Box: Nội dung --}}
    <div class="wg-box mb-30">
      <div class="title-box">
        <i class="icon-file-text"></i>
        <div class="body-text">Nội dung bài viết</div>
      </div>
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
      <div class="flex gap10 mt-4">
        <a href="{{ route('admin.posts.edit', $post) }}" class="tf-button"><i class="icon-edit-3 me-1"></i> Chỉnh sửa</a>
        <a href="{{ route('admin.posts.index') }}" class="tf-button style-1"><i class="icon-arrow-left me-1"></i> Quay lại</a>
      </div>
    </div>
  </div>
</div>
@endsection

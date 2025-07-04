@extends('admin.layouts.AdminLayout')

@section('main')
  <div class="main-content-inner">
    <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Chi tiết bình luận #{{ $comment->id }}</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="{{ route('Admin.comments.index') }}"><div class="text-tiny">Bình luận</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Chi tiết</div></li>
        </ul>
      </div>

      <div class="wg-box">
        <fieldset class="mb-4">
          <div class="body-title mb-10">Sản phẩm</div>
          <div class="body-text font-semibold">{{ $comment->product->name ?? '[Sản phẩm đã xóa]' }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Tác giả</div>
          <div class="body-text">{{ $comment->tac_gia }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Trạng thái</div>
          <div class="body-text">
            {!! $comment->trang_thai
              ? '<span class="badge bg-success">Hiển thị</span>'
              : '<span class="badge bg-secondary">Ẩn</span>' !!}
          </div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Ngày tạo</div>
          <div class="body-text">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Nội dung</div>
          <div class="border p-3 bg-light rounded">
            {!! nl2br(e($comment->noi_dung)) !!}
          </div>
        </fieldset>
      </div>

      <div class="cols gap10 mt-4">
        <a href="{{ route('Admin.comments.edit', $comment->id) }}" class="tf-button w-full">Chỉnh sửa</a>
        <a href="{{ route('Admin.comments.index') }}" class="tf-button style-1 w-full">Quay lại danh sách</a>
      </div>
    </div>
  </div>
@endsection

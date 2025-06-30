@extends('admin.layouts.AdminLayout')

@section('main')
  <div class="main-content-inner">
    <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Chi tiết đánh giá: #{{ $review->id }}</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="{{ route('Admin.reviews.index') }}"><div class="text-tiny">Đánh giá</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Chi tiết đánh giá</div></li>
        </ul>
      </div>

      <div class="wg-box">
        <fieldset class="mb-4">
          <div class="body-title mb-10">Người dùng</div>
          <div class="body-text font-semibold">{{ $review->user->name ?? 'N/A' }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Sản phẩm</div>
          <div class="body-text font-semibold">{{ $review->product->name ?? 'N/A' }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Số sao</div>
          <div class="body-text">{{ $review->so_sao }} / 5</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Trạng thái</div>
          <div class="body-text">
            {!! $review->trang_thai
              ? '<span class="badge bg-success">Hiển thị</span>'
              : '<span class="badge bg-secondary">Ẩn</span>' !!}
          </div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Ngày tạo</div>
          <div class="body-text">{{ $review->created_at->format('d/m/Y H:i') }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Ngày cập nhật</div>
          <div class="body-text">{{ $review->updated_at->format('d/m/Y H:i') }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Nội dung đánh giá</div>
          <div class="border p-3 bg-light rounded">
            {!! nl2br(e($review->noi_dung ?? 'Không có nội dung')) !!}
          </div>
        </fieldset>
      </div>

      <div class="cols gap10 mt-4">
        <a href="{{ route('Admin.reviews.edit', $review->id) }}" class="tf-button w-full">Chỉnh sửa</a>
        <a href="{{ route('Admin.reviews.index') }}" class="tf-button style-1 w-full">Quay lại danh sách</a>
      </div>
    </div>
  </div>
@endsection

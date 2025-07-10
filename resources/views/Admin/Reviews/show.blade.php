@extends('admin.layouts.AdminLayout')

@section('main')
  <div class="main-content-inner">
    <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Chi tiết đánh giá: #{{ $review->id }}</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#"><div class="text-tiny">Bảng điều khiển</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="{{ route('Admin.reviews.index') }}"><div class="text-tiny">Đánh giá</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Chi tiết đánh giá</div></li>
        </ul>
      </div>
      <div class="wg-box mb-20" style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border-radius:20px;">
        <div class="flex flex-wrap gap40" style="align-items: flex-start;">
          <div style="flex:2; min-width:260px; font-size:18px;">
            <div style="margin-bottom:20px;"><span style="font-weight:600;">Người dùng:</span> <span style="margin-left:12px;">{{ $review->user->name ?? 'N/A' }}</span></div>
            <div style="margin-bottom:20px;"><span style="font-weight:600;">Sản phẩm:</span> <span style="margin-left:12px;">{{ $review->product->name ?? 'N/A' }}</span></div>
            <div style="margin-bottom:20px;"><span style="font-weight:600;">Số sao:</span> <span style="margin-left:12px;">{{ $review->so_sao }} / 5</span></div>
            <div style="margin-bottom:20px;"><span style="font-weight:600;">Trạng thái:</span> <span style="margin-left:12px;">@if($review->trang_thai)<span class="badge" style="background:#27ae60; color:#fff; font-size:14px;">Hiển thị</span>@else<span class="badge" style="background:#f39c12; color:#fff; font-size:14px;">Ẩn</span>@endif</span></div>
            <div style="margin-bottom:20px;"><span style="font-weight:600;">Ngày tạo:</span> <span style="margin-left:12px;">{{ $review->created_at->format('d/m/Y H:i') }}</span></div>
            <div style="margin-bottom:20px;"><span style="font-weight:600;">Ngày cập nhật:</span> <span style="margin-left:12px;">{{ $review->updated_at->format('d/m/Y H:i') }}</span></div>
            <div style="margin-bottom:20px;"><span style="font-weight:600;">Nội dung đánh giá:</span>
              <div style="margin-left:12px; margin-top:8px; font-size:17px; font-weight:400; color:#222;">
                {!! nl2br(e($review->noi_dung ?? 'Không có nội dung')) !!}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex gap10 mt-4 flex-wrap" style="justify-content: flex-end;">
        <a href="{{ route('Admin.reviews.edit', $review->id) }}" class="tf-button w208"><i class="icon-edit-3"></i> Chỉnh sửa</a>
        <a href="{{ route('Admin.reviews.index') }}" class="tf-button style-1 w208"><i class="icon-list"></i> Quay lại danh sách</a>
      </div>
    </div>
  </div>
@endsection

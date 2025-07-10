@extends('admin.layouts.AdminLayout')

@section('main')
  <div class="main-content-inner" style="padding-top: 10px; margin-top: 0;">
    <div class="main-content-wrap" style="padding-top: 0; margin-top: 0;">
      <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Chi tiết bình luận</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng điều khiển</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="{{ route('Admin.comments.index') }}"><div class="text-tiny">Bình luận</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Chi tiết bình luận</div></li>
        </ul>
      </div>
      <div class="wg-box mb-20" style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border-radius:20px;">
        <div class="flex flex-wrap gap40" style="align-items: flex-start;">
          <div style="flex:2; min-width:260px; font-size:18px;">
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Sản phẩm:</span>
              <span style="margin-left:12px;">{{ $comment->product->name ?? '[Sản phẩm đã xóa]' }}</span>
            </div>
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Tác giả:</span>
              <span style="margin-left:12px;">{{ $comment->tac_gia }}</span>
            </div>
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Trạng thái:</span>
              <span style="margin-left:12px;">{{ $comment->trang_thai ? 'Hiển thị' : 'Ẩn' }}</span>
            </div>
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Ngày tạo:</span>
              <span style="margin-left:12px;">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Nội dung:</span>
              <div style="margin-left:12px; margin-top:8px; font-size:17px; font-weight:400; color:#222;">
                {!! nl2br(e($comment->noi_dung)) !!}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex gap10 mt-4 flex-wrap" style="justify-content: flex-end;">
        <a href="{{ route('Admin.comments.edit', $comment->id) }}" class="tf-button w208"><i class="icon-edit-3"></i> Chỉnh sửa</a>
        <a href="{{ route('Admin.comments.index') }}" class="tf-button style-1 w208"><i class="icon-list"></i> Quay lại danh sách</a>
      </div>
    </div>
  </div>
@endsection

@extends('admin.layouts.AdminLayout')

@section('main')
  <div class="main-content-inner">
    <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Chi tiết danh mục</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng điều khiển</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="{{ route('Admin.categories.index') }}"><div class="text-tiny">Danh mục</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Chi tiết danh mục</div></li>
        </ul>
      </div>

      <div class="wg-box mb-20" style="box-shadow:0 4px 24px rgba(0,0,0,0.06); border-radius:20px;">
        <div class="flex flex-wrap gap40" style="align-items: flex-start;">
          <div style="min-width:240px; max-width:280px; flex:1; text-align:center;">
            <div class="body-title mb-14" style="font-size:18px;">Ảnh danh mục</div>
            <div style="background:#fafbfc; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.04); display:flex; align-items:center; justify-content:center; height:260px;">
              @if($category->anh)
                <img src="{{ asset('storage/' . $category->anh) }}" alt="Ảnh danh mục" style="object-fit:contain; width:220px; height:220px; border-radius:12px;">
              @else
                <div class="text-muted">Không có ảnh</div>
              @endif
            </div>
          </div>
          <div style="flex:2; min-width:260px; font-size:18px;">
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Tên danh mục:</span>
              <span style="margin-left:12px;">{{ $category->ten_danh_muc }}</span>
            </div>
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Tình trạng:</span>
              <span style="margin-left:12px;">{{ $category->tinh_trang == 1 ? 'Hiện' : 'Ẩn' }}</span>
            </div>
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Ngày tạo:</span>
              <span style="margin-left:12px;">{{ $category->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Ngày cập nhật:</span>
              <span style="margin-left:12px;">{{ $category->updated_at->format('d/m/Y H:i') }}</span>
            </div>
            <div style="margin-bottom:20px;">
              <span style="font-weight:600;">Mô tả:</span>
              <div style="margin-left:12px; margin-top:8px; font-size:17px; font-weight:400; color:#222;">
                {!! nl2br(e($category->mo_ta ?? 'Không có mô tả')) !!}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="flex gap10 mt-4 flex-wrap" style="justify-content: flex-end;">
        <a href="{{ route('Admin.categories.edit', $category->id) }}" class="tf-button w208"><i class="icon-edit-3"></i> Chỉnh sửa</a>
        <a href="{{ route('Admin.categories.index') }}" class="tf-button style-1 w208"><i class="icon-list"></i> Quay lại danh sách</a>
      </div>
    </div>
  </div>
@endsection

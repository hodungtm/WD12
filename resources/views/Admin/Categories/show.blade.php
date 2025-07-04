@extends('admin.layouts.AdminLayout')

@section('main')
  <div class="main-content-inner">
    <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Chi tiết danh mục: {{ $category->ten_danh_muc }}</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="{{ route('Admin.categories.index') }}"><div class="text-tiny">Danh mục</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Chi tiết danh mục</div></li>
        </ul>
      </div>

      <div class="wg-box">
        <fieldset class="mb-4">
          <div class="body-title mb-10">Tên danh mục</div>
          <div class="body-text font-semibold">{{ $category->ten_danh_muc }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Tình trạng</div>
          <div class="body-text">{{ $category->tinh_trang == 1 ? 'Hiện' : 'Ẩn' }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Ngày tạo</div>
          <div class="body-text">{{ $category->created_at->format('d/m/Y H:i') }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Ngày cập nhật</div>
          <div class="body-text">{{ $category->updated_at->format('d/m/Y H:i') }}</div>
        </fieldset>

        <fieldset class="mb-4">
          <div class="body-title mb-10">Mô tả</div>
          <div class="border p-3 bg-light rounded">
            {!! nl2br(e($category->mo_ta ?? 'Không có mô tả')) !!}
          </div>
        </fieldset>
      </div>

      <div class="wg-box">
        <div class="body-title mb-10">Ảnh danh mục</div>
        @if($category->anh)
          <div class="flex flex-wrap gap10 mt-2">
            <div class="border rounded overflow-hidden">
              <img src="{{ asset('storage/' . $category->anh) }}" style="object-fit: cover; width: 150px; height: 150px;" class="rounded">
            </div>
          </div>
        @else
          <div class="text-muted mt-2">Không có ảnh</div>
        @endif
      </div>

      <div class="cols gap10 mt-4">
        <a href="{{ route('Admin.categories.edit', $category->id) }}" class="tf-button w-full">Chỉnh sửa</a>
        <a href="{{ route('Admin.categories.index') }}" class="tf-button style-1 w-full">Quay lại danh sách</a>
      </div>
    </div>
  </div>
@endsection

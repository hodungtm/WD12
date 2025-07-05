@extends('Admin.Layouts.AdminLayout')
@section('main')

<div class="main-content-inner">
  <div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Sửa danh mục</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('Admin.categories.index') }}"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('Admin.categories.index') }}"><div class="text-tiny">Danh mục</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Sửa danh mục</div></li>
      </ul>
    </div>

    <div class="wg-box">
      <form action="{{ route('Admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="form-style-1 form-new-product">
        @csrf
        @method('PUT')

        {{-- Tên danh mục --}}
        <fieldset class="name">
          <div class="body-title">Tên danh mục <span class="tf-color-1">*</span></div>
          <input type="text" name="ten_danh_muc" class="flex-grow" placeholder="Nhập tên danh mục" value="{{ old('ten_danh_muc', $category->ten_danh_muc) }}" required>
          @error('ten_danh_muc')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </fieldset>

        {{-- Ảnh hiện tại --}}
        <fieldset>
          <div class="body-title">Ảnh hiện tại</div>
          @if($category->anh)
            <img src="{{ asset('storage/' . $category->anh) }}" width="100" class="mb-2" alt="Ảnh danh mục">
          @else
            <div class="body-text text-muted">Không có ảnh</div>
          @endif
        </fieldset>

        {{-- Ảnh mới --}}
        <fieldset>
          <div class="body-title">Đổi ảnh</div>
          <div class="upload-image flex-grow">
            <div class="item up-load">
              <label class="uploadfile" for="anh">
                <span class="icon"><i class="icon-upload-cloud"></i></span>
                <span class="body-text">Chọn ảnh mới hoặc kéo thả</span>
                <input type="file" id="anh" name="anh" accept="image/*">
              </label>
            </div>
          </div>
          @error('anh')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </fieldset>

        {{-- Tình trạng --}}
        <fieldset class="category">
          <div class="body-title">Tình trạng</div>
          <div class="select flex-grow">
            <select name="tinh_trang" required>
              <option value="1" {{ old('tinh_trang', $category->tinh_trang) == 1 ? 'selected' : '' }}>Hiện</option>
              <option value="0" {{ old('tinh_trang', $category->tinh_trang) == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
          </div>
          @error('tinh_trang')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </fieldset>

        {{-- Mô tả --}}
        <fieldset class="name">
          <div class="body-title">Mô tả</div>
          <textarea name="mo_ta" class="flex-grow" rows="4" placeholder="Nhập mô tả">{{ old('mo_ta', $category->mo_ta) }}</textarea>
          @error('mo_ta')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </fieldset>

        {{-- Nút cập nhật --}}
        <div class="bot mt-4">
          <div></div>
          <button type="submit" class="tf-button w208">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

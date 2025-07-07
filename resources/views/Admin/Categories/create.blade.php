@extends('admin.layouts.AdminLayout')

@section('main')

  <div class="main-content-inner">
    <div class="main-content-wrap">

      <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Thêm danh mục</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="{{ route('Admin.categories.index') }}"><div class="text-tiny">Danh mục</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Thêm danh mục</div></li>
        </ul>
      </div>

      <div class="wg-box">
        <form class="form-new-product form-style-1" action="{{ route('Admin.categories.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Tên danh mục --}}
          <fieldset class="name">
            <div class="body-title">Tên danh mục <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="text" name="ten_danh_muc" value="{{ old('ten_danh_muc') }}" required>
            @error('ten_danh_muc')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>

          {{-- Ảnh --}}
          <fieldset>
            <div class="body-title">Ảnh đại diện <span class="tf-color-1">*</span></div>
            <div class="upload-image flex-grow">
              <div class="item up-load">
                <label class="uploadfile" for="anh">
                  <span class="icon"><i class="icon-upload-cloud"></i></span>
                  <span class="body-text">Chọn ảnh hoặc kéo thả</span>
                  <input type="file" id="anh" name="anh" accept="image/*">
                </label>
              </div>
            </div>
            @error('anh')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>

          {{-- Mô tả --}}
          <fieldset class="category">
            <div class="body-title">Mô tả</div>
            <textarea name="mo_ta" class="flex-grow" rows="3">{{ old('mo_ta') }}</textarea>
            @error('mo_ta')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>

          {{-- Tình trạng --}}
          <fieldset class="category">
            <div class="body-title">Tình trạng</div>
            <div class="select flex-grow">
              <select name="tinh_trang" required>
                <option value="">-- Chọn tình trạng --</option>
                <option value="1" {{ old('tinh_trang') == '1' ? 'selected' : '' }}>Hiện</option>
                <option value="0" {{ old('tinh_trang') == '0' ? 'selected' : '' }}>Ẩn</option>
              </select>
            </div>
            @error('tinh_trang')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>

          {{-- Nút lưu --}}
          <div class="bot mt-4">
            <div></div>
            <button class="tf-button w208" type="submit">Lưu</button>
          </div>

        </form>
      </div>

    </div>
  </div>

@endsection

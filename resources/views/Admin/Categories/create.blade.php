@extends('admin.layouts.AdminLayout')

@section('main')

  <div class="main-content-inner">
    <div class="main-content-wrap">

      <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Thêm danh mục</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng điều khiển</div></a></li>
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
            <input class="flex-grow" type="text" name="ten_danh_muc" placeholder="Nhập tên danh mục" value="{{ old('ten_danh_muc') }}" required>
            @error('ten_danh_muc')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>

          {{-- Ảnh --}}
          <fieldset>
            <div class="body-title">Ảnh đại diện <span class="tf-color-1">*</span></div>
            <div class="upload-image flex-grow">
              <div class="item up-load">
                <label class="uploadfile h250" for="anh">
                  <span class="icon"><i class="icon-upload-cloud"></i></span>
                  <span class="body-text">Kéo thả hoặc chọn <span class="tf-color">tải ảnh lên</span></span>
                  <input type="file" id="anh" name="anh" accept="image/*" onchange="previewImage(event)">
                </label>
                <div style="margin-top: 10px; text-align: center;">
                  <img id="preview-image" src="#" alt="Preview" style="display:none; max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #eee;">
                </div>
              </div>
            </div>
            @error('anh')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>

          {{-- Mô tả --}}
          <fieldset class="category">
            <div class="body-title">Mô tả</div>
            <textarea name="mo_ta" class="flex-grow" rows="3" placeholder="Nhập mô tả...">{{ old('mo_ta') }}</textarea>
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

  <script>
    function previewImage(event) {
      const input = event.target;
      const preview = document.getElementById('preview-image');
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      } else {
        preview.src = '#';
        preview.style.display = 'none';
      }
    }
  </script>

@endsection

@extends('Admin.Layouts.AdminLayout')

@section('main')
  <div class="main-content-inner">
    <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Chỉnh sửa danh mục</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Danh mục</div></li>
        </ul>
      </div>
      <div class="wg-box">
        <form class="form-new-product form-style-1" action="{{ route('Admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <fieldset class="name">
            <div class="body-title">Tên danh mục <span class="tf-color-1">*</span></div>
            <input class="flex-grow" type="text" name="ten_danh_muc" placeholder="Nhập tên danh mục" value="{{ old('ten_danh_muc', $category->ten_danh_muc) }}" required>
            @error('ten_danh_muc')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>
          <fieldset>
            <div class="body-title">Ảnh hiện tại</div>
            @if($category->anh)
              <img id="current-image" src="{{ asset('storage/' . $category->anh) }}" alt="Ảnh danh mục" style="max-width: 120px; max-height: 120px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 10px;">
            @else
              <div class="body-text text-muted">Không có ảnh</div>
            @endif
          </fieldset>
          <fieldset>
            <div class="body-title">Đổi ảnh</div>
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
          <fieldset class="category">
            <div class="body-title">Mô tả</div>
            <textarea name="mo_ta" class="flex-grow" rows="3" placeholder="Nhập mô tả...">{{ old('mo_ta', $category->mo_ta) }}</textarea>
            @error('mo_ta')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>
          <fieldset class="category">
            <div class="body-title">Tình trạng</div>
            <div class="select flex-grow">
              <select name="tinh_trang" required>
                <option value="1" {{ old('tinh_trang', $category->tinh_trang) == 1 ? 'selected' : '' }}>Hiện</option>
                <option value="0" {{ old('tinh_trang', $category->tinh_trang) == 0 ? 'selected' : '' }}>Ẩn</option>
              </select>
            </div>
            @error('tinh_trang')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </fieldset>
          <div class="bot mt-4">
            <div></div>
            <button class="tf-button w208" type="submit">Cập nhật</button>
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

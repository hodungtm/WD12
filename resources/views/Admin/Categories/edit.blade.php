@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chỉnh sửa danh mục</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('Admin.categories.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('Admin.categories.index') }}">
                            <div class="text-tiny">Danh mục</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chỉnh sửa danh mục</div>
                    </li>
                </ul>
            </div>

            <form action="{{ route('Admin.categories.update', $category->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="wg-box mb-30">
                    <div class="form-group">
                        <div class="body-title mb-10">Tên danh mục <span class="tf-color-1">*</span></div>
                        <input type="text" name="ten_danh_muc" class="form-control mb-10" placeholder="Nhập tên danh mục"
                            value="{{ old('ten_danh_muc', $category->ten_danh_muc) }}" required>
                        @error('ten_danh_muc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @if($category->anh)
                        <div class="current-image-section mb-3">
                            <h5 class="body-title">Ảnh hiện tại</h5>
                            <div class="flex flex-wrap gap10">
                                <div class="border rounded p-1 text-center"
                                    style="position: relative; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('storage/' . $category->anh) }}"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px;">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="body-title mb-10">Đổi ảnh</div>
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <div class="upload-image" style="flex: 0 0 auto;">
                                <div class="item up-load">
                                    <label class="uploadfile h250" for="anh"
                                        style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 200px; width: 200px; border: 1px dashed #ccc; border-radius: 8px; text-align: center;">
                                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                                        <span class="body-text" style="font-size: 13px;">Kéo thả hoặc chọn <span
                                                class="tf-color">tải ảnh lên</span></span>
                                        <input type="file" id="anh" name="anh" accept="image/*" onchange="previewImage(event)" style="display: none;">
                                    </label>
                                </div>
                            </div>
                            <div class="preview-image" style="flex: 1;">
                                <div id="preview-image-box" style="display: flex; gap: 6px; flex-wrap: wrap;"></div>
                            </div>
                        </div>
                        @error('anh')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="wg-box mb-30">
                    <div class="form-group">
                        <div class="body-title mb-10">Mô tả</div>
                        <textarea name="mo_ta" class="form-control" rows="4"
                            placeholder="Nhập mô tả...">{{ old('mo_ta', $category->mo_ta) }}</textarea>
                        @error('mo_ta')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="body-title mb-10">Trạng thái</div>
                        <select name="tinh_trang" class="form-control" required>
                            <option value="1" {{ old('tinh_trang', $category->tinh_trang) == 1 ? 'selected' : '' }}>Hiện
                            </option>
                            <option value="0" {{ old('tinh_trang', $category->tinh_trang) == 0 ? 'selected' : '' }}>Ẩn
                            </option>
                        </select>
                        @error('tinh_trang')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-start gap-3 mt-4">
                    <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                        <i class="icon-save"></i> Cập nhật danh mục
                    </button>
                    <a href="{{ route('Admin.categories.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                        <i class="icon-x"></i> Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const previewBox = document.getElementById('preview-image-box');
            previewBox.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100px';
                        img.style.maxHeight = '100px';
                        img.style.borderRadius = '8px';
                        img.style.border = '1px solid #eee';
                        img.style.marginRight = '8px';
                        previewBox.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
@endsection
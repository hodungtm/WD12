@extends('admin.layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Thêm danh mục mới</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.categories.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.categories.index') }}">
                            <div class="text-tiny">Danh mục</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Thêm danh mục mới</div>
                    </li>
                </ul>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="wg-box mb-30">
                    <div class="form-group">
                        <div class="body-title mb-10">Tên danh mục <span class="tf-color-1">*</span></div>
                        <input type="text" name="ten_danh_muc" class="form-control mb-10" placeholder="Nhập tên danh mục" value="{{ old('ten_danh_muc') }}" required>
                        @error('ten_danh_muc')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="body-title mb-10">Ảnh đại diện <span class="tf-color-1">*</span></div>
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
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="wg-box mb-30">
                    <div class="form-group">
                        <div class="body-title mb-10">Mô tả</div>
                        <textarea name="mo_ta" class="form-control" rows="4" placeholder="Nhập mô tả...">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="body-title mb-10">Trạng thái</div>
                        <select name="tinh_trang" class="form-control" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="1" {{ old('tinh_trang') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ old('tinh_trang') == '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        @error('tinh_trang')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-start gap-3 mt-4">
                    <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                        <i class="icon-save"></i> Lưu danh mục
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                        <i class="icon-x"></i> Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
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

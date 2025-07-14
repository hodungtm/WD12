@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Tạo mới bài viết</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('posts.index') }}"><div class="text-tiny">Bài viết</div></a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">Tạo bài viết</div></li>
                </ul>
            </div>
            <div class="wg-box">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="mb-4">
                        <div class="body-title mb-2">Tiêu đề</div>
                        <input type="text" name="title" class="form-control mb-1" placeholder="Nhập tiêu đề bài viết" value="{{ old('title') }}" required>
                        @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <fieldset class="mb-4">
                        <div class="body-title mb-2">Trạng thái</div>
                        <select name="status" class="form-control mb-1" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Đã đăng</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                            <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <fieldset class="mb-4">
                        <div class="body-title mb-2">Nội dung</div>
                        <textarea name="content" id="editor" rows="10" class="form-control mb-1" required>{{ old('content') }}</textarea>
                        @error('content')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <fieldset class="mb-4">
                        <div class="body-title mb-2">Ảnh đại diện</div>
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <div class="upload-image" style="flex: 0 0 auto;">
                                <label class="uploadfile h250" for="image"
                                    style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 200px; width: 200px; border: 1px dashed #ccc; border-radius: 8px; text-align: center;">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="body-text" style="font-size: 13px;">Kéo thả hoặc chọn <span class="tf-color">tải ảnh lên</span></span>
                                    <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)" style="display: none;">
                                </label>
                            </div>
                            <div class="preview-image" style="flex: 1;">
                                <div id="preview-image-box" style="display: flex; gap: 6px; flex-wrap: wrap;"></div>
                            </div>
                        </div>
                        @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <div class="flex gap10 mt-4">
                        <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                            <i class="icon-save"></i> Lưu Bài Viết
                        </button>
                        <a href="{{ route('posts.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                            <i class="icon-x"></i> Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error('CKEditor lỗi:', error);
                });
        });

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

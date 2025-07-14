@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-30">
            <div class="title-box flex items-center gap10">
                <i class="icon-image" style="font-size: 32px; color: #1abc9c;"></i>
                <div>
                    <h3 style="margin-bottom:2px;">Thêm Banner mới</h3>
                    <div class="body-text text-muted" style="font-size:15px;">Tạo mới banner quảng cáo cho hệ thống</div>
                </div>
            </div>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.banners.index') }}"><div class="text-tiny">Banner</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Thêm mới</div></li>
            </ul>
        </div>
        <div class="wg-box mb-30">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset class="mb-4">
                    <div class="body-title mb-10">Tiêu đề</div>
                    <input type="text" name="tieu_de" class="form-control mb-10" value="{{ old('tieu_de') }}" placeholder="Tiêu đề cho banner">
                    @error('tieu_de')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset class="mb-4">
                    <div class="body-title mb-10">Nội dung</div>
                    <textarea name="noi_dung" class="form-control mb-10" rows="3" placeholder="Nội dung của banner">{{ old('noi_dung') }}</textarea>
                    @error('noi_dung')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset class="mb-4">
                    <div class="body-title mb-10">Loại Banner</div>
                    <select name="loai_banner" class="form-control mb-10">
                        <option value="slider" {{ old('loai_banner') == 'slider' ? 'selected' : '' }}>Slideshow</option>
                        <option value="footer" {{ old('loai_banner') == 'footer' ? 'selected' : '' }}>Footer</option>
                    </select>
                    @error('loai_banner')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset class="mb-4">
                    <div class="body-title mb-10">Trạng thái</div>
                    <select name="trang_thai" class="form-control mb-10">
                        <option value="hien" {{ old('trang_thai') == 'hien' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="an" {{ old('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('trang_thai')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset class="mb-4">
                    <div class="body-title mb-10">Ảnh banner</div>
                    <div class="upload-image flex-grow">
                        <div class="item up-load">
                            <label class="uploadfile h250" for="hinh_anh">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="body-text">Kéo thả hoặc chọn <span class="tf-color">tải ảnh lên</span></span>
                                <input type="file" id="hinh_anh" name="list_image[]" accept="image/*" multiple onchange="previewBannerImages(event)">
                            </label>
                        </div>
                    </div>
                    <div id="preview-banner-images" style="display:flex; gap:10px; flex-wrap:nowrap; margin-top:16px; align-items:center;"></div>
                    @error('list_image.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </fieldset>
                <div class="flex justify-end gap10 mt-3">
                    <button type="submit" class="tf-button btn-sm w-auto px-3 py-2"><i class="icon-plus"></i> Thêm</button>
                    <a href="{{ route('admin.banners.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2"><i class="icon-x"></i> Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowCount = 1;
    document.getElementById('add-row').addEventListener('click', function () {
        const tableBody = document.getElementById('image-table-body');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="d-flex align-items-center gap10">
                <img id="preview_${rowCount}" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s" width="60" style="border-radius:8px; border:1.5px solid #eee; margin-right:8px;">
                <input type="file" name="list_image[id_${rowCount}]" class="form-control mx-2" onchange="previewImageAndAddToSlideshow(this, ${rowCount})" style="width:100%;">
                <button class="btn btn-light remove-row" onclick="removeRow(this)"><i class="icon-trash"></i></button>
            </td>
        `;
        tableBody.appendChild(newRow);
        rowCount++;
    });
});
function previewImageAndAddToSlideshow(input, rowIndex) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById(`preview_${rowIndex}`).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function removeRow(btn) {
    btn.closest('tr').remove();
}
function previewBannerImages(event) {
    const preview = document.getElementById('preview-banner-images');
    preview.innerHTML = '';
    Array.from(event.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '80px';
            img.style.maxHeight = '80px';
            img.style.borderRadius = '8px';
            img.style.border = '1.5px solid #eee';
            img.style.marginRight = '8px';
            img.style.background = '#fff';
            img.style.boxShadow = '0 2px 8px rgba(0,0,0,0.04)';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endsection

@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Thêm banner mới</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Thêm banner</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    @if (session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="flex gap10">
                    <a href="{{ route('admin.banners.index') }}" class="tf-button style-1">
                        <i class="icon-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf
                <div class="wg-table table-all-category">
                    <ul class="flex flex-column gap20">
                        <li>
                            <label for="tieu_de" class="form-label">Tiêu đề:</label>
                            <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de') }}" placeholder="Tiêu đề cho banner">
                        </li>

                        <li>
                            <label for="noi_dung" class="form-label">Nội dung:</label>
                            <textarea name="noi_dung" class="form-control" placeholder="Nội dung của banner">{{ old('noi_dung') }}</textarea>
                        </li>

                        <li>
                            <label for="loai_banner" class="form-label">Loại Banner:</label>
                            <select name="loai_banner" class="form-select">
                                <option value="slider" {{ old('loai_banner') == 'slider' ? 'selected' : '' }}>Slideshow</option>
                                <option value="footer" {{ old('loai_banner') == 'footer' ? 'selected' : '' }}>Footer</option>
                            </select>
                        </li>

                        <li>
                            <label for="trang_thai" class="form-label">Trạng thái:</label>
                            <select name="trang_thai" class="form-select">
                                <option value="hien" {{ old('trang_thai') == 'hien' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="an" {{ old('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </li>

                        <li>
                            <div class="d-flex justify-content-between align-items-end">
                                <label class="form-label">Ảnh slide</label>
                                <div id="add-row" class="btn btn-secondary btn-sm mb-2">+</div>
                            </div>
                            <table class="table align-middle mb-0">
                                <tbody id="image-table-body">
                                    <tr>
                                        <td class="d-flex align-items-center justify-content-around">
                                            <img id="preview_0"
                                                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s"
                                                width="50px" height="auto">
                                            <input type="file" id="hinh_anh" name="list_image[id_0]"
                                                class="form-control mx-2"
                                                onchange="previewImageAndAddToSlideshow(this, 0)">
                                            <button class="btn btn-light remove-row" onclick="removeRow(this)"><i
                                                    class="icon-trash-2 text-danger"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-end gap10 mt-3">
                    <button type="submit" class="tf-button style-1">
                        <i class="icon-plus"></i> Thêm
                    </button>
                    <a href="{{ route('admin.banners.index') }}" class="tf-button bg-secondary">
                        <i class="icon-x"></i> Hủy
                    </a>
                </div>
            </form>

            <div class="divider mt-4"></div>

            <h3 class="mt-3">Xem trước slide</h3>
            <div id="bannerCarousel" class="carousel slide mb-3 p-2" data-bs-ride="carousel">
                <div class="carousel-inner" id="carouselImages"></div>
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarouselModal"
                    data-bs-slide="prev" style="background: none; border: none;">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarouselModal"
                    data-bs-slide="next" style="background: none; border: none;">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowCount = 1;

    document.getElementById('add-row').addEventListener('click', function () {
        const tableBody = document.getElementById('image-table-body');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td class="d-flex align-items-center justify-content-around">
                <img id="preview_${rowCount}" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s" width="50px">
                <input type="file" name="list_image[id_${rowCount}]" class="form-control mx-2" onchange="previewImageAndAddToSlideshow(this, ${rowCount})">
                <button class="btn btn-light remove-row" onclick="removeRow(this)"><i class="icon-trash-2 text-danger"></i></button>
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

            const carouselInner = document.getElementById('carouselImages');
            const newItem = document.createElement('div');
            newItem.classList.add('carousel-item');
            newItem.innerHTML = `
                <img src="${e.target.result}" class="d-block w-100 img-fluid" style="height: 300px; object-fit: cover;" alt="Slide ${rowIndex+1}">
            `;
            if (carouselInner.children.length === 0) {
                newItem.classList.add('active');
            }
            carouselInner.appendChild(newItem);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeRow(btn) {
    btn.closest('tr').remove();
}
</script>
@endpush

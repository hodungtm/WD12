@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thêm banner mới</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Thêm banner</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data"
                    class="mt-3">
                    @csrf

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

                    <div class="form-group mt-3">
                        <label class="form-label">Tiêu đề:</label>
                        <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de') }}"
                            placeholder="Tiêu đề cho banner">
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label">Nội dung:</label>
                        <textarea name="noi_dung" class="form-control" placeholder="Nội dung của banner">{{ old('noi_dung') }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label">Loại Banner:</label>
                        <select name="loai_banner" class="form-control">
                            <option value="slider" {{ old('loai_banner') == 'slider' ? 'selected' : '' }}>Slideshow</option>
                            <option value="footer" {{ old('loai_banner') == 'footer' ? 'selected' : '' }}>Footer</option>
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label">Trạng thái:</label>
                        <select name="trang_thai" class="form-control">
                            <option value="hien" {{ old('trang_thai') == 'hien' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="an" {{ old('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>

                    <div class="filter-choices-input mt-3">
                        <div class="d-flex justify-content-between align-items-end">
                            <label class="form-label">Ảnh slide</label>
                            <div id="add-row" class="btn btn-success btn-sm mb-2">+</div>
                        </div>

                        <table class="table align-middle mb-0">
                            <tbody id="image-table-body">
                                <tr>
                                    <td class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img id="preview_0"
                                                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s"
                                                width="50px" style="object-fit: cover;">

                                            <input type="file" name="list_image[new_0]" class="form-control mx-2"
                                                onchange="previewImageAndAddToSlideshow(this, 0)">

                                            <button type="button"
                                                class="btn btn-light text-danger d-flex justify-content-center align-items-center rounded-circle p-2"
                                                style="width: 40px; height: 40px;" onclick="removeRow(this)"
                                                title="Xoá ảnh">
                                                <i class="icon-trash-2" style="font-size: 20px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="icon-plus"></i> Thêm
                        </button>
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary-custom">
                            <i class="icon-x"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-5">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Xem trước slide</h5>
        </div>
        <div class="card-body">
            <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="carouselImages">
                    {{-- Ảnh sẽ được JS thêm ở đây --}}
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
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
        let tableBody = document.getElementById('image-table-body');
        let newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <img id="preview_${rowCount}" 
                         src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s" 
                         width="50px" style="object-fit: cover;">

                    <input type="file" 
                           name="list_image[new_${rowCount}]" 
                           class="form-control mx-2" 
                           onchange="previewImageAndAddToSlideshow(this, ${rowCount})">

                    <button type="button"
                        class="btn btn-light text-danger d-flex justify-content-center align-items-center rounded-circle p-2"
                        style="width: 40px; height: 40px;" 
                        onclick="removeRow(this)" 
                        title="Xoá ảnh">
                        <i class="icon-trash-2" style="font-size: 20px;"></i>
                    </button>
                </div>
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
            // Cập nhật ảnh nhỏ bên cạnh input
            const previewImg = document.getElementById(`preview_${rowIndex}`);
            if (previewImg) {
                previewImg.src = e.target.result;
            }

            // Tìm carousel item theo preview id
            const carouselInner = document.getElementById('carouselImages');
            const existingItem = carouselInner.querySelector(`[data-preview-id="preview_${rowIndex}"]`);

            if (existingItem) {
                // Nếu đã tồn tại: cập nhật ảnh
                const carouselImg = existingItem.querySelector('img');
                if (carouselImg) {
                    carouselImg.src = e.target.result;
                    carouselImg.alt = `Slide ${rowIndex + 1}`;
                }
            } else {
                // Nếu chưa có thì tạo mới
                const newItem = document.createElement('div');
                newItem.classList.add('carousel-item');
                newItem.setAttribute('data-preview-id', `preview_${rowIndex}`);

                if (carouselInner.children.length === 0) {
                    newItem.classList.add('active');
                }

                newItem.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                        <img src="${e.target.result}" class="img-fluid rounded"
                             style="max-height: 100%; max-width: 100%; object-fit: contain;"
                             alt="Slide ${rowIndex + 1}">
                    </div>
                `;

                carouselInner.appendChild(newItem);
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}




        // Xoá dòng ảnh được thêm mới
        function removeRow(btn) {
    const row = btn.closest('tr');
    const img = row.querySelector('img');

    if (img) {
        const previewId = img.id;

        const carouselItems = document.querySelectorAll('#carouselImages .carousel-item');
        carouselItems.forEach(item => {
            if (item.getAttribute('data-preview-id') === previewId) {
                item.remove();
            }
        });
    }

    row.remove();

    // Cập nhật lại class active nếu không còn item active
    const activeItem = document.querySelector('#carouselImages .carousel-item.active');
    if (!activeItem) {
        const firstItem = document.querySelector('#carouselImages .carousel-item');
        if (firstItem) {
            firstItem.classList.add('active');
        }
    }
}

    </script>
@endpush

@push('styles')
    <style>
        /* ===== FORM CHUNG ===== */
        .form-group label,
        .form-label {
            font-size: 15px;
            font-weight: 600;
            color: #222;
            margin-bottom: 6px;
            display: block;
        }

        input.form-control,
        textarea.form-control,
        select.form-control {
            font-size: 15px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #ccc;
            background-color: #fff;
            color: #333;
            transition: 0.2s ease;
        }

        input.form-control:hover,
        textarea.form-control:hover,
        select.form-control:hover {
            border-color: #6366f1;
        }

        input.form-control:focus,
        textarea.form-control:focus,
        select.form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            outline: none;
        }

        /* ===== BUTTON ===== */
        .btn-warning,
        .btn-secondary-custom {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            transition: background-color 0.2s, color 0.2s;
            border: none;
            min-width: 100px;
            text-align: center;
        }

        .btn-secondary-custom {
            background-color: #e5e7eb;
            color: #333;
        }

        /* ===== CĂN CHỈNH NÚT ===== */
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        #carouselImages .carousel-item img {
    object-fit: contain;
    max-height: 100%;
    max-width: 100%;
}

    </style>
@endpush

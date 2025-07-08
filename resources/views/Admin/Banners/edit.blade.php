@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Sửa banner: {{ $banner->tieu_de }}</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Sửa banner</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!--  Thêm đoạn sau ngay sau -->
                    <div id="delete-images-wrapper"></div>

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
                        <label>Tiêu đề:</label>
                        <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror"
                            value="{{ old('tieu_de', $banner->tieu_de) }}">
                    </div>

                    <div class="form-group mt-3">
                        <label>Nội dung:</label>
                        <textarea name="noi_dung" class="form-control @error('noi_dung') is-invalid @enderror">{{ old('noi_dung', $banner->noi_dung) }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label>Loại banner:</label>
                        <select name="loai_banner" class="form-control">
                            <option value="slider"
                                {{ old('loai_banner', $banner->loai_banner) == 'slider' ? 'selected' : '' }}>Slideshow
                            </option>
                            <option value="footer"
                                {{ old('loai_banner', $banner->loai_banner) == 'footer' ? 'selected' : '' }}>Footer</option>
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label>Trạng thái:</label>
                        <select name="trang_thai" class="form-control">
                            <option value="hien" {{ old('trang_thai', $banner->trang_thai) == 'hien' ? 'selected' : '' }}>
                                Hiển thị</option>
                            <option value="an" {{ old('trang_thai', $banner->trang_thai) == 'an' ? 'selected' : '' }}>Ẩn
                            </option>
                        </select>
                    </div>

                    <div class="filter-choices-input mt-3">
                        <div class="d-flex justify-content-between align-items-end">
                            <label class="form-label">Ảnh slide</label>
                            <div id="add-row" class="btn btn-success btn-sm mb-2">+</div>
                        </div>
                        <table class="table align-middle mb-0">
                            <tbody id="image-table-body">
                                @foreach ($banner->hinhAnhBanner as $index => $item)
                                    <tr>
                                        <td class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img id="preview_{{ $index }}"
                                                    src="{{ Storage::url($item->hinh_anh) }}" width="50px">

                                                <input type="file" name="list_image[{{ $item->id }}]"
                                                    class="form-control mx-2"
                                                    onchange="previewImage(this, {{ $index }})">

                                                {{-- Nút xóa + input ẩn sẽ thêm vào DOM khi bấm nút --}}
                                                <button type="button"
                                                    class="btn btn-light text-danger d-flex justify-content-center align-items-center rounded-circle p-2"
                                                    style="width: 40px; height: 40px;" onclick="markImageForDelete(this)"
                                                    data-id="{{ $item->id }}" title="Xoá ảnh">
                                                    <i class="icon-trash-2" style="font-size: 20px;"></i>
                                                </button>


                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-end">
                        <div>
                            <div id="delete-images-wrapper"></div>
                            <button type="submit" class="btn btn-warning">Sửa</button>
                            <a href="{{ route('admin.banners.index') }}" class="btn-secondary-custom">Quay lại</a>
                        </div>
                    </div>

                </form>
            </div>

            <div class="mt-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Slide ở đây</h5>
                    </div>
                    <div class="card-body">
                        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <div class="d-flex justify-content-center" style="height: 300px;">
                                            <img src="{{ Storage::url($hinhAnh->hinh_anh) }}"
                                                class="img-fluid rounded shadow-sm"
                                                style="max-height: 100%; object-fit: contain;"
                                                alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@push('styles')
    <!-- gridjs css -->
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/gridjs/theme/mermaid.min.css') }}">
@endpush

@push('scripts')
    {{-- <!-- prismjs plugin -->
    <script src="{{ asset('assets/admin/libs/prismjs/prism.js') }}"></script>

    <!-- gridjs js -->
    <script src="{{ asset('assets/admin/libs/gridjs/gridjs.umd.js') }}"></script> --}}
    <!--  Đây là chỗ hiển thị dữ liệu phân trang -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = {{ count($banner->hinhAnhBanner) }};

            // Bắt sự kiện nút +
            document.getElementById('add-row').addEventListener('click', function() {
                let tableBody = document.getElementById('image-table-body');
                let newRow = document.createElement('tr');

                newRow.innerHTML = `
                <td class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <img id="preview_${rowCount}" 
                             src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s" width="50px"
                             width="50px" style="object-fit: cover;">
                        
                        <input type="file" 
                               name="list_image[new_${rowCount}]" 
                               class="form-control mx-2" 
                               onchange="previewImage(this, ${rowCount})">

                        <button type="button"
                            class="btn btn-light text-danger d-flex justify-content-center align-items-center rounded-circle p-2"
                            style="width: 40px; height: 40px;" onclick="markImageForDelete(this)"
                            data-id="{{ $item->id }}" title="Xoá ảnh">
                            <i class="icon-trash-2" style="font-size: 20px;"></i>
                        </button>
                    </div>
                </td>
            `;

                tableBody.appendChild(newRow);
                rowCount++;
            });
        });

        // Xem trước hình ảnh
        function previewImage(input, rowIndex) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(`preview_${rowIndex}`).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // function previewImageAndAddToSlideshow(input, rowIndex) {
        //     if (input.files && input.files[0]) {
        //         const reader = new FileReader();
        //         reader.onload = function(e) {
        //             // Cập nhật ảnh xem trước
        //             document.getElementById(`preview_${rowIndex}`).setAttribute('src', e.target.result);

        //             // Thêm ảnh vào carousel
        //             var carouselInner = document.getElementById('carouselImages');
        //             var newCarouselItem = document.createElement('div');
        //             newCarouselItem.classList.add('carousel-item');
        //             newCarouselItem.innerHTML = `
        //             <img src="${e.target.result}" class="d-block w-100 img-fluid" style="height: 300px; object-fit: cover;" alt="Image ${rowIndex + 1}">
        //         `;

        //             // Nếu là ảnh đầu tiên, đặt class 'active'
        //             if (carouselInner.children.length === 0) {
        //                 newCarouselItem.classList.add('active');
        //             }

        //             // Thêm ảnh vào carousel
        //             carouselInner.appendChild(newCarouselItem);
        //         };
        //         reader.readAsDataURL(input.files[0]);
        //     }
        // }

        // Xoá dòng ảnh được thêm mới
        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        // Đánh dấu ảnh cũ để xoá
        function markImageForDelete(button, imageId) {
            console.log('Xóa ảnh với ID:', imageId);

            // Xoá dòng hiển thị ảnh cũ
            const row = button.closest('tr');
            row.remove();

            // Tạo input hidden để Laravel biết ảnh nào cần xoá
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;

            document.querySelector('form').appendChild(input);
        }


        function markImageForDelete(button) {
            const imageId = button.getAttribute('data-id');
            if (!imageId) {
                console.error(" Không có ID ảnh để xoá");
                return;
            }

            let row = button.closest('tr');
            row.remove();

            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;

            const wrapper = document.getElementById('delete-images-wrapper');
            if (wrapper) {
                wrapper.appendChild(input);
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

        /* .btn-warning {
                    background-color: #c19e14;
                    color: white;
                } */

        /* .btn-warning:hover {
                    background-color: #a88612;
                } */

        .btn-secondary-custom {
            background-color: #e5e7eb;
            /* xám nhạt */
            color: #333;
        }



        /* ===== CĂN CHỈNH NÚT ===== */
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
@endpush

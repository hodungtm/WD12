@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Sửa banner: {{ $banner->tieu_de }}</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Sửa banner</div></li>
            </ul>
        </div>

        <div class="row">
            <!-- Form sửa -->
            <div class="col-lg-8">
                <div class="wg-box">
                    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                            <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de', $banner->tieu_de) }}">
                        </div>

                        <div class="form-group mt-3">
                            <label>Nội dung:</label>
                            <textarea name="noi_dung" class="form-control">{{ old('noi_dung', $banner->noi_dung) }}</textarea>
                        </div>

                        <div class="form-group mt-3">
                            <label>Loại banner:</label>
                            <select name="loai_banner" class="form-control">
                                <option value="slider" {{ old('loai_banner', $banner->loai_banner) == 'slider' ? 'selected' : '' }}>Slideshow</option>
                                <option value="footer" {{ old('loai_banner', $banner->loai_banner) == 'footer' ? 'selected' : '' }}>Footer</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label>Trạng thái:</label>
                            <select name="trang_thai" class="form-control">
                                <option value="hien" {{ old('trang_thai', $banner->trang_thai) == 'hien' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="an" {{ old('trang_thai', $banner->trang_thai) == 'an' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <div class="d-flex justify-between items-end mb-2">
                                <label>Ảnh slide</label>
                                <div id="add-row" class="btn btn-secondary btn-sm">+</div>
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
                                                        <button type="button" class="btn btn-light text-danger"
                                                            onclick="markImageForDelete(this, {{ $item->id }})">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-end gap10">
                            <button type="submit" class="btn btn-warning"><i class="icon-edit-3"></i> Cập nhật</button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Carousel -->
            <div class="col-lg-4">
                <div class="wg-box text-center">
                    <div id="bannerCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ Storage::url($hinhAnh->hinh_anh) }}" class="d-block mx-auto img-fluid" style="height: 300px; object-fit: cover;" alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">Next</span>
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

<!-- prismjs plugin -->
{{-- <script src="{{ asset('assets/admin/libs/prismjs/prism.js') }}"></script>

<!-- gridjs js -->
<script src="{{ asset('assets/admin/libs/gridjs/gridjs.umd.js') }}"></script>
<!--  Đây là chỗ hiển thị dữ liệu phân trang -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rowCount = {{ count($banner->hinhAnhBanner) }};

        // Thêm sự kiện cho nút 'Thêm hàng'
        document.getElementById('add-row').addEventListener('click', function() {
            var tableBody = document.getElementById('image-table-body');
            var newRow = document.createElement('tr');

            newRow.innerHTML = `
                    <td class="d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <img id="preview_${rowCount}" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s" width="50px">
                            <input type="file" id="hinh_anh" name="list_image[id_${rowCount}]" class="form-control mx-2" onchange="previewImage(this, ${rowCount})">
                                                        <button class="btn btn-light remove-row" onclick="removeRow(this)"><i class="bx bx-trash"></i></button>
                        </div>
                    </td>
                `;

            tableBody.appendChild(newRow);
            rowCount++;
        });




    });

    function previewImage(input, rowIndex) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(`preview_${rowIndex}`).setAttribute('src', e.target.result);
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

    function removeRow(item) {
        var row = item.closest('tr');
        row.remove();
    }

    function markImageForDelete(button, imageId) {
        // Xóa hàng hiển thị
        let row = button.closest('tr');
        row.remove();

        // Tạo input hidden để Laravel biết xóa ảnh nào
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_images[]';
        input.value = imageId;

        // Thêm vào form
        document.querySelector('form').appendChild(input);
    }
</script> --}}

@push('scripts')
    <!-- prismjs plugin -->
    <script src="{{ asset('assets/admin/libs/prismjs/prism.js') }}"></script>

    <!-- gridjs js -->
    <script src="{{ asset('assets/admin/libs/gridjs/gridjs.umd.js') }}"></script>
    <!--  Đây là chỗ hiển thị dữ liệu phân trang -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var rowCount = {{ count($banner->hinhAnhBanner) }};

            // Thêm sự kiện cho nút 'Thêm hàng'
            document.getElementById('add-row').addEventListener('click', function() {
                var tableBody = document.getElementById('image-table-body');
                var newRow = document.createElement('tr');

                newRow.innerHTML = `
                    <td class="d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <img id="preview_${rowCount}" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s" width="50px">
                            <input type="file" id="hinh_anh" name="list_image[id_${rowCount}]" class="form-control mx-2" onchange="previewImage(this, ${rowCount})">
                                                        <button class="btn btn-light remove-row" onclick="removeRow(this)"><i class="bx bx-trash"></i></button>
                        </div>
                    </td>
                `;

                tableBody.appendChild(newRow);
                rowCount++;
            });




        });

        function previewImage(input, rowIndex) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(`preview_${rowIndex}`).setAttribute('src', e.target.result);
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

        function removeRow(item) {
            var row = item.closest('tr');
            row.remove();
        }

        function markImageForDelete(button, imageId) {
        // Xóa hàng hiển thị
        let row = button.closest('tr');
        row.remove();

        // Tạo input hidden để Laravel biết xóa ảnh nào
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_images[]';
        input.value = imageId;

        // Thêm vào form
        document.querySelector('form').appendChild(input);
    }
    </script>
@endpush


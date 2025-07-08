@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Chi tiết banner: {{ $banner->tieu_de }}</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Chi tiết banner</div></li>
            </ul>
        </div>

        <div class="row">
            <!-- Thông tin -->
            <div class="col-lg-8">
                <div class="wg-box">
                    <div class="form-group">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" readonly value="{{ $banner->tieu_de }}">
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label">Nội dung</label>
                        <textarea class="form-control" rows="4" readonly>{{ $banner->noi_dung }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label">Loại banner</label>
                        <input type="text" class="form-control" readonly value="{{ $banner->loai_banner }}">
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label">Trạng thái</label><br>
                        <span class="badge {{ $banner->trang_thai === 'hien' ? 'bg-success' : 'bg-warning' }}">
                            {{ $banner->trang_thai === 'hien' ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary-custom">
                            <i class="icon-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carousel nhỏ -->
            <div class="col-lg-4">
                <div class="wg-box text-center">
                    <div id="bannerCarouselModal" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="previewCarouselSmall">
                            @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <div class="d-flex justify-content-center align-items-center" style="height: 250px;">
                                    <img src="{{ Storage::url($hinhAnh->hinh_anh) }}" 
                                         class="img-fluid rounded shadow-sm"
                                         style="max-height: 100%; object-fit: contain;"
                                         alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarouselModal" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarouselModal" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#imageModal">
                        <i class="icon-maximize"></i> Xem lớn
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem lớn -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xem ảnh lớn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="previewCarouselLarge">
                        @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="d-flex justify-content-center align-items-center" style="height: 500px;">
                                <img src="{{ Storage::url($hinhAnh->hinh_anh) }}" 
                                     class="img-fluid rounded shadow"
                                     style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                     alt="Slide {{ $key + 1 }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-group label,
    .form-label {
        font-weight: 600;
        font-size: 15px;
        color: #222;
        margin-bottom: 6px;
    }

    input.form-control,
    textarea.form-control {
        font-size: 15px;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #ccc;
        background-color: #fff;
        color: #333;
    }

    input.form-control:focus,
    textarea.form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        outline: none;
    }

    .btn-secondary-custom {
        background-color: #e5e7eb;
        color: #333;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-secondary-custom:hover {
        background-color: #d1d5db;
    }

    .carousel-inner img {
        object-fit: contain;
    }
</style>
@endpush

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
                        <label>Tiêu đề</label>
                        <input type="text" class="form-control" readonly value="{{ $banner->tieu_de }}">
                    </div>

                    <div class="form-group mt-3">
                        <label>Nội dung</label>
                        <textarea class="form-control" rows="4" readonly>{{ $banner->noi_dung }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label>Loại banner</label>
                        <input type="text" class="form-control" readonly value="{{ $banner->loai_banner }}">
                    </div>

                    <div class="form-group mt-3">
                        <label>Trạng thái</label><br>
                        <span class="badge {{ $banner->trang_thai === 'hien' ? 'bg-success' : 'bg-warning' }}">
                            {{ $banner->trang_thai === 'hien' ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </div>

                    <div class="mt-4 d-flex justify-end gap10">
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
            </div>

            <!-- Carousel -->
            <div class="col-lg-4">
                <div class="wg-box text-center">
                    <div id="bannerCarouselModal" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ Storage::url($hinhAnh->hinh_anh) }}" class="d-block mx-auto img-fluid" alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
                            </div>
                            @endforeach
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarouselModal"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarouselModal"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#imageModal">
                        Xem lớn
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xem lớn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ Storage::url($hinhAnh->hinh_anh) }}" class="d-block w-100 img-fluid"
                                alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
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
@endsection

@push('styles')
<style>
    .carousel-inner img {
        max-height: 400px;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script></script>
@endpush

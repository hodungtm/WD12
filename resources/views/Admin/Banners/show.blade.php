@extends('Admin.Layouts.AdminLayout')
@section('main')
    <div class="row">
        <!-- Chi tiết banner -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header" id="bannerFormToggle" style="cursor: pointer;">
                    <h5 class="fs-16 mb-0">Chi tiết banner</h5>
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="mb-3">
                            <label for="title">Tiêu đề</label>
                            <input type="text" class="form-control" readonly value="{{ $banner->tieu_de }}">
                        </div>
                        <div class="mb-3">
                            <label for="bannerImage" class="form-label">Nội dung:</label>
                            <textarea class="form-control" id="content" rows="5" readonly>{{ $banner->noi_dung }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="loaiBanner" class="form-label">Loại Banner:</label>
                            <input type="text" class="form-control" id="loaiBanner" value="{{ $banner->loai_banner }}"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái:
                                <span class="{{ $banner->trang_thai === 'hien' ? 'badge bg-success' : 'bg-warning' }}">
                                    {{ $banner->trang_thai === 'hien' ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </label>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ảnh banner -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center p-3 position-relative">
                    <div id="bannerCarouselModal" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($hinhAnh->hinh_anh) }}" class="d-block mx-auto img-fluid"
                                        alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
                                </div>
                            @endforeach
                        </div>

                        <!-- Nút điều hướng trái/phải -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarouselModal"
                            data-bs-slide="prev" style="background: transparent; border: none; outline: none; box-shadow: none;">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarouselModal"
                            data-bs-slide="next" style="background: transparent; border: none; outline: none; box-shadow: none;">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"></span>
                        </button>
                    </div>

                    <!-- Nút xem lớn -->
                    <button type="button" class="btn btn-view-full mt-3" data-bs-toggle="modal"
                        data-bs-target="#imageModal" style="background-color: #001C40; color: white;">
                        Xem lớn
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Hình ảnh lớn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bannerCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($hinhAnh->hinh_anh) }}" class="d-block w-100 img-fluid"
                                        alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel"
                            data-bs-slide="prev" style="background: transparent; border: none; outline: none; box-shadow: none;">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel"
                            data-bs-slide="next" style="background: transparent; border: none; outline: none; box-shadow: none;">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .carousel {
            display: flex;
            justify-content: space-between;
        }

        .modal-body img {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
        }

        .carousel-inner img {
            max-width: 100%;
            max-height: 500px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
    </style>
@endpush



@push('scripts')
    <script></script>
@endpush

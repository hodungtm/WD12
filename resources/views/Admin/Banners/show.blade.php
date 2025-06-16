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
                            <input type="text" class="form-control" id="loaiBanner" value="{{ $banner->loai_banner }}" readonly>
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

        <!-- Ảnh banner bên phải -->
        <div class="card-body">
    @if ($banner->hinhAnhBanner->count())
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <img src="{{ Storage::url($hinhAnh->hinh_anh) }}"
                             class="d-block w-100 border rounded"
                             style="max-height:250px; height:auto; object-fit:contain;"
                             alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
                    </div>
                @endforeach
            </div>

            @if ($banner->hinhAnhBanner->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Trước</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Sau</span>
                </button>
            @endif
        </div>
    @else
        <p class="text-muted">Không có hình ảnh</p>
    @endif
</div>

@endsection

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
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fs-16 mb-0">Hình ảnh</h5>
                </div>
                <div class="card-body">
                    @foreach ($banner->hinhAnhBanner as $key => $hinhAnh)
                        <div class="mb-3">
                            <img src="{{ Storage::url($hinhAnh->hinh_anh) }}"
                                 class="img-fluid border rounded w-100"
                                 style="max-height:250px; height:auto; object-fit:contain; display:block; margin:auto;"

                                 alt="Banner {{ $banner->id }} Image {{ $key + 1 }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('Admin.Layouts.AdminLayout')
@section('title', 'Danh sách banner | Quản trị Admin')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">

            <!-- Tiêu đề + breadcrumb -->
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách banner</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Banner</div>
                    </li>
                </ul>
            </div>

            @if (session('success'))
                <div class="alert alert-success" id="success-alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <div class="wg-filter flex-grow">
                            <div class="filter-toolbar">

                                {{-- Form lọc --}}
                                <form method="GET" action="{{ route('admin.banners.index') }}" class="filter-form">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                            <select name="loai_banner" class="form-control">
                                <option value="">Loại banner</option>
                                <option value="slider" {{ request('loai_banner') == 'slider' ? 'selected' : '' }}>Slider</option>
                                <option value="footer" {{ request('loai_banner') == 'footer' ? 'selected' : '' }}>Footer</option>
                            </select>
                            <select name="trang_thai" class="form-control">
                                <option value="">Trạng thái</option>
                                <option value="hien" {{ request('trang_thai') == 'hien' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="an" {{ request('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                            <select name="sort" class="form-control">
                                <option value="">Sắp xếp</option>
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                            <select name="per_page" class="form-control">
                                <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 dòng</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 dòng</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 dòng</option>
                            </select>
                            <button type="submit" class="btn btn-primary search-btn">
                                <i class="bx bx-search-alt-2"></i> Tìm kiếm
                            </button>
                        </form>

                            </div>
                        </div>

                    </div>
                    <div class="flex gap10">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.banners.create') }}"
                            style="padding: 5px 10px; font-size: 15px; white-space: nowrap;">
                            <i class="bx bx-plus"></i> Thêm
                        </a>
                    </div>
                </div>

                <div class="wg-table table-all-category mt-3">
                    <ul class="table-title flex mb-14">
                        <li class="col-id">
                            <div class="body-title">ID</div>
                        </li>
                        <li class="col-title">
                            <div class="body-title">Tiêu đề</div>
                        </li>
                        <li class="col-images">
                            <div class="body-title">Ảnh</div>
                        </li>
                        <li class="col-type">
                            <div class="body-title">Loại banner</div>
                        </li>
                        <li class="col-status">
                            <div class="body-title">Trạng thái</div>
                        </li>
                        <li class="col-action">
                            <div class="body-title">Hành động</div>
                        </li>
                    </ul>

                    <ul class="flex flex-column">
                        @forelse ($banners as $banner)
                            <li class="product-item flex mb-10">
                                <div class="col-id">
                                    {{ ($banners->currentPage() - 1) * $banners->perPage() + $loop->iteration }}
                                </div>

                                <div class="col-title">{{ $banner->tieu_de }}</div>
                                <div class="col-images">
                                    @if ($banner->hinhAnhBanner && $banner->hinhAnhBanner->count())
                                        @foreach ($banner->hinhAnhBanner as $img)
                                            <img src="{{ asset('storage/' . $img->hinh_anh) }}" alt="ảnh banner"
                                                style="max-width: 80px; max-height: 80px;" class="me-1 mb-1">
                                        @endforeach
                                    @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                    @endif
                                </div>
                                <div class="col-type">{{ $banner->loai_banner }}</div>
                                <div class="col-status">
                                    <span class="badge {{ $banner->trang_thai === 'hien' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $banner->trang_thai === 'hien' ? 'Hiện' : 'Ẩn' }}
                                    </span>
                                </div>
                                <div class="col-action list-icon-function">
                                    <a href="{{ route('admin.banners.show', $banner->id) }}" class="item eye"
                                        title="Xem"><i class="icon-eye"></i></a>
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="item edit"
                                        title="Sửa"><i class="icon-edit-3"></i></a>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa banner này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="item trash" title="Xóa"
                                            style="background: none; border: none;">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <div class="text-muted px-3">Chưa có banner nào.</div>
                        @endforelse
                    </ul>
                </div>

                <div class="divider mt-3"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">Tổng: {{ $banners->total() }} banner</div>
                    {{ $banners->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('fade');
                alertBox.style.transition = "opacity 0.5s";
                alertBox.style.opacity = 0;
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }
    });
</script>

<style>
    /* Cải thiện phần tiêu đề bảng và các cột dữ liệu */
    .table-title li,
    .product-item>div {
        display: flex;
        align-items: center;
        padding: 10px 8px;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        /* Màu chữ đậm hơn */
    }

    .table-title {
        background-color: #f7f7f7;
        border-radius: 6px;
    }

    .body-title {
        font-size: 14px;
        font-weight: 600;
        color: #111;
    }

    /* Cột */
    .col-id {
        flex: 0.3;
        min-width: 40px;
    }

    .col-title {
        flex: 1.5;
        min-width: 120px;
    }

    .col-images {
        flex: 2;
        min-width: 150px;
        flex-wrap: wrap;
        gap: 6px;
        display: flex;
    }

    .col-type {
        flex: 1;
        min-width: 100px;
    }

    .col-status {
        flex: 1;
        min-width: 80px;
    }

    .col-action {
        flex: 1.2;
        min-width: 120px;
        display: flex;
        gap: 10px;
    }

    /* Badge trạng thái */
    .badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 13px;
        border-radius: 20px;
        font-weight: 500;
        color: white;
    }

    .bg-success {
        background-color: #28a745;
    }

    .bg-warning {
        background-color: #ffc107;
        color: #333;
    }

    /* Icon chức năng */
    .list-icon-function a,
    .list-icon-function button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        border-radius: 6px;
        width: 32px;
        height: 32px;
        transition: all 0.2s;
        color: #333;
        background: white;
    }

    .list-icon-function a:hover,
    .list-icon-function button:hover {
        background-color: #f0f0f0;
        color: #007bff;
        border-color: #007bff;
    }

    /* Ảnh banner */
    .col-images img {
        max-width: 80px;
        max-height: 80px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    /* Phần tìm kiếm + nút */
    /* .form-search input {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
        width: 200px;
    } */

    .button-submit button {
        padding: 8px 12px;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 8px;
        transition: background-color 0.2s;
    }

    .button-submit button:hover {
        background-color: #0056b3;
    }

    /* Nút Thêm mới */
    .tf-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border: 2px solid #ff5722;
        color: #ff5722;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s;
        background: white;
    }

    .tf-button:hover {
        background-color: #ff5722;
        color: white;
    }

    /* Breadcrumb */
    .breadcrumbs .text-tiny {
        font-size: 13px;
        color: #999;
    }

    .breadcrumbs li i {
        color: #ccc;
    }

    html,
    body {
        height: auto !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    .main-content-inner,
    .main-content-wrap {
        overflow: visible !important;
        min-height: 100vh;
    }

    .wg-box {
        overflow: visible;
    }

    .filter-toolbar {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-form .form-control {
    padding: 6px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    min-width: 100px;
    width: auto;
    flex-shrink: 0;
}

.filter-form input.form-control {
    width: 160px;
}
</style>

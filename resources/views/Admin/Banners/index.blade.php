@extends('Admin.Layouts.AdminLayout')
@section('title', 'Danh sách banner | Quản trị Admin')

@section('main')

    @if (session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="app-title d-flex justify-content-between align-items-center mb-3">
        <ul class="app-breadcrumb breadcrumb side mb-0">
            <li class="breadcrumb-item active"><b>Danh sách banner</b></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile card shadow-sm rounded-3 border-0">
                <div class="tile-body p-4">

                    {{-- Nút thêm mới --}}
                    <div class="d-flex align-items-center justify-content-start mb-3" style="gap: 10px;">
                        <a href="{{ route('admin.banners.create') }}"
                            class="btn btn-outline-success btn-sm d-flex align-items-center" style="gap: 5px;">
                            <i class="fas fa-plus"></i> Thêm mới banner
                        </a>
                    </div>

                    {{-- Form tìm kiếm --}}
                    <form method="GET" action="" class="mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Tìm kiếm..." value="{{ request('search') }}"
                                style="width: 220px; height: 32px;">
                            <button class="btn btn-outline-warning btn-sm d-flex align-items-center" type="submit"
                                style="height: 32px;">
                                <i class="fas fa-search me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>

                    {{-- <form method="GET" action="" class="d-flex mb-3" style="max-width: 600px;">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm"
                        value="{{ request('search') }}" aria-label="Tìm kiếm">

                    <select name="loai_banner" class="form-select me-2">
                        <option value="">-- Tất cả loại --</option>
                        <option value="home" {{ request('loai_banner') == 'home' ? 'selected' : '' }}>Trang chủ</option>
                        <option value="product" {{ request('loai_banner') == 'product' ? 'selected' : '' }}>Sản phẩm
                        </option>
                        <option value="about" {{ request('loai_banner') == 'about' ? 'selected' : '' }}>Giới thiệu</option>
                        <!-- Thêm các loại khác nếu có -->
                    </select>

                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form> --}}

                {{-- <div class="d-flex justify-content-between mb-3 flex-wrap" style="gap: 10px;">
             
                    <form method="GET" action="" class="d-flex" style="max-width: 400px;">
                        <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm theo tiêu đề"
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                
                    <form method="GET" action="" class="d-flex" style="max-width: 300px;">
                        <select name="loai_banner" class="form-select me-2" onchange="this.form.submit()">
                            <option value="">-- Lọc theo loại banner --</option>
                            <option value="home" {{ request('loai_banner') == 'home' ? 'selected' : '' }}>Trang chủ
                            </option>
                            <option value="product" {{ request('loai_banner') == 'product' ? 'selected' : '' }}>Sản phẩm
                            </option>
                            <option value="about" {{ request('loai_banner') == 'about' ? 'selected' : '' }}>Giới thiệu
                            </option>
                            <!-- Thêm các loại khác nếu có -->
                        </select>
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </form>
                </div> --}}

                    {{-- Table --}}
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead style="background-color: #f8f9fa; font-weight: bold;">
                            <tr>
                                <th style="width: 50px;">STT</th>
                                <th>Tiêu đề</th>
                                <th style="width: 500px;">Album ảnh</th>
                                <th class="text-nowrap">Loại banner</th>
                                <th class="text-nowrap">Trạng thái</th>
                                <th class="text-nowrap">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($banners as $index => $banner)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $banner->tieu_de }}</td>
                                    <td>
                                        @if ($banner->hinhAnhBanner && $banner->hinhAnhBanner->count())
                                            @foreach ($banner->hinhAnhBanner as $img)
                                                <img src="{{ asset('storage/' . $img->hinh_anh) }}" alt="ảnh banner"
                                                    width="60" class="img-thumbnail me-1 mb-1">
                                            @endforeach
                                        @else
                                            <span class="text-muted">Chưa có ảnh</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">{{ $banner->loai_banner }}</td>
                                    <td class="text-nowrap">
                                        <span
                                            class="badge {{ $banner->trang_thai === 'hien' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $banner->trang_thai === 'hien' ? 'Hiển thị' : 'Ẩn' }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-outline-info btn-sm" title="Xem"
                                            href="{{ route('admin.banners.show', $banner->id) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a class="btn btn-outline-warning btn-sm" title="Sửa"
                                            href="{{ route('admin.banners.edit', $banner->id) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa banner này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm" title="Xóa" type="submit">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có banner nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if ($banners->hasPages())
                        <div class="mt-3">
                            {{ $banners->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Script thông báo --}}
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

@endsection

@extends('Admin.Layouts.AdminLayout')
@section('title', 'Danh sách banner | Quản trị Admin')
@section('main')
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item">Danh sách banner</li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.banners.create') }}">Thêm banner</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Danh sách banner</h3>

                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary mb-3">
                    + Thêm mới banner
                </a>

                <form method="GET" action="" class="d-flex mb-3" style="max-width: 400px;">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm"
                        value="{{ request('search') }}" aria-label="Tìm kiếm">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                @if (session('success'))
    <div id="success-alert" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Tiêu đề</th>
                            <th style="width: 500px;">Album Ảnh</th>
                            <th class="text-nowrap" style="width: 120px;">Loại banner</th>
                            <th class="text-nowrap" style="width: 100px;">Trạng thái</th>
                            <th class="text-nowrap" style="width: 130px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $index => $banner)
                            

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $banner->tieu_de }}</td>
                                <td>
                                    @if ($banner->hinhAnhBanner && $banner->hinhAnhBanner->count())
                                        @foreach ($banner->hinhAnhBanner as $img)
                                            <img src="{{ asset('storage/' . $img->hinh_anh) }}" alt="ảnh banner" width="100px"
                                                class="mb-1">
                                        @endforeach
                                    @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">{{ $banner->loai_banner }}</td>
                                <td class="text-nowrap">
                                    <span class="badge {{ $banner->trang_thai === 'hien' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $banner->trang_thai === 'hien' ? 'Hiện' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <a class="btn btn-outline-info btn-sm" title="Xem" href="{{ route('admin.banners.show', $banner->id) }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn btn-outline-primary btn-sm" title="Sửa" href="{{ route('admin.banners.edit', $banner->id) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display:inline-block;"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa banner này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Xóa" type="submit">
                                            <i class="bi bi-trash"></i>
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
                 {{ $banners->links() }}
                {{-- Nếu sau này dùng phân trang --}}
                  
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
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


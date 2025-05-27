@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="app-title d-flex justify-content-between align-items-center">
        <ul class="app-breadcrumb breadcrumb side mb-0">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách bài viết</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">

                    {{-- Các nút chức năng --}}
                    <div class="d-flex align-items-center justify-content-start mb-3" style="gap: 10px;">
                        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm d-flex align-items-center"
                            style="gap: 5px;">
                            <i class="fas fa-plus"></i> Tạo mới bài viết
                        </a>
                        <a href="#" class="btn btn-warning btn-sm d-flex align-items-center nhap-tu-file"
                            title="Tải từ file" style="gap: 5px;">
                            <i class="fas fa-file-upload"></i> Tải từ file
                        </a>
                        <a class="btn btn-info btn-sm d-flex align-items-center print-file" onclick="window.print()"
                            style="gap: 5px;">
                            <i class="fas fa-print"></i> In dữ liệu
                        </a>
                        <a class="btn btn-secondary btn-sm d-flex align-items-center js-textareacopybtn" style="gap: 5px;">
                            <i class="fas fa-copy"></i> Sao chép
                        </a>
                        <a class="btn btn-success btn-sm d-flex align-items-center" href="#" style="gap: 5px;">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </a>
                        <a class="btn btn-danger btn-sm d-flex align-items-center pdf-file" style="gap: 5px;">
                            <i class="fas fa-file-pdf"></i> Xuất PDF
                        </a>
                        <a class="btn btn-secondary btn-sm d-flex align-items-center" href="#"
                            style="background-color: #6c757d; border-color: #6c757d; color: white; gap: 5px;">
                            <i class="fas fa-trash-alt"></i> Xóa tất cả
                        </a>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- Điều khiển hiển thị số mục --}}
                        <div class="d-flex align-items-center">
                            <span class="me-2">Hiện</span>
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="ms-2">danh mục</span>
                        </div>

                        {{-- Form tìm kiếm --}}
                        <form method="GET" action="{{ route('posts.index') }}">
                            <div class="input-group input-group-sm">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="keyword" class="form-control"
                                        placeholder="Nhập thông tin..." value="{{ request('keyword') }}" style="min-width: 200px; height: 40px;" >
                                    <button style="min-width: 50px; min-height: 40px;" class="btn btn-primary " type="submit">
                                        <i class="fas fa-search me-1" >Tìm Kiếm</i>
                                    </button>
                                </div>  

                            </div>
                        </form>
                    </div>
                    {{-- Thông báo --}}
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- Bảng danh sách bài viết --}}
                    <table class="table table-hover table-bordered js-copytextarea" id="sampleTable">
                        <thead>
                            <tr class="text-center">
                                <th><input type="checkbox" id="all"></th>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Ảnh</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr class="text-center align-middle">
                                    <td><input type="checkbox" name="select_post[]" value="{{ $post->id }}"></td>
                                    <td>{{ $post->id }}</td>
                                    <td class="text-start">{{ $post->title }}</td>
                                    <td>
                                        @if ($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" alt="Ảnh" width="100"
                                                class="img-thumbnail">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($post->status === 'published')
                                            <span class="badge bg-success">Đã đăng</span>
                                        @elseif($post->status === 'draft')
                                            <span class="badge bg-secondary">Nháp</span>
                                        @else
                                            <span class="badge bg-warning">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm"
                                            title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm"
                                            title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Chưa có bài viết nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Phân trang --}}
                    @if ($posts->hasPages())
                        <div class="mt-3">
                            {{ $posts->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

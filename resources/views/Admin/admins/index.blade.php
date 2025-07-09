@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách tài khoản Admin</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">

                    <div class="row element-button mb-3">
                        {{-- <div class="col-sm-2">
                            <a class="btn btn-add btn-sm" href="{{ route('admin.admins.create') }}" title="Thêm">
                                <i class="fas fa-plus"></i> Thêm Admin mới
                            </a>
                        </div> --}}

                        {{-- <div class="col-sm-2">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.roles.index') }}"
                                title="Quản lý phân quyền">
                                <i class="fas fa-user-shield"></i> Quản lý phân quyền
                            </a>
                        </div> --}}
                        <div class="d-flex align-items-center justify-content-start mb-3" style="gap: 10px;">
                           
                            <a href="" class="btn btn-warning btn-sm d-flex align-items-center" title="Tải từ file"
                                style="gap: 5px;">
                                <i class="fas fa-file-upload"></i> Tải từ file
                            </a>

                            <a class="btn btn-info btn-sm d-flex align-items-center" onclick="window.print()"
                                style="gap: 5px;">
                                <i class="fas fa-print"></i> In dữ liệu
                            </a>

                            <button type="button"
                                class="btn btn-secondary btn-sm d-flex align-items-center js-textareacopybtn"
                                style="gap: 5px;">
                                <i class="fas fa-copy"></i> Sao chép
                            </button>

                            <a class="btn btn-success btn-sm d-flex align-items-center" href="" style="gap: 5px;">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </a>

                            <a class="btn btn-danger btn-sm d-flex align-items-center" href="" style="gap: 5px;">
                                <i class="fas fa-file-pdf"></i> Xuất PDF
                            </a>

                            <form action="" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm d-flex align-items-center"
                                    style="background-color: #6c757d; border-color: #6c757d; color: white; gap: 5px;">
                                    <i class="fas fa-trash-alt"></i> Xóa tất cả
                                </button>
                            </form>
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-success btn-sm" href="{{ route('admin.audit_logs.index') }}"
                                title="Xem lịch sử hoạt động">
                                <i class="fas fa-history"></i> Lịch sử hoạt động
                            </a>
                        </div>

                        
                    </div>

                    <form method="GET" action="{{ route('admin.admins.index') }}" class="mb-4">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <div class="input-group shadow-sm">
                                    <input type="text" name="search" class="form-control rounded-start"
                                        placeholder="🔍 Tìm kiếm tên hoặc email..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-auto mt-2 mt-md-0">
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-hover table-bordered" id="adminTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Ảnh đại diện</th>
                                {{-- <th>Phân quyền</th> <!-- thêm cột roles --> --}}
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <img src="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : asset('default-avatar.png') }}"
                                            width="50" height="50" class="rounded-circle" alt="Avatar">
                                    </td>
                                    {{-- <td>
                                        @if($admin->roles->isNotEmpty())
                                        @foreach($admin->roles as $role)
                                        <span class="badge bg-info text-dark">{{ $role->name }}</span>
                                        @endforeach
                                        @else
                                        <span class="text-muted">Chưa phân quyền</span>
                                        @endif
                                    </td> --}}
                                    <td>
                                        @if($admin->is_active == 1)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Khóa</span>
                                        @endif
                                    </td>
                                    <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-primary btn-sm"
                                            title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-info btn-sm"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $admins->withQueryString()->links() }}

                </div>
            </div>
        </div>
    </div>
@endsection
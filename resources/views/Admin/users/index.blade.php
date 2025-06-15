@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="app-title">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách tài khoản</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">

                    <div class="row element-button mb-3">
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

                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
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
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary dropdown-toggle shadow-sm" type="button"
                                        id="filterRoleBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-filter me-1"></i> Bộ lọc
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="filterRoleBtn">
                                        <li>
                                            <a class="dropdown-item {{ request('role') == '' ? 'active' : '' }}"
                                                href="{{ route('admin.users.index') }}">
                                                <i class="fas fa-users me-1"></i> Tất cả
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request('role') == 'Admin' ? 'active' : '' }}"
                                                href="{{ route('admin.users.index', ['role' => 'Admin']) }}">
                                                <i class="fas fa-user-shield me-1"></i> Admin
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ request('role') == 'User' ? 'active' : '' }}"
                                                href="{{ route('admin.users.index', ['role' => 'User']) }}">
                                                <i class="fas fa-user me-1"></i> User
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary shadow-sm">
                                        <i class="fas fa-times me-1"></i> Xóa bộ lọc
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>


                    <table class="table table-hover table-bordered" id="userTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh đại diện</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Phone</th>
                                <th>Giới tính</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
                                            width="50" height="50" class="rounded-circle" alt="Avatar">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-primary">Admin</span>
                                        @else
                                            <span class="badge bg-secondary">User</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ ucfirst($user->gender) }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Bị khóa</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>

                                        <!-- Xem chi tiết -->
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <!-- Khóa / Mở tài khoản (chỉ hiện nếu không phải admin) -->
                                        @if($user->role !== 'admin')
                                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST"
                                                class="d-inline-block"
                                                onsubmit="return confirm('Bạn có chắc muốn {{ $user->is_active ? 'khóa' : 'mở khóa' }} tài khoản này?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warning btn-sm"
                                                    title="{{ $user->is_active ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}">
                                                    <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }}"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Sửa (chỉ khi là admin) -->
                                        @if($user->role === 'admin')
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm"
                                                title="Chỉnh sửa tài khoản admin">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        <!-- Xóa -->
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
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

                    {{ $users->withQueryString()->links() }}

                </div>
            </div>
        </div>
    </div>
@endsection
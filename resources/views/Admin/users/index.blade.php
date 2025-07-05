@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="app-title d-flex justify-content-between align-items-center">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <ul class="app-breadcrumb breadcrumb side mb-0">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách tài khoản</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">

                    {{-- Các nút chức năng --}}
                    {{-- <div class="d-flex align-items-center justify-content-start mb-3" style="gap: 10px;">
                        <a href="#" class="btn btn-warning btn-sm d-flex align-items-center" title="Tải từ file"
                            style="gap: 5px;">
                            <i class="fas fa-file-upload"></i> Tải từ file
                        </a>
                        <a class="btn btn-info btn-sm d-flex align-items-center" onclick="window.print()" style="gap: 5px;">
                            <i class="fas fa-print"></i> In dữ liệu
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm d-flex align-items-center js-textareacopybtn"
                            style="gap: 5px;">
                            <i class="fas fa-copy"></i> Sao chép
                        </button>
                        <a class="btn btn-success btn-sm d-flex align-items-center" href="#" style="gap: 5px;">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </a>
                        <a class="btn btn-danger btn-sm d-flex align-items-center" href="#" style="gap: 5px;">
                            <i class="fas fa-file-pdf"></i> Xuất PDF
                        </a>
                        <form action="#" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả?');"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary btn-sm d-flex align-items-center"
                                style="gap: 5px;">
                                <i class="fas fa-trash-alt"></i> Xóa tất cả
                            </button>
                        </form>
                        <a class="btn btn-success btn-sm d-flex align-items-center"
                            href="{{ route('admin.audit_logs.index') }}" style="gap: 5px;">
                            <i class="fas fa-history"></i> Lịch sử hoạt động
                        </a>
                    </div> --}}
                    {{-- Bộ lọc & tìm kiếm --}}
                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            {{-- Bộ lọc vai trò --}}
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle shadow-sm" type="button"
                                    id="filterRoleBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Vai trò
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterRoleBtn">
                                    {{-- Tất cả --}}
                                    <li>
                                        <a class="dropdown-item {{ !request('role') ? 'active' : '' }}"
                                            href="{{ route('admin.users.index', request()->except('role')) }}">
                                            <i class="fas fa-users me-1"></i> Tất cả
                                        </a>
                                    </li>
                                    {{-- Admin --}}
                                    <li>
                                        <a class="dropdown-item {{ request('role') === 'admin' ? 'active' : '' }}"
                                            href="{{ route('admin.users.index', array_merge(request()->all(), ['role' => 'admin'])) }}">
                                            <i class="fas fa-user-shield me-1"></i> Admin
                                        </a>
                                    </li>
                                    {{-- User --}}
                                    <li>
                                        <a class="dropdown-item {{ request('role') === 'user' ? 'active' : '' }}"
                                            href="{{ route('admin.users.index', array_merge(request()->all(), ['role' => 'user'])) }}">
                                            <i class="fas fa-user me-1"></i> User
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Bộ lọc số lượng hiển thị --}}
                            <div class="d-flex align-items-center">
                                <label class="me-2 mb-0">Hiện:</label>
                                <select name="per_page" class="form-control-sm" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="ms-2">tài khoản</span>
                            </div>

                            {{-- Ô tìm kiếm --}}
                            <div class="d-flex align-items-center">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Tìm tên hoặc email..." value="{{ request('search') }}"
                                        style="min-width: 200px; height: 40px;">
                                    <button class="btn btn-outline-warning" type="submit"
                                        style="min-width: 50px; height: 40px;">
                                        <i class="fas fa-search me-1"></i> Tìm
                                    </button>
                                </div>
                            </div>

                            {{-- Nút xóa bộ lọc --}}
                            @if(request()->hasAny(['role', 'search', 'per_page']))
                                <div>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary shadow-sm">
                                        <i class="fas fa-times me-1"></i> Xóa bộ lọc
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>


                    {{-- Bảng danh sách tài khoản --}}
                    <form action="{{ route('admin.users.delete.selected') }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xóa các tài khoản đã chọn?');">
                        @csrf
                        @method('DELETE')

                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th><input type="checkbox" id="check_all"></th>
                                    <th>ID</th>
                                    <th>Ảnh đại diện</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Giới tính</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="align-middle text-center">
                                        <td>
                                            {{-- Chỉ cho phép chọn user (không chọn admin) --}}
                                            @if($user->role === 'user')
                                                <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                                    class="check_item">
                                            @endif
                                        </td>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
                                                width="50" height="50" class="rounded-circle" alt="Avatar">
                                        </td>
                                        <td class="text-start">{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($user->gender) }}</td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-danger">Bị khóa</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($user->role === 'admin' && $user->id === Auth::id())
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            @if($user->role === 'user' || ($user->role === 'admin' && $user->id === Auth::id()))
                                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Xóa tài khoản này?')"
                                                        class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($user->role === 'user')
                                                <form method="POST" action="{{ route('admin.users.toggle-active', $user->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button onclick="return confirm('Bạn có chắc muốn đổi trạng thái?')"
                                                        class="btn btn-outline-warning btn-sm">
                                                        @if($user->is_active)
                                                            <i class="fas fa-lock"></i>
                                                        @else
                                                            <i class="fas fa-unlock"></i>
                                                        @endif
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">Chưa có tài khoản nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Nút xóa các tài khoản đã chọn --}}
                        <button type="submit" class="btn btn-outline-danger btn-sm mt-2" style="gap: 5px;">
                            <i class="fas fa-trash-alt"></i> Xóa các tài khoản đã chọn
                        </button>
                    </form>

                    {{-- Script chọn tất cả --}}
                    <script>
                        document.getElementById('check_all').addEventListener('change', function () {
                            document.querySelectorAll('.check_item').forEach(cb => cb.checked = this.checked);
                        });
                    </script>


                    {{-- Phân trang --}}
                    @if($users->hasPages())
                        <div class="mt-3">
                            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
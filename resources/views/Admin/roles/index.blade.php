@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="app-title">
        {{-- XÓA ĐOẠN HIỂN THỊ LỖI/THÔNG BÁO Ở ĐÂY --}}
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách quyền</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">

                    <div class="row element-button mb-3">
                        <div class="col-sm-2">
                            <a class="btn btn-add btn-sm" href="{{ route('admin.roles.create') }}" title="Thêm">
                                <i class="fas fa-plus"></i> Thêm quyền mới
                            </a>
                        </div>

                        <div class="col-sm-2">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.admins.index') }}"
                                title="Quay lại quản lý Admin">
                                <i class="fas fa-users"></i> Quay lại quản lý Admin
                            </a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('admin.roles.index') }}" class="mb-4">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <div class="input-group shadow-sm">
                                    <input type="text" name="search" class="form-control rounded-start"
                                        placeholder="🔍 Tìm kiếm tên quyền..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-auto mt-2 mt-md-0">
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-hover table-bordered" id="rolesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên quyền</th>
                                <th>Mô tả</th>
                                <th>Ngày tạo</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->description ?? '-' }}</td>
                                    <td>{{ $role->created_at ? $role->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa quyền này?')">
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

                    {{ $roles->withQueryString()->links() }}

                </div>
            </div>
        </div>
    </div>
@endsection

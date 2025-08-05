@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Danh sách tài khoản Admin</a></li>
            <li class="breadcrumb-item active"><b>Chi tiết tài khoản #{{ $admin->id }}</b></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="tile">
                <h4 class="tile-title mb-4">Thông tin chi tiết tài khoản</h4>
                <div class="tile-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <img src="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : asset('default-avatar.png') }}"
                                 alt="Avatar" class="rounded-circle shadow" width="150" height="150">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th>ID:</th>
                                    <td>{{ $admin->id }}</td>
                                </tr>
                                <tr>
                                    <th>Họ và tên:</th>
                                    <td>{{ $admin->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $admin->email }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        @if($admin->is_active)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Đã khóa</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo:</th>
                                    <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày cập nhật:</th>
                                    <td>{{ $admin->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Sửa
                        </a>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

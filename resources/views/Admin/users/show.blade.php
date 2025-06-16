@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="app-title">
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item">Tài khoản</li>
    <li class="breadcrumb-item active"><a href="#">Chi tiết tài khoản</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="tile-title mb-0">Chi tiết người dùng</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
          <i class="fa fa-arrow-left"></i> Quay lại
        </a>
      </div>

      <div class="text-center mb-4">
        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
             width="120" height="120" class="rounded-circle border" alt="Avatar">
      </div>

      <table class="table table-bordered">
        <tr>
          <th style="width: 200px;">ID</th>
          <td>{{ $user->id }}</td>
        </tr>
        <tr>
          <th>Họ tên</th>
          <td>{{ $user->name }}</td>
        </tr>
        <tr>
          <th>Email</th>
          <td>{{ $user->email }}</td>
        </tr>
        {{-- <tr>
          <th>Số điện thoại</th>
          <td>{{ $user->phone ?? '—' }}</td>
        </tr> --}}
        <tr>
          <th>Giới tính</th>
          <td>
            @php
              $genderLabels = ['male' => 'Nam ♂', 'female' => 'Nữ ♀', 'other' => 'Khác ⚧'];
            @endphp
            {{ $genderLabels[$user->gender] ?? '—' }}
          </td>
        </tr>
        <tr>
          <th>Vai trò</th>
          <td>
            @if($user->role === 'admin')
              <span class="badge bg-info">Admin</span>
            @elseif($user->role === 'super-admin')
              <span class="badge bg-danger">Super Admin</span>
            @else
              <span class="badge bg-secondary">User</span>
            @endif
          </td>
        </tr>
        <tr>
          <th>Trạng thái</th>
          <td>
            @if($user->is_active)
              <span class="badge bg-success">Hoạt động</span>
            @else
              <span class="badge bg-danger">Bị khóa</span>
            @endif
          </td>
        </tr>
        <tr>
          <th>Ngày tạo</th>
          <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
        </tr>
      </table>
    </div>
  </div>
</div>
@endsection

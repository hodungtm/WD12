@extends('Admin.Layouts.AdminLayout')

@section('main')

<div class="app-title">
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item">Tài khoản</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.edit', $user) }}">Chỉnh sửa tài khoản</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Chỉnh sửa tài khoản người dùng</h3>

      {{-- Hiển thị lỗi --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form class="row" action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Tên --}}
        <div class="form-group col-md-6">
          <label class="control-label">Họ tên</label>
          <input class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        {{-- Email --}}
        <div class="form-group col-md-6">
          <label class="control-label">Email</label>
          <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        {{-- Mật khẩu --}}
        <div class="form-group col-md-6">
          <label class="control-label">Mật khẩu mới (nếu muốn đổi)</label>
          <input class="form-control" type="password" name="password" placeholder="Để trống nếu không đổi">
        </div>

        {{-- Giới tính --}}
        <div class="form-group col-md-6">
          <label class="control-label">Giới tính</label>
          <select class="form-control" name="gender">
            <option value="">-- Chọn giới tính --</option>
            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
          </select>
        </div>
        {{-- Avatar --}}
        <div class="form-group col-md-6">
          <label class="control-label">Ảnh đại diện</label>
          <input class="form-control" type="file" name="avatar">
          @if ($user->avatar)
            <div class="mt-2">
              <img src="{{ asset('storage/' . $user->avatar) }}" width="100" class="rounded" alt="Avatar hiện tại" onerror="this.style.display='none'">
            </div>
          @endif
        </div>

        {{-- Nút --}}
        <div class="form-group col-md-12 mt-3">
          <button class="btn btn-save" type="submit">Cập nhật</button>
          <a class="btn btn-cancel" href="{{ route('admin.users.index') }}">Hủy bỏ</a>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection

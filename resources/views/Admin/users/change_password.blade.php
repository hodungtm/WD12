@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="app-title">
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item">Tài khoản</li>
    <li class="breadcrumb-item active">Đổi mật khẩu</li>
  </ul>
</div>

<div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Đổi mật khẩu</h5>
      </div>
      <div class="card-body">
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

        <form action="{{ route('admin.users.update-password', $user) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
          <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary">Quay lại</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

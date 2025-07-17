@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Đổi mật khẩu</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.users.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.users.index') }}">
                            <div class="text-tiny">Tài khoản</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Đổi mật khẩu</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="title-box mb-20">
                    <i class="icon-key"></i>
                    <div class="body-text">Cập nhật mật khẩu mới cho tài khoản</div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success mb-3">{{ session('success') }}</div>
                @endif
                <form action="{{ route('admin.users.update-password', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-4"style="max-width: 400px;">
                        <div class="body-title mb-10">Mật khẩu hiện tại<span class="tf-color-1">*</span></div>
                        <input type="password" name="current_password" class="form-control" required>
                      </div>
                    <div class="form-group mb-4" style="max-width: 400px;">
                        <div class="body-title mb-10">Mật khẩu mới <span class="tf-color-1">*</span></div>
                        <input type="password" name="password" class="form-control" required>
                        @error('password')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-4" style="max-width: 400px;">
                        <div class="body-title mb-10">Nhập lại mật khẩu <span class="tf-color-1">*</span></div>
                        <input type="password" name="password_confirmation" class="form-control" required>
                        @error('password_confirmation')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="flex justify-start gap-3 mt-4">
                        <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                            <i class="icon-save"></i> Cập nhật mật khẩu
                        </button>
                        <a href="{{ route('admin.users.edit', $user) }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                            <i class="icon-x"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

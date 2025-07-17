@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chỉnh sửa tài khoản</h3>
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
                        <div class="text-tiny">Chỉnh sửa tài khoản</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="title-box mb-20">
                    <i class="icon-user-edit"></i>
                    <div class="body-text">Cập nhật thông tin tài khoản người dùng</div>
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
                @if(session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif
                <form id="edit-form" action="{{ route('admin.users.update', $user) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group mb-4" style="max-width: 400px;">
                                <div class="body-title mb-10">Họ tên <span class="tf-color-1">*</span></div>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                    required>
                                @error('name')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4" style="max-width: 400px;">
                                <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4" style="max-width: 400px;">
                                <div class="body-title mb-10">Giới tính</div>
                                <select name="gender" class="form-control">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam
                                    </option>
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>
                                        Nữ</option>
                                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>
                                        Khác</option>
                                </select>
                                @error('gender')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4" style="max-width: 400px;">
                                <div class="body-title mb-10">Ảnh đại diện</div>
                                <input type="file" name="avatar" class="form-control">
                                @if ($user->avatar)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $user->avatar) }}" width="100"
                                            class="rounded shadow-sm border" alt="Avatar hiện tại"
                                            onerror="this.style.display='none'">
                                    </div>
                                @endif
                                @error('avatar')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-start gap-3 mt-4">
                        <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                            <i class="icon-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                            <i class="icon-x"></i> Quay lại danh sách
                        </a>
                        <a href="{{ route('admin.users.update-password', $user) }}"
                            class="tf-button btn-sm w-auto px-3 py-2">
                            <i class="icon-key"></i> Đổi mật khẩu
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
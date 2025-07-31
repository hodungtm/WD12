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

            {{-- Merged wg-box with card structure and notifications --}}
            <div class="wg-box">
                <div class="title-box mb-20">
                    <i class="icon-user-edit"></i>
                    <div class="body-text">Cập nhật thông tin tài khoản người dùng</div>
                </div>

                {{-- Thông báo lỗi validation (từ Code 1, đã tồn tại trong Code 2) --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Thông báo session (từ Code 1, đã tồn tại trong Code 2) --}}
                @if(session('success'))
                    <div class="alert alert-success mb-3">{{ session('success') }}</div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info mb-3">{{ session('info') }}</div>
                @endif

                {{-- Form chính --}}
                <form id="edit-form" action="{{ route('admin.users.update', $user) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        {{-- Họ tên --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4" style="max-width: 400px;"> {{-- Style from Code 1 --}}
                                <div class="body-title mb-10">Họ tên <span class="tf-color-1">*</span></div> {{-- Style from Code 1 --}}
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                @error('name')
<small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4" style="max-width: 400px;"> {{-- Style from Code 1 --}}
                                <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div> {{-- Style from Code 1 --}}
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Giới tính --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4" style="max-width: 400px;"> {{-- Style from Code 1 --}}
                                <div class="body-title mb-10">Giới tính</div> {{-- Style from Code 1 --}}
                                <select name="gender" class="form-control"> {{-- Used form-control from Code 1 --}}
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gender')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Ảnh đại diện --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4" style="max-width: 400px;"> {{-- Style from Code 1 --}}
                                <div class="body-title mb-10">Ảnh đại diện</div> {{-- Style from Code 1 --}}
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

                    {{-- Nút hành động --}}
                    <div class="flex justify-start gap-3 mt-4"> {{-- Flex and gap from Code 1 --}}
                        <button type="button" class="tf-button btn-sm w-auto px-3 py-2" id="open-password-modal"> {{-- Button styling from Code 1, with ID from Code 2 --}}
                            <i class="icon-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2"> {{-- Button styling from Code 1 --}}
                            <i class="icon-x"></i> Quay lại danh sách
                        </a>
                        {{-- "Đổi mật khẩu" button (from Code 2, adjusted styling to Code 1's aesthetic) --}}
                        <a href="{{ route('admin.users.update-password', $user) }}" class="tf-button style-1 btn-sm w-auto px-3 py-2"> {{-- Used style-1 for consistent look --}}
                            <i class="icon-key"></i> Đổi mật khẩu
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-labelledby="confirmPasswordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Vui lòng nhập mật khẩu để xác nhận:</label>
                        <input type="password" class="form-control" id="confirm-password" placeholder="Mật khẩu">
                        <div class="text-danger mt-1" id="password-error" style="display: none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-confirm-password" class="btn btn-primary">Xác nhận & Cập nhật</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('open-password-modal').addEventListener('click', function () {
const modal = new bootstrap.Modal(document.getElementById('confirmPasswordModal'));
        modal.show();
    });

    document.getElementById('submit-confirm-password').addEventListener('click', function () {
        const password = document.getElementById('confirm-password').value;
        const errorBox = document.getElementById('password-error');
        errorBox.style.display = 'none';

        if (!password) {
            errorBox.textContent = 'Vui lòng nhập mật khẩu!';
            errorBox.style.display = 'block';
            return;
        }

        fetch("{{ route('admin.users.verify-password.post', $user->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ password: password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.verified) {
                document.getElementById('edit-form').submit();
            } else {
                errorBox.textContent = 'Mật khẩu không chính xác!';
                errorBox.style.display = 'block';
            }
        })
        .catch(() => {
            errorBox.textContent = 'Lỗi xác minh mật khẩu. Vui lòng thử lại.';
            errorBox.style.display = 'block';
        });
    });
</script>
@endsection
@extends('Admin.Layouts.AdminLayout')

@section('title', 'Chỉnh sửa tài khoản Admin')

@section('main')
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Danh sách Admin</a></li>
            <li class="breadcrumb-item active">Chỉnh sửa tài khoản</li>
        </ul>
    </div>

    <div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Chỉnh sửa tài khoản Admin</h3>
            <div class="tile-body">
                <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="form-group col-md-6">
                            <label class="control-label">Tên</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $admin->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $admin->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Mật khẩu <small>(để trống nếu không đổi)</small></label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        {{-- ẢNH ĐẠI DIỆN --}}
                        <div class="form-group col-md-6">
                            <label class="control-label">Ảnh đại diện</label>
                            @if ($admin->avatar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar"
                                         class="rounded-circle" width="100" height="100">
                                </div>
                            @endif
                            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6 mt-6">
                            <label class="control-label">Trạng thái</label>
                            <select name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                <option value="1" {{ old('is_active', $admin->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('is_active', $admin->is_active) == 0 ? 'selected' : '' }}>Khóa</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <button type="submit" class="btn btn-save mt-3">Lưu lại</button>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-cancel mt-3">Hủy bỏ</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.getElementById('roleDropdown');
    const selectBox = dropdown.querySelector('.select-box');
    const options = dropdown.querySelector('.options');
    const selectedText = dropdown.querySelector('.selected-text');
    const placeholder = dropdown.querySelector('.placeholder');
    const hiddenInput = dropdown.querySelector('#roleInput');

    // Lấy giá trị role đã chọn từ server (edit mode)
    // Cần truyền biến từ backend, ví dụ $selectedRoleId = admin có role đầu tiên hoặc mặc định
    let selectedRoleId = @json(old('roles', isset($admin) && $admin->roles->count() ? $admin->roles->first()->id : null));

   function updateSelected(roleId, roleName) {
    if (roleId) {
        selectedText.textContent = roleName;
        placeholder.style.display = 'none';
        hiddenInput.value = roleId;
        // selectBox.style.boxShadow = "0 0 8px 2px rgba(0, 123, 255, 0.5)";  // Đã bỏ đổ bóng
    } else {
        selectedText.textContent = '';
        placeholder.style.display = 'inline';
        hiddenInput.value = '';
        selectBox.style.boxShadow = 'none';
    }
}


    // Khởi tạo giá trị nếu có role chọn sẵn
    if (selectedRoleId) {
        const selectedOption = dropdown.querySelector(`.option-item[data-id="${selectedRoleId}"]`);
        if (selectedOption) {
            updateSelected(selectedRoleId, selectedOption.textContent.trim());
        }
    }

    selectBox.addEventListener('click', () => {
        options.style.display = options.style.display === 'block' ? 'none' : 'block';
    });

    options.querySelectorAll('.option-item').forEach(option => {
        option.addEventListener('click', () => {
            const id = option.getAttribute('data-id');
            const name = option.textContent.trim();
            updateSelected(id, name);
            options.style.display = 'none';
        });
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
            options.style.display = 'none';
        }
    });
});
</script>
<style>
  .option-item {
    padding: 8px 12px;
    cursor: pointer;
    user-select: none;
  }

  .option-item:hover,
  .option-item:focus {
    background-color: #555;
    color: white;
    outline: none;
  }
</style> --}}

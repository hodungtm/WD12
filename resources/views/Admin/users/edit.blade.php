@extends('Admin.Layouts.AdminLayout')

@section('main')

<div class="app-title">
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item">Tài khoản</li>
    <li class="breadcrumb-item active">Chỉnh sửa tài khoản</li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-10 offset-lg-1">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
      <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Chỉnh sửa tài khoản người dùng</h5>
        <a href="{{ route('admin.users.edit-password', $user) }}" class="btn btn-warning btn-sm">
          <i class="fas fa-key me-1"></i> Đổi mật khẩu
        </a>
      </div>

      <div class="card-body">
        {{-- Thông báo --}}
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

        {{-- Form --}}
        <form id="edit-form" action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row g-4">
            {{-- Thông tin cá nhân --}}
            <div class="col-md-6">
              <label class="form-label">Họ tên <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Giới tính</label>
              <select name="gender" class="form-select">
                <option value="">-- Chọn giới tính --</option>
                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
              </select>
            </div>

            {{-- Ảnh đại diện --}}
            <div class="col-md-6">
              <label class="form-label">Ảnh đại diện</label>
              <input type="file" name="avatar" class="form-control">
              @if ($user->avatar)
                <div class="mt-2">
                  <img src="{{ asset('storage/' . $user->avatar) }}" width="100" class="rounded shadow-sm border"
                       alt="Avatar hiện tại" onerror="this.style.display='none'">
                </div>
              @endif
            </div>
          </div>

          {{-- Nút --}}
          <div class="mt-4 d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-primary" id="open-password-modal">
              <i class="fas fa-save me-1"></i> Cập nhật
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal xác nhận mật khẩu -->
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

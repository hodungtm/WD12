@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="container mt-4">
        <h2>Thêm tài khoản Admin</h2>

        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Tên Admin</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="Nhập tên admin">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="Nhập email admin">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">    
                <label><strong>Phân quyền</strong></label>
                <div class="single-select-dropdown" id="roleDropdown" style="position: relative;">
                    <div class="select-box" tabindex="0"
                        style="border: 1px solid #ccc; border-radius: 5px; padding: 8px; min-height: 40px; cursor: pointer; display: flex; align-items: center;">
                        <span class="selected-text" style="color:#aaa; flex-grow: 1;">Chọn quyền...</span>
                        <span class="arrow" style="margin-left: auto;">&#9662;</span> <!-- mũi tên xuống -->
                    </div>
                    <div class="options"
                        style="display: none; position: absolute; top: 100%; left: 0; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 5px; background: white; box-shadow: 0 2px 6px rgba(0,0,0,0.15); z-index: 1000;">
                        @foreach($roles as $role)
                            <div class="option-item" data-value="{{ $role->id }}" style="padding: 8px 12px; cursor: pointer;">
                                {{ $role->name }}
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="roles[]" id="roleInput" value="{{ old('roles')[0] ?? '' }}">
                </div>
            </div>



            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Nhập mật khẩu">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder="Nhập lại mật khẩu">
            </div>

            <div class="form-group">
                <label for="is_active">Trạng thái</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Khóa</option>
                </select>
            </div>


            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.getElementById('roleDropdown');
    const selectBox = dropdown.querySelector('.select-box');
    const options = dropdown.querySelector('.options');
    const selectedText = dropdown.querySelector('.selected-text');
    const hiddenInput = dropdown.querySelector('#roleInput');

    // Mở/đóng dropdown khi click
    selectBox.addEventListener('click', () => {
      options.style.display = options.style.display === 'block' ? 'none' : 'block';
    });

    // Khi chọn 1 quyền
    options.querySelectorAll('.option-item').forEach(option => {
      option.addEventListener('click', () => {
        const value = option.getAttribute('data-value');
        const text = option.textContent.trim();

        // Cập nhật text hiển thị và input ẩn
        selectedText.textContent = text;
        selectedText.style.color = '#000'; // đổi màu chữ khi chọn
        hiddenInput.value = value;

        options.style.display = 'none'; // đóng dropdown
      });
    });

    // Đóng dropdown khi click ra ngoài
    document.addEventListener('click', (e) => {
      if (!dropdown.contains(e.target)) {
        options.style.display = 'none';
      }
    });

    // Khởi tạo: nếu có giá trị cũ thì hiển thị text tương ứng
    const oldValue = hiddenInput.value;
    if (oldValue) {
      const selectedOption = Array.from(options.children).find(opt => opt.getAttribute('data-value') === oldValue);
      if (selectedOption) {
        selectedText.textContent = selectedOption.textContent.trim();
        selectedText.style.color = '#000';
      }
    }
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
</style>
@extends('Admin.Layouts.AdminLayout')
@section('main')
<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item">Danh sách sản phẩm</li>
            <li class="breadcrumb-item"><a href="#">Thêm sản phẩm</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h1>Thêm danh mục mới</h1>

            
                <form action="{{ route('Admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label>Tên danh mục:</label><br>
                    <input type="text" name="ten_danh_muc" value="{{ old('ten_danh_muc') }}" class="form-control" >
                    @error('ten_danh_muc')
                        <div style="color: red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                    <br><br>

                    <label for="image" class="form-label">Ảnh:</label><br>
                    <input class="form-control" type="file" name="anh" accept="image/*">
                    @error('anh')
                        <div style="color: red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                    <br><br>

                    <label>Tình trạng:</label><br>
                    <select class="form-control" name="tinh_trang" required>
                        <option value="1" {{ old('tinh_trang') == '1' ? 'selected' : '' }}>Hiện</option>
                        <option value="0" {{ old('tinh_trang') == '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('tinh_trang')
                        <div style="color: red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                    <br><br>

                    <label>Mô tả:</label><br>
                    <textarea name="mo_ta" class="form-control" rows="4">{{ old('mo_ta') }}</textarea>
                    @error('mo_ta')
                        <div style="color: red; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                    <br><br>

                    <button class="btn btn-add btn-sm" type="submit">Lưu</button>
                </form>

            </div>
        </div>
    </div>
</main>


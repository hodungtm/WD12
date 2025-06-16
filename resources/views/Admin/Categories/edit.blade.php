@extends('Admin.Layouts.AdminLayout')
@section('main')
<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item">Danh sách sản phẩm</li>
            <li class="breadcrumb-item"><a href="#">Sửa sản phẩm</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h1>Sửa danh mục</h1>

               

                <form action="{{ route('Admin.categories.update', $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Tên danh mục --}}
                    <label>Tên danh mục:</label><br>
                    <input class="form-control" type="text" name="ten_danh_muc" value="{{ old('ten_danh_muc', $category->ten_danh_muc) }}"
                        >
                    @error('ten_danh_muc')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                    <br><br>

                    {{-- Ảnh hiện tại --}}
                    <label>Ảnh hiện tại:</label><br>
                    @if($category->anh)
                        <img src="{{ asset('storage/' . $category->anh) }}" width="120" alt="Ảnh danh mục"><br><br>
                    @else
                        Không có ảnh<br><br>
                    @endif

                    {{-- Đổi ảnh --}}
                    <label>Đổi ảnh:</label><br>
                    <input class="form-control" type="file" name="anh" accept="image/*">
                    @error('anh')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                    <br><br>

                    {{-- Tình trạng --}}
                    <label>Tình trạng:</label><br>
                    <select class="form-control" name="tinh_trang" required>
                        <option value="1" {{ (old('tinh_trang', $category->tinh_trang) == 1) ? 'selected' : '' }}>Hiện</option>
                        <option value="0" {{ (old('tinh_trang', $category->tinh_trang) == 0) ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    @error('tinh_trang')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                    <br><br>

                    {{-- Mô tả --}}
                    <label>Mô tả:</label><br>
                    <textarea name="mo_ta" class="form-control" rows="4">{{ old('mo_ta', $category->mo_ta) }}</textarea>
                    @error('mo_ta')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                    <br><br>

                    <button class="btn btn-add btn-sm" type="submit">Cập nhật</button>
                </form>

                <a class="btn btn-primary btn-sm trashed" href="{{ route('Admin.categories.index') }}">← Quay về danh sách</a>

            </div>
        </div>
    </div>
</main>


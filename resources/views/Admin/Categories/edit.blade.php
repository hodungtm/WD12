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
                <h1>Sửa danh mục</h1>

                @if ($errors->any())
                    <div style="color: red;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('Admin.categories.update', $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <label>Mã danh mục:</label><br>
                    <input class="form-control" type="text" name="ma_danh_muc" value="{{ old('ma_danh_muc', $category->ma_danh_muc) }}"
                        required><br><br>

                    <label>Tên danh mục:</label><br>
                    <input class="form-control" type="text" name="ten_danh_muc" value="{{ old('ten_danh_muc', $category->ten_danh_muc) }}"
                        required><br><br>

                    <label>Ảnh hiện tại:</label><br>
                    @if($category->anh)
                        <img src="{{ asset('storage/' . $category->anh) }}" width="120" alt="Ảnh danh mục"><br><br>
                    @else
                        Không có ảnh<br><br>
                    @endif

                    <label>Đổi ảnh:</label><br>
                    <input class="form-control" type="file" name="anh" accept="image/*"><br><br>

                    <label>Tình trạng:</label><br>
                    <select class="form-control" name="tinh_trang" required>
                        <option value="1" {{ (old('tinh_trang', $category->tinh_trang) == 1) ? 'selected' : '' }}>Hiện
                        </option>
                        <option value="0" {{ (old('tinh_trang', $category->tinh_trang) == 0) ? 'selected' : '' }}>Ẩn
                        </option>
                    </select><br><br>

                    <button class="btn btn-add btn-sm" type="submit">Cập nhật</button>
                </form>

                <a class="btn btn-primary btn-sm trashed" href="{{ route('Admin.categories.index') }}">← Quay về danh sách</a>

            </div>

        </div>
</main>
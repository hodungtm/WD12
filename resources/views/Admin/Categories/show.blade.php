@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('Admin.categories.index') }}">Danh sách danh mục</a></li>
            <li class="breadcrumb-item active">Chi tiết danh mục</li>
        </ul>
    </div>

    <div class="tile">
        <h2>Chi tiết danh mục</h2>

        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $category->id }}</td>
            </tr>
            <tr>
                <th>Tên danh mục</th>
                <td>{{ $category->ten_danh_muc }}</td>
            </tr>
            <tr>
                <th>Mô tả</th>
                <td>{{ $category->mo_ta ?? 'Không có mô tả' }}</td>
            </tr>
            <tr>
                <th>Tình trạng</th>
                <td>{{ $category->tinh_trang == 1 ? 'Hiện' : 'Ẩn' }}</td>
            </tr>
            <tr>
                <th>Ảnh</th>
                <td>
                    @if($category->anh)
                        <img src="{{ asset('storage/' . $category->anh) }}" width="150" alt="Ảnh danh mục">
                    @else
                        Không có ảnh
                    @endif
                </td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Ngày cập nhật</th>
                <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <a href="{{ route('Admin.categories.index') }}" class="btn btn-primary">← Quay lại</a>
        <a href="{{ route('Admin.categories.edit', $category->id) }}" class="btn btn-warning">Sửa danh mục</a>
    </div>
</main>



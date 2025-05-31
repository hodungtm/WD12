@extends('Admin.Layouts.AdminLayout')
@section('main')
<main class="app-content">
    <div class="app-title">
        <h5>Danh mục đã xóa</h5>
        <a href="{{ route('Admin.categories.index') }}" class="btn btn-sm btn-secondary">← Quay lại</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Ngày xóa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->ten_danh_muc }}</td>
                <td>{{ $category->mo_ta }}</td>
                <td>{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('Admin.categories.restore', $category->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success btn-sm" type="submit" title="Khôi phục"><i class="fas fa-undo"></i></button>
                    </form>
                    <form action="{{ route('Admin.categories.forceDelete', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xóa vĩnh viễn?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit" title="Xóa vĩnh viễn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $categories->links('pagination::bootstrap-4') }}
</main>


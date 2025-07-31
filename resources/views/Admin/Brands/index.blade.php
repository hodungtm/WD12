@extends('Admin.Layouts.AdminLayout')
@section('main')

<h1>Danh sách Thương Hiệu</h1>

<a href="{{ route('admin.brand.create') }}" class="btn btn-primary mb-4">Thêm Thương Hiệu</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Logo</th>
            <th>Tên Thương Hiệu</th>
            <th>Slug</th>
            <th>Mô Tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($brands as $brand)
        <tr>
            <td>{{ $brand->id }}</td>
            <td>
                @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo Thương Hiệu" width="50">
                @else
                Chưa có
                @endif
            </td>
            <td>{{ $brand->name }}</td>
            <td>{{ $brand->slug }}</td>
            <td>{{ $brand->description }}</td>
            <td>
                <a href="{{ route('admin.brand.edit',$brand->id) }}" class="btn btn-warning">Sửa</a>
                <form action="{{ route('admin.brand.destroy',$brand->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có thật sự muốn xóa?')">
                        Xóa
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $brands->links() }}


@endsection
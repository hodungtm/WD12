@extends('Admin.Layouts.AdminLayout')
@section('main')

<h1>Danh sách thuộc tính</h1>

<a href="{{ route('admin.attribute.create') }}" class="btn btn-primary mb-3">Thêm thuộc tính</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Slug</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attributes as $attribute)
        <tr>
            <td>{{ $attribute->id }}</td>
            <td>{{ $attribute->name }}</td>
            <td>{{ $attribute->slug }}</td>
            <td>
                <a href="{{ route('admin.attribute.edit', $attribute) }}">Sửa</a> |
                <a href="{{ route('admin.attribute.show', $attribute) }}">Xem</a> |
                <form action="{{ route('admin.attribute.destroy', $attribute) }}" method="POST" style="display:inline">
                    @csrf
                    @method("DELETE")
                    <button type="submit" onclick="return confirm('Xóa thật chứ?')">
                        Xóa
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if (session('success'))
<div>{{ session('success') }}</div>
@endif


@endsection
@extends('Admin.Layouts.AdminLayout')
@section('main')

<h1>Danh sách giá trị thuộc tính</h1>

<a href="{{ route('admin.attributeValue.create') }}" class="btn btn-primary mb-3">Thêm giá trị thuộc tính</a>

@if (session('success'))
<div class="alert alert-success mt-3">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Thuộc tính</th>
            <th>Valor</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attributeValues as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->attribute->name ?? '' }}</td>
            <td>{{ $item->value }}</td>
            <td>
                <a href="{{ route('admin.attributeValue.edit', $item->id) }}" class="btn btn-warning">Sửa</a>
                <form action="{{ route('admin.attributeValue.destroy', $item->id) }}" method="POST"
                    style="display: inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Bạn có thật sự muốn xóa giá trị thuộc tính này?')">
                        Xóa
                    </button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $attributeValues->links()}}
@endsection
@extends('Admin.Layouts.AdminLayout')
@section('main')

<h1>Danh sách sản phẩm</h1>

<a href="{{ route('admin.product.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên</th>
            <th>Thương hiệu</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>
                @if ($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" width="50">
                @endif
            </td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->brand->name ?? '' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price) }} VNĐ</td>
            <td>
                <a href="{{ route('admin.product.show', $item->id) }}">Xem</a> |
                <a href="{{ route('admin.product.edit', $item->id) }}">Sửa</a> |
                <form action="{{ route('admin.product.destroy', $item->id) }}" method="POST" style="display: inline"
                    onsubmit="return confirm('Bạn có thật sự muốn xóa sản phẩm này?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Xóa</button>
                </form>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links()}}

@endsection
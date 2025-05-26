@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
    <div class="app-title">


        <h2>Danh mục đã xóa mềm</h2>

        <a href="{{ route('Admin.categories.index') }}">← Quay lại danh sách</a>
        <br><br>

        @if(session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif

        @if($trashed->count() > 0)

                <form method="GET" action="{{ route('Admin.categories.trashed') }}"class="d-flex">
                    <input  type="text" name="keyword" placeholder="Tìm kiếm..." value="{{ $search ?? '' }}"class="form-control me-2">
                   <button class="btn btn-add btn-sm" type="submit">  Tìm</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã danh mục</th>
                        <th>Tên danh mục</th>
                        <th>Ảnh</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trashed as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td>{{ $cat->ma_danh_muc }}</td>
                            <td>{{ $cat->ten_danh_muc }}</td>
                            <td>
                                @if($cat->anh)
                                    <img src="{{ asset('storage/' . $cat->anh) }}" width="60">
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-add btn-sm" href="{{ route('Admin.categories.restore', $cat->id) }}">Khôi
                                    phục</a> |
                                <form action="{{ route('Admin.categories.forceDelete', $cat->id) }}" method="POST"
                                    style="display:inline" onsubmit="return confirm('Xóa vĩnh viễn?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary btn-sm trash">Xóa vĩnh viễn</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @else
        <p>Không có danh mục nào đã bị xóa.</p>
    @endif
    </div>
</main>
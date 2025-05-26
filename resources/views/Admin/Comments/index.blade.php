@extends('Admin.Layouts.AdminLayout')
@section('main')
    <h1>Quản lý Bình luận</h1>
@if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    <form method="GET" action="{{ route('Admin.comments.index') }}"class="mb-3 d-flex">
        <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm kiếm nội dung" value="{{ request('keyword') }}">
        <button type="submit"class="btn btn-primary"><i class="bi bi-search"></i>Tìm</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Tác giả</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->product ? $comment->product->name : '[Sản phẩm đã xóa]' }}</td>
                    <td>{{ $comment->tac_gia }}</td>
                    <td>{{ $comment->noi_dung }}</td>
                    <td>
                        @if(!$comment->trang_thai)
                            <form method="POST" action="{{ route('Admin.comments.approve', $comment->id) }}">
                                @csrf
                                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                                <input type="hidden" name="page" value="{{ request('page') }}">
                                <button type="submit" class="btn btn-success btn-sm">Duyệt</button>
                            </form>
                        @else
                            <span class="badge bg-success" >Đã duyệt</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('Admin.comments.edit', $comment->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a> |
                        <form method="POST" action="{{ route('Admin.comments.destroy', $comment->id) }}" style="display:inline"
                            onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $comments->links('pagination::bootstrap-4') }}

@endsection
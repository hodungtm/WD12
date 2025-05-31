@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
    <div class="app-title">
       
           <h5>Bình luận đã xóa</h5>
            <a href="{{ route('Admin.comments.index') }}" class="btn btn-sm btn-secondary">← Quay lại</a>
        
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif



    <table class="table table-hover table-bordered" id="trashTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Tác giả</th>
                <th>Nội dung</th>
                <th>Ngày xóa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
            <tr>
                <td>{{ $comment->id }}</td>
                <td>{{ $comment->product ? $comment->product->name : '[Sản phẩm đã xóa]' }}</td>
                <td>{{ $comment->tac_gia }}</td>
                <td>{{ Str::limit($comment->noi_dung, 50) }}</td>
                <td>{{ $comment->deleted_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('Admin.comments.restore', $comment->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        <button class="btn btn-success btn-sm" type="submit" title="Khôi phục"><i class="fas fa-undo"></i></button>
                    </form>

                    <form action="{{ route('Admin.comments.forceDelete', $comment->id) }}" method="POST"
                        style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn?')">
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

    {{ $comments->links('pagination::bootstrap-4') }}
</main>



@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
    <div class="app-title">
        <h5>Danh mục đã xóa</h5>
        <a href="{{ route('Admin.reviews.index') }}" class="btn btn-sm btn-secondary">← Quay lại</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif



    <table class="table table-hover table-bordered" id="trashTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Số sao</th>
                <th>Nội dung</th>
                <th>Ngày xóa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $review)
            <tr>
                <td>{{ $review->id }}</td>
                <td>{{ $review->user->name ?? 'N/A' }}</td>
                <td>{{ $review->product->name ?? 'N/A' }}</td>
                <td>{{ $review->so_sao }}</td>
                <td>{{ Str::limit($review->noi_dung, 50) }}</td>
                <td>{{ $review->deleted_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('Admin.reviews.restore', $review->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        <button class="btn btn-success btn-sm" type="submit" title="Khôi phục"><i class="fas fa-undo"></i></button>
                    </form>

                    <form action="{{ route('Admin.reviews.forceDelete', $review->id) }}" method="POST"
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

    {{ $reviews->links('pagination::bootstrap-4') }}
</main>


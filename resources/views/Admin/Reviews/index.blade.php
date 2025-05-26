@extends('Admin.Layouts.AdminLayout')
@section('main')

  

        <h1>Quản lý Đánh giá</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('Admin.reviews.index') }}" method="GET" class="mb-3 d-flex">
            <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm nội dung..."
                value="{{ $keyword }}">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Tìm</button>
        </form>

        <a href="{{ route('Admin.reviews.create') }}" class="btn btn-success mb-3">Thêm đánh giá mới</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người dùng</th>
                    <th>Sản phẩm</th>
                    <th>Số sao</th>
                    <th>Nội dung</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
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
                        <td>{!! $review->trang_thai ? '<span class="badge bg-success">Hiển thị</span>' : '<span class="badge bg-secondary">Ẩn</span>' !!}
                        </td>
                        <td>{{ $review->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('Admin.reviews.edit', $review->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('Admin.reviews.destroy', $review->id) }}" method="POST"
                                style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       {{ $reviews->links('pagination::bootstrap-4') }}

@endsection


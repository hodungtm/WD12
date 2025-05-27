@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Quản lý Bình luận</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="row element-button">
                        <div class="col-sm-2">
                            <!-- Nếu cần thêm chức năng như thêm mới hoặc chuyển đến comment đã xóa -->
                        </div>
                    </div>

                    <form method="GET" action="{{ route('Admin.comments.index') }}" class="d-flex mb-3"
                        style="max-width: 400px;">
                        <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm kiếm nội dung"
                            value="{{ request('keyword') }}" aria-label="Tìm kiếm nội dung">
                       <button class="btn btn-outline" type="submit" style="height: calc(2.7rem + 2px);">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>


                    <table class="table table-hover table-bordered" id="sampleTable">
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
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('Admin.comments.edit', $comment->id) }}"
                                            class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <form method="POST" action="{{ route('Admin.comments.destroy', $comment->id) }}"
                                            style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $comments->links('pagination::bootstrap-4') }}

                </div>
            </div>
        </div>
    </div>
</main>
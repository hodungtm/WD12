@extends('Admin.Layouts.AdminLayout')

@section('main')
<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Chi tiết bình luận</b></a></li>
        </ul>
    </div>

    <div class="tile">
        <div class="tile-body">
            <h4><b>ID:</b> {{ $comment->id }}</h4>
            <p><b>Sản phẩm:</b> {{ $comment->product->name ?? 'N/A' }}</p>
            <p><b>Tác giả:</b> {{ $comment->tac_gia }}</p>
            <p><b>Nội dung:</b> {{ $comment->noi_dung }}</p>
            <p><b>Trạng thái:</b>
                {!! $comment->trang_thai 
                    ? '<span class="badge bg-success">Hiển thị</span>' 
                    : '<span class="badge bg-secondary">Ẩn</span>' !!}
            </p>
            <p><b>Ngày tạo:</b> {{ $comment->created_at->format('d/m/Y H:i') }}</p>
            <a href="{{ route('Admin.comments.index') }}" class="btn btn-primary mt-3">Quay lại</a>
        </div>
    </div>
</main>


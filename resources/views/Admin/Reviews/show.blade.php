@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item"><a href="{{ route('Admin.reviews.index') }}">Danh sách Đánh giá</a></li>
            <li class="breadcrumb-item active"><b>Xem chi tiết Đánh giá</b></li>
        </ul>
    </div>

    <div class="tile">
        <h3>Chi tiết Đánh giá ID: {{ $review->id }}</h3>

        <table class="table table-bordered">
            <tr>
                <th>Người dùng</th>
                <td>{{ $review->nguoi_dung_id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Sản phẩm</th>
                <td>{{ $review->product->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Số sao</th>
                <td>{{ $review->so_sao }}</td>
            </tr>
            <tr>
                <th>Nội dung</th>
                <td>{{ $review->noi_dung }}</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td>
                    {!! $review->trang_thai
                        ? '<span class="badge bg-success">Hiển thị</span>'
                        : '<span class="badge bg-secondary">Ẩn</span>'
                    !!}
                </td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Ngày cập nhật</th>
                <td>{{ $review->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <a href="{{ route('Admin.reviews.index') }}" class="btn btn-primary mt-3">← Quay lại danh sách</a>
    </div>
</main>



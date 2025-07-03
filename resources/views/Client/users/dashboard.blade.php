@extends('Client.Layouts.ClientLayout')
@section('main')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="https://via.placeholder.com/100" class="rounded-circle mb-3" alt="avatar">
                    <h5>{{ $user->name }}</h5>
                    <hr>
                    <ul class="list-unstyled text-start px-3">
                        <li><a href="#" class="text-decoration-none">📦 Danh sách sản phẩm</a></li>
                        <li><a href="#" class="text-decoration-none">👤 Thông tin tài khoản</a></li>
                        <li><a href="#" class="text-decoration-none">📍 Thông tin địa chỉ</a></li>
                        <li><a href="#" class="text-decoration-none text-danger">🚪 Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between mb-3">
                <div class="text-center flex-fill">
                    <div class="bg-light rounded p-3">
                        <h4 class="mb-0">{{ count($user->purchased) }}</h4>
                        <small>Sản phẩm đã mua</small>
                    </div>
                </div>
                <div class="text-center flex-fill">
                    <div class="bg-light rounded p-3 mx-2">
                        <h4 class="mb-0">{{ count($user->favorites) }}</h4>
                        <small>Sản phẩm yêu thích</small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Sản phẩm đã mua
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Tổng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($user->purchased) == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-3">Chưa có sản phẩm nào</td>
                                </tr>
                            @else
                                @foreach ($user->purchased as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>{{ number_format($order->total, 0, ',', '.') }}₫</td>
                                        <td><a href="#" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

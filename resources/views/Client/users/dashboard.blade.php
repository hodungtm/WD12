@extends('Client.Layouts.ClientLayout')

@section('main')
<style>
    .profile-avatar {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }

    .sidebar-link:hover {
        text-decoration: underline;
    }

    .stat-box {
        background: #f0f2f5;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .stat-box h4 {
        margin-bottom: 5px;
    }
</style>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="https://via.placeholder.com/100" class="rounded-circle profile-avatar mb-3" alt="avatar">
                    <h5 class="mb-1">Nguyễn Văn A</h5>
                    <small class="text-muted">a.nguyen@email.com</small>
                    <hr>
                    <ul class="list-unstyled text-start px-3">
                        <li><a href="#" class="text-decoration-none sidebar-link">📦 Đơn hàng</a></li>
                        <li><a href="#" class="text-decoration-none sidebar-link">❤️ Yêu thích</a></li>
                        <li><a href="#" class="text-decoration-none sidebar-link">👤 Thông tin tài khoản</a></li>
                        <li><a href="#" class="text-decoration-none sidebar-link">📍 Địa chỉ giao hàng</a></li>
                        <li><a href="#" class="text-decoration-none text-danger sidebar-link">🚪 Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Thống kê -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="stat-box">
                        <h4>5</h4>
                        <small>Sản phẩm đã mua</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-box">
                        <h4>12</h4>
                        <small>Sản phẩm yêu thích</small>
                    </div>
                </div>
            </div>

            <!-- Thông tin tài khoản -->
            <div class="card mb-4">
                <div class="card-header">
                    👤 Thông tin tài khoản
                </div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> Nguyễn Văn A</p>
                    <p><strong>Email:</strong> a.nguyen@email.com</p>
                    <p><strong>Số điện thoại:</strong> 0987654321</p>
                    <p><strong>Ngày tạo tài khoản:</strong> 01/01/2023</p>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Chỉnh sửa</a>
                </div>
            </div>

            <!-- Địa chỉ giao hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    📍 Địa chỉ giao hàng
                </div>
                <div class="card-body">
                    <p><strong>Địa chỉ:</strong> 123 Nguyễn Trãi, Quận 1, TP. HCM</p>
                    <p><strong>Thành phố:</strong> Hồ Chí Minh</p>
                    <p><strong>Quốc gia:</strong> Việt Nam</p>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Chỉnh sửa</a>
                </div>
            </div>

            <!-- Đơn hàng đã mua -->
            <div class="card">
                <div class="card-header">
                    📦 Lịch sử đơn hàng
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#1001</td>
                                <td>2</td>
                                <td><span class="badge bg-success">Đã giao</span></td>
                                <td>12/06/2024</td>
                                <td>1.200.000₫</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">Xem</a></td>
                            </tr>
                            <tr>
                                <td>#1002</td>
                                <td>1</td>
                                <td><span class="badge bg-warning">Đang xử lý</span></td>
                                <td>24/06/2024</td>
                                <td>650.000₫</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">Xem</a></td>
                            </tr>
                            <tr>
                                <td>#1003</td>
                                <td>3</td>
                                <td><span class="badge bg-success">Đã giao</span></td>
                                <td>01/07/2024</td>
                                <td>2.500.000₫</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">Xem</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

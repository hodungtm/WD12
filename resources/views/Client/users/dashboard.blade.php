@extends('Client.Layouts.ClientLayout')

@section('main')
<style>
    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
    }

    .sidebar-link:hover {
        text-decoration: none;
        transform: translateX(3px);
        transition: all 0.2s ease;
    }

    .stat-box {
        background: #f0f2f5;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .stat-box h4 {
        margin-bottom: 5px;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .card-header {
        background: #45B8AC;
        color: white;
        font-weight: bold;
    }
</style>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card shadow border-0 text-center p-3">
                <div class="position-relative mx-auto" style="width: 120px; height: 120px;">
                    <img src="{{ $user->avatar ?? 'https://via.placeholder.com/120' }}"
                        class="rounded-circle border border-3 border-white shadow profile-avatar"
                        alt="Avatar">
                </div>
                <div class="mt-3">
                    <h5 class="fw-semibold mb-0">{{ $user->name }}</h5>
                    <small class="text-muted d-block">{{ $user->email }}</small>
                </div>
                <hr class="my-3">
                <div class="text-start px-3">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none d-flex align-items-center text-dark sidebar-link">
                                <i class="fas fa-box me-2 text-primary"></i> Đơn hàng
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none d-flex align-items-center text-dark sidebar-link">
                                <i class="fas fa-heart me-2 text-danger"></i> Yêu thích
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none d-flex align-items-center text-dark sidebar-link">
                                <i class="fas fa-user me-2 text-info"></i> Thông tin tài khoản
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none d-flex align-items-center text-dark sidebar-link">
                                <i class="fas fa-map-marker-alt me-2 text-warning"></i> Địa chỉ giao hàng
                            </a>
                        </li>
                        <li class="mt-3">
                            <a href="#" class="text-decoration-none d-flex align-items-center text-danger sidebar-link fw-bold">
                                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                            </a>
                        </li>
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
                        <h4>{{ $user->orders?->count() ?? 0 }}</h4>
                        <small>Sản phẩm đã mua</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-box">
                        <h4>{{ $user->wishlist?->count() ?? 0 }}</h4>
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
                    <p><strong>Họ tên:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Ngày tạo tài khoản:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Chỉnh sửa</a>
                </div>
            </div>

            <!-- Địa chỉ giao hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    📍 Địa chỉ giao hàng
                </div>
                <div class="card-body">
                    <p><strong>Địa chỉ:</strong> {{ $user->address->detail ?? 'Chưa có' }}</p>
                    <p><strong>Thành phố:</strong> {{ $user->address->city ?? '' }}</p>
                    <p><strong>Quốc gia:</strong> {{ $user->address->country ?? 'Việt Nam' }}</p>
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
                            @forelse($user->orders as $order)
                            <tr>
                                <td>#{{ $order->code }}</td>
                                <td>{{ $order->total_quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ number_format($order->total_price) }}₫</td>
                                <td>
                                    <a href="{{ route('client.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary">Xem</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Chưa có đơn hàng</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

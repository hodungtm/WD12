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
                        <li class="mb-2"><a href="#" class="text-decoration-none d-flex align-items-center text-dark sidebar-link"><i class="fas fa-box me-2 text-primary"></i> Đơn hàng</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none d-flex align-items-center text-dark sidebar-link"><i class="fas fa-heart me-2 text-danger"></i> Yêu thích</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none d-flex align-items-center text-dark sidebar-link"><i class="fas fa-user me-2 text-info"></i> Thông tin tài khoản</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none d-flex align-items-center text-dark sidebar-link"><i class="fas fa-map-marker-alt me-2 text-warning"></i> Địa chỉ giao hàng</a></li>
                        <li class="mt-3"><a href="#" class="text-decoration-none d-flex align-items-center text-danger sidebar-link fw-bold"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">

            {{-- Alert Message --}}
            @if(session('success_info'))
                <div class="alert alert-success">{{ session('success_info') }}</div>
            @endif
            @if(session('success_pass'))
                <div class="alert alert-success">{{ session('success_pass') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

            <!-- Tabs: Thông tin - Chỉnh sửa - Đổi mật khẩu -->
            <div class="card mb-4">
                <div class="card-header">
                    👤 Quản lý tài khoản
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="accountTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab">Thông tin</a></li>
                        <li class="nav-item"><a class="nav-link" id="edit-tab" data-bs-toggle="tab" href="#edit" role="tab">Chỉnh sửa</a></li>
                        <li class="nav-item"><a class="nav-link" id="pass-tab" data-bs-toggle="tab" href="#password" role="tab">Đổi mật khẩu</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#address">Địa chỉ giao hàng</a></li>                    </ul>
                    <div class="tab-content">
                        <!-- Tab 1: Xem -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <p><strong>Họ tên:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                            <p><strong>Ngày tạo tài khoản:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>

                        <!-- Tab 2: Chỉnh sửa -->
                        <div class="tab-pane fade" id="edit" role="tabpanel">
                            <form method="POST" action="{{ route('user.updateInfo') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Họ tên</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                </div>
                                <button class="btn btn-success">Lưu thay đổi</button>
                            </form>
                        </div>

                        <!-- Tab 3: Đổi mật khẩu -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form method="POST" action="{{ route('user.changePassword') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" required>
                                </div>
                                <button class="btn btn-warning">Đổi mật khẩu</button>
                            </form>
                        </div>

                        <!-- Tab 4: Địa chỉ -->
<div class="tab-pane fade" id="address" role="tabpanel">
    @if(session('success_address'))
        <div class="alert alert-success">
            {{ session('success_address') }}
        </div>
    @endif
    <form method="POST" action="{{ route('user.saveAddressSession') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Địa chỉ chi tiết</label>
            <input type="text" name="detail" class="form-control" value="{{ session('address.detail', '') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Thành phố</label>
            <input type="text" name="city" class="form-control" value="{{ session('address.city', '') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Quốc gia</label>
            <input type="text" name="country" class="form-control" value="{{ session('address.country', 'Việt Nam') }}">
        </div>
        <button class="btn btn-primary">Lưu địa chỉ</button>
    </form>

    <div class="mt-4">
        <h6><strong>Địa chỉ đang hiển thị:</strong></h6>
        <p><strong>Chi tiết:</strong> {{ session('address.detail', 'Chưa nhập') }}</p>
        <p><strong>Thành phố:</strong> {{ session('address.city', 'Chưa nhập') }}</p>
        <p><strong>Quốc gia:</strong> {{ session('address.country', 'Việt Nam') }}</p>
    </div>
</div>

                    </div>
                </div>
            </div>

            <!-- Địa chỉ -->
<div class="card mb-4">
    <div class="card-header">📍 Địa chỉ giao hàng</div>
    <div class="card-body">
        <p><strong>Địa chỉ:</strong> {{ session('address.detail', 'Chưa có') }}</p>
        <p><strong>Thành phố:</strong> {{ session('address.city', '') }}</p>
        <p><strong>Quốc gia:</strong> {{ session('address.country', 'Việt Nam') }}</p>
    </div>
</div>

            <!-- Lịch sử đơn hàng -->
            <div class="card">
                <div class="card-header">📦 Lịch sử đơn hàng</div>
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
                                <td><span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ number_format($order->total_price) }}₫</td>
                                <td><a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Xem</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Chưa có đơn hàng</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    // Khi chuyển tab thì lưu ID tab vào localStorage
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function(tabLink) {
        tabLink.addEventListener('shown.bs.tab', function (e) {
            localStorage.setItem('activeTab', e.target.getAttribute('href'));
        });
    });

    // Khi trang load lại thì set lại tab
    document.addEventListener("DOMContentLoaded", function () {
        const activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            const tabTrigger = document.querySelector(`a[href="${activeTab}"]`);
            if (tabTrigger) {
                new bootstrap.Tab(tabTrigger).show();
            }
        }
    });
</script>


@endsection

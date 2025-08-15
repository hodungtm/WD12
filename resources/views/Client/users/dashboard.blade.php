@extends('Client.Layouts.ClientLayout')

@section('main')
<style>
    /* Modern Dashboard Styles */
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #4db7b3 0%, #b683ea 100%);
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(130, 150, 239, 0.3);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50px, -50px);
    }

    .dashboard-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .dashboard-header .user-email {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }

    .sidebar-container {
        background: white;
        border-radius: 20px;
        padding: 2rem 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f2f5;
        height: fit-content;
        position: sticky;
        top: 2rem;
    }

    .sidebar-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        text-align: center;
        padding-bottom: 1rem;
        border-bottom: 3px solid #4db7b3;
    }

    .nav-tabs {
        border: none;
        flex-direction: column;
    }

    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: transparent;
    }

    .nav-tabs .nav-link:hover {
        background: linear-gradient(135deg, #4db7b3 0%, #b683ea 100%);
        color: white;
        transform: translateX(5px);
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #102171 0%, #7505e6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .nav-tabs .nav-link i {
        font-size: 1.1rem;
        width: 20px;
    }

    .tab-content {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f2f5;
        min-height: 600px;
    }

    .tab-pane h4 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #4db7b3;
    }

    /* Dashboard Features Grid */
    .dashboard-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 2rem 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #f0f2f5;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #4db7b3 0%, #b683ea 100%);
        transition: all 0.3s ease;
        z-index: 1;
    }

    .feature-card:hover::before {
        left: 0;
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .feature-card > * {
        position: relative;
        z-index: 2;
    }

    .feature-card i {
        font-size: 3rem;
        color: #4db7b3;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .feature-card:hover i {
        color: white;
        transform: scale(1.1);
    }

    .feature-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
        transition: color 0.3s ease;
    }

    .feature-card:hover h3 {
        color: white;
    }

    /* Table Styles */
    .modern-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f2f5;
    }

    .modern-table .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #4db7b3 0%, #b683ea 100%);
        color: white;
    }

    .modern-table th {
        padding: 1.2rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        font-size: 0.85rem;
    }

    .modern-table td {
        padding: 1.2rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }

    .modern-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Status badges - Updated colors */
    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bg-warning { background-color: #ffc107 !important; color: #000 !important; border: 1px solid #ffb300; }
    .bg-info { background-color: #17a2b8 !important; color: #fff !important; border: 1px solid #138496; }
    .bg-primary { background-color: #007bff !important; color: #fff !important; border: 1px solid #0056b3; }
    .bg-success { background-color: #28a745 !important; color: #fff !important; border: 1px solid #1e7e34; }
    .bg-danger { background-color: #dc3545 !important; color: #fff !important; border: 1px solid #bd2130; }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Form Styles */
    .modern-form .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .modern-form .form-control,
    .modern-form .form-select {
        border-radius: 12px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .modern-form .form-control:focus,
    .modern-form .form-select:focus {
        border-color: #4db7b3;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .modern-form .btn {
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .modern-form .btn-primary {
        background: linear-gradient(135deg, #4db7b3 0%, #b683ea 100%);
        border: none;
    }

    .modern-form .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    /* Product Cards for Wishlist */
    .product-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f2f5;
        transition: all 0.3s ease;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .product-card .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .product-card .product-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .product-card .price-box {
        font-size: 1.1rem;
        font-weight: 700;
        color: #e74c3c;
        margin-bottom: 1rem;
    }

    .product-card .product-action {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .product-card .btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .dashboard-header {
            padding: 2rem 1.5rem;
        }
        
        .dashboard-header h1 {
            font-size: 1.8rem;
        }
        
        .sidebar-container {
            margin-bottom: 2rem;
        }
        
        .nav-tabs {
            flex-direction: row;
            overflow-x: auto;
        }
        
        .nav-tabs .nav-link {
            white-space: nowrap;
            margin-right: 0.5rem;
            margin-bottom: 0;
        }
    }

    @media (max-width: 576px) {
        .dashboard-features {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .feature-card {
            padding: 1.5rem 1rem;
        }
        
        .feature-card i {
            font-size: 2.5rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Modal Enhancements */
    .modal-content {
        border-radius: 20px !important;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #4db7b3 0%, #b683ea 100%);
        color: white;
        border-radius: 20px 20px 0 0 !important;
        border: none;
    }

    .modal-title {
        font-weight: 700;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border: none;
        padding: 1rem 2rem 2rem;
    }
/* Nút chọn màu & size trong modal */
.color-btn.demo-style-btn,
.size-btn.demo-style-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 16px;
    border: 2px solid #e1e1e1; /* Viền nhạt hơn */
    border-radius: 5px; /* Bo tròn nút */
    background: #fff;
    color: #333;
    font-weight: 600;
    font-size: 15px;
    margin: 4px 8px 4px 0;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: none;
    outline: none;
    white-space: nowrap;
}

.color-btn.demo-style-btn.active,
.size-btn.demo-style-btn.active {
    border-color: #4DB7B3; /* Màu viền khi được chọn */
    background: #4DB7B3; /* Màu nền khi được chọn */
    color: #fff;
}

.color-btn.demo-style-btn:hover,
.size-btn.demo-style-btn:hover {
    border-color: #4DB7B3;
    color: #4DB7B3;
    background: #e6f8fa; /* Màu nền khi hover */
}

/* Input group cho số lượng */
.input-group {
    border-radius: 5px; /* Bo tròn input group */
    overflow: hidden;
    border: 1.5px solid #e1e1e1;
    background: #fff;
    height: 40px;
}

.input-group .btn {
    background: #fff;
    border: none;
    color: #222;
    font-size: 20px;
    width: 40px;
    height: 40px;
    padding: 0;
    font-weight: 700;
    border-radius: 5px; /* Bo tròn nút tăng giảm */
}

.input-group .btn:active,
.input-group .btn:focus {
    background: #f4f4f4;
    color: #4DB7B3; /* Màu khi nhấn */
}

.input-group .form-control {
    border: none;
    box-shadow: none;
    font-size: 16px;
    font-weight: 700;
    color: #222;
    background: #fff;
    height: 40px;
    width: 60px;
    padding: 0;
    border-radius: 0;
}

/* Nút thêm vào giỏ hàng trong modal */
#modalAddToCartBtn {
    border-radius: 5px; /* Bo tròn nút */
    font-size: 15px;
    font-weight: 700;
    padding: 0 18px;
    min-width: 140px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: 0.5px;
    background: black; /* Màu chủ đạo */
    color: #fff;
    border: 2px solid #4DB7B3;
    transition: all 0.2s;
}

#modalAddToCartBtn:hover:not(:disabled) {
    background: #2D8E89 !important; /* Màu đậm hơn khi hover */
    border-color: #2D8E89 !important;
    color: #fff !important;
}

#modalAddToCartBtn:disabled {
    background: #888;
    border-color: #888;
    color: #fff;
    cursor: not-allowed;
}
    /* Welcome Message */
    .welcome-message {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(240, 147, 251, 0.3);
    }

    .welcome-message h5 {
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* Info Cards */
    .info-display {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #4db7b3;
    }

    .info-display p {
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .info-display strong {
        color: #2c3e50;
    }
</style>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1><i class="fas fa-tachometer-alt me-3"></i>Xin chào, {{ $user->name }}!</h1>
        <p class="user-email">{{ $user->email }}</p>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="sidebar-container">
                <h4 class="sidebar-title">TÀI KHOẢN CỦA TÔI</h4>
                <ul class="nav flex-column nav-tabs" id="dashboardTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab">
                            <i class="fas fa-home"></i>Bảng điều khiển
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">
                            <i class="fas fa-shopping-bag"></i>Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="addresses-tab" data-bs-toggle="tab" href="#addresses" role="tab">
                            <i class="fas fa-map-marker-alt"></i>Địa chỉ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="account-tab" data-bs-toggle="tab" href="#account" role="tab">
                            <i class="fas fa-user-cog"></i>Thông tin tài khoản
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="wishlist-tab" data-bs-toggle="tab" href="#wishlist" role="tab">
                            <i class="fas fa-heart"></i>Yêu thích
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt"></i>Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- Dashboard Tab -->
                <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                    <div class="welcome-message">
                        <h5><i class="fas fa-star me-2"></i>Chào mừng trở lại!</h5>
                        <p>Từ trang tài khoản, bạn có thể xem đơn hàng gần đây, quản lý địa chỉ giao hàng và chỉnh sửa thông tin tài khoản, mật khẩu.</p>
                    </div>
                    
                    <div class="dashboard-features">
                        <a href="#orders" class="feature-card link-to-tab text-decoration-none">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>ĐƠN HÀNG</h3>
                        </a>
                        <a href="#addresses" class="feature-card link-to-tab text-decoration-none">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3>ĐỊA CHỈ</h3>
                        </a>
                        <a href="#account" class="feature-card link-to-tab text-decoration-none">
                            <i class="fas fa-user-cog"></i>
                            <h3>THÔNG TIN</h3>
                        </a>
                        <a href="#wishlist" class="feature-card link-to-tab text-decoration-none">
                            <i class="fas fa-heart"></i>
                            <h3>YÊU THÍCH</h3>
                        </a>
                        <a href="{{ route('logout') }}" class="feature-card text-decoration-none">
                            <i class="fas fa-sign-out-alt"></i>
                            <h3>ĐĂNG XUẤT</h3>
                        </a>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div class="tab-pane fade" id="orders" role="tabpanel">
                    <h4><i class="fas fa-shopping-bag me-2"></i>Đơn hàng của bạn</h4>
                    <div class="modern-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-barcode me-2"></i>Mã đơn</th>
                                    <th><i class="fas fa-box me-2"></i>Sản phẩm</th>
                                    <th><i class="fas fa-sort-numeric-up me-2"></i>Số lượng</th>
                                    <th><i class="fas fa-money-bill me-2"></i>Giá</th>
                                    <th><i class="fas fa-calendar me-2"></i>Ngày đặt</th>
                                    <th><i class="fas fa-info-circle me-2"></i>Trạng thái</th>
                                    <th><i class="fas fa-cogs me-2"></i>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orderItems as $item)
                                    <tr>
                                        <td><span class="font-monospace text-primary fw-bold">{{ $item->order->order_code ?? '' }}</span></td>
                                        <td>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                                        <td><span class="badge bg-light text-dark">{{ $item->quantity }}</span></td>
                                        <td class="fw-bold text-success">{{ number_format($item->price) }}₫</td>
                                        <td>{{ $item->order->created_at->format('d/m/Y') ?? '' }}</td>
                                        <td>
                                            @php
                                                $status = $item->order->status ?? '';
                                                $badgeClass = '';
                                                switch($status) {
                                                    case 'Đang chờ': $badgeClass = 'bg-warning text-dark'; break;
                                                    case 'Xác nhận đơn': $badgeClass = 'bg-info text-white'; break;
                                                    case 'Đang giao hàng': $badgeClass = 'bg-primary text-white'; break;
                                                    case 'Hoàn thành': $badgeClass = 'bg-success text-white'; break;
                                                    case 'Đã hủy': $badgeClass = 'bg-danger text-white'; break;
                                                    default: $badgeClass = 'bg-secondary text-white';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                @if ($item->order->status === 'Đang chờ')
                                                    <button type="button" class="btn btn-outline-danger btn-sm btn-cancel-order"
                                                        data-bs-toggle="modal" data-bs-target="#cancelModal"
                                                        data-order-id="{{ $item->order->id }}">
                                                        <i class="fas fa-times"></i> Hủy
                                                    </button>
                                                @endif
                                                
                                                @if($item->order->status === 'Hoàn thành' && $item->order->orderItems->first())
                                                    <button type="button" class="btn btn-outline-warning btn-sm btn-open-review-modal"
                                                        data-product-id="{{ $item->order->orderItems->first()->product_id }}"
                                                        data-order-item-id="{{ $item->order->orderItems->first()->id }}">
                                                        <i class="fas fa-star"></i> Đánh giá
                                                    </button>
                                                @endif
                                                
                                                <a href="{{ route('client.orders.show', $item->order_id) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                                            <br>Chưa có đơn hàng nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Addresses Tab -->
                <div class="tab-pane fade" id="addresses" role="tabpanel">
                    <h4><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ giao hàng</h4>
                    <div class="modern-form">
                        <form method="POST" action="{{ route('user.saveAddress') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ giao hàng</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', $user->address) }}" 
                                    placeholder="Nhập địa chỉ giao hàng của bạn">
                            </div>
                            <button class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu địa chỉ
                            </button>
                        </form>
                        
                        <div class="info-display mt-4">
                            <h6><strong><i class="fas fa-info-circle me-2"></i>Địa chỉ hiện tại:</strong></h6>
                            <p>{{ $user->address ?? 'Chưa có địa chỉ' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Tab -->
                <div class="tab-pane fade" id="account" role="tabpanel">
                    <h4><i class="fas fa-user-cog me-2"></i>Thông tin tài khoản</h4>
                    <ul class="nav nav-tabs mb-4" id="accountDetailTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab">
                                <i class="fas fa-user me-2"></i>Thông tin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="edit-tab" data-bs-toggle="tab" href="#edit" role="tab">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pass-tab" data-bs-toggle="tab" href="#password" role="tab">
                                <i class="fas fa-key me-2"></i>Đổi mật khẩu
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <!-- Info Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="info-display">
                                <p><strong><i class="fas fa-user me-2"></i>Họ tên:</strong> {{ $user->name }}</p>
                                <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> {{ $user->email }}</p>
                                <p><strong><i class="fas fa-phone me-2"></i>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                                <p><strong><i class="fas fa-calendar-plus me-2"></i>Ngày tạo tài khoản:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        
                        <!-- Edit Tab -->
                        <div class="tab-pane fade" id="edit" role="tabpanel">
                            <div class="modern-form">
                                <form method="POST" action="{{ route('user.updateInfo') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Họ tên</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Giới tính</label>
                                        <select name="gender" class="form-select">
                                            <option value="" {{ $user->gender == null ? 'selected' : '' }}>Chưa chọn</option>
                                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                            <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh đại diện</label>
                                        <input type="file" name="avatar" class="form-control" accept="image/*">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="Avatar" class="mt-3 rounded-circle"
                                                style="width:80px;height:80px;object-fit:cover;border:3px solid #4db7b3;">
                                        @endif
                                    </div>
                                    <button class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Password Tab -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <div class="modern-form">
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
                                    <button class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>Đổi mật khẩu
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wishlist Tab -->
                <div class="tab-pane fade" id="wishlist" role="tabpanel">
                    <h4><i class="fas fa-heart me-2"></i>Danh sách yêu thích</h4>
                    <div class="row">
                        @if($wishlists->count() > 0)
                            @foreach($wishlists as $wishlist)
                                @php
                                    $product = $wishlist->product;
                                    $image = $product->images->first()->image ?? 'product_images/default.jpg';
                                    $name = $product->name ?? 'Sản phẩm';
                                    $variants = $product->variants;
                                    $minPrice = $variants->min('price');
                                    $minSale = $variants->min('sale_price');
                                    $discountPercent = $minSale > 0 ? round((($minPrice - $minSale) / $minPrice) * 100) : 0;
                                @endphp
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="product-card">
                                        <div class="position-relative">
                                            <a href="{{ route('client.product.detail', $product->id) }}">
                                                <img src="{{ asset('storage/' . $image) }}" 
                                                     alt="{{ $name }}" 
                                                     class="product-image"
                                                     onerror="this.src='{{ asset('assets/images/no-image.png') }}'">
                                            </a>
                                            @if ($discountPercent > 0)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-danger">-{{ $discountPercent }}%</span>
                                                </div>
                                            @endif
                                        </div>

                                        <h5 class="product-title">
                                            <a href="{{ route('client.product.detail', $product->id) }}" 
                                               class="text-decoration-none text-dark">{{ $name }}</a>
                                        </h5>

                                        <div class="price-box">
                                            @if ($minSale > 0)
                                                <del class="text-muted me-2">{{ number_format($minPrice, 0, ',', '.') }}₫</del>
                                                <span class="text-danger fw-bold">{{ number_format($minSale, 0, ',', '.') }}₫</span>
                                            @else
                                                <span class="fw-bold">{{ number_format($minPrice, 0, ',', '.') }}₫</span>
                                            @endif
                                        </div>

                                        <div class="product-action">
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="event.preventDefault(); submitDeleteForm('remove-wishlist-{{ $wishlist->id }}')"
                                                    title="Xóa khỏi yêu thích">
                                                <i class="fas fa-heart-broken"></i> Xóa
                                            </button>
                                            
                                            <form id="remove-wishlist-{{ $wishlist->id }}"
                                                  action="{{ route('client.wishlist.remove', $wishlist->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <button type="button" class="btn btn-primary btn-sm btn-add-cart" 
                                                    data-bs-toggle="modal" data-bs-target="#variantModal" 
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-variants='@json($product->variants)'>
                                                <i class="fas fa-shopping-cart"></i> Thêm
                                            </button>

                                            <a href="{{ route('client.product.detail', $product->id) }}"
                                               class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-heart-broken fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có sản phẩm yêu thích nào</h5>
                                <p class="text-muted">Hãy thêm những sản phẩm bạn yêu thích để dễ dàng tìm lại sau này!</p>
                                <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i>Khám phá sản phẩm
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Variant Modal -->
<div class="modal fade" id="variantModal" tabindex="-1" role="dialog" aria-labelledby="variantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModalLabel">
                    <i class="fas fa-shopping-cart me-2"></i>Chọn biến thể sản phẩm
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img id="modalProductImage" src="" alt="Product" class="img-fluid rounded"
                            style="max-height: 200px; object-fit: cover; border: 2px solid #f0f2f5;">
                    </div>
                    <div class="col-md-8">
                        <h6 id="modalProductName" class="mb-3 fw-bold"></h6>

                        <form id="variantForm">
                            @csrf
                            <input type="hidden" id="modalProductId" name="product_id">
                            <input type="hidden" id="modalVariantId" name="variant_id">

                            <div class="form-group mb-3">
                                <label class="form-label mb-2 fw-bold">Màu sắc:</label>
                                <div class="d-flex flex-wrap gap-2" id="colorOptions"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label mb-2 fw-bold">Kích thước:</label>
                                <div class="d-flex flex-wrap gap-2" id="sizeOptions"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label mb-2 fw-bold">Tồn kho:</label>
                                <span id="modalInventoryInfo" class="text-muted"></span>
                            </div>

                            <div class="form-group mb-3">
                                <div id="modalDynamicPrice" class="fw-bold fs-5 text-primary"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label mb-2 fw-bold">Số lượng:</label>
                                <div class="d-flex align-items-center" style="gap: 16px;">
                                    <div class="input-group" style="width: 140px;">
                                        <button type="button" class="btn btn-outline-secondary" id="modalQtyMinus">-</button>
                                        <input id="modalQuantityInput" type="number" name="quantity" value="1" min="1"
                                            class="form-control text-center" style="max-width: 60px;" readonly>
                                        <button type="button" class="btn btn-outline-secondary" id="modalQtyPlus">+</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Đóng
                </button>
                <button type="button" class="btn btn-primary" id="modalAddToCartBtn" disabled>
                    <i class="fas fa-shopping-cart me-2"></i>THÊM VÀO GIỎ HÀNG
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="reviewForm" method="POST" action="{{ route('client.product.review', 0) }}" class="comment-form m-0">
                @csrf
                <input type="hidden" name="product_id" id="reviewProductId">
                <input type="hidden" name="order_item_id" id="reviewOrderItemId">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">
                        <i class="fas fa-star me-2"></i>Đánh giá sản phẩm
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="rating-form mb-3">
                        <label for="modal-rating">Đánh giá của bạn <span class="required text-danger">*</span></label>
                        <span class="rating-stars d-block mt-2" id="modal-rating-stars">
                            <a class="star-1" href="#" style="color: #ccc; font-size: 1.5rem; text-decoration: none; margin-right: 5px;">★</a>
                            <a class="star-2" href="#" style="color: #ccc; font-size: 1.5rem; text-decoration: none; margin-right: 5px;">★</a>
                            <a class="star-3" href="#" style="color: #ccc; font-size: 1.5rem; text-decoration: none; margin-right: 5px;">★</a>
                            <a class="star-4" href="#" style="color: #ccc; font-size: 1.5rem; text-decoration: none; margin-right: 5px;">★</a>
                            <a class="star-5" href="#" style="color: #ccc; font-size: 1.5rem; text-decoration: none; margin-right: 5px;">★</a>
                        </span>
                        <input type="hidden" name="so_sao" id="modal-rating" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="modal-noi-dung">Nội dung đánh giá <span class="required text-danger">*</span></label>
                        <textarea name="noi_dung" id="modal-noi-dung" cols="5" rows="6"
                            class="form-control" required
                            placeholder="Viết đánh giá của bạn về sản phẩm này..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Hủy
                    </button>
                    <input type="submit" class="btn btn-primary" value="Gửi đánh giá">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="cancelForm" method="POST" action="{{ route('client.orders.cancel') }}">
            @csrf
            <input type="hidden" name="order_id" id="cancelOrderId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">
                        <i class="fas fa-ban me-2"></i>Lý do hủy đơn hàng
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <textarea name="cancel_reason" id="cancel_reason" rows="4" class="form-control" maxlength="255" required
                        placeholder="Vui lòng nhập lý do hủy đơn (tối đa 255 ký tự)"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check me-2"></i>Xác nhận hủy
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Tab navigation functionality
document.querySelectorAll('.link-to-tab').forEach(function (link) {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        var target = this.getAttribute('href');
        var tabTrigger = document.querySelector(`a[href="${target}"]`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    });
});

// Save and restore active tab
document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function (tabLink) {
    tabLink.addEventListener('shown.bs.tab', function (e) {
        localStorage.setItem('activeTab', e.target.getAttribute('href'));
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        const tabTrigger = document.querySelector(`a[href="${activeTab}"]`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    }
});

// Form submission helpers
function submitForm(formId) {
    const form = document.getElementById(formId);
    if (form) form.submit();
}

function submitDeleteForm(formId) {
    if (confirm("Bạn có chắc chắn muốn bỏ sản phẩm ra khỏi danh sách yêu thích không?")) {
        submitForm(formId);
    }
}

// Modal variables
let variantModal, selectedModalColor, selectedModalSize, currentVariants;

// Initialize modals when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    variantModal = new bootstrap.Modal(document.getElementById('variantModal'));
    
    // Variant modal event listeners
    document.getElementById('modalQtyMinus').onclick = function () {
        const qtyInput = document.getElementById('modalQuantityInput');
        let v = parseInt(qtyInput.value) || 1;
        if (v > 1) qtyInput.value = v - 1;
    };

    document.getElementById('modalQtyPlus').onclick = function () {
        const qtyInput = document.getElementById('modalQuantityInput');
        let v = parseInt(qtyInput.value) || 1;
        if (!qtyInput.max || v < parseInt(qtyInput.max)) qtyInput.value = v + 1;
    };

    document.getElementById('modalAddToCartBtn').onclick = function () {
        if (!selectedModalColor || !selectedModalSize) {
            showAlert('Vui lòng chọn màu sắc và kích thước trước khi thêm vào giỏ hàng!', 'error');
            return;
        }

        const formData = new FormData(document.getElementById('variantForm'));
        const productId = document.getElementById('modalProductId').value;
        
        fetch(`/client/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                variantModal.hide();
                showAlert(data.message || 'Đã thêm vào giỏ hàng!', 'success');
                if (typeof updateCartCount === 'function') updateCartCount();
            } else {
                showAlert(data.message || 'Có lỗi khi thêm vào giỏ hàng!', 'error');
            }
        })
        .catch(err => {
            console.error('Lỗi khi thêm vào giỏ hàng:', err);
            showAlert('Lỗi hệ thống!', 'error');
        });
    };

    // Review modal event listeners
    document.querySelectorAll('.btn-open-review-modal').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var productId = btn.getAttribute('data-product-id');
            var orderItemId = btn.getAttribute('data-order-item-id');
            var form = document.getElementById('reviewForm');
            form.action = "{{ route('client.product.review', 'PRODUCT_ID') }}".replace('PRODUCT_ID', productId);
            document.getElementById('reviewProductId').value = productId;
            document.getElementById('reviewOrderItemId').value = orderItemId;
            form.reset();
            var modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            modal.show();
        });
    });

    // Star rating functionality
    const stars = document.querySelectorAll('#modal-rating-stars a');
    const ratingInput = document.getElementById('modal-rating');
    stars.forEach((star, idx) => {
        star.addEventListener('click', function (e) {
            e.preventDefault();
            stars.forEach(s => s.style.color = '#ccc');
            for (let i = 0; i <= idx; i++) {
                stars[i].style.color = '#FFD700';
            }
            ratingInput.value = idx + 1;
        });
    });

    // Cancel order modal
    document.querySelectorAll('.btn-cancel-order').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-order-id');
            document.getElementById('cancelOrderId').value = orderId;
            document.getElementById('cancel_reason').value = '';
        });
    });
});

// Variant modal functions
document.getElementById('variantModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const productId = button.getAttribute('data-product-id');
    const productName = button.getAttribute('data-product-name');
    const variantsString = button.getAttribute('data-variants');

    try {
        const variants = JSON.parse(variantsString);
        populateVariantModal(productId, productName, variants);
    } catch (error) {
        console.error("Lỗi khi phân tích cú pháp JSON variants:", error);
    }
});

function populateVariantModal(productId, productName, variants) {
    currentVariants = variants || [];
    selectedModalColor = null;
    selectedModalSize = null;
    
    document.getElementById('modalProductId').value = productId;
    document.getElementById('modalProductName').textContent = productName;
    
    // Load product image
    fetch(`/api/product/${productId}/image`)
        .then(res => res.json())
        .then(data => {
            if (data.image) {
                document.getElementById('modalProductImage').src = data.image;
            }
        })
        .catch(err => {
            document.getElementById('modalProductImage').src = '/assets/images/no-image.png';
        });

    // Populate colors
    const colorOptions = document.getElementById('colorOptions');
    colorOptions.innerHTML = '';
    const uniqueColorIds = [...new Set(variants.map(v => v.color_id))].filter(id => id);
    uniqueColorIds.forEach(colorId => {
        const variant = variants.find(v => v.color_id === colorId);
        const color = variant?.color || { id: colorId, name: `Màu ${colorId}` };
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-primary btn-sm';
        btn.dataset.color = color.id;
        btn.textContent = color.name || `Màu ${colorId}`;
        btn.onclick = () => selectModalColor(color.id);
        colorOptions.appendChild(btn);
    });

    // Populate sizes
    const sizeOptions = document.getElementById('sizeOptions');
    sizeOptions.innerHTML = '';
    const uniqueSizeIds = [...new Set(variants.map(v => v.size_id))].filter(id => id);
    uniqueSizeIds.forEach(sizeId => {
        const variant = variants.find(v => v.size_id === sizeId);
        const size = variant?.size || { id: sizeId, name: `Size ${sizeId}` };
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-secondary btn-sm';
        btn.dataset.size = size.id;
        btn.textContent = size.name || `Size ${sizeId}`;
        btn.onclick = () => selectModalSize(size.id);
        sizeOptions.appendChild(btn);
    });

    // Reset form
    document.getElementById('modalQuantityInput').value = 1;
    document.getElementById('modalInventoryInfo').textContent = '';
    document.getElementById('modalDynamicPrice').innerHTML = '';
    document.getElementById('modalAddToCartBtn').disabled = true;
}

function selectModalColor(colorId) {
    selectedModalColor = colorId;
    document.querySelectorAll('#colorOptions button').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`#colorOptions button[data-color="${colorId}"]`).classList.add('active');
    updateModalVariant();
}

function selectModalSize(sizeId) {
    selectedModalSize = sizeId;
    document.querySelectorAll('#sizeOptions button').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`#sizeOptions button[data-size="${sizeId}"]`).classList.add('active');
    updateModalVariant();
}

function updateModalVariant() {
    const variant = currentVariants.find(v =>
        v.color_id == selectedModalColor && v.size_id == selectedModalSize
    );
    
    const qtyInput = document.getElementById('modalQuantityInput');
    const inventoryInfo = document.getElementById('modalInventoryInfo');
    const dynamicPrice = document.getElementById('modalDynamicPrice');
    const addToCartBtn = document.getElementById('modalAddToCartBtn');

    if (variant) {
        document.getElementById('modalVariantId').value = variant.id;
        qtyInput.max = variant.quantity;
        qtyInput.value = Math.min(parseInt(qtyInput.value) || 1, variant.quantity);
        qtyInput.removeAttribute('readonly');
        inventoryInfo.textContent = `${variant.quantity} sản phẩm`;
        addToCartBtn.disabled = variant.quantity <= 0;

        if (variant.sale_price && variant.sale_price < variant.price) {
            dynamicPrice.innerHTML = `<del class="text-muted me-2">${parseInt(variant.price).toLocaleString('vi-VN')}₫</del><span class="fw-bold text-danger">${parseInt(variant.sale_price).toLocaleString('vi-VN')}₫</span>`;
        } else {
            dynamicPrice.innerHTML = `<span class="fw-bold">${parseInt(variant.price).toLocaleString('vi-VN')}₫</span>`;
        }
    } else {
        document.getElementById('modalVariantId').value = '';
        qtyInput.value = 1;
        qtyInput.setAttribute('readonly', true);
        inventoryInfo.textContent = '';
        dynamicPrice.innerHTML = '';
        addToCartBtn.disabled = true;
    }
}

function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);
    setTimeout(() => { alertDiv.remove(); }, 3500);
}
</script>
@endsection
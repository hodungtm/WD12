@extends('Client.Layouts.ClientLayout')

@section('main')
<style>
    .porto-dashboard-sidebar {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 6px;
        padding: 30px 20px;
        min-height: 100%;
    }
    .porto-dashboard-sidebar .nav-link {
        color: #222;
        font-weight: 500;
        border: none;
        border-radius: 0;
        padding: 12px 0;
        transition: color 0.2s;
    }
    .porto-dashboard-sidebar .nav-link.active,
    .porto-dashboard-sidebar .nav-link:hover {
        color: #007bff;
        background: none;
        font-weight: bold;
    }
    .porto-dashboard-feature {
        border: 1px solid #eee;
        border-radius: 6px;
        background: #fff;
        text-align: center;
        padding: 32px 0 20px 0;
        margin-bottom: 24px;
        transition: box-shadow 0.2s;
        min-height: 160px;
    }
    .porto-dashboard-feature:hover {
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    }
    .porto-dashboard-feature i {
        font-size: 2.5rem;
        color: #bbb;
        margin-bottom: 10px;
    }
    .porto-dashboard-feature h3 {
        font-size: 1.1rem;
        font-weight: bold;
        margin: 0;
        color: #222;
    }
    .porto-dashboard-welcome {
        font-size: 1.1rem;
        margin-bottom: 18px;
    }
    .porto-dashboard-content {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 6px;
        padding: 30px 24px;
        margin-bottom: 24px;
    }
    /* Wishlist styles lấy từ wishlist.blade.php */
    .container {
            max-width: 1360px;
        }

        /* Giao diện tổng thể bảng */
        .table-wishlist {
            background-color: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .table-wishlist thead {
            background-color: #f8f9fa;
            color: #333;
        }

        .table-wishlist th {
            padding: 15px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid #dee2e6;
        }

        /* Dòng sản phẩm */
        .table-wishlist .product-row {
            transition: background-color 0.3s ease;
        }

        .table-wishlist .product-row:hover {
            background-color: #fdfdfd;
        }

        /* Cột ảnh */
        .product-image-container {
            width: 90px;
            height: 90px;
            overflow: hidden;
            border-radius: 6px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Tên sản phẩm */
        .product-title a {
            color: #333;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
        }

        .product-title a:hover {
            color: #38bcb2;
        }

        /* Size, màu */
        .product-row p {
            margin-bottom: 2px;
            font-size: 13px;
            color: #666;
        }

        /* Giá */
        .price-box {
            font-size: 15px;
            font-weight: 500;
            color: #222;
        }

        .price-box del {
            color: #aaa;
            font-size: 13px;
        }

        /* Tồn kho */
        .stock-status {
            font-size: 14px;
            font-weight: 500;
        }

        /* Button hành động */
        td.action {
            gap: 8px;
            justify-content: flex-start;
            height: auto !important;
        }

        td.action form {
            margin: 0;
        }

        td.action .btn {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .btn.btn-add-cart {
            background-color: #38bcb2;
            border: none;
            color: #fff;
        }

        .btn.btn-add-cart:hover {
            background-color: #2fa7a0;
        }

        .btn.btn-quickview {
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            color: #333;
        }

        .btn.btn-quickview:hover {
            background-color: #e2e6ea;
        }
        .product-default .btn-add-cart i {
            display: inline-block !important;
        }
        .product-default .btn-add-cart i {
    display: inline-block !important;
}
.product-action {
    display: flex;
    justify-content: center;
    gap: 8px; /* khoảng cách giữa các nút, có thể điều chỉnh */
}

.product-action a.btn-icon-wish,
.product-action a.btn-quickview {
    display: flex;
    align-items: center;
    justify-content: center;
}
    .product-default {
        border-radius: 6px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
        overflow: hidden;
        background: #fff;
        padding: 12px 12px 18px 12px;
        margin-bottom: 24px;
    }
    .product-default figure {
        width: 180px;
        height: 180px;
        margin: 0 auto 12px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fafafa;
        border-radius: 6px;
        position: relative;
    }
    .product-default img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .product-details {
        width: 100%;
        padding: 0;
    }
    .product-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 6px;
        text-align: center;
    }
    .price-box {
        font-size: 15px;
        font-weight: 500;
        color: #222;
        text-align: center;
        margin-bottom: 8px;
    }
    .product-action {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 8px;
    }
    .modal-content {
    border-radius: 8px; /* Bo tròn góc modal */
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 8px 8px 0 0; /* Bo tròn góc trên của header */
}

.modal-title {
    font-weight: 700;
    color: #222;
}

.modal-footer {
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 8px 8px; /* Bo tròn góc dưới của footer */
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


</style>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-lg-0 mb-4">
            <div class="porto-dashboard-sidebar">
                <h4 class="text-uppercase mb-3" style="font-size:1.1rem;letter-spacing:1px;">MY ACCOUNT</h4>
                <ul class="nav flex-column nav-tabs" id="dashboardTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab">Bảng điều khiển</a></li>
                    <li class="nav-item"><a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">Đơn hàng</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" id="downloads-tab" data-bs-toggle="tab" href="#downloads" role="tab">Tải về</a></li> --}}
                    <li class="nav-item"><a class="nav-link" id="addresses-tab" data-bs-toggle="tab" href="#addresses" role="tab">Địa chỉ</a></li>
                    <li class="nav-item"><a class="nav-link" id="account-tab" data-bs-toggle="tab" href="#account" role="tab">Thông tin tài khoản</a></li>
                    <li class="nav-item"><a class="nav-link" id="shipping-tab" data-bs-toggle="tab" href="#shipping" role="tab">Địa chỉ giao hàng</a></li>
                    <li class="nav-item"><a class="nav-link" id="wishlist-tab" data-bs-toggle="tab" href="#wishlist" role="tab">Yêu thích</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
        <!-- Main Content -->
        <div class="col-lg-9 tab-content">
            <!-- Dashboard tab -->
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                <div class="porto-dashboard-content">
                    <div class="porto-dashboard-welcome">
                        Xin chào <strong class="text-dark">{{ $user->name }}</strong> ({{ $user->email }})
                    </div>
                    <div class="mb-3">
                        Từ trang tài khoản, bạn có thể xem <a href="#orders" class="link-to-tab text-primary">đơn hàng gần đây</a>, quản lý <a href="#addresses" class="link-to-tab text-primary">địa chỉ giao hàng và thanh toán</a>, và <a href="#account" class="link-to-tab text-primary">chỉnh sửa thông tin tài khoản, mật khẩu</a>.
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-4">
                            <a href="#orders" class="porto-dashboard-feature link-to-tab d-block">
                                <i class="fas fa-box"></i>
                                <h3>ĐƠN HÀNG</h3>
                            </a>
                        </div>
                        {{-- <div class="col-6 col-md-4">
                            <a href="#downloads" class="porto-dashboard-feature link-to-tab d-block">
                                <i class="fas fa-cloud-download-alt"></i>
                                <h3>TẢI VỀ</h3>
                            </a>
                        </div> --}}
                        <div class="col-6 col-md-4">
                            <a href="#addresses" class="porto-dashboard-feature link-to-tab d-block">
                                <i class="fas fa-map-marker-alt"></i>
                                <h3>ĐỊA CHỈ</h3>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="#account" class="porto-dashboard-feature link-to-tab d-block">
                                <i class="fas fa-user"></i>
                                <h3>THÔNG TIN</h3>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="#wishlist" class="porto-dashboard-feature link-to-tab d-block">
                                <i class="fas fa-heart"></i>
                                <h3>YÊU THÍCH</h3>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="{{ route('logout') }}" class="porto-dashboard-feature d-block">
                                <i class="fas fa-sign-out-alt"></i>
                                <h3>ĐĂNG XUẤT</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Orders tab -->
            <div class="tab-pane fade" id="orders" role="tabpanel">
                <div class="porto-dashboard-content">
                    <h4 class="mb-3"><i class="fas fa-box me-2"></i>Đơn hàng của bạn</h4>
                    <table class="table table-order text-left">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orderItems as $item)
                            <tr>
                                <td>{{ $item->order->order_code ?? '' }}</td>
                                <td>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price) }}₫</td>
                                <td>{{ $item->order->created_at->format('d/m/Y') ?? '' }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->order->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($item->order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('client.orders.show', $item->order_id) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted">Chưa có sản phẩm nào đã mua</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Downloads tab -->
            {{-- <div class="tab-pane fade" id="downloads" role="tabpanel">
                <div class="porto-dashboard-content text-center">
                    <h4 class="mb-3"><i class="fas fa-cloud-download-alt me-2"></i>Tải về</h4>
                    <p>Chưa có file nào để tải về.</p>
                </div>
            </div> --}}
            <!-- Addresses tab -->
            <div class="tab-pane fade" id="addresses" role="tabpanel">
                <div class="porto-dashboard-content">
                    <h4 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ giao hàng</h4>
                    <form method="POST" action="{{ route('user.saveAddress') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                        </div>
                        <button class="btn btn-primary">Lưu địa chỉ</button>
                    </form>
                    <div class="mt-4">
                        <h6><strong>Địa chỉ đang hiển thị:</strong></h6>
                        <p><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa nhập' }}</p>
                    </div>
                </div>
            </div>
            <!-- Account Details tab -->
            <div class="tab-pane fade" id="account" role="tabpanel">
                <div class="porto-dashboard-content">
                    <h4 class="mb-3"><i class="fas fa-user me-2"></i>Thông tin tài khoản</h4>
                    <ul class="nav nav-tabs mb-3" id="accountDetailTab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab">Thông tin</a></li>
                        <li class="nav-item"><a class="nav-link" id="edit-tab" data-bs-toggle="tab" href="#edit" role="tab">Chỉnh sửa</a></li>
                        <li class="nav-item"><a class="nav-link" id="pass-tab" data-bs-toggle="tab" href="#password" role="tab">Đổi mật khẩu</a></li>
                    </ul>
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
                                    <select name="gender" class="form-control">
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
                                        <img src="{{ $user->avatar }}" alt="Avatar" class="mt-2 rounded-circle" style="width:60px;height:60px;object-fit:cover;">
                                    @endif
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
                    </div>
                </div>
            </div>
            <!-- Shopping Address tab (alias of addresses) -->
            <div class="tab-pane fade" id="shipping" role="tabpanel">
                <div class="porto-dashboard-content">
                    <h4 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ giao hàng</h4>
                    <p><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa có' }}</p>
                </div>
            </div>
            <!-- Wishlist tab -->
            <div class="tab-pane fade" id="wishlist" role="tabpanel">
                <div class="porto-dashboard-content">
                    <h4 class="mb-3"><i class="fas fa-heart me-2"></i>Yêu thích</h4>
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
                                <div class="col-md-4 mb-4">
                                    <div class="product-default">
                                        <figure>
                                            <a href="{{ route('client.product.detail', $product->id) }}">
                                                <img src="{{ asset('storage/' . $image) }}" width="140" height="140" alt="{{ $name }}">
                                            </a>
                                            @if ($discountPercent > 0)
                                                <div class="label-group" style="position:absolute;top:10px;left:10px;">
                                                    <div class="product-label label-hot">-{{ $discountPercent }}%</div>
                                                </div>
                                            @endif
                                        </figure>

                                        <div class="product-details">
                                            <div class="category-list">
                                                <a href="#" class="product-category">Yêu thích</a>
                                            </div>
                                            <h3 class="product-title">
                                                <a href="{{ route('client.product.detail', $product->id) }}">{{ $name }}</a>
                                            </h3>

                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:80%"></span>
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                            </div>

                                            <div class="price-box">
                                                @if ($minSale > 0)
                                                    <del class="text-muted">{{ number_format($minPrice, 0, ',', '.') }}₫</del>
                                                    <span class="product-price text-danger">{{ number_format($minSale, 0, ',', '.')
                                                        }}₫</span>
                                                @else
                                                    <span class="product-price">{{ number_format($minPrice, 0, ',', '.') }}₫</span>
                                                @endif
                                            </div>

                                            <div class="product-action">
                                                <a href="#" class="btn-icon-wish" title="Xoá yêu thích"
                                                    onclick="event.preventDefault(); submitDeleteForm('remove-wishlist-{{ $wishlist->id }}')">
                                                    <i class="icon-heart" style="color: #e74c3c;"></i>
                                                </a>
                                                <form id="remove-wishlist-{{ $wishlist->id }}"
                                                    action="{{ route('client.wishlist.remove', $wishlist->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>


                                                {{-- Thêm vào giỏ hàng --}}
                                                <form action="{{ route('client.cart.add', $product->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id ?? '' }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="button" class="btn-icon btn-add-cart" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#variantModal"
                                                    data-product-id="{{ $product->id }}" 
                                                    data-product-name="{{ $product->name }}" 
                                                    data-variants='@json($product->variants)'>
                                                <i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span>
                                            </button>
                                                </form>
                                                

                                                <a href="{{ route('client.product.detail', $product->id) }}" class="btn-quickview" title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center mb-0">Bạn chưa có sản phẩm yêu thích nào.</p>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="modal fade" id="variantModal" tabindex="-1" role="dialog" aria-labelledby="variantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModalLabel">Chọn biến thể sản phẩm</h5>
                
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img id="modalProductImage" src="" alt="Product" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <h6 id="modalProductName" class="mb-3"></h6>
                        
                        <form id="variantForm">
                            @csrf
                            <input type="hidden" id="modalProductId" name="product_id">
                            <input type="hidden" id="modalVariantId" name="variant_id">
                            
                            <div class="form-group mb-3">
                                <label class="form-label mb-2 fw-bold">Màu sắc:</label>
                                <div class="d-flex flex-wrap gap-2" id="colorOptions">
                                    </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label mb-2 fw-bold">Kích thước:</label>
                                <div class="d-flex flex-wrap gap-2" id="sizeOptions">
                                    </div>
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
                
                <button type="button" class="btn btn-dark" id="modalAddToCartBtn" disabled>
                    <i class="icon-shopping-cart"></i> THÊM VÀO GIỎ HÀNG
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    // Chuyển tab khi click các link-to-tab
    document.querySelectorAll('.link-to-tab').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var target = this.getAttribute('href');
            var tabTrigger = document.querySelector(`a[href="${target}"]`);
            if(tabTrigger) {
                new bootstrap.Tab(tabTrigger).show();
            }
        });
    });
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

    function submitForm(formId) {
        const form = document.getElementById(formId);
        if (form) form.submit();
    }
    function submitDeleteForm(formId) {
        if (confirm("Bạn có chắc chắn muốn bỏ sản phẩm ra khỏi danh sách yêu thích không?")) {
            submitForm(formId);
        }
    }
     // Khởi tạo modal
     variantModal = new bootstrap.Modal(document.getElementById('variantModal'));
        
        // Event listeners cho modal
        document.getElementById('modalQtyMinus').onclick = function() {
            const qtyInput = document.getElementById('modalQuantityInput');
            let v = parseInt(qtyInput.value) || 1;
            if (v > 1) qtyInput.value = v - 1;
        };

        document.getElementById('modalQtyPlus').onclick = function() {
            const qtyInput = document.getElementById('modalQuantityInput');
            let v = parseInt(qtyInput.value) || 1;
            if (!qtyInput.max || v < parseInt(qtyInput.max)) qtyInput.value = v + 1;
        };

        document.getElementById('modalAddToCartBtn').onclick = function() {
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
        
        // Lắng nghe sự kiện mở modal của Bootstrap
        document.getElementById('variantModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const variantsString = button.getAttribute('data-variants');
            
            try {
                const variants = JSON.parse(variantsString);
                // Gọi hàm điền dữ liệu vào modal
                populateVariantModal(productId, productName, variants);
            } catch (error) {
                console.error("Lỗi khi phân tích cú pháp JSON variants:", error);
            }
        });

        // Hàm điền dữ liệu vào modal
        function populateVariantModal(productId, productName, variants) {
            currentVariants = variants || [];
            selectedModalColor = null;
            selectedModalSize = null;
            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductName').textContent = productName;
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
            const colorOptions = document.getElementById('colorOptions');
            colorOptions.innerHTML = '';
            const uniqueColorIds = [...new Set(variants.map(v => v.color_id))].filter(id => id);
            uniqueColorIds.forEach(colorId => {
                const variant = variants.find(v => v.color_id === colorId);
                const color = variant?.color || { id: colorId, name: `Màu ${colorId}` };
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'color-btn demo-style-btn';
                btn.dataset.color = color.id;
                btn.textContent = color.name || `Màu ${colorId}`;
                btn.onclick = () => selectModalColor(color.id);
                colorOptions.appendChild(btn);
            });
            const sizeOptions = document.getElementById('sizeOptions');
            sizeOptions.innerHTML = '';
            const uniqueSizeIds = [...new Set(variants.map(v => v.size_id))].filter(id => id);
            uniqueSizeIds.forEach(sizeId => {
                const variant = variants.find(v => v.size_id === sizeId);
                const size = variant?.size || { id: sizeId, name: `Size ${sizeId}` };
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'size-btn demo-style-btn';
                btn.dataset.size = size.id;
                btn.textContent = size.name || `Size ${sizeId}`;
                btn.onclick = () => selectModalSize(size.id);
                sizeOptions.appendChild(btn);
            });
            document.getElementById('modalQuantityInput').value = 1;
            document.getElementById('modalInventoryInfo').textContent = '';
            document.getElementById('modalDynamicPrice').innerHTML = '';
            document.getElementById('modalAddToCartBtn').disabled = true;
        }

        function selectModalColor(colorId) {
            selectedModalColor = colorId;
            document.querySelectorAll('#colorOptions .color-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`#colorOptions .color-btn[data-color="${colorId}"]`).classList.add('active');
            updateModalVariant();
        }

        function selectModalSize(sizeId) {
            selectedModalSize = sizeId;
            document.querySelectorAll('#sizeOptions .size-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`#sizeOptions .size-btn[data-size="${sizeId}"]`).classList.add('active');
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
                    dynamicPrice.innerHTML = `<del style="font-size:16px;color:#bbb;font-weight:600;margin-right:10px;">₫${parseInt(variant.price).toLocaleString('vi-VN')}</del><span style="font-size:20px;font-weight:700;color:#222;">₫${parseInt(variant.sale_price).toLocaleString('vi-VN')}</span>`;
                } else {
                    dynamicPrice.innerHTML = `<span style="font-size:20px;font-weight:700;color:#222;">₫${parseInt(variant.price).toLocaleString('vi-VN')}</span>`;
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
    const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>';
    const alertDiv = document.createElement('div');
    alertDiv.className = 'custom-alert';
    alertDiv.setAttribute('data-type', type);
                alertDiv.innerHTML = `<span class="alert-text">${message}</span><span class="icon-warning">${icon}</span><button type="button" class="close" onclick="this.parentElement.remove()"><span aria-hidden="true">&times;</span></button>`;
    let alertStack = document.getElementById('alert-stack');
    if (!alertStack) {
        alertStack = document.createElement('div');
        alertStack.id = 'alert-stack';
        alertStack.style.cssText = 'position: fixed; top: 80px; right: 24px; z-index: 9999;';
        document.body.appendChild(alertStack);
    }
    alertStack.appendChild(alertDiv);
    setTimeout(() => { alertDiv.remove(); }, 3500);
}
    
</script>

@endsection

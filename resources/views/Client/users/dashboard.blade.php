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
                    <p><strong>Địa chỉ:</strong> {{ session('address.detail', 'Chưa có') }}</p>
                    <p><strong>Thành phố:</strong> {{ session('address.city', '') }}</p>
                    <p><strong>Quốc gia:</strong> {{ session('address.country', 'Việt Nam') }}</p>
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
                                                    <button type="submit" class="btn-icon btn-add-cart" >
                                                        
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
</script>

@endsection

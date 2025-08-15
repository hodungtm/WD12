@extends('admin.layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
<div class="main-content-wrap">
    
    <!-- note: Header với breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Dashboard</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Tổng quan</div></li>
        </ul>
    </div>
    
    <!-- note: Hiển thị thông báo thành công -->
    @if (session('success'))
    <div class="alert"
        style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
        <i class="icon-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
    </div>
    @endif

    <!-- note: Hiển thị thông báo lỗi -->
    @if (session('error'))
    <div class="alert"
        style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
        <i class="icon-alert-triangle" style="margin-right: 6px;"></i> {{ session('error') }}
    </div>
    @endif

    <!-- note: Form tìm kiếm và lọc dữ liệu -->
    <form method="GET" action="" class="mb-3 d-flex align-items-center gap-2 position-relative flex-wrap">
        <input type="text" name="q" class="form-control w-auto dashboard-search" style="min-width:220px;" placeholder="Tìm kiếm sản phẩm, đơn hàng, khách hàng, mã giảm giá..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-primary dashboard-search-btn"><i class="fas fa-search"></i> Tìm kiếm</button>
        <!-- note: Dropdown chọn khoảng thời gian -->
        <select name="type" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
            <option value="today" {{ $type === 'today' ? 'selected' : '' }}>Hôm nay</option>
            <option value="week" {{ $type === 'week' ? 'selected' : '' }}>Tuần</option>
            <option value="month" {{ $type === 'month' ? 'selected' : '' }}>Tháng</option>
            <option value="year" {{ $type === 'year' ? 'selected' : '' }}>Năm</option>
            <option value="custom" {{ $type === 'custom' ? 'selected' : '' }}>Tùy chỉnh</option>
        </select>
        <!-- note: Dropdown chọn danh mục -->
        <select name="category_id" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->ten_danh_muc }}</option>
            @endforeach
        </select>
        <!-- note: Dropdown chọn trạng thái đơn hàng -->
        <select name="status" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
            <option value="">Tất cả trạng thái</option>
            @php
                $statuses = ['Đang chờ', 'Xác nhận đơn', 'Đang giao hàng', 'Hoàn thành', 'Đã hủy'];
            @endphp
            @foreach($statuses as $stt)
                <option value="{{ $stt }}" {{ request('status') == $stt ? 'selected' : '' }}>{{ $stt }}</option>
            @endforeach
        </select>
        <!-- note: Date picker cho khoảng thời gian tùy chỉnh -->
        @if($type === 'today')
            <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}" class="form-control w-auto d-inline-block ms-auto dashboard-date-picker" onchange="this.form.submit()" style="min-width: 150px; max-width: 180px;">
        @elseif($type === 'custom')
            <div class="d-flex align-items-center gap-2">
                <input type="date" name="start_date" value="{{ request('start_date', now()->subDays(7)->toDateString()) }}" class="form-control w-auto d-inline-block dashboard-date-picker" onchange="this.form.submit()" style="min-width: 150px; max-width: 180px;">
                <span>-</span>
                <input type="date" name="end_date" value="{{ request('end_date', now()->toDateString()) }}" class="form-control w-auto d-inline-block dashboard-date-picker" onchange="this.form.submit()" style="min-width: 150px; max-width: 180px;">
            </div>
        @endif
    </form>
    
    <!-- note: CSS tùy chỉnh cho form tìm kiếm -->
    <style>
        .dashboard-search { border-radius: 20px; padding-left: 16px; }
        .dashboard-search-btn { border-radius: 20px; padding: 6px 18px; font-weight: 500; }
        .dashboard-search-btn i { margin-right: 4px; }
        .dashboard-date-picker { border-radius: 20px; height: 40px; }
        .form-select { border-radius: 20px; height: 40px; }
        .form-select:focus, .dashboard-date-picker:focus { border-color: #1abc9c; box-shadow: 0 0 5px rgba(26, 188, 156, 0.5); }
        @media (max-width: 576px) {
            .form-control, .form-select, .dashboard-search-btn {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }
            .dashboard-date-picker {
                max-width: 100%;
            }
        }

        #orderStatusFilter {
            border-radius: 20px;
            height: 40px;
            min-width: 200px;
            border: 1px solid #ddd;
        }
        
        #orderStatusFilter:focus {
            border-color: #1abc9c;
            box-shadow: 0 0 5px rgba(26, 188, 156, 0.5);
        }
    </style>

    <!-- note: Thống kê tổng quan - 4 card chính -->
    <div class="row g-4">
        @foreach([
            ['Doanh thu', $totalRevenue, $percentRevenue, '#28a745', 'revenueChart'],
            ['Đơn hàng', $ordersCount, $percentOrders, '#dc3545', 'ordersChart'],
            ['Khách hàng', $usersCount, $percentUsers, '#007bff', 'customersChart'],
            ['Sản phẩm', $productsCount, $percentProducts, '#ffc107', 'productsChart']
        ] as [$title, $value, $percent, $color, $chartId])
        <div class="col-md-3">
            <div class="card shadow p-3" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="icon text-white rounded-circle p-2" style="background-color: {{ $color }}">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="fw-bold text-{{ $percent >= 0 ? 'success' : 'danger' }}">
                        {{ $percent >= 0 ? '+' : '' }}{{ $percent }}%
                    </div>
                </div>
                <div class="mt-2">
                    <h4 class="fw-bold mb-0">{{ is_numeric($value) ? number_format($value) : $value }}</h4>
                    <small>{{ $title }}</small>
                </div>
                <canvas id="{{ $chartId }}"></canvas>
            </div>
        </div>
        @endforeach
    </div>

    <!-- note: CSS tùy chỉnh cho bảng -->
    <style>
        .dashboard-table,
        .dashboard-table thead,
        .dashboard-table tbody {
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
        }
        .dashboard-table th, .dashboard-table td {
            border: none !important;
            font-size: 1.1rem;
        }
        .dashboard-link {
            color: #1abc9c;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
            margin-left: 12px;
            font-size: 1rem;
            position: relative;
        }
        .dashboard-link:hover {
            color: #148f77;
            text-decoration: underline;
        }
    </style>

    <!-- note: Hàng 2: 3 box - Top sản phẩm bán chạy, sản phẩm bán kém nhất và sản phẩm sắp hết hàng -->
    <div class="row g-4 mt-4" style="display: flex; align-items: stretch;">
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <div class="title-box d-flex align-items-center justify-content-between">
                    <span><i class="icon-star"></i> <span class="body-title">Top 5 sản phẩm bán chạy</span></span>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Tên sản phẩm</th>
                                <th style="width: 30%; text-align: right;">Đã bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $item)
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->product_name ?? 'N/A' }}</td>
                                <td style="text-align: right;">{{ $item->sold }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <div class="title-box d-flex align-items-center justify-content-between">
                    <span><i class="icon-trending-down"></i> <span class="body-title">Top 5 sản phẩm bán kém nhất</span></span>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Tên sản phẩm</th>
                                <th style="width: 30%; text-align: right;">Đã bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($worstProducts as $item)
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->product_name ?? 'N/A' }}</td>
                                <td style="text-align: right;">{{ $item->sold }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <div class="title-box"><i class="icon-alert-circle"></i><div class="body-title">Sản phẩm sắp hết hàng</div></div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Tên sản phẩm</th>
                                <th style="width: 30%; text-align: right;">Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStock as $variant)
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $variant->product->name ?? 'N/A' }}</td>
                                <td style="text-align: right;">{{ $variant->quantity }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Không có sản phẩm nào sắp hết</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- note: Hàng 3: 3 box nhỏ - Thống kê đơn hàng, doanh thu, khách hàng -->
    <div class="row g-4 mt-4" style="display: flex; align-items: stretch;">
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- note: Nội dung box 1 - Thống kê đơn hàng theo trạng thái -->
                <div class="title-box">
                    <i class="icon-check"></i>
                    <div class="body-title">Đơn hàng theo trạng thái</div>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Trạng thái</th>
                                <th style="width: 30%; text-align: right;">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Đang chờ</td><td style="text-align: right;">{{ $totalPendingOrders }}</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Xác nhận đơn</td><td style="text-align: right;">{{ $totalConfirmedOrders }}</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Đang giao hàng</td><td style="text-align: right;">{{ $totalShippingOrders }}</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Hoàn thành</td><td style="text-align: right;">{{ $totalCompletedOrders }}</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Đã hủy</td><td style="text-align: right;">{{ $totalCancelledOrders }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- note: Nội dung box 2 - Thống kê doanh thu -->
                <div class="title-box">
                    <i class="icon-cash"></i>
                    <div class="body-title">Doanh thu hôm nay / tuần này / tháng này</div>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Khoảng thời gian</th>
                                <th style="width: 30%; text-align: right;">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Hôm nay</td><td style="text-align: right;">{{ number_format($todayRevenue) }} đ</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Tuần này</td><td style="text-align: right;">{{ number_format($thisWeekRevenue) }} đ</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Tháng này</td><td style="text-align: right;">{{ number_format($thisMonthRevenue) }} đ</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- note: Nội dung box 3 - Thống kê top danh mục bán chạy -->
                <div class="title-box">
                    <i class="icon-star"></i>
                    <div class="body-title">Top danh mục bán chạy</div>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Danh mục</th>
                                <th style="width: 30%; text-align: right;">Đã bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCategories as $cat)
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $cat->ten_danh_muc }}</td>
                                <td style="text-align: right;">{{ $cat->total_sold }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- note: Hàng 4: 3 box nhỏ - Tổng tồn kho, khách hàng mới, khách hàng chi tiêu nhiều -->
    <div class="row g-4 mt-4" style="display: flex; align-items: stretch;">
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- note: Nội dung box 1 - Tổng tồn kho theo sản phẩm -->
                <div class="title-box">
                    <i class="icon-box"></i>
                    <div class="body-title d-flex align-items-center justify-content-between">
                        Tổng tồn kho theo sản phẩm
                        <a href="{{ route('products.index') }}" class="dashboard-link">Xem tất cả</a>
                    </div>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Sản phẩm</th>
                                <th style="width: 30%; text-align: right;">Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($totalStockPerProduct->take(10) as $stock)
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $stock->product->name ?? 'N/A' }}</td>
                                <td style="text-align: right;">{{ $stock->total_stock }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- note: Nội dung box 2 - Khách hàng mới hôm nay -->
                <div class="title-box">
                    <i class="icon-user"></i>
                    <div class="body-title">Khách hàng mới hôm nay</div>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Tên</th>
                                <th style="width: 50%;">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($newCustomers as $cust)
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $cust->name }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $cust->email }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- note: Nội dung box 3 - Khách hàng chi tiêu nhiều nhất -->
                <div class="title-box">
                    <i class="icon-crown"></i>
                    <div class="body-title">Khách hàng chi tiêu nhiều nhất</div>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Tên</th>
                                <th style="width: 30%; text-align: right;">Tổng chi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCustomers as $cust)
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $cust->name }}</td>
                                <td style="text-align: right;">{{ number_format($cust->total_spent) }} đ</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- note: Hàng 5: 2 box nhỏ - Mã giảm giá đang hoạt động và Đánh giá -->
    <div class="row g-4 mt-4" style="display: flex; align-items: stretch;">
        <div class="col-md-6" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- note: Nội dung box 1 - Mã giảm giá đang hoạt động -->
                <div class="title-box">
                    <i class="icon-tag"></i>
                    <div class="body-title d-flex align-items-center justify-content-between">
                        Mã giảm giá đang hoạt động
                        <a href="{{ route('admin.discounts.index') }}" class="dashboard-link">Xem tất cả</a>
                    </div>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Mã</th>
                                <th style="width: 50%;">Phần trăm</th>
                                <th style="width: 30%;">Hết hạn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeDiscountList->take(5) as $discount)
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $discount->code }}</td>
                                <td>{{ $discount->discount_percent ?? 'N/A' }}%</td>
                                <td>{{ $discount->end_date ? \Carbon\Carbon::parse($discount->end_date)->format('d/m/Y') : 'Không xác định' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Không có mã giảm giá nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- note: Nội dung box 2 - Đánh giá -->
                <div class="title-box">
                    <i class="icon-message-square"></i>
                    <div class="body-title">Đánh giá</div>
                </div>
                <div class="wg-table table-product-list mt-3" style="overflow-x:auto;">
                    <table class="table table-borderless dashboard-table" style="table-layout: fixed; width: 100%; max-width: 100%; min-width: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Loại</th>
                                <th style="width: 30%; text-align: right;">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Tổng</td><td style="text-align: right;">{{ $reviewStats->total_reviews }}</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Tốt</td><td style="text-align: right;">{{ $reviewStats->good_reviews }}</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Xấu</td><td style="text-align: right;">{{ $reviewStats->bad_reviews }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Thêm ngay sau form tìm kiếm và trước div class="row g-4" chứa các biểu đồ thống kê -->
<div class="row mb-4 align-items-center">
    <div class="col-md-12">
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0 fw-bold">Lọc theo trạng thái:</label>
            <select id="orderStatusFilter" class="form-select w-auto">
                <option value="all">Tất cả trạng thái</option>
                <option value="pending">Đang chờ</option>
                <option value="confirmed">Xác nhận đơn</option>  
                <option value="shipping">Đang giao hàng</option>
                <option value="completed">Hoàn thành</option>
                <option value="cancelled">Đã hủy</option>
            </select>
        </div>
    </div>
</div>

<!-- Cập nhật CSS để làm nổi bật dropdown -->
<style>
    #orderStatusFilter {
        border-radius: 20px;
        height: 40px;
        min-width: 200px;
        border: 1px solid #ddd;
        padding: 0 15px;
        background-color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    #orderStatusFilter:hover {
        border-color: #1abc9c;
    }
    
    #orderStatusFilter:focus {
        border-color: #1abc9c;
        box-shadow: 0 0 5px rgba(26, 188, 156, 0.5);
        outline: none;
    }

    .dashboard-filter-label {
        font-size: 14px;
        color: #555;
    }
</style>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($labels) !!};
const revenueData = {!! json_encode($revenueData) !!};
const orderData = {!! json_encode($orderData) !!};

// Biến lưu trữ instance của biểu đồ
let revenueChart = null;
let ordersChart = null;

function formatCurrency(value) {
    return value.toLocaleString() + " đ";
}

function drawChart() {
    // Khởi tạo biểu đồ doanh thu
    if (revenueChart) {
        revenueChart.destroy();
    }
    
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: revenueData,
                backgroundColor: revenueData.map(value => value === 0 ? '#28a74566' : '#28a745'),
                borderColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: context => `Mốc: ${context[0].label}`,
                        label: context => `Doanh thu: ${formatCurrency(context.parsed.y)}`
                    }
                }
            },
            scales: {
                x: {
                    display: false,
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                },
                y: {
                    display: false,
                    beginAtZero: true
                }
            }
        }
    });

    // Khởi tạo biểu đồ đơn hàng
    if (ordersChart) {
        ordersChart.destroy();
    }
    
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    ordersChart = new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: orderData,
                backgroundColor: orderData.map(value => value === 0 ? '#dc354566' : '#dc3545'),
                borderColor: '#dc3545',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: context => `Mốc: ${context[0].label}`,
                        label: context => `Số đơn: ${context.parsed.y}`
                    }
                }
            },
            scales: {
                x: {
                    display: false,
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                },
                y: {
                    display: false,
                    beginAtZero: true
                }
            }
        }
    });
}

// Khởi tạo biểu đồ khi trang load
document.addEventListener('DOMContentLoaded', function() {
    drawChart();
});
</script>
@endsection

@endsection

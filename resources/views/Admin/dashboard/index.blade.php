@extends('admin.layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
<div class="main-content-wrap">
    
    <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Dashboard</h3>

        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Tổng quan</div></li>
        </ul>
    </div>
    @if (session('success'))
    <div class="alert"
        style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
        <i class="icon-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert"
        style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
        <i class="icon-alert-triangle" style="margin-right: 6px;"></i> {{ session('error') }}
    </div>
@endif
    <form method="GET" action="" class="mb-3 d-flex align-items-center gap-2 position-relative">
        <input type="text" name="q" class="form-control w-auto dashboard-search" style="min-width:220px;" placeholder="Tìm kiếm sản phẩm, đơn hàng, khách hàng, mã giảm giá..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-primary dashboard-search-btn"><i class="fas fa-search"></i> Tìm kiếm</button>
        <select name="type" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
            <option value="today" {{ $type === 'today' ? 'selected' : '' }}>Hôm nay</option>
            <option value="week" {{ $type === 'week' ? 'selected' : '' }}>Tuần</option>
            <option value="month" {{ $type === 'month' ? 'selected' : '' }}>Tháng</option>
            <option value="year" {{ $type === 'year' ? 'selected' : '' }}>Năm</option>
        </select>
        <select name="category_id" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->ten_danh_muc }}</option>
            @endforeach
        </select>
        @if(request('type') === 'today')
            <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}" class="form-control w-auto d-inline-block ms-auto dashboard-date-picker" onchange="this.form.submit()" style="min-width: 150px; max-width: 180px;">
        @endif
    </form>
    <style>
.dashboard-search { border-radius: 20px; padding-left: 16px; }
.dashboard-search-btn { border-radius: 20px; padding: 6px 18px; font-weight: 500; }
.dashboard-search-btn i { margin-right: 4px; }
.dashboard-date-picker { border-radius: 20px; height: 40px; }
</style>

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

    {{-- Hàng 2: 2 box dài --}}
    <div class="row g-4 mt-4" style="display: flex; align-items: stretch;">
        <div class="col-md-6" style="display: flex; flex-direction: column;">
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
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->product->name ?? 'N/A' }}</td>
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
        <div class="col-md-6" style="display: flex; flex-direction: column;">
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

    {{-- Hàng 3: 3 box nhỏ --}}
    <div class="row g-4 mt-4" style="display: flex; align-items: stretch;">
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- Nội dung box 1 -->
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
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Đang giao hàng</td><td style="text-align: right;">{{ $totalShippingOrders }}</td></tr>
                            <tr><td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Hoàn thành</td><td style="text-align: right;">{{ $totalCompletedOrders }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- Nội dung box 2 -->
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
                <!-- Nội dung box 3 -->
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

    {{-- Hàng 4: 3 box nhỏ --}}
    <div class="row g-4 mt-4" style="display: flex; align-items: stretch;">
        <div class="col-md-4" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
                <!-- Nội dung box 1 -->
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
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $stock->product->name }}</td>
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
                <!-- Nội dung box 2 -->
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
                <!-- Nội dung box 3 -->
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

    {{-- Hàng 5: 2 box nhỏ --}}
    <div class="row g-4 mt-4" style="display: flex; align-items: stretch;">
        <div class="col-md-6" style="display: flex; flex-direction: column;">
            <div class="wg-box" style="flex: 1 1 auto;">
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
                                <td>{{ $discount->discount_percent }}%</td>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = {!! json_encode($labels) !!};

function formatCurrency(value) {
    return value.toLocaleString() + " đ";
}

function drawChart(id, data, color) {
    new Chart(document.getElementById(id).getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                borderColor: color,
                fill: false,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: color,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        title: context => `Mốc: ${context[0].label}`,
                        label: context => `Giá trị: ${formatCurrency(context.parsed.y)}`
                    }
                }
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: { display: false },
                y: { display: false }
            }
        }
    });
}

drawChart('revenueChart', {!! json_encode($revenueData) !!}, '#28a745');
drawChart('ordersChart', {!! json_encode($orderData) !!}, '#dc3545');
drawChart('customersChart', {!! json_encode($userData) !!}, '#007bff');
drawChart('productsChart', {!! json_encode($productData) !!}, '#ffc107');
</script>
@endsection

@endsection

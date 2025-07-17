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

    <form method="GET" class="mb-3">
        <select name="type" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
            <option value="week" {{ $type === 'week' ? 'selected' : '' }}>Tuần</option>
            <option value="month" {{ $type === 'month' ? 'selected' : '' }}>Tháng</option>
            <option value="year" {{ $type === 'year' ? 'selected' : '' }}>Năm</option>
        </select>
    </form>

    <div class="row g-4">
        {{-- Doanh thu --}}
        <div class="col-md-3">
            <div class="card shadow p-3" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="icon bg-success text-white rounded-circle p-2">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="text-{{ $percentRevenue >= 0 ? 'success' : 'danger' }} fw-bold">
                        {{ $percentRevenue >= 0 ? '+' : '' }}{{ $percentRevenue }}%
                    </div>
                </div>
                <div class="mt-2">
                    <h4 class="fw-bold mb-0">{{ number_format($totalRevenue) }} đ</h4>
                    <small>Doanh thu</small>
                </div>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Đơn hàng --}}
        <div class="col-md-3">
            <div class="card shadow p-3" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="icon bg-danger text-white rounded-circle p-2">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="text-{{ $percentOrders >= 0 ? 'success' : 'danger' }} fw-bold">
                        {{ $percentOrders >= 0 ? '+' : '' }}{{ $percentOrders }}%
                    </div>
                </div>
                <div class="mt-2">
                    <h4 class="fw-bold mb-0">{{ $ordersCount }}</h4>
                    <small>Đơn hàng</small>
                </div>
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        {{-- Khách hàng --}}
        <div class="col-md-3">
            <div class="card shadow p-3" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="icon bg-primary text-white rounded-circle p-2">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="text-{{ $percentUsers >= 0 ? 'success' : 'danger' }} fw-bold">
                        {{ $percentUsers >= 0 ? '+' : '' }}{{ $percentUsers }}%
                    </div>
                </div>
                <div class="mt-2">
                    <h4 class="fw-bold mb-0">{{ $usersCount }}</h4>
                    <small>Khách hàng</small>
                </div>
                <canvas id="customersChart"></canvas>
            </div>
        </div>

        {{-- Sản phẩm --}}
        <div class="col-md-3">
            <div class="card shadow p-3" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="icon bg-warning text-white rounded-circle p-2">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="text-{{ $percentProducts >= 0 ? 'success' : 'danger' }} fw-bold">
                        {{ $percentProducts >= 0 ? '+' : '' }}{{ $percentProducts }}%
                    </div>
                </div>
                <div class="mt-2">
                    <h4 class="fw-bold mb-0">{{ $productsCount }}</h4>
                    <small>Sản phẩm</small>
                </div>
                <canvas id="productsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="wg-box mt-4">
        <div class="title-box">
            <i class="icon-star"></i>
            <div class="body-title">Top 5 sản phẩm bán chạy</div>
        </div>
        <div class="wg-table table-product-list mt-3">
            <ul class="table-title flex mb-14" style="gap: 2px;">
                <li style="flex:2;">Tên sản phẩm</li>
                <li style="flex:1;">Đã bán</li>
            </ul>
            <ul class="flex flex-column">
                @forelse($topProducts as $item)
                <li class="wg-product item-row" style="gap: 2px; align-items:center;">
                    <div class="body-text" style="flex:2;">{{ $item->product->name ?? 'N/A' }}</div>
                    <div class="body-text" style="flex:1;">{{ $item->sold }}</div>
                </li>
                @empty
                <li class="text-muted text-center">Không có dữ liệu</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="wg-box mt-4">
        <div class="title-box">
            <i class="icon-alert-circle"></i>
            <div class="body-title">Sản phẩm sắp hết hàng</div>
        </div>
        <div class="wg-table table-product-list mt-3">
            <ul class="table-title flex mb-14" style="gap: 2px;">
                <li style="flex:2;">Tên sản phẩm</li>
                <li style="flex:1;">Tồn kho</li>
            </ul>
            <ul class="flex flex-column">
                @forelse($lowStock as $variant)
                <li class="wg-product item-row" style="gap: 2px; align-items:center;">
                    <div class="body-text" style="flex:2;">{{ $variant->product->name ?? 'N/A' }}</div>
                    <div class="body-text" style="flex:1;">{{ $variant->quantity }}</div>
                </li>
                @empty
                <li class="text-muted text-center">Không có sản phẩm nào sắp hết</li>
                @endforelse
            </ul>
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

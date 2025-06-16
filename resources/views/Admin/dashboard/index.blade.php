@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="container-fluid">
        <h1 class="mb-4">Thống kê tổng quan</h1>

        {{-- Form lọc thời gian --}}
        <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label>Bắt đầu</label>
                <input type="datetime-local" name="from" value="{{ request('from') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Kết thúc</label>
                <input type="datetime-local" name="to" value="{{ request('to') }}" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        {{-- Thống kê ô --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-white text-dark fw-bold border p-3">
                    <h5>Tổng đơn hàng</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-white text-dark fw-bold border p-3">
                    <h5>Tổng người dùng</h5>
                    <h3>{{ $totalUsers }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-white text-dark fw-bold border p-3">
                    <h5>Tổng đánh giá / bình luận</h5>
                    <h3>{{ $totalReviews  }}/{{ $totalComments }}</h3>

                </div>
            </div>

        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <div class="card p-4 shadow-sm" style="height: 400px;">
                    <h4 class="mb-4 fw-bold">📅 Biểu đồ số đơn hàng theo thời gian</h4>
                    <canvas id="ordersTimeChart" style="height: 100%; width: 100%;"></canvas>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4 shadow-sm" style="height: 400px;">
                    <h4 class="mb-4 fw-bold">📦 Biểu đồ trạng thái đơn hàng</h4>
                    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                        <canvas id="orderStatusChart" style="max-width: 300px; max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top sản phẩm bán chạy --}}
        <div class="card p-4 mb-5">
            <h4>Top sản phẩm bán chạy</h4>
            <ul>
                @foreach ($topProducts as $product)
                    <li>{{ $product->name }} - {{ $product->total_orders }} đơn hàng</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Biểu đồ đơn hàng theo ngày
        const ctx1 = document.getElementById('ordersTimeChart').getContext('2d');
        const ordersTimeChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode($ordersByDate->pluck('date')) !!},
                datasets: [{
                    label: 'Số đơn hàng',
                    data: {!! json_encode($ordersByDate->pluck('count')) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Ngày'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Hiển thị số nguyên
                            stepSize: 1,
                            callback: function (value) {
                                return Number.isInteger(value) ? value : null;
                            },
                            precision: 0,
                            color: 'black',
                            font: {
                                weight: 'bold'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Đơn hàng',
                            color: 'black',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'black',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            weight: 'bold'
                        },
                        titleFont: {
                            weight: 'bold'
                        }
                    }
                }
            }
        });

        // Biểu đồ trạng thái đơn hàng
        const ctx2 = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($orderStatus->pluck('status')) !!},
                datasets: [{
                    label: 'Trạng thái đơn hàng',
                    data: {!! json_encode($orderStatus->pluck('count')) !!},
                    backgroundColor: [
                        '#007bff', '#ffc107', '#28a745', '#dc3545'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection
@extends('admin.layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Thống kê tổng quan</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Tổng quan</div></li>
      </ul>
    </div>

    <div class="wg-box p-4">
      <div class="title-box mb-3">
        <i class="icon-search"></i>
        <div class="body-text">Lọc thống kê theo thời gian</div>
      </div>

      <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap gap20">
        <div>
          <label class="text-tiny block mb-1">Bắt đầu</label>
          <input type="datetime-local" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div>
          <label class="text-tiny block mb-1">Kết thúc</label>
          <input type="datetime-local" name="to" value="{{ request('to') }}" class="form-control">
        </div>
        <div class="flex items-end">
          <button type="submit" class="tf-button">Lọc</button>
        </div>
        <div class="flex items-end">
          <a href="{{ route('admin.dashboard') }}" class="tf-button style-1">Reset</a>
        </div>
      </form>
    </div>

    <div class="grid md:grid-cols-3 gap20 mt-4">
      <div class="wg-box text-center py-4">
        <div class="body-title mb-2">📦 Tổng đơn hàng</div>
        <div class="text-2xl font-bold">{{ $totalOrders }}</div>
      </div>
      <div class="wg-box text-center py-4">
        <div class="body-title mb-2">👤 Tổng người dùng</div>
        <div class="text-2xl font-bold">{{ $totalUsers }}</div>
      </div>
      <div class="wg-box text-center py-4">
        <div class="body-title mb-2">⭐ Đánh giá / 💬 Bình luận</div>
        <div class="text-2xl font-bold">{{ $totalReviews }}/{{ $totalComments }}</div>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap20 mt-4">
      <div class="wg-box p-4">
        <div class="body-title mb-3">📅 Biểu đồ đơn hàng theo thời gian</div>
        <canvas id="ordersTimeChart" style="height: 320px; width: 100%;"></canvas>
      </div>
      <div class="wg-box p-4">
        <div class="body-title mb-3">📊 Biểu đồ trạng thái đơn hàng</div>
        <div class="flex justify-center">
          <canvas id="orderStatusChart" style="max-width: 300px;"></canvas>
        </div>
      </div>
    </div>

    <div class="wg-box p-4 mt-4">
      <div class="body-title mb-3">🔥 Top sản phẩm bán chạy</div>
      <ul class="list-disc pl-5 text-base">
        @forelse ($topProducts as $product)
          <li>{{ $product->name }} - {{ $product->total_orders }} đơn hàng</li>
        @empty
          <li>Không có dữ liệu</li>
        @endforelse
      </ul>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
            stepSize: 1,
            callback: function (value) {
              return Number.isInteger(value) ? value : null;
            },
            precision: 0
          },
          title: {
            display: true,
            text: 'Đơn hàng'
          }
        }
      },
      plugins: {
        legend: { labels: { font: { weight: 'bold' } }},
        tooltip: {
          bodyFont: { weight: 'bold' },
          titleFont: { weight: 'bold' }
        }
      }
    }
  });

  const ctx2 = document.getElementById('orderStatusChart').getContext('2d');
  const orderStatusChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: {!! json_encode($orderStatus->pluck('status')) !!},
      datasets: [{
        label: 'Trạng thái đơn hàng',
        data: {!! json_encode($orderStatus->pluck('count')) !!},
        backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545'],
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

@extends('Admin.Layouts.AdminLayout')

@section('title', 'Báo cáo lượt sử dụng mã giảm giá')

@section('main')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm border-start border-4" style="border-color: #41BFBF;">
            <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Danh sách mã</a></li>
            <li class="breadcrumb-item active" aria-current="page">Báo cáo</li>
        </ol>
    </nav>
    <div class="card shadow-sm border-0">
        <div class="card-header text-white" style="background-color: #41BFBF;">
            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Báo cáo lượt sử dụng mã giảm giá</h5>
        </div>
        <div class="card-body px-4 py-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã giảm giá</th>
                            <th>Người dùng</th>
                            <th>Mã đơn hàng</th>
                            <th>Thời gian sử dụng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usages as $usage)
                        <tr>
                            <td>{{ $usage->discount->code }}</td>
                            <td>{{ $usage->user->name ?? 'Khách' }}</td>
                            <td>{{ $usage->order_code ?? 'N/A' }}</td>
                            <td>{{ $usage->used_at ? \Carbon\Carbon::parse($usage->used_at)->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Hiển thị {{ $usages->firstItem() ?? 0 }} - {{ $usages->lastItem() ?? 0 }} trên tổng số {{ $usages->total() ?? 0 }} lượt sử dụng
            </div>
            <div>
                {{ $usages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

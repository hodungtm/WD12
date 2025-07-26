@extends('Admin.layouts.AdminLayout')
@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        
        <div class="flex items-center flex-wrap justify-between gap20 mb-30">
            <h3>Danh sách đơn hàng</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Đơn hàng</div></li>
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
        <div class="wg-box">
            <div class="title-box">
                <i class="icon-book-open"></i>
                <div class="body-text">Tìm kiếm đơn hàng theo mã hoặc tên khách hàng.</div>
            </div>
            <div class="flex flex-column gap10 mb-3">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="form-search w-100" style="margin-bottom: 10px;">
                    <fieldset class="name" style="width: 100%;">
                        <input type="text" placeholder="Tìm theo mã đơn hàng..." name="search" value="{{ request('search') }}" style="width: 100%; min-width: 200px;">
                        <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff; position: absolute; right: 5px; top: 50%; transform: translateY(-50%);">
                            <i class="icon-search" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                        </button>
                    </fieldset>
                </form>
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="flex gap10 flex-wrap align-items-center">
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap10 flex-wrap align-items-center" style="margin-bottom: 0;">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="status" class="form-select" style="width: 140px;">
                                <option value="">-- Trạng thái --</option>
                                <option value="Đang chờ" {{ request('status') == 'Đang chờ' ? 'selected' : '' }}>Đang chờ</option>
                                <option value="Hoàn thành" {{ request('status') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="Đã hủy" {{ request('status') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            <select name="payment_method" class="form-select" style="width: 140px;">
                                <option value="">-- Thanh toán --</option>
                                <option value="Tiền mặt" {{ request('payment_method') == 'Tiền mặt' ? 'selected' : '' }}>Tiền mặt</option>
                                <option value="Chuyển khoản ngân hàng" {{ request('payment_method') == 'Chuyển khoản ngân hàng' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                                <option value="Momo" {{ request('payment_method') == 'Momo' ? 'selected' : '' }}>Momo</option>
                                <option value="ZaloPay" {{ request('payment_method') == 'ZaloPay' ? 'selected' : '' }}>ZaloPay</option>
                            </select>
                            <select name="shipping_method" class="form-select" style="width: 140px;">
                                <option value="">-- Vận chuyển --</option>
                                @foreach($shippingMethods as $method)
                                    <option value="{{ $method->id }}" {{ request('shipping_method') == $method->id ? 'selected' : '' }}>{{ $method->name }}</option>
                                @endforeach
                            </select>
                            <select name="sort_created" class="form-select" style="width: 120px;">
                                <option value="">-- Ngày tạo --</option>
                                <option value="desc" {{ request('sort_created') === 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('sort_created') === 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                            <select name="sort_total" class="form-select" style="width: 120px;">
                                <option value="">-- Tổng tiền --</option>
                                <option value="asc" {{ request('sort_total') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                                <option value="desc" {{ request('sort_total') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                            </select>
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff;">
                                <i class="icon-filter" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="wg-table table-product-list mt-3">
                <ul class="table-title flex mb-14" style="gap: 2px;">
                    <li style="flex-basis: 150px;"><div class="body-title">Mã đơn hàng</div></li>
                    <li style="flex-basis: 150px;"><div class="body-title">Khách hàng</div></li>
                    <li style="flex-basis: 120px;"><div class="body-title">Tổng tiền</div></li>
                    <li style="flex-basis: 140px;"><div class="body-title">PT Thanh toán</div></li>
                    <li style="flex-basis: 140px;"><div class="body-title">Vận chuyển</div></li>
                    <li style="flex-basis: 120px;"><div class="body-title">Trạng thái</div></li>
                    <li style="flex-basis: 160px;"><div class="body-title">Hành động</div></li>
                </ul>
                <ul class="flex flex-column">
                    @forelse($orders as $order)
                        <li class="wg-product item-row flex" style="gap: 2px; align-items: center;">
                            <div class="body-text mt-4" style="flex-basis: 150px;">{{ $order->order_code }}</div>
                            <div class="body-text mt-4" style="flex-basis: 150px;">{{ $order->receiver->name ?? $order->user->name ?? 'N/A' }}</div>
                            <div class="body-text mt-4" style="flex-basis: 120px;">{{ number_format($order->final_amount ?? 0, 0, ',', '.') }}₫</div>
                            <div class="body-text mt-4" style="flex-basis: 140px;">
                                @switch($order->payment_method)
                                    @case('Tiền mặt') Tiền mặt @break
                                    @case('Chuyển khoản ngân hàng') Chuyển khoản ngân hàng @break
                                    @case('Momo') Momo @break
                                    @case('ZaloPay') ZaloPay @break
                                    @default {{ ucfirst($order->payment_method) }}
                                @endswitch
                            </div>
                            <div class="body-text mt-4" style="flex-basis: 140px;">{{ $order->shippingMethod->name ?? '---' }}</div>
                            <div style="flex-basis: 120px;">
                                <div class="{{ $order->status === 'Hoàn thành' ? 'block-available' : ($order->status === 'Đang chờ' ? 'block-stock' : ($order->status === 'Đã hủy' ? 'block-stock' : 'block-available')) }} bg-1 fw-7" style="display: inline-block; min-width: 80px; text-align: center; border-radius: 8px; padding: 6px 18px; font-size: 15px; font-weight: 600; background: #f3f7f6; color: {{ $order->status === 'Hoàn thành' ? '#1abc9c' : ($order->status === 'Đang chờ' ? '#e67e22' : ($order->status === 'Đã hủy' ? '#e74c3c' : '#1abc9c')) }}; letter-spacing: 0.5px; vertical-align: middle; margin-top: 2px;">
                                    {{ $order->status }}
                                </div>
                            </div>
                            <div class="list-icon-function" style="flex-basis: 160px;">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="item eye"><i class="icon-eye"></i></a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="item edit"><i class="icon-edit-3"></i></a>
                                
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-muted py-3">Không tìm thấy đơn hàng nào.</li>
                    @endforelse
                </ul>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10">
                <div class="text-tiny">Hiển thị từ {{ $orders->firstItem() }} đến {{ $orders->lastItem() }} trong tổng số {{ $orders->total() }} đơn hàng</div>
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
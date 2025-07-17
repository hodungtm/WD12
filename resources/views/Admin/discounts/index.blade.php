@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Danh sách mã giảm giá</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">Mã giảm giá</div></li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="title-box">
                    <i class="icon-percent"></i>
                    <div class="body-text">Tìm kiếm mã giảm giá theo mã hoặc lọc theo loại, trạng thái.</div>
                </div>
                <div class="flex flex-column gap10 mb-3">
                    <form method="GET" action="{{ route('admin.discounts.index') }}" class="form-search w-100" style="margin-bottom: 10px;">
                        <div class="search-input" style="width: 100%; position: relative;">
                            <input type="text" placeholder="Tìm kiếm mã giảm giá..." name="search" value="{{ request('search') }}" style="width: 100%; min-width: 200px;">
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff; position: absolute; right: 5px; top: 50%; transform: translateY(-50%);">
                                <i class="icon-search" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </div>
                    </form>
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <form method="GET" action="{{ route('admin.discounts.index') }}" class="flex gap10 flex-wrap align-items-center" style="margin-bottom: 0;">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="type" class="form-select" style="width: 140px;">
                                <option value="">-- Loại mã --</option>
                                <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
                                <option value="shipping" {{ request('type') == 'shipping' ? 'selected' : '' }}>Vận chuyển</option>
                                <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
                            </select>
                            <select name="status" class="form-select" style="width: 120px;">
                                <option value="">-- Trạng thái --</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                            <select name="per_page" class="form-select" style="width: 110px;">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 dòng</option>
                                <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 dòng</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 dòng</option>
                                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 dòng</option>
                            </select>
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff;">
                                <i class="icon-filter" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </form>
                        <div class="flex gap10">
                            <a href="{{ route('admin.discounts.create') }}" class="tf-button style-1 w200">
                                <i class="icon-plus"></i> Thêm mã giảm giá
                            </a>
                        </div>
                    </div>
                </div>
                <div class="wg-table table-product-list mt-3">
                    <ul class="table-title flex mb-14" style="gap: 2px;">
                        <li style="flex-basis: 60px; text-align:center;">#</li>
                        <li style="flex-basis: 120px; text-align:center;">Mã</li>
                        <li style="flex-basis: 200px; text-align:center;">Mô tả</li>
                        <li style="flex-basis: 100px; text-align:center;">Giảm</li>
                        <li style="flex-basis: 120px; text-align:center;">Bắt đầu</li>
                        <li style="flex-basis: 120px; text-align:center;">Kết thúc</li>
                        <li style="flex-basis: 100px; text-align:center;">Tối đa</li>
                        <li style="flex-basis: 120px; text-align:center;">Đơn tối thiểu</li>
                        <li style="flex-basis: 120px; text-align:center;">Tối đa giảm</li>
                        <li style="flex-basis: 100px; text-align:center;">Loại</li>
                        <li style="flex-basis: 140px; text-align:center;">Hành động</li>
                    </ul>
                    <ul class="flex flex-column" id="discount-list">
                        @forelse ($discounts as $discount)
                        <li class="wg-product item-row" style="gap: 2px; align-items:center; border-bottom:1px solid #f0f0f0;">
                            <div class="body-text mt-4" style="flex-basis: 60px; text-align:center;">{{ $loop->iteration }}</div>
                            <div class="body-text mt-4" style="flex-basis: 120px; text-align:center;">{{ $discount->code }}</div>
                            <div class="body-text mt-4" style="flex-basis: 200px; text-align:center;">{{ $discount->description }}</div>
                            <div class="body-text mt-4" style="flex-basis: 100px; text-align:center;">
                                @if ($discount->discount_amount)
                                    {{ number_format($discount->discount_amount) }} VNĐ
                                @else
                                    {{ $discount->discount_percent }}%
                                @endif
                            </div>
                            <div class="body-text mt-4" style="flex-basis: 120px; text-align:center;">{{ $discount->start_date }}</div>
                            <div class="body-text mt-4" style="flex-basis: 120px; text-align:center;">{{ $discount->end_date }}</div>
                            <div class="body-text mt-4" style="flex-basis: 100px; text-align:center;">{{ $discount->max_usage }}</div>
                            <div class="body-text mt-4" style="flex-basis: 120px; text-align:center;">{{ number_format($discount->min_order_amount) }} VNĐ</div>
                            <div class="body-text mt-4" style="flex-basis: 120px; text-align:center;">
                                @if ($discount->max_discount_amount)
                                    {{ number_format($discount->max_discount_amount) }} VNĐ
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </div>
                            <div style="flex-basis: 100px; text-align:center;">
                                @switch($discount->type)
                                    @case('order') <span class="badge-status badge-instock">Đơn</span> @break
                                    @case('shipping') <span class="badge-status badge-outstock">Ship</span> @break
                                    @case('product') <span class="badge-status badge-instock">Sản phẩm</span> @break
                                    @default <span class="badge-status badge-outstock">Khác</span>
                                @endswitch
                            </div>
                            <div class="list-icon-function flex justify-center gap10" style="flex-basis: 140px;">
                                <a href="{{ route('admin.discounts.show', $discount->id) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
                                <a href="{{ route('admin.discounts.edit', $discount) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
                                <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mã này?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: red" title="Xóa mã giảm giá">
                                        <i class="icon-trash" style="color: red; font-size: 20px;"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-muted py-3">Chưa có mã giảm giá nào.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10" id="pagination-links">
                    <div class="text-tiny">Hiển thị từ {{ $discounts->firstItem() }} đến {{ $discounts->lastItem() }} trong tổng số {{ $discounts->total() }} mã giảm giá</div>
                    <div class="ajax-pagination">{!! $discounts->appends(request()->query())->links('pagination::bootstrap-5') !!}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

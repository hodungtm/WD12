@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Danh sách mã giảm giá</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#">
                        <div class="text-tiny">Dashboard</div>
                    </a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Mã giảm giá</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap mb-3">
                <div class="wg-filter flex-grow">
                    <form method="GET" action="{{ route('admin.discounts.index') }}" class="form-search flex items-center gap10">
                        <fieldset class="name">
                            <input type="text" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="flex gap10">
                    <a href="{{ route('admin.discounts.create') }}" class="tf-button style-1">
                        <i class="icon-plus"></i> Thêm mới
                    </a>
                    <a href="{{ route('admin.discounts.trashed') }}" class="tf-button style-1 ">
                        <i class="icon-trash-2"></i> Thùng rác
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.discounts.bulkDelete') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa các mã đã chọn?');">
                @csrf
                @method('DELETE')
                <div class="wg-table table-all-category mt-3">
                    <ul class="table-title flex mb-14">
                        <li style="width: 3%"><input type="checkbox" id="check_all"></li>
                        <li style="width: 8%">#</li>
                        <li style="width: 10%">Mã</li>
                        <li style="width: 15%">Mô tả</li>
                        <li style="width: 10%">Giảm</li>
                        <li style="width: 10%">Bắt đầu</li>
                        <li style="width: 10%">Kết thúc</li>
                        <li style="width: 10%">Tối đa</li>
                        <li style="width: 10%">Đơn tối thiểu</li>
                        <li style="width: 10%">Tối đa giảm</li>
                        <li style="width: 7%">Loại</li>
                        <li style="width: 7%">Hành động</li>
                    </ul>
                    <ul class="flex flex-column" id="discount-list">
                        @forelse ($discounts as $discount)
                        <li class="product-item flex mb-10">
                            <div style="width: 3%"><input type="checkbox" name="selected_discounts[]" value="{{ $discount->id }}" class="check_item"></div>
                            <div style="width: 8%">{{ $loop->iteration }}</div>
                            <div style="width: 10%">{{ $discount->code }}</div>
                            <div style="width: 15%">{{ $discount->description }}</div>
                            <div style="width: 10%">
                                @if ($discount->discount_amount)
                                    {{ number_format($discount->discount_amount) }} VNĐ
                                @else
                                    {{ $discount->discount_percent }}%
                                @endif
                            </div>
                            <div style="width: 10%">{{ $discount->start_date }}</div>
                            <div style="width: 10%">{{ $discount->end_date }}</div>
                            <div style="width: 10%">{{ $discount->max_usage }}</div>
                            <div style="width: 10%">{{ number_format($discount->min_order_amount) }} VNĐ</div>
                            <div style="width: 10%">
                                @if ($discount->max_discount_amount)
                                    {{ number_format($discount->max_discount_amount) }} VNĐ
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </div>
                            <div style="width: 7%">
                                @switch($discount->type)
                                    @case('order') <span class="badge bg-primary">Đơn</span> @break
                                    @case('shipping') <span class="badge bg-success">Ship</span> @break
                                    @case('product') <span class="badge bg-warning">Sản phẩm</span> @break
                                    @default <span class="badge bg-secondary">Khác</span>
                                @endswitch
                            </div>
                            <div class="col-action list-icon-function" style="width: 7%">
                                <a href="{{ route('admin.discounts.show', $discount->id) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
                                <a href="{{ route('admin.discounts.edit', $discount) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
                                <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa mã này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="item trash" style="background: none; border: none;"><i class="icon-trash-2"></i></button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-muted">Chưa có mã giảm giá nào.</li>
                        @endforelse
                    </ul>
                </div>

                <button type="submit" class="tf-button style-1 mt-3">
                    <i class="icon-trash"></i> Xóa các mã đã chọn
                </button>
            </form>

            <div class="divider mt-3"></div>
            <div class="flex items-center justify-between flex-wrap gap10" id="pagination-links">
                <div class="text-tiny">Tổng: {{ $discounts->total() }} mã giảm giá</div>
                <div class="ajax-pagination">{!! $discounts->links('pagination::bootstrap-5') !!}</div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('check_all').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('.check_item');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.addEventListener('DOMContentLoaded', () => {
        // XÓA ĐOẠN HIỂN THỊ LỖI/THÔNG BÁO Ở ĐÂY
    });

    document.addEventListener("click", function (e) {
        if (e.target.closest('.ajax-pagination a')) {
            e.preventDefault();
            let url = e.target.closest('a').href;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newList = doc.getElementById('discount-list');
                const newPagination = doc.getElementById('pagination-links');

                document.getElementById('discount-list').innerHTML = newList.innerHTML;
                document.getElementById('pagination-links').innerHTML = newPagination.innerHTML;

                window.history.pushState({}, '', url);
            });
        }
    });
</script>
@endsection

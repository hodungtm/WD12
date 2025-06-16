@extends('Admin.layouts.AdminLayout')

@section('main')
<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0">Chỉnh sửa đơn hàng</h5>
        </div>
        <div class="card-body">
            {{-- HIỂN THỊ THÔNG BÁO TỪ SESSION --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Cảnh báo khi đơn hàng đã hoàn thành --}}
            @if ($order->status === 'Hoàn thành')
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle"></i> Đơn hàng này đã được **Hoàn thành** và không thể chỉnh sửa chi tiết. Vui lòng quay lại trang danh sách đơn hàng hoặc xem chi tiết đơn hàng.
                </div>
            @endif

            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Vô hiệu hóa toàn bộ form nếu đơn hàng đã hoàn thành --}}
                <fieldset {{ $order->status === 'Hoàn thành' ? 'disabled' : '' }}>

                    {{-- BẢNG 1: NGƯỜI ĐẶT & NGƯỜI NHẬN --}}
                    <div class="tile">
                        <h3 class="tile-title">Thông tin người đặt và người nhận</h3>
                        <div class="tile-body">
                            <div class="row">
                                {{-- Người đặt (Editable when not completed, otherwise readonly) --}}
                                <div class="form-group col-md-4">
                                    <label>Người đặt</label>
                                    <input class="form-control" type="text" value="{{ $order->user->name ?? '' }} ({{ $order->user->email ?? '' }})" readonly>
                                    <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>SĐT người đặt</label>
                                    <input class="form-control" type="text" value="{{ $order->user->phone ?? '' }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Địa chỉ người đặt</label>
                                    <input class="form-control" type="text" value="{{ $order->user->address ?? '' }}" readonly>
                                </div>

                                {{-- Người nhận (Editable when not completed, otherwise readonly) --}}
                                <div class="form-group col-md-4">
                                    <label>Người nhận</label>
                                    <input class="form-control" type="text" value="{{ $order->receiver->name ?? ($order->user->name ?? '---') }} ({{ $order->receiver->email ?? ($order->user->email ?? '---') }})" readonly>
                                    <input type="hidden" name="receiver_id" value="{{ $order->receiver_id }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>SĐT người nhận</label>
                                    <input class="form-control" type="text" value="{{ $order->receiver->phone ?? ($order->user->phone ?? '') }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Địa chỉ người nhận</label>
                                    <input class="form-control" type="text" value="{{ $order->receiver->address ?? ($order->user->address ?? '') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BẢNG 2: THÔNG TIN ĐƠN HÀNG --}}
                    <div class="tile mt-4">
                        <h3 class="tile-title">Thông tin đơn hàng</h3>
                        <div class="tile-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Mã đơn hàng</label>
                                    <input class="form-control" type="text" value="{{ $order->order_code }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Ngày đặt hàng</label>
                                    <input type="date" class="form-control @error('order_date') is-invalid @enderror" name="order_date" value="{{ old('order_date', $order->order_date) }}" readonly>
                                    @error('order_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Phương thức thanh toán</label>
                                    <input class="form-control" type="text" name="payment_method" value="{{ old('payment_method', $order->payment_method) }}" readonly>
                                    @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Phương thức Vận Chuyển</label>
                                    <input class="form-control" type="text" value="{{ $order->shippingMethod->name ?? '---' }}" readonly>
                                    <input type="hidden" name="shipping_method_id" value="{{ $order->shipping_method_id }}">
                                    <small class="form-text text-muted mt-2">
                                        <strong>Phí vận chuyển:</strong>
                                        <span id="shipping-fee-edit">
                                            {{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫
                                        </span>
                                    </small>
                                   
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Mã giảm giá</label>
                                    <input class="form-control" type="text"
                                        value="{{ $order->discount->code ?? 'Không có' }}" readonly>
                                    <input type="hidden" name="discount_id"
                                        value="{{ old('discount_id', $order->discount_id) }}">
                                  
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tình trạng đơn hàng</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status">
                                        <option value="Đang chờ" {{ old('status', $order->status) === 'Đang chờ' ? 'selected' : '' }}>Đang chờ</option>
                                        <option value="Đang giao hàng" {{ old('status', $order->status) === 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                                        <option value="Hoàn thành" {{ old('status', $order->status) === 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="Đã hủy" {{ old('status', $order->status) === 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tình trạng thanh toán</label>
                                    <select class="form-control @error('payment_status') is-invalid @enderror" name="payment_status">
                                        <option value="Chờ thanh toán" {{ old('payment_status', $order->payment_status) === 'Chờ thanh toán' ? 'selected' : '' }}>Chờ thanh toán</option>
                                        <option value="Đã thanh toán" {{ old('payment_status', $order->payment_status) === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                    </select>
                                    @error('payment_status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-12 mt-3">
                                    <label>Ghi chú</label>
                                    <textarea class="form-control" rows="3" name="note" readonly>{{ old('note', $order->note) }} </textarea>
                                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BẢNG 3: DANH SÁCH SẢN PHẨM (chỉ hiển thị, không chỉnh sửa) --}}
                    <div class="tile mt-4">
                        <h4 class="tile-title">Chi tiết sản phẩm</h4>
                        <div class="tile-body">
                            @php
                                $itemsToDisplay = ($order->status === 'Hoàn thành') ? $order->archivedOrderItems : $order->orderItems;
                                $totalItemsPrice = 0; // Khởi tạo tổng tiền sản phẩm
                            @endphp

                            @if ($order->status === 'Hoàn thành' && $itemsToDisplay->isEmpty())
                                <div class="alert alert-warning">
                                    Đơn hàng đã hoàn thành nhưng không tìm thấy chi tiết sản phẩm được lưu trữ.
                                </div>
                            @elseif ($order->status !== 'Hoàn thành' && $itemsToDisplay->isEmpty())
                                <div class="alert alert-info">
                                    Đơn hàng chưa có sản phẩm nào.
                                </div>
                            @endif

                        <table class="table table-bordered">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên sản phẩm</th>
            <th>Màu sắc</th>
            <th>Size</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalItemsPrice = 0; // Khởi tạo biến tổng tiền sản phẩm
        @endphp

        {{-- Lặp qua các item (có thể là OrderItem hoặc ArchivedOrderItem) --}}
        @foreach ($itemsToDisplay as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    {{-- Lấy tên sản phẩm --}}
                    @if ($item instanceof \App\Models\ArchivedOrderItem)
                        {{ $item->product_name ?? 'N/A' }}
                    @else
                        {{ $item->product->name ?? 'N/A' }}
                    @endif
                </td>
                <td>
                    {{-- Lấy tên màu sắc --}}
                    @if ($item instanceof \App\Models\ArchivedOrderItem)
                        {{ $item->color_name ?? '---' }}
                    @else
                        @php
                            $colorName = '---';
                            if ($item->productVariant && $item->productVariant->attributeValues) {
                                foreach ($item->productVariant->attributeValues as $attrValue) {
                                    if ($attrValue->attribute && (strtolower($attrValue->attribute->name) === 'màu' || strtolower($attrValue->attribute->name) === 'color')) {
                                        $colorName = $attrValue->value;
                                        break; // Tìm thấy màu, thoát vòng lặp
                                    }
                                }
                            }
                        @endphp
                        {{ $colorName }}
                    @endif
                </td>
                <td>
                    {{-- Lấy tên size --}}
                    @if ($item instanceof \App\Models\ArchivedOrderItem)
                        {{ $item->size_name ?? '---' }}
                    @else
                        @php
                            $sizeName = '---';
                            if ($item->productVariant && $item->productVariant->attributeValues) {
                                foreach ($item->productVariant->attributeValues as $attrValue) {
                                    if ($attrValue->attribute && strtolower($attrValue->attribute->name) === 'size') {
                                        $sizeName = $attrValue->value;
                                        break; // Tìm thấy size, thoát vòng lặp
                                    }
                                }
                            }
                        @endphp
                        {{ $sizeName }}
                    @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->final_price ?? $item->price, 0, ',', '.') }}₫</td>
                <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
            </tr>
            @php
                $totalItemsPrice += $item->total_price;
            @endphp
        @endforeach
        <tr>
            <td colspan="6" class="text-end"><strong>Tổng tiền sản phẩm:</strong></td>
            <td><strong>{{ number_format($totalItemsPrice, 0, ',', '.') }}₫</strong></td>
        </tr>
        <tr>
            <td colspan="6" class="text-end"><strong>Phí vận chuyển:</strong></td>
            <td><strong>{{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫</strong></td>
        </tr>
        @if (($order->discount_amount ?? 0) > 0)
            <tr>
                <td colspan="6" class="text-end"><strong>Giảm giá:</strong></td>
                <td><strong>- {{ number_format($order->discount_amount, 0, ',', '.') }}₫</strong></td>
            </tr>
        @endif
        <tr>
            <td colspan="6" class="text-end"><strong>Tổng thanh toán:</strong></td>
            <td><strong>{{ number_format($order->final_amount, 0, ',', '.') }}₫</strong></td>
        </tr>
    </tbody>
</table>
                        </div>
                    </div>

                </fieldset> {{-- Kết thúc fieldset disabled --}}

                {{-- Nút --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning" {{ $order->status === 'Hoàn thành' ? 'disabled' : '' }}>Cập nhật</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-dark">Trở lại</a>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info">Xem chi tiết</a>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($order->status !== 'Hoàn thành')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // shippingMethodSelect không còn là dropdown trong form này, nên script này có thể không cần thiết nếu bạn chỉ hiển thị readonly.
        // Nếu bạn có ý định thêm lại dropdown select cho shipping method, hãy đảm bảo ID là 'shipping-method-edit'
        const shippingMethodSelect = document.getElementById('shipping-method-select'); // Changed ID for clarity if you were to re-introduce a select dropdown
        const shippingFeeDisplay = document.getElementById('shipping-fee-edit');

        if (shippingMethodSelect) { // Sẽ không chạy nếu shippingMethodSelect là null
            shippingMethodSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const fee = parseFloat(selected.getAttribute('data-fee')) || 0;
                shippingFeeDisplay.innerText = fee.toLocaleString('vi-VN') + '₫';
            });

            const initialSelected = shippingMethodSelect.options[shippingMethodSelect.selectedIndex];
            const initialFee = parseFloat(initialSelected.getAttribute('data-fee')) || 0;
            shippingFeeDisplay.innerText = initialFee.toLocaleString('vi-VN') + '₫';
        } else {
            // Log hoặc thông báo nếu element không tìm thấy, để debug
            console.warn("Element with ID 'shipping-method-select' not found. Shipping fee script may not function.");
        }
    });
</script>
@endif

@endsection
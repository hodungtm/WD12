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
                        <i class="fa fa-info-circle"></i> Đơn hàng này đã được <strong>Hoàn thành</strong> và không thể chỉnh
                        sửa chi tiết.
                    </div>
                @endif

                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <fieldset {{ $order->status === 'Hoàn thành' ? 'disabled' : '' }}>
                        {{-- NGƯỜI ĐẶT & NGƯỜI NHẬN --}}
                        <div class="tile">
                            <h3 class="tile-title">Thông tin người đặt và người nhận</h3>
                            <div class="tile-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Người đặt</label>
                                        <input class="form-control" type="text" value="{{ $order->user->name ?? '' }}"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>SĐT người đặt</label>
                                        <input class="form-control" type="text" value="{{ $order->user->phone ?? '' }}"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Địa chỉ người đặt</label>
                                        <input class="form-control" type="text" value="{{ $order->user->address ?? '' }}"
                                            readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Người nhận</label>
                                        <input class="form-control" type="text"
                                            value="{{ $order->receiver_name ?? ($order->user->name ?? '---') }}" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>SĐT người nhận</label>
                                        <input class="form-control" type="text"
                                            value="{{ $order->receiver_phone ?? ($order->user->phone ?? '') }}" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Địa chỉ người nhận</label>
                                        <input class="form-control" type="text"
                                            value="{{ $order->receiver_address ?? ($order->user->address ?? '') }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- THÔNG TIN ĐƠN HÀNG --}}
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
                        <input class="form-control" type="text" value="{{ $order->order_date }}" readonly>
                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Phương thức thanh toán</label>
                                        <input class="form-control" type="text" name="payment_method"
                                            value="{{ old('payment_method', $order->payment_method) }}" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Phương thức Vận Chuyển</label>
                                        <input class="form-control" type="text"
                                            value="{{ $order->shippingMethod->name ?? '---' }}" readonly>
                                        <input type="hidden" name="shipping_method_id"
                                            value="{{ $order->shipping_method_id }}">
                                        <small class="form-text text-muted mt-2">
                                            <strong>Phí vận chuyển:</strong>
                                            {{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫
                                        </small>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Mã giảm giá</label>
                                        <input class="form-control" type="text"
                                            value="{{ $order->discount_code ?? 'Không có' }}" readonly>
                                        
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
                                        <select class="form-control @error('payment_status') is-invalid @enderror"
                                            name="payment_status">
                                            <option value="Chờ thanh toán" {{ old('payment_status', $order->payment_status) === 'Chờ thanh toán' ? 'selected' : '' }}>Chờ thanh
                                                toán</option>
                                            <option value="Đã thanh toán" {{ old('payment_status', $order->payment_status) === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán
                                            </option>
                                        </select>
                                        @error('payment_status') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-md-12 mt-3">
                                        <label>Ghi chú</label>
                                        <textarea class="form-control" rows="3" name="note"
                                            readonly>{{ old('note', $order->note) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- DANH SÁCH SẢN PHẨM --}}
                        <div class="tile mt-4">
                            <h4 class="tile-title">Chi tiết sản phẩm</h4>
                            <div class="tile-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Sản phẩm</th>
                                            <th>Size</th>
                                            <th>Màu sắc</th>
                                            <th>Số lượng</th>
                                            <th>Đơn giá</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalItemsPrice = 0; @endphp
                                        @foreach ($order->orderItems as $index => $item)
                                            @php
                                                $color = '---';
                                                $size = '---';
                                                if (!empty($item->variant_name)) {
                                                    $parts = preg_split('/\s*[-\/]\s*/', $item->variant_name);
                                                    $size = $parts[0] ?? '---';
                                                    $color = $parts[1] ?? '---';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product_image)
                                                            <img src="{{ asset('storage/' . $item->product_image) }}" alt="Ảnh"
                                                                width="60" height="60" class="me-2 rounded border">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $item->product_name ?? '---' }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $size }}</td>
                                                <td>{{ $color }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->final_price ?? $item->price, 0, ',', '.') }}₫</td>
                                                <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
                                            </tr>
                                            @php $totalItemsPrice += $item->total_price; @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="6" class="text-end"><strong>Tổng tiền sản phẩm:</strong></td>
                                            <td><strong>{{ number_format($totalItemsPrice, 0, ',', '.') }}₫</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                            <td><strong>{{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫</strong>
                                            </td>
                                        </tr>
                                        @if (($order->discount_amount ?? 0) > 0)
                                            <tr>
                                                <td colspan="6" class="text-end"><strong>Giảm giá:</strong></td>
                                                <td><strong>-
                                                        {{ number_format($order->discount_amount, 0, ',', '.') }}₫</strong></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="6" class="text-end"><strong>Tổng thanh toán:</strong></td>
                                            <td><strong>{{ number_format($order->final_amount, 0, ',', '.') }}₫</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-warning" {{ $order->status === 'Hoàn thành' ? 'disabled' : '' }}>Cập nhật</button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-dark">Trở lại</a>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info">Xem chi tiết</a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection
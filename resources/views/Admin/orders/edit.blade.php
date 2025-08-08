@extends('admin.layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chỉnh sửa đơn hàng</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.orders.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.orders.index') }}">
                            <div class="text-tiny">Đơn hàng</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chỉnh sửa đơn hàng</div>
                    </li>
                </ul>
            </div>
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
            @if ($order->status === 'Hoàn thành')
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle"></i> Đơn hàng này đã được <strong>Hoàn thành</strong> và không thể chỉnh sửa chi tiết.
                </div>
            @endif
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="wg-box mb-30">
                    <fieldset>
                        <div class="body-title mb-10">Thông tin người đặt và người nhận</div>
                        <div class="flex flex-wrap gap20">
                            <div class="form-group" style="min-width: 220px;">
                                <label>Người đặt</label>
                                <input class="form-control" type="text" value="{{ $order->user->name ?? '' }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>SĐT người đặt</label>
                                <input class="form-control" type="text" value="{{ $order->user->phone ?? '' }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Địa chỉ người đặt</label>
                                <input class="form-control" type="text" value="{{ $order->user->address ?? '' }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Người nhận</label>
                                <input class="form-control" type="text" value="{{ $order->receiver_name ?? ($order->user->name ?? '---') }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>SĐT người nhận</label>
                                <input class="form-control" type="text" value="{{ $order->receiver_phone ?? ($order->user->phone ?? '') }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Địa chỉ người nhận</label>
                                <input class="form-control" type="text" value="{{ $order->receiver_address ?? ($order->user->address ?? '') }}" readonly>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="wg-box mb-30">
                    <fieldset>
                        <div class="body-title mb-10">Thông tin đơn hàng</div>
                        <div class="flex flex-wrap gap20">
                            <div class="form-group" style="min-width: 220px;">
                                <label>Mã đơn hàng</label>
                                <input class="form-control" type="text" value="{{ $order->order_code }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Ngày đặt hàng</label>
                                <input class="form-control" type="text" value="{{ $order->order_date }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Phương thức thanh toán</label>
                                <input class="form-control" type="text" name="payment_method" value="{{ old('payment_method', $order->payment_method) }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Phương thức vận chuyển</label>
                                <input class="form-control" type="text" value="{{ $order->shippingMethod->name ?? '---' }}" readonly>
                                <input type="hidden" name="shipping_method_id" value="{{ $order->shipping_method_id }}">
                                <small class="form-text text-muted mt-2">
                                    <strong>Phí vận chuyển:</strong> {{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫
                                </small>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Mã giảm giá</label>
                                <input class="form-control" type="text" value="{{ $order->discount_code ?? 'Không có' }}" readonly>
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Tình trạng đơn hàng</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status">
                                    <option value="Đang chờ" {{ old('status', $order->status) === 'Đang chờ' ? 'selected' : '' }}>Đang chờ</option>
                                    <option value="Xác nhận đơn" {{ old('status', $order->status) === 'Xác nhận đơn' ? 'selected' : '' }}>Xác nhận đơn hàng</option>
                                    <option value="Đang giao hàng" {{ old('status', $order->status) === 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                                    <option value="Hoàn thành" {{ old('status', $order->status) === 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="Đã hủy" {{ old('status', $order->status) === 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group" style="min-width: 220px;">
                                <label>Tình trạng thanh toán</label>
                                <select class="form-control @error('payment_status') is-invalid @enderror" name="payment_status">
                                    <option value="Chờ thanh toán" {{ old('payment_status', $order->payment_status) === 'Chờ thanh toán' ? 'selected' : '' }}>Chờ thanh toán</option>
                                    <option value="Đã thanh toán" {{ old('payment_status', $order->payment_status) === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                </select>
                                @error('payment_status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group w-full mt-3">
                                <label>Ghi chú</label>
                                <textarea class="form-control" rows="3" name="note" readonly>{{ old('note', $order->note) }}</textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="wg-box mb-30">
                    <div class="body-title mb-10">Chi tiết sản phẩm</div>
                    <div class="wg-table">
                        <table class="table-product-list">
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
                                        $size = '---';
                                        $color = '---';
                                        if (!empty($item->variant_name)) {
                                            $parts = preg_split('/\s*[-\/]\s*/', $item->variant_name);
                                            $size = $parts[1] ?? '---';
                                            $color = $parts[0] ?? '---';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product_image)
                                                    <img src="{{ asset('storage/' . $item->product_image) }}" alt="Ảnh" width="60" height="60" class="me-2 rounded border">
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
                                    <td><strong>{{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫</strong></td>
                                </tr>
                                @if (($order->discount_amount ?? 0) > 0)
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Giảm giá:</strong></td>
                                        <td><strong>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</strong></td>
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
                <div class="flex justify-start gap-3 mt-4">
                    <button type="submit" class="tf-button btn-sm w-auto px-3 py-2" {{ $order->status === 'Hoàn thành' ? 'disabled' : '' }}>
                        <i class="icon-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                        <i class="icon-x"></i> Trở lại
                    </a>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="tf-button style-1 btn-sm w-auto px-3 py-2">
                        <i class="icon-eye"></i> Xem chi tiết
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
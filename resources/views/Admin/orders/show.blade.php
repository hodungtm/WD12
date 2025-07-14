@extends('admin.layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chi tiết đơn hàng: {{ $order->order_code }}</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.orders.index') }}">
                            <div class="text-tiny">Đơn hàng</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chi tiết</div>
                    </li>
                </ul>
            </div>
            {{-- Box: Thông tin người đặt & người nhận --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-user"></i>
                    <div class="body-text">Thông tin người đặt & người nhận</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Người đặt:</label>
                        <p>{{ $order->user->name ?? 'Không rõ' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">SĐT người đặt:</label>
                        <p>{{ $order->user->phone ?? 'Không có' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Địa chỉ người đặt:</label>
                        <p>{{ $order->user->address ?? 'Không có' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Người nhận:</label>
                        <p>{{ $order->receiver_name ?? 'Trùng người đặt' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">SĐT người nhận:</label>
                        <p>{{ $order->receiver_phone ?? 'Không có' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Địa chỉ người nhận:</label>
                        <p>{{ $order->receiver_address ?? 'Không có' }}</p>
                    </div>
                </div>
            </div>
            {{-- Box: Thông tin đơn hàng --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-file-text"></i>
                    <div class="body-text">Thông tin đơn hàng</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Mã đơn hàng:</label>
                        <p>{{ $order->order_code }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Ngày đặt hàng:</label>
                        <p>{{ $order->order_date }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Phương thức thanh toán:</label>
                        <p>{{ $order->payment_method }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Phương thức vận chuyển:</label>
                        <p>{{ optional($order->shippingMethod)->name ?? 'Không có' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Mã giảm giá:</label>
                        <p>{{ $order->discount_code ?? 'Không có' }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Tình trạng đơn hàng:</label>
                        <p>{{ $order->status }}</p>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Tình trạng thanh toán:</label>
                        <p>{{ $order->payment_status }}</p>
                    </div>
                    <div class="w-full">
                        <label class="body-title">Ghi chú:</label>
                        <p>{{ $order->note ?? 'Không có' }}</p>
                    </div>
                </div>
            </div>
            {{-- Box: Danh sách sản phẩm --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-package"></i>
                    <div class="body-text">Danh sách sản phẩm</div>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
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
                                    $productName = $item->product_name ?? 'Sản phẩm đã xoá';
                                    $variantName = $item->variant_name ?? '';
                                    $variantImage = $item->product_image ?? null;
                                    $color = '---';
                                    $size = '---';
                                    if (!empty($variantName)) {
                                        $parts = preg_split('/\s*[-\/]\s*/', $variantName);
                                        $size = $parts[0] ?? '---';
                                        $color = $parts[1] ?? '---';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($variantImage)
                                                <img src="{{ asset('storage/' . $variantImage) }}" alt="Ảnh" width="60" height="60" class="me-2 rounded border">
                                            @endif
                                            <div>
                                                <div><strong>{{ $productName }}</strong></div>
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
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Box: Tổng tiền & thanh toán --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-credit-card"></i>
                    <div class="body-text">Tổng tiền & thanh toán</div>
                </div>
                <div class="flex flex-col gap10">
                    <div><strong>Tổng tiền sản phẩm:</strong> {{ number_format($totalItemsPrice, 0, ',', '.') }}₫</div>
                    <div><strong>Phí vận chuyển:</strong> {{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫</div>
                    @if (($order->discount_amount ?? 0) > 0)
                        <div><strong>Giảm giá:</strong> -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</div>
                    @endif
                    <div><strong>Tổng thanh toán:</strong> <span class="text-success">{{ number_format($order->final_amount, 0, ',', '.') }}₫</span></div>
                </div>
            </div>
            <div class="flex gap10 mt-4">
                <a href="{{ route('admin.orders.edit', $order->id) }}" class="tf-button">
                    <i class="icon-edit-3 me-1"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.orders.index') }}" class="tf-button style-1">
                    <i class="icon-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
@endsection
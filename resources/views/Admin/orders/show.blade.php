@extends('admin.layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="row">
                <div class="col-12">
                    <div class="card" id="demo">
                        <div class="row">
                            <div class="col-lg-12">
                                
                                <div class="card-header border-bottom-dashed p-4">
                                    <div class="card-body bg-light p-4 ribbon-box position-relative">
                                       

                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <div>
                                                    <span style="font-size: 1.2em; font-weight: bold;">Mã đơn hàng:</span>
                                                    <span style="font-size: 1.2em; color: red; font-weight: bold;">{{ $order->order_code }}</span>
                                                </div>
                                                <div style="font-size: 1.1em; margin-top: 10px;">
                                                    Ngày tạo: {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Main Content --}}
                            <div class="ps-4 pe-4">
                                <div class="row">
                                    {{-- Products Section --}}
                                    <div class="col-md-8">
                                        <div class="card mb-3 bg-light">
                                            <div class="card-body">
                                                <h4 style="font-weight: bold;">Thông tin sản phẩm</h4>
                                                
                                                @foreach ($order->orderItems as $item)
                                                    @php
                                                        $productName = $item->product_name ?? 'Sản phẩm đã xoá';
                                                        $variantName = $item->variant_name ?? '';
                                                        $variantImage = $item->product_image ?? null;
                                                        $color = '---';
                                                        $size = '---';
                                                        if (!empty($variantName)) {
                                                            $parts = preg_split('/\s*[-\/]\s*/', $variantName);
                                                            $size = $parts[1] ?? '---';
                                                            $color = $parts[0] ?? '---';
                                                        }
                                                    @endphp
                                                    
                                                    <div class="row g-0 {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                                                        <div class="col-sm-5">
                                                            @if($variantImage)
                                                                <img src="{{ asset('storage/' . $variantImage) }}" 
                                                                     alt="Ảnh Sản Phẩm" 
                                                                     class="img-fluid" 
                                                                     style="max-width: 100%; max-height: 150px; object-fit: contain; border-radius: 10px;">
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                                     style="height: 150px;">
                                                                    <i class="icon-image text-muted" style="font-size: 3rem;"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <h4>{{ $productName }}</h4>
                                                            
                                                            @if($size !== '---')
                                                                <p class="no-dots" style="font-size: 15px;">
                                                                    Kích thước: <span style="color: #007bff;">{{ $size }}</span>
                                                                </p>
                                                            @endif
                                                            
                                                            @if($color !== '---')
                                                                <p class="no-dots" style="font-size: 15px;">
                                                                    Màu sắc: <span style="padding: 5px;">{{ $color }}</span>
                                                                </p>
                                                            @endif
                                                            
                                                            <p class="no-dots" style="font-size: 15px;">
                                                                Số lượng: {{ $item->quantity }}
                                                            </p>
                                                            
                                                            <p class="no-dots" style="font-size: 15px;">
                                                                Đơn giá: {{ number_format($item->final_price ?? $item->price, 0, ',', '.') }} VNĐ
                                                            </p>
                                                            
                                                            <hr>
                                                            
                                                            <p style="font-size: 15px; font-weight: bold;">
                                                                Thành tiền: <span style="padding: 5px; color: red;">
                                                                    {{ number_format($item->total_price, 0, ',', '.') }} VNĐ
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Customer Information --}}
                                    <div class="col-md-4">
                                        <div class="card mb-3 bg-light">
                                            <div class="card-body">
                                                <h4 style="font-weight: bold;">Thông tin khách hàng</h4>
                                                
                                                <div style="display: flex; align-items: center; margin-bottom: 20px">
                                                    <div style="width: 40px; height: 40px; border-radius: 10%; margin-right: 10px; background-color: #007bff; display: flex; align-items: center; justify-content: center; color: white;">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <p style="font-size: 15px; margin: 0;">
                                                        {{ $order->user->name ?? 'Không rõ' }}<br>
                                                        <span style="font-size: 80%; color: #66696b;">Khách hàng</span>
                                                    </p>
                                                </div>

                                                <p style="font-size: 15px;"><span style="font-weight: bold; color: #0056b3;">Giới tính:</span><br>
                                                    {{ $order->user->gender ?? 'Không có' }}</p>
                                                    
                                                <p style="font-size: 15px;"><span style="font-weight: bold; color: #0056b3;">Địa chỉ:</span><br>
                                                    {{ $order->user->address ?? 'Không có' }}</p>
                                                    
                                                <p style="font-size: 15px;"><span style="font-weight: bold; color: #0056b3;">Số điện thoại:</span><br>
                                                    {{ $order->user->phone ?? 'Không có' }}</p>
                                                    
                                                <p style="font-size: 15px;"><span style="font-weight: bold; color: #0056b3;">Email:</span><br>
                                                    {{ $order->user->email ?? 'Không có' }}</p>
                                            </div>
                                        </div>

                                        {{-- Shipping Information --}}
                                        <div class="card mb-3 bg-light">
                                            <div class="card-body">
                                                <h4 style="font-weight: bold;">Thông tin giao hàng</h4>

                                                <p style="font-size: 15px;"><span style="font-weight: bold; color: #0056b3;">Người nhận:</span><br>
                                                    {{ $order->receiver_name ?? 'Trùng người đặt' }}</p>

                                                <p style="font-size: 15px;"><span style="font-weight: bold; color: #0056b3;">Địa chỉ giao hàng:</span><br>
                                                    {{ $order->receiver_address ?? 'Không có' }}</p>

                                                <p style="font-size: 15px;"><span style="font-weight: bold; color: #0056b3;">SĐT người nhận:</span><br>
                                                    {{ $order->receiver_phone ?? 'Không có' }}</p>

                                                <p style="font-size: 15px;"><span style="font-weight: bold; color: #0056b3;">Phương thức vận chuyển:</span><br>
                                                    {{ optional($order->shippingMethod)->name ?? 'Không có' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Order Details - Moved to full width --}}
                                    <div class="col-12">
                                        <div class="card mb-3 bg-light">
                                            <div class="card-body">
                                                <h4 style="font-weight: bold;">Chi tiết đơn hàng</h4>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p style="font-size: 15px; font-weight: bold;">Phương thức thanh toán: <span style="padding: 5px;">{{ $order->payment_method }}</span></p>
                                                        <p style="font-size: 15px; font-weight: bold;">Trạng thái thanh toán: <span style="padding: 5px;">{{ $order->payment_status }}</span></p>
                                                        
                                                        {{-- Thêm trạng thái đơn hàng --}}
                                                        <p style="font-size: 15px; font-weight: bold;">
                                                            Trạng thái đơn hàng: 
                                                            <span >
                                                                {{ $order->status }}
                                                            </span>
                                                        </p>

                                                        @if($order->discount_code)
                                                            <p style="font-size: 15px; font-weight: bold;">Mã giảm giá: <span style="padding: 5px;">{{ $order->discount_code }}</span></p>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-6">
                                                        @php $totalItemsPrice = $order->orderItems->sum('total_price'); @endphp
                                                        <p style="font-size: 15px;">Tổng tiền sản phẩm: {{ number_format($totalItemsPrice, 0, ',', '.') }} VNĐ</p>
                                                        <p style="font-size: 15px;">Phí vận chuyển: {{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }} VNĐ</p>
                                                        @if (($order->discount_amount ?? 0) > 0)
                                                            <p style="font-size: 15px;">Giảm giá: -{{ number_format($order->discount_amount, 0, ',', '.') }} VNĐ</p>
                                                        @endif
                                                        <p style="font-size: 15px; font-weight: bold;">
                                                            Số tiền thanh toán: <span style="padding: 5px; color: red;">
                                                                {{ number_format($order->final_amount, 0, ',', '.') }} VNĐ
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>

                                                @if($order->note)
                                                    <hr>
                                                    <p style="font-size: 15px;"><span style="font-weight: bold;">Ghi chú:</span><br>
                                                        {{ $order->note }}</p>
                                                @endif

                                                @if($order->cancel_reason)
                                                    <p style="font-size: 15px;"><span style="font-weight: bold; color: red;">Lý do hủy:</span><br>
                                                        {{ $order->cancel_reason }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                              
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
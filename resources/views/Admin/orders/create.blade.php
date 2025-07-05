@extends('admin.layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tạo đơn hàng mới</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="#"><div class="text-tiny">Đơn hàng</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Tạo mới</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="title-box">
                <i class="icon-cart"></i>
                <div class="body-text">Điền thông tin chi tiết đơn hàng</div>
            </div>

            <form method="POST" action="{{ route('admin.orders.store') }}">
                @csrf

                <div class="flex flex-wrap gap20">
                    <!-- Người đặt -->
                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Người đặt</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- Chọn người đặt --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Ngày tạo đơn</label>
                        <input type="date" class="form-control" name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}">
                        @error('order_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Thông tin người nhận -->
                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Tên người nhận</label>
                        <input type="text" class="form-control" name="receiver_name" value="{{ old('receiver_name') }}">
                        @error('receiver_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="receiver_phone" value="{{ old('receiver_phone') }}">
                        @error('receiver_phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="receiver_email" value="{{ old('receiver_email') }}">
                        @error('receiver_email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group w-full">
                        <label class="form-label">Địa chỉ</label>
                        <textarea class="form-control" name="receiver_address" rows="2">{{ old('receiver_address') }}</textarea>
                        @error('receiver_address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Sản phẩm -->
                    <div class="form-group w-full">
                        <div class="body-title">Danh sách sản phẩm</div>
                        <div id="product-wrapper"></div>
                        <button type="button" id="add-product" class="tf-button style-1 mt-2">+ Thêm sản phẩm</button>
                    </div>

                    <!-- Phương thức thanh toán & vận chuyển -->
                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Phương thức thanh toán</label>
                        <select class="form-control" name="payment_method">
                            <option value="Tiền mặt">Tiền mặt</option>
                            <option value="Chuyển khoản ngân hàng">Chuyển khoản ngân hàng</option>
                            <option value="Momo">Momo</option>
                            <option value="ZaloPay">ZaloPay</option>
                        </select>
                    </div>


                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Phương thức vận chuyển</label>
                        <select name="shipping_method_id" id="shipping-method" class="form-control">
                            <option value="">-- Chọn phương thức --</option>
                            @foreach($shippingMethods as $method)
                                <option value="{{ $method->id }}" data-fee="{{ $method->fee }}">
                                    {{ $method->name }} ({{ number_format($method->fee) }}đ)
                                </option>
                            @endforeach
                        </select>
                        <small><strong>Phí vận chuyển: </strong><span id="shipping-fee">0đ</span></small>
                    </div>

                    <div class="form-group w-full">
                        <label class="form-label">Mã khuyến mãi</label>
                        <select name="discount_code" class="form-control">
                            <option value="">-- Không áp dụng --</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->code }}">
                                    {{ $discount->code }} - 
                                    @if($discount->discount_percent > 0)
                                        Giảm {{ $discount->discount_percent }}%
                                    @else
                                        Giảm {{ number_format($discount->discount_amount) }}₫
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

            <div class="form-group">
                <label>Phương thức vận chuyển</label>
                <select name="shipping_method_id" id="shipping-method" class="form-control" required>
                    <option value="">-- Chọn phương thức vận chuyển --</option>
                    @foreach($shippingMethods as $method)
                        <option value="{{ $method->id }}" data-fee="{{ $method->fee }}"
                            {{ old('shipping_method_id') == $method->id ? 'selected' : '' }}>
                            {{ $method->name }} ({{ number_format($method->fee, 0, ',', '.') }}đ)
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted mt-2">
                    <strong>Phí vận chuyển:</strong> <span id="shipping-fee">0đ</span>
                </small>
            </div>
<div class="form-group">
    <label for="discount_code">Mã khuyến mãi</label>
    <select name="discount_code" class="form-control @error('discount_code') is-invalid @enderror">
        <option value="">-- Không áp dụng --</option>
        @foreach($discounts as $discount)
            <option value="{{ $discount->code }}" {{ old('discount_code') == $discount->code ? 'selected' : '' }}>
                {{ $discount->code }} - 
                @if($discount->discount_percent > 0)
                    Giảm {{ $discount->discount_percent }}%
                @else
                    Giảm {{ number_format($discount->discount_amount, 0, ',', '.') }}₫
                @endif
            </option>
        @endforeach
    </select>
    @error('discount_code')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>




                    <div class="form-group w-full">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="note" rows="2">{{ old('note') }}</textarea>
                    </div>

                    <div class="form-group w-full text-end mt-3">
                        <button class="tf-button style-1" type="submit">Lưu đơn hàng</button>
                        <a href="{{ route('admin.orders.index') }}" class="tf-button style-2">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




<script>
    function updateVariantInfo(group) {
        const select = group.querySelector('.variant-select');
        const priceDisplay = group.querySelector('.product-price');
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value !== "") {
            const price = selectedOption.getAttribute('data-price');
            priceDisplay.value = Number(price).toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        } else {
            priceDisplay.value = '';
        }
    }

    document.getElementById('shipping-method').addEventListener('change', function () {
        const fee = parseInt(this.selectedOptions[0].getAttribute('data-fee')) || 0;
        document.getElementById('shipping-fee').innerText = fee.toLocaleString('vi-VN') + 'đ';
    });

    let index = 1;
    document.getElementById('add-product').addEventListener('click', function () {
        const wrapper = document.getElementById('product-wrapper');
        const newGroup = document.createElement('div');
        newGroup.classList.add('product-group', 'mb-3', 'border', 'p-3');

        let productOptions = ``;
        @foreach($products as $product)
            @foreach($product->variants as $variant)
                @php
                    $variantDisplay = '';
                    if ($variant->size) $variantDisplay .= 'Size: ' . $variant->size->name;
                    if ($variant->color) $variantDisplay .= ($variantDisplay ? ' - ' : '') . 'Color: ' . $variant->color->name;
                    $price = ($variant->sale_price > 0 && $variant->sale_price < $variant->price) ? $variant->sale_price : $variant->price;
                @endphp
                productOptions += `<option value="{{ $variant->id }}" data-price="{{ $price }}">
                    {{ $product->name }} - {{ $variantDisplay }}
                </option>`;
            @endforeach
        @endforeach

        newGroup.innerHTML = `
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Sản phẩm & Biến thể</label>
                    <select name="products[${index}][variant_id]" class="form-control variant-select" required>
                        <option value="">-- Chọn sản phẩm & biến thể --</option>
                        ${productOptions}
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Số lượng</label>
                    <input type="number" name="products[${index}][quantity]" class="form-control" min="1" value="1" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Giá (tự động)</label>
                    <input type="text" class="form-control product-price" readonly>
                </div>
                <div class="form-group col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-product">Xóa</button>
                </div>
            </div>`;
        wrapper.appendChild(newGroup);
        updateVariantInfo(newGroup);
        index++;
    });

    document.getElementById('product-wrapper').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product')) {
            const productGroups = document.querySelectorAll('.product-group');
            if (productGroups.length > 1) {
                e.target.closest('.product-group').remove();
            } else {
                alert('Đơn hàng phải có ít nhất 1 sản phẩm');
            }
        }
    });

    document.getElementById('product-wrapper').addEventListener('change', function (e) {
        if (e.target.classList.contains('variant-select')) {
            const group = e.target.closest('.product-group');
            updateVariantInfo(group);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        updateVariantInfo(document.querySelector('.product-group'));
        document.getElementById('shipping-method').dispatchEvent(new Event('change'));
    });
</script>
@endsection

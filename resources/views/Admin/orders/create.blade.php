@extends('Admin.layouts.AdminLayout')
@section('main')
<div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item">Đơn hàng</li>
        <li class="breadcrumb-item active"><a href="#">Tạo mới đơn hàng</a></li>
    </ul>
</div>

<div class="tile">
    <h3 class="tile-title">Thêm đơn hàng</h3>
    <div class="tile-body">
        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf

            <div class="form-group">
                <label>Người Đặt</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Chọn Người Đặt --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

          <div class="form-group">
    <label for="receiver_name">Tên người nhận</label>
    <input type="text" class="form-control" name="receiver_name" value="{{ old('receiver_name') }}" required>
    @error('receiver_name') <span class="text-danger">{{ $message }}</span> @enderror
</div>

<div class="form-group">
    <label for="receiver_phone">Số điện thoại</label>
    <input type="text" class="form-control" name="receiver_phone" value="{{ old('receiver_phone') }}" required>
    @error('receiver_phone') <span class="text-danger">{{ $message }}</span> @enderror
</div>

<div class="form-group">
    <label for="receiver_email">Email</label>
    <input type="email" class="form-control" name="receiver_email" value="{{ old('receiver_email') }}">
    @error('receiver_email') <span class="text-danger">{{ $message }}</span> @enderror
</div>

<div class="form-group">
    <label for="receiver_address">Địa chỉ</label>
    <textarea class="form-control" name="receiver_address" rows="3" required>{{ old('receiver_address') }}</textarea>
    @error('receiver_address') <span class="text-danger">{{ $message }}</span> @enderror
</div>

            <div class="form-group">
                <label>Ngày tạo đơn</label>
                <input type="date" class="form-control" name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}" required>
                @error('order_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div id="product-wrapper">
                <h5>Danh sách sản phẩm</h5>

                <div class="product-group mb-3 border p-3">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Sản phẩm & Biến thể</label>
                            <select name="products[0][variant_id]" class="form-control variant-select" required>
                                <option value="">-- Chọn sản phẩm & biến thể --</option>
                                @foreach($products as $product)
                                    @foreach($product->variants as $variant)
                                        @php
                                            $variantDisplay = '';
                                            if ($variant->size) $variantDisplay .= 'Size: ' . $variant->size->name;
                                            if ($variant->color) $variantDisplay .= ($variantDisplay ? ' - ' : '') . 'Color: ' . $variant->color->name;
                                            $price = ($variant->sale_price > 0 && $variant->sale_price < $variant->price) ? $variant->sale_price : $variant->price;
                                        @endphp
                                        <option value="{{ $variant->id }}" data-price="{{ $price }}">
                                            {{ $product->name }} - {{ $variantDisplay }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Số lượng</label>
                            <input type="number" name="products[0][quantity]" class="form-control" min="1" value="1" required>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Giá (tự động)</label>
                            <input type="text" class="form-control product-price" readonly>
                        </div>

                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-product">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="add-product" class="btn btn-info mb-3">+ Thêm sản phẩm</button>

            <div class="form-group">
                <label>Phương thức thanh toán</label>
                <select class="form-control" name="payment_method" required>
                    <option value="Tiền mặt" {{ old('payment_method') == 'Tiền mặt' ? 'selected' : '' }}>Tiền mặt</option>
                    <option value="Chuyển khoản ngân hàng" {{ old('payment_method') == 'Chuyển khoản ngân hàng' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                    <option value="Momo" {{ old('payment_method') == 'Momo' ? 'selected' : '' }}>Momo</option>
                    <option value="ZaloPay" {{ old('payment_method') == 'ZaloPay' ? 'selected' : '' }}>ZaloPay</option>
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
                <label>Mã giảm giá</label>
                <input type="text" name="discount_code" class="form-control" placeholder="Nhập mã giảm giá" value="{{ old('discount_code') }}">
            </div>

            <div class="form-group">
                <label>Ghi chú</label>
                <textarea class="form-control" name="note" rows="3">{{ old('note') }}</textarea>
            </div>

            <button class="btn btn-primary" type="submit">Lưu đơn hàng</button>
            <a class="btn btn-secondary" href="{{ route('admin.orders.index') }}">Hủy</a>
        </form>
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

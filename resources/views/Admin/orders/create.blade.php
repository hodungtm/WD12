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
                    <label>Người Đặt </label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Chọn Người Đặt --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Người Nhận </label>
                    <select name="receiver_id" class="form-control" required>
                        <option value="">-- Chọn Người Nhận --</option>
                        @foreach($receivers as $receiver)
                            <option value="{{ $receiver->id }}" {{ old('receiver_id') == $receiver->id ? 'selected' : '' }}>{{ $receiver->name }} ({{ $receiver->email }})</option>
                        @endforeach
                    </select>
                    @error('receiver_id') <span class="text-danger">{{ $message }}</span> @enderror
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
                                            {{-- Lấy tên thuộc tính từ attributeValues --}}
                                            @php
                                                $attributeNames = [];
                                                if ($variant->attributeValues) {
                                                    foreach ($variant->attributeValues as $attrValue) {
                                                        if ($attrValue->attribute) {
                                                            $attributeNames[] = $attrValue->attribute->name . ': ' . $attrValue->value;
                                                        }
                                                    }
                                                }
                                                $variantDisplay = implode(' - ', $attributeNames);
                                            @endphp
                                            <option value="{{ $variant->id }}"
                                                data-price="{{ $variant->variant_sale_price > 0 ? $variant->variant_sale_price : $variant->variant_price }}"
                                                {{ (old('products.0.variant_id') == $variant->id) ? 'selected' : '' }}>
                                                {{ $product->name }} - {{ $variantDisplay }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                                @error('products.0.variant_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label>Số lượng</label>
                                <input type="number" name="products[0][quantity]" class="form-control" min="1" value="{{ old('products.0.quantity', 1) }}" required>
                                @error('products.0.quantity') <span class="text-danger">{{ $message }}</span> @enderror
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
                    @error('products') <span class="text-danger">{{ $message }}</span> @enderror
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
                    @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Phương thức vận chuyển</label>
                    <select name="shipping_method_id" id="shipping-method" class="form-control" required>
                        <option value="">-- Chọn phương thức vận chuyển --</option>
                        @foreach ($shippingMethods as $method)
                            <option value="{{ $method->id }}" data-fee="{{ $method->fee }}"
                                {{ old('shipping_method_id') == $method->id ? 'selected' : '' }}>
                                {{ $method->name }} ({{ number_format($method->fee, 0, ',', '.') }}đ)
                            </option>
                        @endforeach
                    </select>
                    @error('shipping_method_id') <span class="text-danger">{{ $message }}</span> @enderror

                    <small class="form-text text-muted mt-2">
                        <strong>Phí vận chuyển:</strong> <span id="shipping-fee">0đ</span>
                    </small>
                </div>

                <div class="form-group">
                    <label for="discount_id">Mã giảm giá</label>
                    <select name="discount_id" id="discount_id" class="form-control">
                        <option value="">-- Chọn mã giảm giá --</option>
                        @foreach($discounts as $discount)
                            <option value="{{ $discount->id }}"
                                {{ old('discount_id') == $discount->id ? 'selected' : '' }}>
                                {{ $discount->code }}
                                ({{ $discount->description }}
                                - {{ $discount->discount_percent > 0
                                        ? $discount->discount_percent . '%'
                                        : number_format($discount->discount_amount) . ' VNĐ' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea class="form-control" name="note" rows="3">{{ old('note') }}</textarea>
                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button class="btn btn-primary" type="submit">Lưu đơn hàng</button>
                <a class="btn btn-secondary" href="{{ route('admin.orders.index') }}">Hủy</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('shipping-method').addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const fee = parseInt(selected.getAttribute('data-fee')) || 0;
            document.getElementById('shipping-fee').innerText = fee.toLocaleString('vi-VN') + 'đ';
        });

        // Gọi ngay khi load trang để cập nhật phí vận chuyển ban đầu nếu có giá trị cũ được chọn
        document.addEventListener('DOMContentLoaded', function () {
            const shippingMethodSelect = document.getElementById('shipping-method');
            if (shippingMethodSelect.value) {
                const selected = shippingMethodSelect.options[shippingMethodSelect.selectedIndex];
                const fee = parseInt(selected.getAttribute('data-fee')) || 0;
                document.getElementById('shipping-fee').innerText = fee.toLocaleString('vi-VN') + 'đ';
            }
        });
    </script>


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

        document.getElementById('product-wrapper').addEventListener('change', function (e) {
            if (e.target.classList.contains('variant-select')) {
                const group = e.target.closest('.product-group');
                updateVariantInfo(group);
            }
        });

        let index = 1; // Bắt đầu từ 1 vì phần tử đầu tiên là index 0
        document.getElementById('add-product').addEventListener('click', function () {
            const wrapper = document.getElementById('product-wrapper');
            const newGroup = document.createElement('div');
            newGroup.classList.add('product-group', 'mb-3', 'border', 'p-3');

            // Xây dựng lại các option sản phẩm cho group mới
            let productOptions = '';
            @foreach($products as $product)
                @foreach($product->variants as $variant)
                    @php
                        $attributeNames = [];
                        if ($variant->attributeValues) {
                            foreach ($variant->attributeValues as $attrValue) {
                                if ($attrValue->attribute) {
                                    $attributeNames[] = $attrValue->attribute->name . ': ' . $attrValue->value;
                                }
                            }
                        }
                        $variantDisplay = implode(' - ', $attributeNames);
                    @endphp
                    productOptions += `<option value="{{ $variant->id }}"
                        data-price="{{ $variant->variant_sale_price > 0 ? $variant->variant_sale_price : $variant->variant_price }}">
                        {{ $product->name }} - ${decodeHtmlEntities("{{ $variantDisplay }}")}
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
                </div>
            `;
            wrapper.appendChild(newGroup);
            updateVariantInfo(newGroup);
            index++;
        });

        document.getElementById('product-wrapper').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-product')) {
                const productGroups = document.querySelectorAll('.product-group');
                if (productGroups.length > 1) {
                    e.target.closest('.product-group').remove();
                    updateProductIndexes();
                } else {
                    alert('Đơn hàng phải có ít nhất 1 sản phẩm');
                }
            }
        });

        function updateProductIndexes() {
            const productGroups = document.querySelectorAll('.product-group');
            productGroups.forEach((group, idx) => {
                const selects = group.querySelectorAll('[name*="[variant_id]"]');
                const quantities = group.querySelectorAll('[name*="[quantity]"]');

                selects.forEach(select => select.name = `products[${idx}][variant_id]`);
                quantities.forEach(qty => qty.name = `products[${idx}][quantity]`);
            });
            index = productGroups.length; // Cập nhật lại index cho thêm mới
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Cập nhật thông tin cho sản phẩm đầu tiên khi tải trang
            const firstProductGroup = document.querySelector('.product-group');
            if (firstProductGroup) {
                updateVariantInfo(firstProductGroup);
            }
            // Gọi hàm này để đảm bảo index đúng khi trang được tải lại với old input
            updateProductIndexes();
        });

        // Helper function to decode HTML entities in JavaScript
        function decodeHtmlEntities(str) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(str, 'text/html');
            return doc.documentElement.textContent;
        }
    </script>
@endsection
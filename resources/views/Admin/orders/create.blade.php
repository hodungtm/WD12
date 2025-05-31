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
                <label>Khách hàng</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Chọn khách hàng --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Ngày tạo đơn</label>
                <input type="date" class="form-control" name="order_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                @error('order_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div id="product-wrapper">
                <h5>Danh sách sản phẩm</h5>
                <div class="product-group mb-3 border p-3">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>Sản phẩm</label>
                            <select name="products[0][variant_id]" class="form-control variant-select" required>
                                <option value="">-- Chọn sản phẩm & biến thể --</option>
                                @foreach($products as $product)
                                    @foreach($product->variants as $variant)
                                        <option value="{{ $variant->id }}"
                                            data-product="{{ $product->name }}"
                                            {{-- Lấy giá đã trừ khuyến mãi: ưu tiên sale_price nếu > 0, ngược lại là price --}}
                                            data-price="{{ $variant->variant_sale_price > 0 ? $variant->variant_sale_price : $variant->variant_price }}"
                                            data-size="{{ $variant->size }}"
                                            data-color="{{ $variant->color }}">
                                            {{ $product->name }} - Size: {{ $variant->size }} - Màu: {{ $variant->color }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('products.0.variant_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-2">
                            <label>Giá</label>
                            <input type="text" name="products[0][price]" class="form-control product-price" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Số lượng</label>
                            <input type="number" name="products[0][quantity]" class="form-control" min="1" required>
                            @error('products.0.quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-product">Xóa</button>
                        </div>
                    </div>
                </div>
                @error('products')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="button" id="add-product" class="btn btn-info mb-3">+ Thêm sản phẩm</button>

            <div class="form-group">
                <label>Phương thức thanh toán</label>
                <select class="form-control" name="payment_method" required>
                    <option value="Tiền mặt">Tiền mặt</option>
                    <option value="Chuyển khoản ngân hàng">Chuyển khoản ngân hàng</option>
                    <option value="Momo">Momo</option>
                    <option value="ZaloPay">ZaloPay</option>
                </select>
                @error('payment_method')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Ghi chú</label>
                <textarea class="form-control" name="note" rows="3"></textarea>
                @error('note')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">Lưu đơn hàng</button>
            <a class="btn btn-secondary" href="{{ route('admin.orders.index') }}">Hủy</a>
        </form>
    </div>
</div>

<script>
    // Hàm cập nhật thông tin biến thể (giá)
    function updateVariantInfo(group) {
        const select = group.querySelector('.variant-select');
        const priceInput = group.querySelector('.product-price');

        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value !== "") {
            const price = selectedOption.getAttribute('data-price');
            // Định dạng giá thành tiền Việt Nam Đồng (VND)
            priceInput.value = Number(price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        } else {
            priceInput.value = '';
        }
    }

    // Lắng nghe sự kiện thay đổi trên các select sản phẩm
    document.getElementById('product-wrapper').addEventListener('change', function(e) {
        if (e.target.classList.contains('variant-select')) {
            const group = e.target.closest('.product-group');
            updateVariantInfo(group);
        }
    });

    let index = 1; // Bắt đầu index từ 1 cho các sản phẩm thêm mới
    document.getElementById('add-product').addEventListener('click', function () {
        const wrapper = document.getElementById('product-wrapper');
        const newGroup = document.createElement('div');
        newGroup.classList.add('product-group', 'mb-3', 'border', 'p-3');
        newGroup.innerHTML = `
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label>Sản phẩm</label>
                    <select name="products[${index}][variant_id]" class="form-control variant-select" required>
                        <option value="">-- Chọn sản phẩm & biến thể --</option>
                        @foreach($products as $product)
                            @foreach($product->variants as $variant)
                                <option value="{{ $variant->id }}"
                                    data-product="{{ $product->name }}"
                                    data-price="{{ $variant->variant_sale_price > 0 ? $variant->variant_sale_price : $variant->variant_price }}"
                                    data-size="{{ $variant->size }}"
                                    data-color="{{ $variant->color }}">
                                    {{ $product->name }} - Size: {{ $variant->size }} - Màu: {{ $variant->color }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Giá</label>
                    <input type="text" name="products[${index}][price]" class="form-control product-price" readonly>
                </div>
                <div class="form-group col-md-2">
                    <label>Số lượng</label>
                    <input type="number" name="products[${index}][quantity]" class="form-control" min="1" required>
                </div>
                <div class="form-group col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-product">Xóa</button>
                </div>
            </div>
        `;
        wrapper.appendChild(newGroup);
        // Khởi tạo giá cho nhóm sản phẩm mới ngay lập tức
        updateVariantInfo(newGroup);
        index++;
    });

    // Lắng nghe sự kiện click trên nút xóa sản phẩm
    document.getElementById('product-wrapper').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            const productGroups = document.querySelectorAll('.product-group');
            if (productGroups.length > 1) { // Đảm bảo luôn có ít nhất 1 sản phẩm
                e.target.closest('.product-group').remove();
                updateProductIndexes(); // Cập nhật lại index sau khi xóa
            } else {
                alert('Đơn hàng phải có ít nhất 1 sản phẩm');
            }
        }
    });

    // Hàm cập nhật lại index của các trường input sau khi thêm/xóa sản phẩm
    function updateProductIndexes() {
        const productGroups = document.querySelectorAll('.product-group');
        productGroups.forEach((group, idx) => {
            const inputs = group.querySelectorAll('[name^="products["]');
            inputs.forEach(input => {
                // Lấy phần tử cuối cùng trong tên (ví dụ: 'variant_id', 'quantity', 'price')
                const key = input.name.match(/\[(\w+)\]$/)[1];
                input.name = `products[${idx}][${key}]`;
            });
        });
        index = productGroups.length; // Cập nhật lại biến index toàn cục
    }

    // Khởi tạo giá cho sản phẩm đầu tiên khi tải trang
    document.addEventListener('DOMContentLoaded', function() {
        const firstProductGroup = document.querySelector('.product-group');
        if (firstProductGroup) {
            updateVariantInfo(firstProductGroup);
        }
    });
</script>
@endsection
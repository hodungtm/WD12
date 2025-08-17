@extends('Client.Layouts.ClientLayout')

@section('main')
    <main class="main">
        <div class="container my-5">
            <ul class="checkout-progress-bar d-flex justify-content-center flex-wrap mb-4">
                <li class="active">
                    <a href="#">Shopping Cart</a>
                </li>
                <li>
                    <a href="{{ route('client.checkout.show') }}">Checkout</a>
                </li>
                <li class="disabled">
                    <a href="#">Order Complete</a>
                </li>
            </ul>

            @if($cartItems->isEmpty())
                <div class="text-center py-5">
                    <h4>Chưa có sản phẩm trong giỏ hàng.</h4>
                    <a href="{{ route('client.index') }}" class="btn btn-outline-primary mt-3">Tiếp tục mua sắm</a>
                </div>
            @else
                @php
                    $subtotal = $cartItems->sum(fn($item) => $item->variant->sale_price * $item->quantity);
                @endphp
                <div class="row">
                    <div class="col-lg-8">
                        <div class="cart-table-container">
                            <table class="table table-cart">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Product</th>
                                        <th></th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        @php
                                            $variant = $item->variant;
                                            $product = $item->product;
                                            $imagePath = $product->images->first()->image ?? 'no-image.jpg';
                                            $price = $variant->sale_price;
                                            $total = $price * $item->quantity;
                                        @endphp
                                        <tr class="product-row">
                                            <td>
                                                <input form="checkoutForm" type="checkbox" name="selected_items[]"
                                                    value="{{ $item->id }}" class="item-checkbox">
                                            </td>
                                            <td>
                                                <div class="cart-item-img-wrapper">
                                                    <a href="{{ route('client.product.detail', $product->id) }}">
                                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}"
                                                            width="80">
                                                    </a>
                                                    <button type="button" class="btn-remove icon-cancel" title="Xoá"
                                                        onclick="confirmDelete({{ $item->id }})"></button>
                                                </div>
                                            </td>
                                            <td>
                                                <h5>{{ $product->name }}</h5>
                                                <small>Màu: {{ $variant->color->name ?? '-' }}, Size:
                                                    {{ $variant->size->name ?? '-' }}</small>
                                            </td>
                                            <td>{{ number_format($price, 0, ',', '.') }}₫</td>
                                            <td>
                                                <div class="product-single-qty">
                                                    <div class="input-group">
                                                        <button type="button" class="btn-quantity btn-decrease"
                                                            data-id="{{ $item->id }}">-</button>
                                                        <input type="number" name="quantities[{{ $item->id }}]"
                                                            value="{{ $item->quantity }}" min="1"
                                                            class="quantity-input form-control text-center" style="width: 60px;"
                                                            data-id="{{ $item->id }}">
                                                        <button type="button" class="btn-quantity btn-increase"
                                                            data-id="{{ $item->id }}">+</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">{{ number_format($total, 0, ',', '.') }}₫</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <h3>CART TOTALS</h3>
                            <table class="table table-totals">
                                <tbody>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}₫</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td id="total">{{ number_format($subtotal, 0, ',', '.') }}₫</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="checkout-methods">
                                <form id="checkoutForm" action="{{ route('client.checkout.show') }}" method="GET">
                                    <button type="submit" class="btn btn-block btn-dark">
                                        Tiến hành thanh toán <i class="fa fa-arrow-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @foreach($cartItems as $item)
            <form id="delete-form-{{ $item->id }}" action="{{ route('client.cart.remove', $item->id) }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </main>
@endsection

<style>
    .cart-item-img-wrapper {
        position: relative;
        display: inline-block;
    }

    .cart-item-img-wrapper .btn-remove {
        position: absolute;
        top: 0;
        right: -25px;
        background: transparent;
        border: none;
        color: #999;
        cursor: pointer;
        font-size: 14px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-item-img-wrapper .btn-remove:hover {
        color: #e74c3c;
    }

    .checkout-progress-bar {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 0 0 30px 0;
        font-weight: 600;
        font-size: 16px;
    }

    .checkout-progress-bar li {
        position: relative;
        color: #999;
        margin: 0 10px;
    }

    .checkout-progress-bar li.active a {
        color: #4db7b3;
    }

    .checkout-progress-bar li a {
        text-decoration: none;
        color: inherit;
    }

    .checkout-progress-bar li:not(:last-child)::after {
        content: '>';
        position: absolute;
        right: -15px;
        color: #999;
    }

    /* CSS cho nút tăng/giảm */
    .input-group {
        display: flex;
        align-items: stretch;
    }

    .btn-quantity {
        width: 40px;
        font-size: 20px;
        font-weight: bold;
        border: 1px solid #ced4da;
        background-color: #fff;
        color: #000;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .btn-quantity:hover {
        background-color: #f0f0f0;
    }

    .quantity-input {
        text-align: center;
        border: 1px solid #ced4da;
        width: 60px;
        font-size: 18px;
        height: 45px;
    }

    .btn-quantity,
    .quantity-input {
        height: 45px;
        width: 23px;
    }
    
    .quantity-input::-webkit-inner-spin-button,
    .quantity-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .quantity-input {
        -moz-appearance: textfield;
    }
</style>

@section('js')
    <script>
        function confirmDelete(id) {
            if (confirm('Bạn có chắc chắn muốn xoá sản phẩm này?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        function formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN').format(value) + '₫';
        }

        function updateTotals(subtotal) {
            document.getElementById('subtotal').textContent = formatCurrency(subtotal);
            document.getElementById('total').textContent = formatCurrency(subtotal);
        }

        // Tính subtotal từ server-side data
        let calculatedSubtotal = 0;
        @if(!$cartItems->isEmpty())
            @foreach($cartItems as $item)
                calculatedSubtotal += {{ $item->variant->sale_price * $item->quantity }};
            @endforeach
        @endif

        // Xử lý cập nhật số lượng bằng AJAX
        let updateTimeouts = {};

        // Hàm gửi AJAX cập nhật số lượng
        function updateQuantityAjax(itemId, quantity, inputElement) {
            if (quantity < 1) quantity = 1;
            
            clearTimeout(updateTimeouts[itemId]);

            updateTimeouts[itemId] = setTimeout(() => {
                fetch("{{ route('client.cart.updateAll') }}", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantities: { [itemId]: quantity }
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateTotals(data.subtotal);
                        inputElement.value = quantity;
                        calculatedSubtotal = data.subtotal; // Cập nhật subtotal
                    } else {
                        alert(data.message || 'Không thể cập nhật số lượng');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Đã xảy ra lỗi khi cập nhật.');
                });
            }, 300);
        }

        // Xử lý nút tăng/giảm
        document.querySelectorAll('.btn-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                const input = document.querySelector(`.quantity-input[data-id='${itemId}']`);
                let currentVal = parseInt(input.value);

                if (this.classList.contains('btn-increase')) {
                    currentVal++;
                } else if (this.classList.contains('btn-decrease') && currentVal > 1) {
                    currentVal--;
                }

                input.value = currentVal;
                updateQuantityAjax(itemId, currentVal, input);
            });
        });

        // Xử lý khi nhập trực tiếp vào input
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function () {
                const itemId = this.dataset.id;
                let quantity = parseInt(this.value);
                if (isNaN(quantity) || quantity < 1) quantity = 1;
                this.value = quantity;

                updateQuantityAjax(itemId, quantity, this);
            });
        });

        // Xử lý form checkout
        document.getElementById('checkoutForm').addEventListener('submit', function (e) {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (!anyChecked) {
                checkboxes.forEach(cb => cb.checked = true);
            }
        });

        // Khởi tạo totals khi trang được tải
        updateTotals(calculatedSubtotal);
    </script>
@endsection
@extends('Client.Layouts.ClientLayout')
@section('main')

<main class="main main-test">
    <div class="container checkout-container">
        <ul class="checkout-progress-bar d-flex justify-content-center flex-wrap">
            <li><a href="{{ route('client.cart.index') }}">Shopping Cart</a></li>
            <li class="active"><a href="#">Checkout</a></li>
            <li class="disabled"><a href="#">Order Complete</a></li>
        </ul>

        <section class="checkout-section pt-4">
            <form action="{{ route('client.checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                @foreach(request('selected_items', []) as $id)
                    <input type="hidden" name="selected_items[]" value="{{ $id }}">
                @endforeach

                <div class="row">
                    <div class="col-lg-7">
                        <h2>Billing details</h2>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="sameAsBuyer" name="sameAsBuyer" onclick="copyBuyerInfo()">
                            <label class="form-check-label" for="sameAsBuyer">
                                Tôi là người nhận hàng
                            </label>
                        </div>

                        <div class="form-group mb-3">
                            <label>Họ tên người nhận</label>
                            <input type="text" name="receiver_name" id="receiver_name" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Số điện thoại</label>
                            <input type="text" name="receiver_phone" id="receiver_phone" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Địa chỉ</label>
                            <textarea name="receiver_address" id="receiver_address" class="form-control" required>{{ old('receiver_address') }}</textarea>
                        </div>

                       <div class="form-group mb-3">
    <label for="discount_code">Chọn mã giảm giá</label>
    <select name="discount_code" id="discountSelect" class="form-control" onchange="updateDiscount()">
        <option value="" data-percent="0" data-max="0" data-min="0">-- Không dùng --</option>
        @foreach($discounts->filter(function($discount) {
            return $discount->max_usage === null || $discount->max_usage > 0; // còn lượt
        }) as $discount)
            <option value="{{ $discount->code }}"
                data-percent="{{ $discount->discount_percent }}"
                data-max="{{ $discount->max_discount_amount }}"
                data-min="{{ $discount->min_order_amount }}">
                {{ $discount->code }} - Giảm {{ $discount->discount_percent }}%
                (tối đa {{ number_format($discount->max_discount_amount) }}₫)
            </option>
        @endforeach
    </select>
</div>

                        <div class="form-group mb-3">
                            <label>Phương thức giao hàng</label>
                            <select name="shipping_method_id" id="shippingSelect" class="form-control" onchange="updateShipping()" required>
                                @foreach($shippingMethods as $method)
                                    <option value="{{ $method->id }}" data-fee="{{ $method->fee }}">
                                        {{ $method->name }} ({{ number_format($method->fee, 0, ',', '.') }}₫)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label>Phương thức thanh toán</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="Tiền mặt">Tiền mặt khi nhận</option>
                                <option value="Chuyển khoản">Chuyển khoản ngân hàng</option>
                                <option value="Momo">MoMo</option>
                                <option value="ZaloPay">ZaloPay</option>
                            </select>
                        </div>

                    </div>

                    <div class="col-lg-5">
                        <div class="order-summary">
                            <h3>YOUR ORDER</h3>
                            <ul class="list-group mb-3">
                                @php $subtotal = 0; @endphp
                                @foreach($cartItems as $item)
                                    @php
                                        $variant = $item->variant;
                                        $product = $item->product;
                                        $imagePath = $product->images->first()->image ?? 'no-image.jpg';
                                        $lineTotal = $variant->sale_price * $item->quantity;
                                        $subtotal += $lineTotal;
                                    @endphp
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="ảnh" width="70" class="img-thumbnail me-2">
                                            <div>
                                                <div>{{ $product->name }}</div>
                                                <small>{{ $variant->color->name ?? '-' }} / {{ $variant->size->name ?? '-' }}</small>
                                            </div>
                                        </div>
                                        <span>{{ number_format($lineTotal, 0, ',', '.') }}₫</span>
                                    </li>
                                @endforeach

                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Tạm tính</span>
                                    <strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong>
                                </li>

                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Phí vận chuyển</span>
                                    @php
                                        $defaultShipping = $shippingMethods->first();
                                        $shippingFee = $defaultShipping?->fee ?? 0;
                                    @endphp
                                    <strong id="shipping-fee-text">{{ number_format($shippingFee, 0, ',', '.') }}₫</strong>
                                </li>

                                <li class="list-group-item d-flex justify-content-between text-success">
                                    <span>Giảm giá</span>
                                    <strong id="discount-amount-text">-{{ number_format(0, 0, ',', '.') }}₫</strong>
                                </li>

                                <li class="list-group-item d-flex justify-content-between fw-bold">
                                    <span>Tổng cộng</span>
                                    @php
                                        $total = $subtotal + $shippingFee;
                                    @endphp
                                    <strong id="total-amount">{{ number_format($total, 0, ',', '.') }}₫</strong>
                                </li>
                            </ul>

                            <button type="submit" class="btn btn-primary btn-lg w-100">Xác nhận đặt hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</main>
<style>
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
</style>
<script>
    @php $user = auth()->user(); @endphp
    // Lấy địa chỉ từ user thay vì session
    const userAddress = "{{ $user->address ?? '' }}";
    function updateShipping() {
        const shippingFee = parseInt(document.querySelector('#shippingSelect option:checked')?.dataset?.fee || 0);
        const subtotal = {{ $subtotal }};
        const discountOption = document.querySelector('#discountSelect option:checked');
        const percent = parseFloat(discountOption?.dataset?.percent || 0);
        const maxDiscount = parseInt(discountOption?.dataset?.max || 0);
        const minOrder = parseInt(discountOption?.dataset?.min || 0);

        let discountAmount = 0;
        if (subtotal >= minOrder && percent > 0) {
            discountAmount = Math.min(Math.round(subtotal * percent / 100), maxDiscount);
        }

        const total = subtotal + shippingFee - discountAmount;

        document.getElementById('shipping-fee-text').innerText = shippingFee.toLocaleString('vi-VN') + '₫';
        document.getElementById('discount-amount-text').innerText = '-' + discountAmount.toLocaleString('vi-VN') + '₫';
        document.getElementById('total-amount').innerText = total.toLocaleString('vi-VN') + '₫';
    }

    function updateDiscount() {
        updateShipping();
    }

    window.onload = () => {
        updateShipping();
    };

    function copyBuyerInfo() {
        const checked = document.getElementById('sameAsBuyer').checked;
        document.querySelector('input[name="receiver_name"]').value = checked ? "{{ $user->name }}" : "";
        document.querySelector('input[name="receiver_phone"]').value = checked ? "{{ $user->phone }}" : "";
        document.querySelector('textarea[name="receiver_address"]').value = checked ? userAddress : "";

        
    }
</script>

@endsection
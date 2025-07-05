@extends('Client.Layouts.ClientLayout')
@section('main')

    <section class="checkout-section pt-80 pb-80">
        <div class="container">
            <h2 class="mb-4">Thông tin thanh toán</h2>

            <form action="{{ route('client.checkout.process') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Thông tin người nhận -->
                    <div class="col-md-6">
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
        <textarea name="receiver_address" id="receiver_address" class="form-control" required></textarea>
    </div>
</div>


                    <!-- Tóm tắt đơn hàng -->
                    <div class="col-md-6">
                        <h4>Đơn hàng của bạn</h4>
                        <ul class="list-group mb-3">
                            @php
                                $subtotal = 0;
                            @endphp

                            @foreach($cartItems as $item)
                                @php
                                    $variant = $item->variant;
                                    $product = $item->product;
                                    $imagePath = $product->images->first()->image
                                        ?? 'no-image.jpg';
                                    $lineTotal = $variant->price * $item->quantity;
                                    $subtotal += $lineTotal;
                                @endphp
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="ảnh" width="70"
                                            class="img-thumbnail me-2">
                                        <div>
                                            <div>{{ $product->name }}</div>
                                            <small>{{ $variant->color->name ?? '-' }} /
                                                {{ $variant->size->name ?? '-' }}</small>
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
                                <strong
                                    id="discount-amount-text">-{{ number_format(session('discount')['amount'] ?? 0, 0, ',', '.') }}₫</strong>
                            </li>

                            <li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>Tổng cộng</span>
                                @php
                                    $discount = session('discount')['amount'] ?? 0;
                                    $total = $subtotal + $shippingFee - $discount;
                                @endphp
                                <strong id="total-amount">{{ number_format($total, 0, ',', '.') }}₫</strong>
                            </li>
                        </ul>

                        <div class="form-group mb-3">
    <label for="discount_code">Chọn mã giảm giá</label>
    <select name="discount_code" id="discountSelect" class="form-control" onchange="updateDiscount()">
        <option value="" data-percent="0" data-max="0" data-min="0">-- Không dùng --</option>
        @foreach($discounts as $discount)
            <option value="{{ $discount->code }}"
                data-percent="{{ $discount->discount_percent }}"
                data-max="{{ $discount->max_discount_amount }}"
                data-min="{{ $discount->min_order_amount }}">
                {{ $discount->code }} - Giảm {{ $discount->discount_percent }}% (tối đa {{ number_format($discount->max_discount_amount) }}₫)
            </option>
        @endforeach
    </select>
</div>

                        <div class="form-group mb-3">
                            <label>Phương thức giao hàng</label>
                            <select name="shipping_method_id" id="shippingSelect" class="form-control"
onchange="updateShipping()" required>
                                @foreach($shippingMethods as $method)
                                    <option value="{{ $method->id }}" data-fee="{{ $method->fee }}">
                                        {{ $method->name }} ({{ number_format($method->fee, 0, ',', '.') }}₫)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label>Phương thức thanh toán</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="Tiền mặt">Tiền mặt khi nhận</option>
                                <option value="Chuyển khoản">Chuyển khoản ngân hàng</option>
                                <option value="Momo">Momo</option>
                                <option value="zaloPay">ZaloPay</option>
                            </select>
                        </div>
                         {{-- <form action="{{ url('/momo_payment') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="total_momo" value="{{ $total_after }}">
                                            <button type="submit" class="btn btn-default check_out" name="payUrl">Thanh
                                                toán MOMO</button>
                                        </form> --}}


                        <button type="submit" class="btn btn-primary btn-lg w-100">Xác nhận đặt hàng</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

<script>
    function updateShipping() {
        const shippingFee = parseInt(document.querySelector('#shippingSelect option:checked')?.dataset?.fee || 0);
        const subtotal = {{ $subtotal }};

        // Handle discount
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
        const isChecked = document.getElementById('sameAsBuyer').checked;

        const nameInput = document.getElementById('receiver_name');
        const phoneInput = document.getElementById('receiver_phone');
        const addressInput = document.getElementById('receiver_address');

        if (isChecked) {
            nameInput.value = @json(Auth::user()->name);
            phoneInput.value = @json(Auth::user()->phone);
            addressInput.value = @json(Auth::user()->address);

            nameInput.readOnly = true;
            phoneInput.readOnly = true;
            addressInput.readOnly = true;
        } else {
            nameInput.readOnly = false;
            phoneInput.readOnly = false;
            addressInput.readOnly = false;

            nameInput.value = "";
            phoneInput.value = "";
            addressInput.value = "";
        }
    }
</script>



@endsection
{{-- function copyBuyerInfo() {
const checked = document.getElementById('sameAsBuyer').checked;
document.querySelector('input[name="receiver_name"]').value = checked ? "{{ auth()->user()->name }}" : "";
document.querySelector('input[name="receiver_phone"]').value = checked ? "{{ auth()->user()->phone }}" : "";
document.querySelector('textarea[name="receiver_address"]').value = checked ? "{{ auth()->user()->address }}" : "";
} --}}
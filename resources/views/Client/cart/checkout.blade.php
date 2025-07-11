@extends('Client.Layouts.ClientLayout')

@section('title', 'Thanh toán đơn hàng')
@section('main')
    <section class="checkout-section pt-80 pb-80">
        <div class="container">
            <h2 class="mb-4">Thông tin thanh toán</h2>

            <form action="{{ route('client.checkout.process') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Thông tin thanh toán (bên trái) -->
                    <div class="col-md-7">
                        <div class="row">
                            <!-- Thông tin người mua -->
                            <div class="col-md-12">
                                <h4>Người mua</h4>
                                <div class="form-group mb-3">
                                    <label>Họ tên</label>
                                    <input type="text" name="buyer_name" class="form-control"
                                        value="{{ Auth::user()->name ?? '' }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Email</label>
                                    <input type="email" name="buyer_email" class="form-control"
                                        value="{{ Auth::user()->email ?? '' }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Số điện thoại</label>
                                    <input type="text" name="buyer_phone" class="form-control"
                                        value="{{ Auth::user()->phone ?? '' }}" required>
                                </div>
                            </div>

                            <!-- Thông tin người nhận -->
                            <div class="col-md-12">
                                <h4>Người nhận</h4>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="sameAsBuyer"
                                        onclick="copyBuyerInfo()">
                                    <label class="form-check-label" for="sameAsBuyer">
                                        Tôi là người nhận hàng
                                    </label>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Họ tên người nhận</label>
                                    <input type="text" name="receiver_name" id="receiver_name" class="form-control"
                                        required>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Số điện thoại</label>
                                    <input type="text" name="receiver_phone" id="receiver_phone" class="form-control"
                                        required>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Địa chỉ</label>
                                    <input type="text" name="receiver_address" id="receiver_address" class="form-control"
                                        required>
                                </div>


                                <div class="form-group mb-3">
                                    <label>Ghi chú đơn hàng</label>
                                    <textarea name="order_note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tóm tắt đơn hàng (bên phải) -->
                    <div class="col-md-5">
                        <h4>Đơn hàng của bạn</h4>
                        <ul class="list-group mb-3">
                            @php $subtotal = 0; @endphp
                            @foreach ($cartItems as $item)
                                @php
                                    $variant = $item->variant;
                                    $product = $item->product;
                                    $imagePath = $product->images->first()->image ?? 'no-image.jpg';
                                    $lineTotal = $variant->price * $item->quantity;
                                    $subtotal += $lineTotal;
                                @endphp
                                <li class="list-group-item">
                                    <div class="d-flex">
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="ảnh" width="70"
                                            height="70" class="img-thumbnail me-3" style="object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            <div class="text-muted small">
                                                Phân loại: {{ $variant->color->name ?? '-' }} /
                                                {{ $variant->size->name ?? '-' }}
                                            </div>
                                            <div class="text-muted small">Số lượng: {{ $item->quantity }}</div>
                                        </div>
                                        <div class="text-end fw-semibold text-nowrap" style="min-width: 100px;">
                                            {{ number_format($lineTotal, 0, ',', '.') }}₫
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                            @php
                                $defaultShipping = $shippingMethods->first();
                                $shippingFee = $defaultShipping?->fee ?? 0;
                                $discount = session('discount')['amount'] ?? 0;
                                $total = $subtotal + $shippingFee - $discount;
                            @endphp

                            <li class="list-group-item d-flex justify-content-between"><span>Tạm
                                    tính</span><strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Phí vận chuyển</span><strong
                                    id="shipping-fee-text">{{ number_format($shippingFee, 0, ',', '.') }}₫</strong></li>
                            <li class="list-group-item d-flex justify-content-between text-success"><span>Giảm
                                    giá</span><strong
                                    id="discount-amount-text">-{{ number_format($discount, 0, ',', '.') }}₫</strong></li>
                            <li class="list-group-item d-flex justify-content-between fw-bold"><span>Tổng cộng</span><strong
                                    id="total-amount">{{ number_format($total, 0, ',', '.') }}₫</strong></li>
                        </ul>


                        <div class="form-group mb-3">
                            <label for="discount_code">Chọn mã giảm giá</label>
                            <select name="discount_code" id="discountSelect" class="form-control"
                                onchange="updateShipping()">
                                <option value="" data-percent="0" data-max="0" data-min="0">-- Không dùng --
                                </option>
                                @foreach ($discounts as $discount)
                                    <option value="{{ $discount->code }}" data-percent="{{ $discount->discount_percent }}"
                                        data-max="{{ $discount->max_discount_amount }}"
                                        data-min="{{ $discount->min_order_amount }}">
                                        {{ $discount->code }} - Giảm {{ $discount->discount_percent }}% (tối đa
                                        {{ number_format($discount->max_discount_amount) }}₫)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Phương thức giao hàng</label>
                            <select name="shipping_method_id" id="shippingSelect" class="form-control"
                                onchange="updateShipping()" required>
                                @foreach ($shippingMethods as $method)
                                    <option value="{{ $method->id }}" data-fee="{{ $method->fee }}">
                                        {{ $method->name }} ({{ number_format($method->fee, 0, ',', '.') }}₫)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label>Phương thức thanh toán</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cod">Tiền mặt khi nhận</option>
                                <option value="bank">Chuyển khoản ngân hàng</option>
                                <option value="momo">Momo</option>
                                <option value="zalopay">ZaloPay</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg">Xác nhận đặt hàng</button>
                        </div>
<section class="checkout-section pt-80 pb-80">
    <div class="container">
        <h2 class="mb-4">Thông tin thanh toán</h2>

        <form action="{{ route('client.checkout.process') }}" method="POST">
            @csrf
            @foreach(request('selected_items', []) as $id)
                <input type="hidden" name="selected_items[]" value="{{ $id }}">
            @endforeach

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
                        @php $subtotal = 0; @endphp
                        @foreach($cartItems as $item)
                            @php
                                $variant = $item->variant;
                                $product = $item->product;
                                $imagePath = $product->images->first()->image ?? 'no-image.jpg';
                                $lineTotal = $variant->price * $item->quantity;
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
                            <strong id="discount-amount-text">-{{ number_format(session('discount')['amount'] ?? 0, 0, ',', '.') }}₫</strong>
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
                                <option value="{{ $discount->code }}" data-percent="{{ $discount->discount_percent }}" data-max="{{ $discount->max_discount_amount }}" data-min="{{ $discount->min_order_amount }}">
                                    {{ $discount->code }} - Giảm {{ $discount->discount_percent }}% (tối đa {{ number_format($discount->max_discount_amount) }}₫)
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

                    <button type="submit" class="btn btn-primary btn-lg w-100">Xác nhận đặt hàng</button>
                </div>
            </form>

            <input type="hidden" id="buyer_name" value="{{ Auth::user()->name ?? '' }}">
            <input type="hidden" id="buyer_phone" value="{{ Auth::user()->phone ?? '' }}">
            <input type="hidden" id="buyer_address" value="{{ Auth::user()->address ?? '' }}">
        </div>
    </section>

    <script>
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

        function copyBuyerInfo() {
            const isChecked = document.getElementById('sameAsBuyer').checked;
            document.getElementById('receiver_name').value = isChecked ? document.getElementById('buyer_name').value : "";
            document.getElementById('receiver_phone').value = isChecked ? document.getElementById('buyer_phone').value : "";
            document.getElementById('receiver_address').value = isChecked ? document.getElementById('buyer_address').value :
                "";

            document.getElementById('receiver_name').readOnly = isChecked;
            document.getElementById('receiver_phone').readOnly = isChecked;
            document.getElementById('receiver_address').readOnly = isChecked;
        }

        window.onload = () => updateShipping();
    </script>
            </div>
        </form>
    </div>
</section>

<script>
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
    document.querySelector('input[name="receiver_name"]').value = checked ? "{{ auth()->user()->name }}" : "";
    document.querySelector('input[name="receiver_phone"]').value = checked ? "{{ auth()->user()->phone }}" : "";
    document.querySelector('textarea[name="receiver_address"]').value = checked ? "{{ auth()->user()->address }}" : "";

    document.querySelector('input[name="receiver_name"]').readOnly = checked;
    document.querySelector('input[name="receiver_phone"]').readOnly = checked;
    document.querySelector('textarea[name="receiver_address"]').readOnly = checked;
}

// Xử lý form nếu chọn Momo
const form = document.querySelector('form');
form.addEventListener('submit', function (e) {
    const paymentMethod = document.getElementById('payment_method').value;
    
    if (paymentMethod === 'Momo') {
        e.preventDefault();

        const formData = new FormData(form);

        // ✅ Bổ sung thủ công selected_items[]
        const selectedInputs = document.querySelectorAll('input[name="selected_items[]"]');
        selectedInputs.forEach(input => {
            formData.append('selected_items[]', input.value);
        });

        fetch('{{ route("momo.payment") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.payUrl) {
                form.querySelector('button[type="submit"]').disabled = true;
                window.location.href = data.payUrl;
            } else {
                alert("Không thể kết nối MoMo");
            }
        })
        .catch(error => {
            console.error("Lỗi MoMo:", error);
            alert("Có lỗi xảy ra khi thanh toán bằng MoMo");
        });
    }
});

</script>
@endsection

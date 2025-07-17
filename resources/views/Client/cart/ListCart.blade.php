@extends('Client.Layouts.ClientLayout')

@section('main')
<main class="main">
    <div class="container my-5">
        <h1 class="mb-4">Giỏ Hàng Của Bạn</h1>

        @if($cartItems->isEmpty())
            <h4 class="text-center">Chưa có sản phẩm trong giỏ hàng.</h4>
        @else
            <form action="{{ route('client.checkout.show') }}" method="GET">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="check-all">
                                        </th>

                                        <th>Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th>Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        @php
                                            $variant = $item->variant;
                                            $product = $item->product;
                                            $imagePath = $product->images->first()->image ?? 'no-image.jpg';
                                            $price = $variant->price;
                                            $total = $price * $item->quantity;
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-checkbox">
                                            </td>
                                            <td><img src="{{ asset('storage/' . $imagePath) }}" width="60" class="img-thumbnail"></td>
                                            <td class="text-start">
                                                <strong>{{ $product->name }}</strong><br>
                                                <small>Màu: {{ $variant->color->name ?? '-' }}<br>Size: {{ $variant->size->name ?? '-' }}</small>
                                            </td>
                                            <td>{{ number_format($price, 0, ',', '.') }}₫</td>
                                            <td style="width: 100px;">
                                                <input type="number" value="{{ $item->quantity }}" min="1"
                                                       class="form-control form-control-sm text-center quantity-input"
                                                       data-price="{{ $price }}" data-id="{{ $item->id }}">
                                            </td>
                                            <td class="item-total">{{ number_format($total, 0, ',', '.') }}₫</td>
                                            <td>
                                                <form action="{{ route('client.cart.remove', $item->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">x</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                THANH TOÁN
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0">
                        @php
                            $subtotal = $cartItems->sum(fn($item) => $item->variant->price * $item->quantity);
                        @endphp
                        <div class="border p-4">
                            <h4 class="mb-3 fw-bold">TỔNG ĐƠN HÀNG</h4>
                            <table class="table">
                                <tr>
                                    <td>Tạm tính</td>
                                    <td class="text-end" id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>Tổng cộng</th>
                                    <th class="text-end" id="total">{{ number_format($subtotal, 0, ',', '.') }}₫</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</main>
@endsection

@section('scripts')
<script>
    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN').format(value) + '₫';
    }

    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const input = row.querySelector('.quantity-input');
            const price = parseInt(input.dataset.price);
            const qty = parseInt(input.value);
            const itemTotal = price * qty;
            row.querySelector('.item-total').textContent = formatCurrency(itemTotal);
            subtotal += itemTotal;
        });
        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('total').textContent = formatCurrency(subtotal);
    }

    // Cập nhật số lượng bằng AJAX
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            const qty = this.value;
            const cartId = this.dataset.id;
            const row = this.closest('tr');

            fetch(`/client/cart/update-quantity/${cartId}`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
        quantity: qty
    })
})
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const totalCell = row.querySelector('.item-total');
                    totalCell.textContent = formatCurrency(data.item_total);
                    updateTotals();
                } else {
                    alert(data.message || 'Cập nhật thất bại.');
                }
            })
            .catch(err => {
                alert('Lỗi mạng!');
                console.error(err);
            });
        });
    });

  document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('check-all');
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            const checked = this.checked;
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = checked);
        });
    }
});


    updateTotals();
</script>
@endsection

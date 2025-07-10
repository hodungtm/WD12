@extends('Client.Layouts.ClientLayout')

@section('main')
<main class="main">
    <div class="container my-5">
        <!-- Thanh bước tiến độ -->
        <ul class="checkout-progress-bar d-flex justify-content-center flex-wrap mb-4">
            <li class="active"><a href="#">Shopping Cart</a></li>
            <li><a href="{{ route('client.checkout.show') }}">Checkout</a></li>
            <li class="disabled"><a href="#">Order Complete</a></li>
        </ul>

        @if($cartItems->isEmpty())
            <h4 class="text-center">Chưa có sản phẩm trong giỏ hàng.</h4>
        @else
            @php
                $subtotal = $cartItems->sum(fn($item) => $item->variant->price * $item->quantity);
            @endphp

            <form action="{{ route('client.checkout.show') }}" method="GET">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th><input type="checkbox" id="check-all"></th>
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
                                            <td>
                                                <img src="{{ asset('storage/' . $imagePath) }}" width="60" class="img-thumbnail">
                                            </td>
                                            <td class="text-start">
                                                <strong>{{ $product->name }}</strong><br>
                                                <small>Màu: {{ $variant->color->name ?? '-' }}<br>Size: {{ $variant->size->name ?? '-' }}</small>
                                            </td>
                                            <td>{{ number_format($price, 0, ',', '.') }}₫</td>
                                            <td style="width: 100px;">
                                                <form action="{{ route('client.cart.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                        class="form-control form-control-sm text-center quantity-input">
                                                </form>
                                            </td>
                                            <td>{{ number_format($total, 0, ',', '.') }}₫</td>
                                            <td>
                                                <form action="{{ route('client.cart.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">x</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="submit" class="btn btn-success px-4 py-2">
                                THANH TOÁN SẢN PHẨM ĐÃ CHỌN
                            </button>
                            <a href="{{ route('client.checkout.show') }}" class="btn btn-dark px-4 py-2">
                                THANH TOÁN TOÀN BỘ
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="border p-4">
                            <h4 class="mb-3 fw-bold">TỔNG ĐƠN HÀNG</h4>
                            <table class="table">
                                <tr>
                                    <td>Tạm tính</td>
                                    <td class="text-end">{{ number_format($subtotal, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>Tổng cộng</th>
                                    <th class="text-end">{{ number_format($subtotal, 0, ',', '.') }}₫</th>
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
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        input.addEventListener('change', function () {
            this.closest('form').submit();
        });
    });

    document.getElementById('check-all').addEventListener('change', function () {
        const checked = this.checked;
        document.querySelectorAll('.item-checkbox').forEach(function (checkbox) {
            checkbox.checked = checked;
        });
    });
</script>
@endsection

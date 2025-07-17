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
					<form action="{{ route('client.cart.updateAll') }}" method="POST">
						@csrf
						@method('PUT')
						<table class="table table-cart">
							<thead>
								<tr>
									<th></th>
									<th>Product</th>
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
											<div class="cart-item-img-wrapper">
												<a href="{{ route('client.product.detail', $product->id) }}">
													<img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}" width="80">
												</a>
												<!-- nút xoá nằm ngoài form cập nhật -->
												<button type="button" class="btn-remove icon-cancel" title="Xoá" onclick="confirmDelete({{ $item->id }})"></button>
											</div>
										</td>
										<td>
											<h5>{{ $product->name }}</h5>
											<small>Màu: {{ $variant->color->name ?? '-' }}, Size: {{ $variant->size->name ?? '-' }}</small>
										</td>
										<td>{{ number_format($price, 0, ',', '.') }}₫</td>
										<td>
											<div class="product-single-qty">
                                                <div class="input-group">
                                                    <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantity }}" min="1" class="horizontal-quantity form-control text-center">
                                                </div>
                                            </div>

										</td>
										<td class="text-right">{{ number_format($total, 0, ',', '.') }}₫</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="text-end mt-3">
							<button type="submit" class="btn btn-primary">Cập nhật giỏ hàng</button>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="cart-summary">
					<h3>CART TOTALS</h3>

					<table class="table table-totals">
						<tbody>
							<tr>
								<td>Subtotal</td>
								<td>{{ number_format($subtotal, 0, ',', '.') }}₫</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td>Total</td>
								<td>{{ number_format($subtotal, 0, ',', '.') }}₫</td>
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

	<!-- Tất cả form xoá nằm ngoài -->
	@foreach($cartItems as $item)
		<form id="delete-form-{{ $item->id }}" action="{{ route('client.cart.remove', $item->id) }}" method="POST" style="display: none;">
			@csrf
			@method('DELETE')
		</form>
	@endforeach
</main>

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

</style>

<script>
function confirmDelete(id) {
	if (confirm('Bạn có chắc chắn muốn xoá sản phẩm này?')) {
		document.getElementById('delete-form-' + id).submit();
	}
}
</script>
@endsection

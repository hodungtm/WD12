@extends('Client.Layouts.ClientLayout')
@section('main')

    <nav class="breadcrumb-section theme1 bg-lighten2 pt-110 pb-110">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="title text-dark text-capitalize">Giỏ hàng</h2>
                    <ol class="breadcrumb bg-transparent justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
                    </ol>
                </div>
            </div>
        </div>
    </nav>

    <section class="whish-list-section theme1 pt-80 pb-80">
        <div class="container">
            <h3 class="text-center mb-4">Giỏ Hàng Của Bạn</h3>

            @if ($cartItems->isEmpty())
                <p class="text-center">Chưa có sản phẩm trong giỏ hàng.</p>
            @else
                <div class="table-responsive">
                    <table class="table text-center align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Biến thể</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                @php
                                    $variant = $item->variant;
                                    $product = $item->product;

                                    // Ưu tiên ảnh của biến thể → fallback về ảnh sản phẩm
                                    $imagePath = null;

                                    if ($variant && $variant->images && $variant->images->first()) {
                                        $imagePath = $variant->images->first()->image;
                                    } elseif ($product && $product->images && $product->images->first()) {
                                        $imagePath = $product->images->first()->image;
                                    } else {
                                        $imagePath = 'no-image.jpg'; // fallback ảnh mặc định
                                    }
                                    $price = $variant->price ?? 0; // Nếu variant null → 0
                                    $total = $price * $item->quantity;
                                @endphp
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="ảnh" width="70"
                                            class="img-thumbnail">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        Màu: {{ $variant->color->name ?? '-' }} <br>
                                        Size: {{ $variant->size->name ?? '-' }}
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('client.cart.update', $item->id) }}">
                                            @csrf
                                            <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                min="1" max="{{ $variant->quantity ?? 10 }}" style="width: 70px;">
                                            <button class="btn btn-sm btn-primary mt-1">Cập nhật</button>
                                        </form>
                                    </td>
                                    <td>{{ number_format($price, 0, ',', '.') }}₫</td>
                                    <td>{{ number_format($total, 0, ',', '.') }}₫</td>
                                    <td>
                                        <form action="{{ route('client.cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('client.checkout.show') }}" class="btn theme-btn--dark1 btn--lg">Thanh toán</a>

                </div>
            @endif
        </div>
    </section>

@endsection

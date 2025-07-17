@extends('Client.Layouts.ClientLayout')


@section('css')
    <style>
        .container {
            max-width: 1360px;
        }

        /* Giao diện tổng thể bảng */
        .table-wishlist {
            background-color: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .table-wishlist thead {
            background-color: #f8f9fa;
            color: #333;
        }

        .table-wishlist th {
            padding: 15px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid #dee2e6;
        }

        /* Dòng sản phẩm */
        .table-wishlist .product-row {
            transition: background-color 0.3s ease;
        }

        .table-wishlist .product-row:hover {
            background-color: #fdfdfd;
        }

        /* Cột ảnh */
        .product-image-container {
            width: 90px;
            height: 90px;
            overflow: hidden;
            border-radius: 6px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Tên sản phẩm */
        .product-title a {
            color: #333;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
        }

        .product-title a:hover {
            color: #38bcb2;
        }

        /* Size, màu */
        .product-row p {
            margin-bottom: 2px;
            font-size: 13px;
            color: #666;
        }

        /* Giá */
        .price-box {
            font-size: 15px;
            font-weight: 500;
            color: #222;
        }

        .price-box del {
            color: #aaa;
            font-size: 13px;
        }

        /* Tồn kho */
        .stock-status {
            font-size: 14px;
            font-weight: 500;
        }

        /* Button hành động */
        td.action {
            gap: 8px;
            justify-content: flex-start;
            height: auto !important;
        }

        td.action form {
            margin: 0;
        }

        td.action .btn {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .btn.btn-add-cart {
            background-color: #38bcb2;
            border: none;
            color: #fff;
        }

        .btn.btn-add-cart:hover {
            background-color: #2fa7a0;
        }

        .btn.btn-quickview {
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            color: #333;
        }

        .btn.btn-quickview:hover {
            background-color: #e2e6ea;
        }
        .product-default .btn-add-cart i {
            display: inline-block !important;
        }
        .product-default .btn-add-cart i {
    display: inline-block !important;
}
.product-action {
    display: flex;
    justify-content: center;
    gap: 8px; /* khoảng cách giữa các nút, có thể điều chỉnh */
}

.product-action a.btn-icon-wish,
.product-action a.btn-quickview {
    display: flex;
    align-items: center;
    justify-content: center;
}
    </style>
@endsection
@section('main')


    <div class="page-wrapper">

        <main class="main">
             <div class="category-banner banner p-0">
                <div class="row align-items-center no-gutters m-0 text-center text-lg-left">
                    <div
                        class="col-md-4 col-xl-2 offset-xl-2 d-flex justify-content-center justify-content-lg-start my-5 my-lg-0">
                        <div class="d-flex flex-column justify-content-center">
                            <h3 class="text-left text-light text-uppercase m-0">Ưu đãi</h3>
                            <h2 class="text-uppercase m-b-1">Giảm 20%</h2>
                            <h3 class="font-weight-bold text-uppercase heading-border ml-0 m-b-3">Thể thao</h3>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4 text-md-center my-5 my-lg-0"
                        style="background-image: url('{{ asset('assets/images/demoes/demo27/banners/shop-banner-bg.png') }}');">
                        <img class="d-inline-block" src="{{ asset('assets/images/demoes/demo27/banners/shop-banner.png') }}" alt="banner"
                            width="400" height="242">
                    </div>
                    <div class="col-md-3 my-5 my-lg-0">
                        <h4 class="font5 line-height-1 m-b-4">Khuyến mãi mùa hè</h4>
                        <a href="#" class="btn btn-teritary btn-lg ml-0">Xem tất cả khuyến mãi</a>
                    </div>
                </div>
            </div>
            <section class="popular-products">
                <div class="container">
                    <div class="row">
                        <div class="products-slider 5col owl-carousel owl-theme appear-animate" data-owl-options="{
            'margin': 0
        }" data-animation-name="fadeInUpShorter" data-animation-delay="200">
                            @foreach ($wishlists as $wishlist)
                                @php
                                    $product = $wishlist->product;
                                    $image = $product->images->first()->image ?? 'product_images/default.jpg';
                                    $name = $product->name ?? 'Sản phẩm';
                                    $variants = $product->variants;
                                    $minPrice = $variants->min('price');
                                    $minSale = $variants->min('sale_price');
                                    $discountPercent = $minSale > 0 ? round((($minPrice - $minSale) / $minPrice) * 100) : 0;
                                @endphp

                                <div class="product-default">
                                    <figure>
                                        <a href="{{ route('client.product.detail', $product->id) }}">
                                            <img src="{{ asset('storage/' . $image) }}" width="280" height="280"
                                                alt="{{ $name }}" style="object-fit: contain;">
                                        </a>
                                        @if ($discountPercent > 0)
                                            <div class="label-group">
                                                <div class="product-label label-hot">-{{ $discountPercent }}%</div>
                                            </div>
                                        @endif
                                    </figure>

                                    <div class="product-details">
                                        <div class="category-list">
                                            <a href="#" class="product-category">Yêu thích</a>
                                        </div>
                                        <h3 class="product-title">
                                            <a href="{{ route('client.product.detail', $product->id) }}">{{ $name }}</a>
                                        </h3>

                                        <div class="ratings-container">
                                            <div class="product-ratings">
                                                <span class="ratings" style="width:80%"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                        </div>

                                        <div class="price-box">
                                            @if ($minSale > 0)
                                                <del class="text-muted">{{ number_format($minPrice, 0, ',', '.') }}₫</del>
                                                <span class="product-price text-danger">{{ number_format($minSale, 0, ',', '.')
                                                    }}₫</span>
                                            @else
                                                <span class="product-price">{{ number_format($minPrice, 0, ',', '.') }}₫</span>
                                            @endif
                                        </div>

                                        <div class="product-action">
                                            <a href="#" class="btn-icon-wish" title="Xoá yêu thích"
                                                onclick="event.preventDefault(); submitDeleteForm('remove-wishlist-{{ $wishlist->id }}')">
                                                <i class="icon-heart" style="color: #e74c3c;"></i>
                                            </a>
                                            <form id="remove-wishlist-{{ $wishlist->id }}"
                                                action="{{ route('client.wishlist.remove', $wishlist->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>


                                            {{-- Thêm vào giỏ hàng --}}
                                            <form action="{{ route('client.cart.add', $product->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id ?? '' }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn-icon btn-add-cart" >
                                                    
                                                    <i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span>
                                                </button>
                                            </form>
                                            

                                            <a href="{{ route('client.product.detail', $product->id) }}" class="btn-quickview" title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            </section>
        </main><!-- End .main -->

    </div><!-- End .page-wrapper -->
@endsection

@section('js')

    <script>
        function submitForm(formId) {
            const form = document.getElementById(formId);
            if (form) form.submit();
        }

        function submitDeleteForm(formId) {
            if (confirm("Bạn có chắc chắn muốn bỏ sản phẩm ra khỏi danh sách yêu thích không?")) {
                submitForm(formId);
            }
        }
    </script>


@endsection
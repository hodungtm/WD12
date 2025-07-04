@extends('Client.Layouts.ClientLayout')

@section('css')
    <style>
    </style>
@endsection
@section('main')

    <body>
        <!-- header start -->
        <header>
            <!-- header-middle satrt -->
            <!-- main slider start -->
            <section class="bg-light position-relative">
                <div class="main-slider dots-style theme1">
                    @foreach($banners as $banner)
                        @foreach($banner->hinhAnhBanner as $hinh)
                            <div class="slider-item bg-img"
                                style="background-image: url('{{ asset('storage/' . $hinh->hinh_anh) }}')">
                                <div class="container">
                                    <div class="row align-items-center slider-height">
                                        <div class="col-12">
                                            <div class="slider-content">
                                                <p class="text text-white text-uppercase animated mb-25"
                                                    data-animation-in="fadeInDown">
                                                    {{ $banner->tieu_de }}
                                                </p>
                                                <h4 class="title text-white animated text-capitalize mb-20"
                                                    data-animation-in="fadeInLeft" data-delay-in="1">
                                                    {{ $banner->noi_dung }}
                                                </h4>
                                                <a href="#"
                                                    class="btn theme--btn1 btn--lg text-uppercase rounded-5 animated mt-45 mt-sm-25"
                                                    data-animation-in="zoomIn" data-delay-in="3">
                                                    Xem ngay
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <!-- slick-progress -->
                <div class="slick-progress">
                    <span></span>
                </div>
                <!-- slick-progress end -->
            </section>
   
            <div class="common-banner pt-80 bg-white">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 mb-30">
                            <div class="banner-thumb">
                                <a href="shop-grid-4-column.html" class="zoom-in d-block overflow-hidden">
                                    <img src="assets/img/banner/1.jpg" alt="banner-thumb-naile">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-30">
                            <div class="banner-thumb">
                                <a href="shop-grid-4-column.html" class="zoom-in d-block overflow-hidden">
                                    <img src="assets/img/banner/2.jpg" alt="banner-thumb-naile">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-30">
                            <div class="banner-thumb">
                                <a href="shop-grid-4-column.html" class="zoom-in d-block overflow-hidden">
                                    <img src="assets/img/banner/3.jpg" alt="banner-thumb-naile">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- common banner  end -->
            <!-- product tab start -->
            <section class="product-tab bg-white pt-50 pb-80">
                <div class="container">
                    <div class="product-tab-nav mb-35">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="section-title text-center mb-30">
                                    <h2 class="title text-dark text-capitalize">SẢN PHẨM CỦA CHÚNG TÔI</h2>
                                    <p class="text mt-10">Add our products to weekly line up</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <nav class="product-tab-menu theme1">
                                    <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
                                        @foreach($categories as $index => $category)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                                    id="tab-{{ $category->id }}" data-bs-toggle="pill"
                                                    href="#category-{{ $category->id }}" role="tab"
                                                    aria-controls="category-{{ $category->id }}"
                                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                                    {{ $category->ten_danh_muc }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </nav>
                            </div>

                        </div>
                    </div>
                    <!-- product-tab-nav end -->
                    <div class="row">
                        <div class="col-12">
                            <div class="tab-content" id="pills-tabContent">
                                @foreach($categories as $index => $category)
                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                        id="category-{{ $category->id }}" role="tabpanel"
                                        aria-labelledby="tab-{{ $category->id }}">

                                        <div class="row">
                                            @forelse($category->products as $product)
                                                <div class="col-lg-3 col-md-4" style="width: 20%">
                                                    <div class="card product-card">
                                                        <div class="card-body p-2">
                                                            <div class="product-thumbnail position-relative text-center">
                                                                <span class="badge badge-danger top-right">New</span>
                                                                <a href=" {{ route('client.product.detail', $product->id) }}">
                                                                    @if($product->images->count())
                                                                        <img src="{{ asset('storage/' . $product->images->first()->image) }}"
                                                                            alt="{{ $product->name }}">
                                                                    @else
                                                                        <p>Không có ảnh</p>
                                                                    @endif
                                                                </a>
                                                                <ul class="product-links d-flex justify-content-center">
                                                                    <li><a href="#"><span class="icon-heart"
                                                                                title="Wishlist"></span></a></li>
                                                                    <li><a href="#" data-bs-toggle="modal"
                                                                            data-bs-target="#compare"><span class="icon-shuffle"
                                                                                title="Compare"></span></a></li>
                                                                    <li><a href="#" data-bs-toggle="modal"
                                                                            data-bs-target="#quick-view"><span
                                                                                class="icon-magnifier"
                                                                                title="Quick view"></span></a></li>
                                                                </ul>
                                                            </div>
                                                            <div class="product-desc mt-3 text-center">
                                                                <h5 class="title">{{ $product->name }}</h5>
                                                                <div class="star-rating mb-2">
                                                                    @for ($i = 1; $i <= 5; $i++) <span
                                                                            class="ion-ios-star{{ $i > $product->rating ? ' de-selected' : '' }}">
                                                                        </span>
                                                                    @endfor
                                                                </div>
                                                                <h6 class="product-price">Liên hệ</h6>
                                                                <button class="btn btn-sm btn-outline-dark mt-2">
                                                                    <i class="icon-basket"></i> Thêm vào giỏ
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center">Chưa có sản phẩm nào.</p>
                                            @endforelse
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- product tab end -->
            <!-- common banner  start -->
            <div class="common-banner bg-white pb-50">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-30">
                            <div class="banner-thumb">
                                <div class="zoom-in d-block overflow-hidden position-relative">
                                    <img src="assets/img/banner/4.jpg" alt="banner-thumb-naile">
                                    <a href="shop-grid-4-column.html"
                                        class="text-uppercase btn theme--btn1 btn--lg banner-btn position-absolute">Men’s</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-30">
                            <div class="banner-thumb">
                                <div class="zoom-in d-block overflow-hidden position-relative">
                                    <img src="assets/img/banner/5.jpg" alt="banner-thumb-naile">
                                    <a href="shop-grid-4-column.html"
                                        class="text-uppercase btn theme--btn1 btn--lg banner-btn position-absolute">woMen’s</a>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-30">
                            <div class="banner-thumb">
                                <div class="zoom-in d-block overflow-hidden position-relative mb-30">
                                    <img src="assets/img/banner/6.jpg" alt="banner-thumb-naile">
                                    <a href="shop-grid-4-column.html"
                                        class="text-uppercase btn theme--btn1 btn--lg banner-btn position-absolute">running</a>
                                </div>
                                <div class="zoom-in d-block overflow-hidden position-relative">
                                    <img src="assets/img/banner/7.jpg" alt="banner-thumb-naile">
                                    <a href="shop-grid-4-column.html"
                                        class="text-uppercase btn theme--btn1 btn--lg banner-btn position-absolute">accessories</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- common banner  end -->
            <!-- product tab repetation start -->
            {{-- <section class="bg-white theme1 pb-80">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-5 col-xl-4 mb-5 mb-lg-0">
                            <div class="card product-card no-shadow theme-border">
                                <div class="product-ctry-init slick-nav-sync">
                                    <div class="slider-item">
                                        <div class="card-body pt-4 px-4 pb-5 position-relative">
                                            <div class="hot-deal d-flex align-items-center justify-content-between">
                                                <h3 class="title text-dark text-capitalize">hot deals</h3><span
                                                    class="badge badge-success position-static cb6">-20%</span>
                                            </div>
                                            <!-- countdown-sync-init -->
                                            <div class="countdown-sync-init">
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/1.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist"
                                                                        class="icon-heart"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare"
                                                                        class="icon-shuffle">

                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view"
                                                                        class="icon-magnifier">
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/2.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist"
                                                                        class="icon-heart"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare"
                                                                        class="icon-shuffle">

                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view"
                                                                        class="icon-magnifier">
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/3.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist"
                                                                        class="icon-heart"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare"
                                                                        class="icon-shuffle">

                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view"
                                                                        class="icon-magnifier">
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/4.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist"
                                                                        class="icon-heart"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare"
                                                                        class="icon-shuffle">

                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view"
                                                                        class="icon-magnifier">
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                            </div>
                                            <!-- countdown-sync-nav -->
                                            <div class="countdown-sync-nav">
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)">
                                                            <img src="assets/img/slider/thumb/1.1.jpg" alt="product-thumb">
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"> <img
                                                                src="assets/img/slider/thumb/2.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"> <img
                                                                src="assets/img/slider/thumb/3.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"><img
                                                                src="assets/img/slider/thumb/4.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                            </div>
                                            <div class="product-desc text-center p-0">
                                                <h3 class="title"><a href="shop-grid-4-column.html">Originals Windbreaker
                                                        Winter
                                                        Jacket</a></h3>
                                                <div class="star-rating">
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                </div>
                                                <div class="text-center position-relative">
                                                    <h6 class="product-price"><del class="del">$23.90</del> <span
                                                            class="onsale">$21.51</span></h6>
                                                    <button class="pro-btn pro-btn-right" data-bs-toggle="modal"
                                                        data-bs-target="#add-to-cart"><i class="icon-basket"></i></button>
                                                </div>
                                                <div class="availability mt-15">
                                                    <p>Availability: <span class="in-stock">300 In Stock</span></p>
                                                </div>
                                            </div>
                                            <div class="clockdiv d-md-flex justify-content-center align-items-center">
                                                <div class="title">Hurry Up! Offers ends in:</div>
                                                <div class="text-center" data-countdown="2022/01/01"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- slider-item End -->
                                    <div class="slider-item">
                                        <div class="card-body pt-4 px-4 pb-5 position-relative">
                                            <div class="hot-deal d-flex align-items-center justify-content-between">
                                                <h3 class="title text-dark text-capitalize">hot deals</h3><span
                                                    class="badge badge-success position-static cb6">-20%</span>
                                            </div>
                                            <!-- countdown-sync-init -->
                                            <div class="countdown-sync-init">
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/1.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist">
                                                                        <i class="icon-heart"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare">
                                                                        <i class="icon-shuffle"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view">
                                                                        <i class="icon-magnifier"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/2.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist">
                                                                        <i class="icon-heart"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare">
                                                                        <i class="icon-shuffle"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view">
                                                                        <i class="icon-magnifier"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/3.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist">
                                                                        <i class="icon-heart"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare">
                                                                        <i class="icon-shuffle"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view">
                                                                        <i class="icon-magnifier"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/4.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist">
                                                                        <i class="icon-heart"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare">
                                                                        <i class="icon-shuffle"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view">
                                                                        <i class="icon-magnifier"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                            </div>
                                            <!-- countdown-sync-nav -->
                                            <div class="countdown-sync-nav">
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)">
                                                            <img src="assets/img/slider/thumb/1.1.jpg" alt="product-thumb">
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"> <img
                                                                src="assets/img/slider/thumb/2.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"> <img
                                                                src="assets/img/slider/thumb/3.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"><img
                                                                src="assets/img/slider/thumb/4.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                            </div>
                                            <div class="product-desc text-center p-0">
                                                <h3 class="title"><a href="shop-grid-4-column.html">Originals Windbreaker
                                                        Winter
                                                        Jacket</a></h3>
                                                <div class="star-rating">
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                </div>
                                                <div class="text-center position-relative">
                                                    <h6 class="product-price"><del class="del">$23.90</del> <span
                                                            class="onsale">$21.51</span></h6>
                                                    <button class="pro-btn pro-btn-right" data-bs-toggle="modal"
                                                        data-bs-target="#add-to-cart"><i class="icon-basket"></i></button>
                                                </div>
                                                <div class="availability mt-15">
                                                    <p>Availability: <span class="in-stock">300 In Stock</span></p>
                                                </div>
                                            </div>
                                            <div class="clockdiv d-md-flex justify-content-center align-items-center">
                                                <div class="title">Hurry Up! Offers ends in:</div>
                                                <div class="text-center" data-countdown="2022/01/01"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- slider-item End -->
                                    <div class="slider-item">
                                        <div class="card-body pt-4 px-4 pb-5 position-relative">
                                            <div class="hot-deal d-flex align-items-center justify-content-between">
                                                <h3 class="title text-dark text-capitalize">hot deals</h3><span
                                                    class="badge badge-success position-static cb6">-20%</span>
                                            </div>
                                            <!-- countdown-sync-init -->
                                            <div class="countdown-sync-init">
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/1.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist">
                                                                        <i class="icon-heart"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare">
                                                                        <i class="icon-shuffle"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view">
                                                                        <i class="icon-magnifier"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/2.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist">
                                                                        <i class="icon-heart"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare">
                                                                        <i class="icon-shuffle"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view">
                                                                        <i class="icon-magnifier"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/3.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist">
                                                                        <i class="icon-heart"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare">
                                                                        <i class="icon-shuffle"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view">
                                                                        <i class="icon-magnifier"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <img src="assets/img/slider/thumb/4.jpg" alt="product-thumb">
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="add to wishlist">
                                                                        <i class="icon-heart"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Add to compare">
                                                                        <i class="icon-shuffle"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        data-original-title="Quick view">
                                                                        <i class="icon-magnifier"></i>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                            </div>
                                            <!-- countdown-sync-nav -->
                                            <div class="countdown-sync-nav">
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)">
                                                            <img src="assets/img/slider/thumb/1.1.jpg" alt="product-thumb">
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"> <img
                                                                src="assets/img/slider/thumb/2.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"> <img
                                                                src="assets/img/slider/thumb/3.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                                <div class="countdown-item">
                                                    <div class="product-thumb">
                                                        <a href="javascript:void(0)"><img
                                                                src="assets/img/slider/thumb/4.1.jpg"
                                                                alt="product-thumb"></a>
                                                    </div>
                                                </div>
                                                <!-- single-product end -->
                                            </div>
                                            <div class="product-desc text-center p-0">
                                                <h3 class="title"><a href="shop-grid-4-column.html">Originals Windbreaker
                                                        Winter
                                                        Jacket</a></h3>
                                                <div class="star-rating">
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                    <span class="ion-ios-star"></span>
                                                </div>
                                                <div class="text-center position-relative">
                                                    <h6 class="product-price"><del class="del">$23.90</del> <span
                                                            class="onsale">$21.51</span></h6>
                                                    <button class="pro-btn pro-btn-right" data-bs-toggle="modal"
                                                        data-bs-target="#add-to-cart"><i class="icon-basket"></i></button>
                                                </div>
                                                <div class="availability mt-15">
                                                    <p>Availability: <span class="in-stock">300 In Stock</span></p>
                                                </div>
                                            </div>
                                            <div class="clockdiv d-md-flex justify-content-center align-items-center">
                                                <div class="title">Hurry Up! Offers ends in:</div>
                                                <div class="text-center" data-countdown="2022/01/01"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- slider-item End -->
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 col-xl-8">
                            <!-- section-title start -->
                            <div class="section-title text-center mb-30">
                                <h2 class="title text-dark text-capitalize">New Arrivals</h2>
                                <p class="text mt-10">Add new products to weekly line up</p>
                            </div>
                            <!-- section-title end -->
                            <div class="product-slider2-init theme1 slick-nav">
                                <div class="slider-item">
                                    <div class="product-list mb-30">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/1.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">New Luxury
                                                                    Men's Slim Fit Shirt Short Sleeve...</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price">$11.90</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                    <div class="product-list">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-success top-left">-10%</span>
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/6.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">New Balance
                                                                    Running Arishi trainers in triple</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price"><del class="del">$23.90</del>
                                                                    <span class="onsale">$21.51</span>
                                                                </h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="product-list mb-30">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/2.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">New Balance
                                                                    Fresh Foam Kaymin from new zeland</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price">$11.90</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                    <div class="product-list">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/7.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">New Balance
                                                                    Running Fuel Cell trainers</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price">$11.90</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="product-list mb-30">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/3.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">Juicy
                                                                    Couture
                                                                    Tricot Logo Stripe Jacket</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price"> $21.51</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                    <div class="product-list">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/8.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">orginal
                                                                    Kaval
                                                                    Windbreaker Winter Jacket</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price">$11.90</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="product-list mb-30">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/4.jpg"
                                                                alt="thumbnail">
                                                            <img class="second-img" src="assets/img/product/12.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">Juicy
                                                                    Couture
                                                                    Tricot Logo Stripe Jacket</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price"><del class="del">$23.90</del>
                                                                    <span class="onsale">$21.51</span>
                                                                </h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                    <div class="product-list">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/9.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">orginal
                                                                    Kaval
                                                                    Windbreaker Winter Jacket</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price">$11.90</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="product-list mb-30">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/5.jpg"
                                                                alt="thumbnail">
                                                            <img class="second-img" src="assets/img/product/12.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">New Luxury
                                                                    Men's
                                                                    Slim Fit Shirt Short Sleeve...</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price">$11.90</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                    <div class="product-list">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/12.jpg"
                                                                alt="thumbnail">
                                                            <img class="second-img" src="assets/img/product/5.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">orginal
                                                                    Kaval
                                                                    Windbreaker Winter Jacket</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price">$11.90</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="product-list mb-30">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/10.jpg"
                                                                alt="thumbnail">
                                                            <img class="second-img" src="assets/img/product/8.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">New Luxury
                                                                    Men's
                                                                    Slim Fit Shirt Short Sleeve...</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price"><del class="del">$23.90</del>
                                                                    <span class="onsale">$21.51</span>
                                                                </h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                    <div class="product-list">
                                        <div class="card product-card">
                                            <div class="card-body p-0">
                                                <div class="media flex-column">
                                                    <div class="product-thumbnail position-relative">
                                                        <span class="badge badge-danger top-right">New</span>
                                                        <a href="single-product.html">
                                                            <img class="first-img" src="assets/img/product/11.jpg"
                                                                alt="thumbnail">
                                                            <img class="second-img" src="assets/img/product/5.jpg"
                                                                alt="thumbnail">
                                                        </a>
                                                        <!-- product links -->
                                                        <ul class="product-links d-flex justify-content-center">
                                                            <li>
                                                                <a href="wishlist.html">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="add to wishlist" class="icon-heart"> </span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#compare">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Add to compare" class="icon-shuffle"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view">
                                                                    <span data-bs-toggle="tooltip" data-placement="bottom"
                                                                        title="Quick view" class="icon-magnifier"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- product links end-->
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="product-desc">
                                                            <h3 class="title"><a href="shop-grid-4-column.html">orginal
                                                                    Kaval
                                                                    Windbreaker Winter Jacket</a></h3>
                                                            <div class="star-rating">
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star"></span>
                                                                <span class="ion-ios-star de-selected"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="product-price">$11.90</h6>
                                                                <button class="pro-btn" data-bs-toggle="modal"
                                                                    data-bs-target="#add-to-cart"><i
                                                                        class="icon-basket"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product-list End -->
                                    </div>
                                </div>
                                <!-- slider-item end -->
                            </div>
                        </div>
                    </div>
                </div>
            </section> --}}
            <!-- product tab repetation end -->
            <!-- staic media start -->
            <section class="static-media-section pb-80 bg-white">
                <div class="container">
                    <div class="static-media-wrap theme-bg rounded-5">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 py-3">
                                <div class="d-flex static-media2 flex-column flex-sm-row">
                                    <img class="align-self-center mb-2 mb-sm-0 me-auto  me-sm-3" src="assets/img/icon/2.png"
                                        alt="icon">
                                    <div class="media-body">
                                        <h4 class="title text-capitalize text-white">Free Shipping</h4>
                                        <p class="text text-white">On all orders over $75.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 py-3">
                                <div class="d-flex static-media2 flex-column flex-sm-row">
                                    <img class="align-self-center mb-2 mb-sm-0 me-auto  me-sm-3" src="assets/img/icon/3.png"
                                        alt="icon">
                                    <div class="media-body">
                                        <h4 class="title text-capitalize text-white">Free Returns</h4>
                                        <p class="text text-white">Returns are free within 9 days</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 py-3">
                                <div class="d-flex static-media2 flex-column flex-sm-row">
                                    <img class="align-self-center mb-2 mb-sm-0 me-auto  me-sm-3" src="assets/img/icon/4.png"
                                        alt="icon">
                                    <div class="media-body">
                                        <h4 class="title text-capitalize text-white">100% Payment Secure</h4>
                                        <p class="text text-white">Your payment are safe with us.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 py-3">
                                <div class="d-flex static-media2 flex-column flex-sm-row">
                                    <img class="align-self-center mb-2 mb-sm-0 me-auto  me-sm-3" src="assets/img/icon/5.png"
                                        alt="icon">
                                    <div class="media-body">
                                        <h4 class="title text-capitalize text-white">Support 24/7</h4>
                                        <p class="text text-white">Contact us 24 hours a day</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- staic media end -->
            <!-- blog-section start -->
            <section class="blog-section theme1 pb-65">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title text-center mb-30">
                                <h2 class="title text-dark text-capitalize">TIN TỨC</h2>
                                <p class="text mt-10">Cập nhật các bài viết và sự kiện thể thao nổi bật của cửa hàng</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="blog-init slick-nav">
                                @foreach($posts as $post)
                                    <div class="slider-item">
                                        <div class="single-blog">
                                            <a class="blog-thumb mb-20 zoom-in d-block overflow-hidden" href="">
                                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                                    style="height: 200px; object-fit: cover;">
                                            </a>
                                            <div class="blog-post-content">
                                                <a class="blog-link theme-color d-inline-block mb-10 text-uppercase"
                                                    href="#">Tin tức</a>
                                                <h3 class="title text-capitalize mb-15">
                                                    <a href="">{{ $post->title }}</a>
                                                </h3>
                                                <h5 class="sub-title">
                                                    Đăng bởi <a class="blog-link theme-color mx-1" href="#">Admin</a>
                                                    {{ $post->created_at->format('d/m/Y') }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- blog-section end -->
            <!-- brand slider start -->
            <div class="brand-slider-section theme1 bg-white">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="brand-init border-top py-35 slick-nav-brand">
                                <div class="slider-item">
                                    <div class="single-brand">
                                        <a href="https://themeforest.net/user/hastech" class="brand-thumb">
                                            <img src="assets/img/brand/1.jpg" alt="brand-thumb-nail">
                                        </a>
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="single-brand">
                                        <a href="https://themeforest.net/user/hastech" class="brand-thumb">
                                            <img src="assets/img/brand/2.jpg" alt="brand-thumb-nail">
                                        </a>
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="single-brand">
                                        <a href="https://themeforest.net/user/hastech" class="brand-thumb">
                                            <img src="assets/img/brand/3.jpg" alt="brand-thumb-nail">
                                        </a>
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="single-brand">
                                        <a href="https://themeforest.net/user/hastech" class="brand-thumb">
                                            <img src="assets/img/brand/4.jpg" alt="brand-thumb-nail">
                                        </a>
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="single-brand">
                                        <a href="https://themeforest.net/user/hastech" class="brand-thumb">
                                            <img src="assets/img/brand/5.jpg" alt="brand-thumb-nail">
                                        </a>
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="single-brand">
                                        <a href="https://themeforest.net/user/hastech" class="brand-thumb">
                                            <img src="assets/img/brand/2.jpg" alt="brand-thumb-nail">
                                        </a>
                                    </div>
                                </div>
                                <!-- slider-item end -->
                                <div class="slider-item">
                                    <div class="single-brand">
                                        <a href="https://themeforest.net/user/hastech" class="brand-thumb">
                                            <img src="assets/img/brand/3.jpg" alt="brand-thumb-nail">
                                        </a>
                                    </div>
                                </div>
                                <!-- slider-item end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- brand slider end -->

            <!-- modals start -->
            <!-- first modal -->
            <div class="modal fade theme1 style1" id="quick-view" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto col-lg-5 mb-5 mb-lg-0">
                                    <div class="product-sync-init mb-20">
                                        <div class="single-product">
                                            <div class="product-thumb">
                                                <img src="assets/img/slider/thumb/1.jpg" alt="product-thumb">
                                            </div>
                                        </div>
                                        <!-- single-product end -->
                                        <div class="single-product">
                                            <div class="product-thumb">
                                                <img src="assets/img/slider/thumb/2.jpg" alt="product-thumb">
                                            </div>
                                        </div>
                                        <!-- single-product end -->
                                        <div class="single-product">
                                            <div class="product-thumb">
                                                <img src="assets/img/slider/thumb/3.jpg" alt="product-thumb">
                                            </div>
                                        </div>
                                        <!-- single-product end -->
                                        <div class="single-product">
                                            <div class="product-thumb">
                                                <img src="assets/img/slider/thumb/4.jpg" alt="product-thumb">
                                            </div>
                                        </div>
                                        <!-- single-product end -->
                                    </div>

                                    <div class="product-sync-nav">
                                        <div class="single-product">
                                            <div class="product-thumb">
                                                <a href="javascript:void(0)"> <img src="assets/img/slider/thumb/1.1.jpg"
                                                        alt="product-thumb"></a>
                                            </div>
                                        </div>
                                        <!-- single-product end -->
                                        <div class="single-product">
                                            <div class="product-thumb">
                                                <a href="javascript:void(0)"> <img src="assets/img/slider/thumb/2.1.jpg"
                                                        alt="product-thumb"></a>
                                            </div>
                                        </div>
                                        <!-- single-product end -->
                                        <div class="single-product">
                                            <div class="product-thumb">
                                                <a href="javascript:void(0)"><img src="assets/img/slider/thumb/3.1.jpg"
                                                        alt="product-thumb"></a>
                                            </div>
                                        </div>
                                        <!-- single-product end -->
                                        <div class="single-product">
                                            <div class="product-thumb">
                                                <a href="javascript:void(0)"><img src="assets/img/slider/thumb/4.1.jpg"
                                                        alt="product-thumb"></a>
                                            </div>
                                        </div>
                                        <!-- single-product end -->
                                    </div>
                                </div>
                                <div class="col-lg-7 mt-5 mt-md-0">
                                    <div class="modal-product-info">
                                        <div class="product-head">
                                            <h2 class="title">New Balance Running Arishi trainers in triple</h2>
                                            <h4 class="sub-title">Reference: demo_5</h4>
                                            <div class="star-content mb-20">
                                                <span class="star-on"><i class="fas fa-star"></i> </span>
                                                <span class="star-on"><i class="fas fa-star"></i> </span>
                                                <span class="star-on"><i class="fas fa-star"></i> </span>
                                                <span class="star-on"><i class="fas fa-star"></i> </span>
                                                <span class="star-on de-selected"><i class="fas fa-star"></i> </span>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <span class="product-price text-center"> <span class="new-price">$29.00</span>
                                            </span>
                                            <p>Break old records and make new goals in the New Balance® Arishi Sport v1.</p>
                                            <ul>
                                                <li>Predecessor: None.</li>
                                                <li>Support Type: Neutral.</li>
                                                <li>Cushioning: High energizing cushioning.</li>
                                            </ul>
                                        </div>
                                        <div class="d-flex mt-30">
                                            <div class="product-size">
                                                <h3 class="title">Dimension</h3>
                                                <select>
                                                    <option value="0">40x60cm</option>
                                                    <option value="1">60x90cm</option>
                                                    <option value="2">80x120cm</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="product-footer">
                                            <div class="product-count style d-flex flex-column flex-sm-row my-4">
                                                <div class="count d-flex">
                                                    <input type="number" min="1" max="10" step="1" value="1">
                                                    <div class="button-group">
                                                        <button class="count-btn increment"><i
                                                                class="fas fa-chevron-up"></i></button>
                                                        <button class="count-btn decrement"><i
                                                                class="fas fa-chevron-down"></i></button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button class="btn theme-btn--dark1 btn--xl mt-5 mt-sm-0 rounded-5">
                                                        <span class="me-2"><i class="ion-android-add"></i></span>
                                                        Add to cart
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="addto-whish-list">
                                                <a href="#"><i class="icon-heart"></i> Add to wishlist</a>
                                                <a href="#"><i class="icon-shuffle"></i> Add to compare</a>
                                            </div>
                                            <div class="pro-social-links mt-10">
                                                <ul class="d-flex align-items-center">
                                                    <li class="share">Share</li>
                                                    <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                                    <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                                    <li><a href="#"><i class="ion-social-google"></i></a></li>
                                                    <li><a href="#"><i class="ion-social-pinterest"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- second modal -->
            <div class="modal fade style2" id="compare" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <div class="modal-body">
                            <h5 class="title"><i class="fa fa-check"></i> Product added to compare.</h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- second modal -->
            <div class="modal fade style3" id="add-to-cart" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center bg-dark">
                            <h5 class="modal-title" id="add-to-cartCenterTitle"> <span class="ion-checkmark-round"></span>
                                Product successfully added to your shopping cart</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-5 divide-right">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="assets/img/modal/1.jpg" alt="img">
                                        </div>
                                        <div class="col-md-6 mb-2 mb-md-0">
                                            <h4 class="product-name">New Balance Running Arishi trainers in triple</h4>
                                            <h5 class="price">$$29.00</h5>
                                            <h6 class="color"><strong>Dimension: </strong>: <span class="dmc">40x60cm</span>
                                            </h6>
                                            <h6 class="quantity"><strong>Quantity:</strong>&nbsp;1</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="modal-cart-content">
                                        <p class="cart-products-count">There is 1 item in your cart.</p>
                                        <p><strong>Total products:</strong>&nbsp;$123.72</p>
                                        <p><strong>Total shipping:</strong>&nbsp;$7.00 </p>
                                        <p><strong>Taxes</strong>&nbsp;$0.00</p>
                                        <p><strong>Total:</strong>&nbsp;$130.72 (tax excl.)</p>
                                        <div class="cart-content-btn">
                                            <button type="button" class="btn theme-btn--dark1 btn--md mt-4"
                                                data-bs-dismiss="modal">Continue
                                                shopping</button>
                                            <button class="btn theme-btn--dark1 btn--md mt-4"><i
                                                    class="ion-checkmark-round"></i>Proceed to
                                                checkout</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
            <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>


    </body>



@endsection
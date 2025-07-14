@extends('Client.Layouts.ClientLayout')
@section('main')
<main class="main">
    <!-- Banner/Slider động -->
    <section class="intro-section">
        <div class="container">
            <div class="home-slider slide-animate owl-carousel owl-theme owl-carousel-lazy">
                @foreach($banners as $banner)
                    @foreach($banner->hinhAnhBanner as $hinh)
                    <div class="home-slide banner d-flex flex-wrap">
                        <div class="col-lg-4 d-flex justify-content-center">
                            <div class="d-flex flex-column justify-content-center appear-animate"
                                data-animation-name="fadeInLeftShorter" data-animation-delay="200">
                                <h4 class="text-light text-uppercase m-b-1">{{ $banner->tieu_de }}</h4>
                                <h2 class="text-uppercase m-b-1">{{ $banner->noi_dung }}</h2>
                                <div>
                                    <a href="#" class="btn btn-dark btn-lg">Xem ngay</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 with-bg"
                            style="background-image: url('{{ asset('storage/' . $hinh->hinh_anh) }}');">
                            <div class="appear-animate" data-animation-name="fadeInLeftShorter"
                                data-animation-delay="500">
                                <img class="m-b-5" src="{{ asset('storage/' . $hinh->hinh_anh) }}" width="740"
                                    height="397" alt="banner" />
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>
    <!-- Danh mục động -->
    <section class="popular-products">
        <div class="container">
            <h2 class="section-title appear-animate" data-animation-name="fadeInUpShorter"
                data-animation-delay="200">Danh mục sản phẩm</h2>
            <div class="categories-slider owl-carousel owl-theme mb-4 appear-animate" data-owl-options="{ 'margin': 2, 'nav': false, 'items': 1, 'responsive': { '992': { 'items': 4 }, '1200': { 'items': 5 } } }" data-animation-name="fadeInUpShorter" data-animation-delay="200">
                @foreach($categories as $category)
                <a href="{{ route('client.listproduct', ['category' => $category->id]) }}" class="product-category" style="text-decoration: none;">
                    <img src="{{ asset('storage/' . $category->anh) }}" alt="icon" width="60" height="60">
                    <div class="category-content">
                        <h3 class="font2 ls-0 text-uppercase mb-0">{{ $category->ten_danh_muc }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Sản phẩm nổi bật động -->
    <section class="popular-products">
        <div class="container">
            <h2 class="section-title appear-animate" data-animation-name="fadeInUpShorter"
                data-animation-delay="200">Sản phẩm nổi bật</h2>
            <div class="row">
                <div class="products-slider 5col owl-carousel owl-theme appear-animate" data-owl-options="{ 'margin': 0 }" data-animation-name="fadeInUpShorter" data-animation-delay="200">
                    @foreach($products as $product)
                    <div class="product-default">
                        <figure>
                            <a href="{{ route('client.product.detail', $product->id) }}">
                                <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image) : asset('assets/images/no-image.png') }}" width="280" height="280" alt="product">
                            </a>
                            <div class="label-group">
                                @if($loop->first)
                                <div class="product-label label-hot">HOT</div>
                                @endif
                            </div>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">{{ $product->category->ten_danh_muc ?? '' }}</a>
                            </div>
                            <h3 class="product-title">
                                <a href="{{ route('client.product.detail', $product->id) }}">{{ $product->name }}</a>
                            </h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    @php
                                        $rating = $product->reviews->avg('so_sao') ?? 0;
                                    @endphp
                                    <span class="ratings" style="width:{{ $rating * 20 }}%"></span>
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                            </div>
                            <div class="price-box">
                                @php
                                    $variant = $product->variants->first();
                                @endphp
                                @if($variant)
                                    @if($variant->sale_price && $variant->sale_price < $variant->price)
                                        <span class="old-price">{{ number_format($variant->price) }}₫</span>
                                        <span class="product-price">{{ number_format($variant->sale_price) }}₫</span>
                                    @else
                                        <span class="product-price">{{ number_format($variant->price) }}₫</span>
                                    @endif
                                @else
                                    <span class="product-price">Liên hệ</span>
                                @endif
                            </div>
                            <div class="product-action">
                                <a href="#" class="btn-icon-wish" title="Yêu thích"
                                           onclick="event.preventDefault(); document.getElementById('add-wishlist-{{ $product->id }}').submit();">
                                            <i class="icon-heart"></i>
                                        </a>
                                        <form id="add-wishlist-{{ $product->id }}" action="{{ route('client.wishlist.add', $product->id) }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>
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
    <!-- Sản phẩm trending động -->
    <section class="trendy-section mb-2">
        <div class="container">
            <h2 class="section-title appear-animate" data-animation-name="fadeInUpShorter"
                data-animation-delay="200">Sản phẩm bán chạy</h2>
            <div class="row appear-animate" data-animation-name="fadeInUpShorter" data-animation-delay="200">
                <div class="products-slider 5col owl-carousel owl-theme" data-owl-options="{ 'margin': 0 }">
                    @foreach($trendingProducts as $product)
                    <div class="product-default">
                        <figure>
                            <a href="{{ route('client.product.detail', $product->id) }}">
                                <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image) : asset('assets/images/no-image.png') }}" width="280" height="280" alt="product">
                            </a>
                            <div class="label-group">
                                <div class="product-label label-hot">HOT</div>
                                @if($product->variants->first() && $product->variants->first()->sale_price && $product->variants->first()->sale_price < $product->variants->first()->price)
                                <div class="product-label label-sale">-{{ 100 - round($product->variants->first()->sale_price / $product->variants->first()->price * 100) }}%</div>
                                @endif
                            </div>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">{{ $product->category->ten_danh_muc ?? '' }}</a>
                            </div>
                            <h3 class="product-title">
                                <a href="{{ route('client.product.detail', $product->id) }}">{{ $product->name }}</a>
                            </h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    @php
                                        $rating = $product->reviews->avg('so_sao') ?? 0;
                                    @endphp
                                    <span class="ratings" style="width:{{ $rating * 20 }}%"></span>
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                            </div>
                            <div class="price-box">
                                @php
                                    $variant = $product->variants->first();
                                @endphp
                                @if($variant)
                                    @if($variant->sale_price && $variant->sale_price < $variant->price)
                                        <span class="old-price">{{ number_format($variant->price) }}₫</span>
                                        <span class="product-price">{{ number_format($variant->sale_price) }}₫</span>
                                    @else
                                        <span class="product-price">{{ number_format($variant->price) }}₫</span>
                                    @endif
                                @else
                                    <span class="product-price">Liên hệ</span>
                                @endif
                            </div>
                            <div class="product-action">
                                <a href="#" class="btn-icon-wish" title="Yêu thích"
                                           onclick="event.preventDefault(); document.getElementById('add-wishlist-{{ $product->id }}').submit();">
                                            <i class="icon-heart"></i>
                                        </a>
                                        <form id="add-wishlist-{{ $product->id }}" action="{{ route('client.wishlist.add', $product->id) }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>
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
            <!-- Banner nhỏ động (footer/khuyến mãi) -->
            <div class="row">
                @foreach($footerBanners as $banner)
                    @foreach($banner->hinhAnhBanner as $hinh)
                    <div class="col-xl-6 mb-2">
                        <div class="banner banner3 d-flex flex-wrap align-items-center bg-gray h-100 appear-animate"
                            data-animation-name="fadeInRightShorter" data-animation-delay="100">
                            <div class="col-sm-4 text-center">
                                <h3 class="font5 mb-0">{{ $banner->tieu_de }}</h3>
                                <h2 class="text-uppercase mb-0">{{ $banner->noi_dung }}</h2>
                            </div>
                            <div class="col-sm-4">
                                <img src="{{ asset('storage/' . $hinh->hinh_anh) }}" alt="banner" width="232" height="124">
                            </div>
                            <div class="col-sm-4 text-center">
                                <a href="#" class="btn btn-dark">Xem ngay</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Blog/tin tức động -->
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
                @foreach($posts as $post)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="single-blog h-100 d-flex flex-column shadow-sm rounded-3 p-2 bg-white" style="transition: box-shadow 0.2s; min-height: 370px;">
                        <a class="blog-thumb mb-3 d-block overflow-hidden rounded-3" 
                           href="{{ route('client.listblog.detail', $post->id) }}">
                            <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/images/no-image.png') }}" 
                                 alt="{{ $post->title }}" style="height: 200px; object-fit: cover; width: 100%; border-radius: 8px; transition: transform 0.2s;">
                        </a>
                        <div class="blog-post-content flex-grow-1 d-flex flex-column">
                            <h3 class="title text-capitalize mb-2" style="font-size: 1.1rem; font-weight: bold;">
                                <a href="{{ route('client.listblog.detail', $post->id) }}" class="text-dark">{{ $post->title }}</a>
                            </h3>
                            <div class="mb-2 text-muted" style="font-size: 0.95rem;">
                                <i class="fa fa-calendar"></i> {{ $post->created_at->format('d/m/Y') }}
                            </div>
                            <div class="mb-3" style="font-size: 0.98rem; color: #555;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 80) }}
                            </div>
                            <a href="{{ route('client.listblog.detail', $post->id) }}" class="btn btn-dark btn-sm align-self-start mb-3">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
<style>
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
.single-blog {
    border: 1px solid #eee;
    border-radius: 8px;
    background: #fff;
    transition: box-shadow 0.2s;
    min-height: 370px;
}
.single-blog:hover {
    box-shadow: 0 4px 24px rgba(0,0,0,0.10);
}
.blog-thumb img {
    border-radius: 8px;
    transition: transform 0.2s;
}
.blog-thumb:hover img {
    transform: scale(1.04);
}
</style>
@extends('Client.Layouts.ClientLayout')

@section('main')
<style>
    .cat-list a.category-link,
    .cat-list a.color-link,
    .cat-list a.size-link {
        color: #666;
        font-weight: 400;
        display: inline-block;
        padding: 2px 0;
        transition: color 0.2s, font-weight 0.2s;
        text-decoration: none;
    }
    .cat-list a.category-link.active,
    .cat-list a.color-link.active,
    .cat-list a.size-link.active,
    .cat-list a.category-link:hover,
    .cat-list a.color-link:hover,
    .cat-list a.size-link:hover {
        color: #1abc9c;
        font-weight: 600;
        text-decoration: underline;
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
.modal-content {
    border-radius: 8px; /* Bo tròn góc modal */
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 8px 8px 0 0; /* Bo tròn góc trên của header */
}

.modal-title {
    font-weight: 700;
    color: #222;
}

.modal-footer {
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 8px 8px; /* Bo tròn góc dưới của footer */
}

/* Nút chọn màu & size trong modal */
.color-btn.demo-style-btn,
.size-btn.demo-style-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 16px;
    border: 2px solid #e1e1e1; /* Viền nhạt hơn */
    border-radius: 5px; /* Bo tròn nút */
    background: #fff;
    color: #333;
    font-weight: 600;
    font-size: 15px;
    margin: 4px 8px 4px 0;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: none;
    outline: none;
    white-space: nowrap;
}

.color-btn.demo-style-btn.active,
.size-btn.demo-style-btn.active {
    border-color: #4DB7B3; /* Màu viền khi được chọn */
    background: #4DB7B3; /* Màu nền khi được chọn */
    color: #fff;
}

.color-btn.demo-style-btn:hover,
.size-btn.demo-style-btn:hover {
    border-color: #4DB7B3;
    color: #4DB7B3;
    background: #e6f8fa; /* Màu nền khi hover */
}

/* Input group cho số lượng */
.input-group {
    border-radius: 5px; /* Bo tròn input group */
    overflow: hidden;
    border: 1.5px solid #e1e1e1;
    background: #fff;
    height: 40px;
}

.input-group .btn {
    background: #fff;
    border: none;
    color: #222;
    font-size: 20px;
    width: 40px;
    height: 40px;
    padding: 0;
    font-weight: 700;
    border-radius: 5px; /* Bo tròn nút tăng giảm */
}

.input-group .btn:active,
.input-group .btn:focus {
    background: #f4f4f4;
    color: #4DB7B3; /* Màu khi nhấn */
}

.input-group .form-control {
    border: none;
    box-shadow: none;
    font-size: 16px;
    font-weight: 700;
    color: #222;
    background: #fff;
    height: 40px;
    width: 60px;
    padding: 0;
    border-radius: 0;
}

/* Nút thêm vào giỏ hàng trong modal */
#modalAddToCartBtn {
    border-radius: 5px; /* Bo tròn nút */
    font-size: 15px;
    font-weight: 700;
    padding: 0 18px;
    min-width: 140px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: 0.5px;
    background: black; /* Màu chủ đạo */
    color: #fff;
    border: 2px solid #4DB7B3;
    transition: all 0.2s;
}

#modalAddToCartBtn:hover:not(:disabled) {
    background: #2D8E89 !important; /* Màu đậm hơn khi hover */
    border-color: #2D8E89 !important;
    color: #fff !important;
}

#modalAddToCartBtn:disabled {
    background: #888;
    border-color: #888;
    color: #fff;
    cursor: not-allowed;
}


</style>
    <div class="category-banner-container bg-gray">
        <div class="container">
            <div class="category-banner banner p-0">
                <div class="row align-items-center no-gutters m-0 text-center text-lg-left">
                    <div
                        class="col-md-4 col-xl-2 offset-xl-2 d-flex justify-content-center justify-content-lg-start my-5 my-lg-0">
                        <div class="d-flex flex-column justify-content-center">
                            <h3 class="text-left text-light text-uppercase m-0">Ưu đãi</h3>
                            <h2 class="text-uppercase m-b-1">{{ isset($pageTitle) ? $pageTitle : 'Cửa hàng' }}</h2>
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
        </div>
    </div>
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cửa hàng</li>
            </ol>
        </div>
    </nav>

    <div class="container">
        <div class="row main-content">
            <div class="col-lg-9">
                <nav class="toolbox sticky-header" data-sticky-options="{'mobile': true}">
                    <div class="toolbox-left">
                        <a href="#" class="sidebar-toggle"><svg data-name="Layer 3" id="Layer_3" viewBox="0 0 32 32"
                                xmlns="http://www.w3.org/2000/svg">
                                <line x1="15" x2="26" y1="9" y2="9" class="cls-1"></line>
                                <line x1="6" x2="9" y1="9" y2="9" class="cls-1"></line>
                                <line x1="23" x2="26" y1="16" y2="16" class="cls-1"></line>
                                <line x1="6" x2="17" y1="16" y2="16" class="cls-1"></line>
                                <line x1="17" x2="26" y1="23" y2="23" class="cls-1"></line>
                                <line x1="6" x2="11" y1="23" y2="23" class="cls-1"></line>
                                <path d="M14.5,8.92A2.6,2.6,0,0,1,12,11.5,2.6,2.6,0,0,1,9.5,8.92a2.5,2.5,0,0,1,5,0Z"
                                    class="cls-2"></path>
                                <path d="M22.5,15.92a2.5,2.5,0,1,1-5,0,2.5,2.5,0,0,1,5,0Z" class="cls-2">
                                </path>
                                <path d="M21,16a1,1,0,1,1-2,0,1,1,0,0,1,2,0Z" class="cls-3"></path>
                                <path d="M16.5,22.92A2.6,2.6,0,0,1,14,25.5a2.6,2.6,0,0,1-2.5-2.58,2.5,2.5,0,0,1,5,0Z"
                                    class="cls-2"></path>
                            </svg>
                            <span>Lọc</span>
                        </a>

                        <div class=" toolbox-sort">
                            <label>Sắp xếp theo:</label>

                            <div class="select-custom">
                                <form method="GET" action="{{ route('client.listproduct') }}" id="sortForm">
                                    <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                    <input type="hidden" name="color" value="{{ request('color') }}">
                                    <input type="hidden" name="size" value="{{ request('size') }}">
                                    <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                                    <input type="hidden" name="price_max" value="{{ request('price_max') }}">

                                    <select name="sort" class="form-control" onchange="this.form.submit()">
                                        <option value="">Mặc định</option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A đến Z</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên: Z đến A</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: thấp đến cao</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: cao đến thấp</option>
                                    </select>
                                </form>
                            </div><!-- End .select-custom -->
                        </div><!-- End .toolbox-item -->
                    </div><!-- End .toolbox-left -->

                    <div class="toolbox-right">
                        <div class="toolbox-show">
                            <label>Hiển thị:</label>

                            <div class="select-custom">
                                <form method="GET" action="{{ route('client.listproduct') }}" id="countForm">
                                    <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                    <input type="hidden" name="color" value="{{ request('color') }}">
                                    <input type="hidden" name="size" value="{{ request('size') }}">
                                    <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                                    <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                    
                                    <select name="count" class="form-control" onchange="this.form.submit()">
                                        <option value="12" {{ request('count', 12) == 12 ? 'selected' : '' }}>12</option>
                                        <option value="24" {{ request('count', 12) == 24 ? 'selected' : '' }}>24</option>
                                        <option value="36" {{ request('count', 12) == 36 ? 'selected' : '' }}>36</option>
                                    </select>
                                </form>
                            </div><!-- End .select-custom -->
                        </div><!-- End .toolbox-item -->

                        <div class="toolbox-item layout-modes">
                            <a href="#" class="layout-btn btn-grid active" title="Lưới">
                                <i class="icon-mode-grid"></i>
                            </a>
                            <a href="#" class="layout-btn btn-list" title="Danh sách">
                                <i class="icon-mode-list"></i>
                            </a>
                        </div><!-- End .layout-modes -->
                    </div><!-- End .toolbox-right -->
                </nav>

                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-6 col-sm-4 col-xl-3">
                            <div class="product-default">
                                <figure>
                                    <a href="{{ route('client.product.detail', ['id' => $product->id]) }}">
                                        @if($product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" width="280"
                                                height="280" alt="{{ $product->name }}">
                                        @else
                                            <img src="{{ asset('assets/images/products/product-1.jpg') }}" width="280" height="280"
                                                alt="{{ $product->name }}">
                                        @endif
                                    </a>
                                    @if($product->discount)
                                        <div class="label-group">
                                            <div class="product-label label-sale">-{{ $product->discount }}%</div>
                                        </div>
                                    @endif
                                </figure>
                                <div class="product-details">
                                    <div class="category-list">
                                        <a href="#"
                                            class="product-category">{{ $product->category->ten_danh_muc ?? 'Category' }}</a>
                                    </div>
                                    <h3 class="product-title">
                                        <a
                                            href="{{ route('client.product.detail', ['id' => $product->id]) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:80%"></span><!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div><!-- End .product-ratings -->
                                    </div><!-- End .product-container -->
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
                                            <button type="button" class="btn-icon btn-add-cart" 
        data-bs-toggle="modal" 
        data-bs-target="#variantModal"
        data-product-id="{{ $product->id }}" 
        data-product-name="{{ $product->name }}"
        data-product-image="{{ asset('storage/' . $product->images->first()->image) }}"
        data-variants='@json($product->variants)'>
    <i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span>
</button>
                                        </form>
                                        <a href="{{ route('client.product.detail', $product->id) }}" class="btn-quickview" title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div><!-- End .product-details -->
                            </div>
                        </div><!-- End .col-xl-3 -->
                    @endforeach
                </div><!-- End .row -->

                <nav class="toolbox toolbox-pagination mb-0">
                    <div class="toolbox-show">
                        <label class="mt-0">Hiển thị:</label>

                        <div class="select-custom">
                            <form method="GET" action="{{ route('client.listproduct') }}" id="countFormBottom">
                                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <input type="hidden" name="color" value="{{ request('color') }}">
                                <input type="hidden" name="size" value="{{ request('size') }}">
                                <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                                <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                                
                                <select name="count" class="form-control" onchange="this.form.submit()">
                                    <option value="12" {{ request('count', 12) == 12 ? 'selected' : '' }}>12</option>
                                    <option value="24" {{ request('count', 12) == 24 ? 'selected' : '' }}>24</option>
                                    <option value="36" {{ request('count', 12) == 36 ? 'selected' : '' }}>36</option>
                                </select>
                            </form>
                        </div><!-- End .select-custom -->
                    </div><!-- End .toolbox-item -->

                    <div class="pagination-wrapper">
                        {{ $products->withQueryString()->links() }}
                    </div>
                </nav>
            </div><!-- End .col-lg-9 -->

            <div class="sidebar-overlay"></div>
            <aside class="sidebar-shop col-lg-3 order-lg-first mobile-sidebar">
                <div class="sidebar-wrapper">
                    <form action="{{ route('client.listproduct') }}" method="GET">
                        <div class="widget">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-body-search" role="button" aria-expanded="true"
                                    aria-controls="widget-body-search">Tìm kiếm</a>
                            </h3>

                            <div class="collapse show" id="widget-body-search">
                                <div class="widget-body">
                                    <div class="blog-search-form">
                                        <input class="form-control rounded" name="keyword" type="text"
                                            value="{{ request('keyword') }}" placeholder="Tìm kiếm sản phẩm...">
                                    </div>
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->

                        <div class="widget">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-body-1" role="button" aria-expanded="true"
                                    aria-controls="widget-body-1">Danh mục</a>
                            </h3>
                            <div class="collapse show" id="widget-body-1">
                                <div class="widget-body">
                                    <ul class="cat-list">
                                        @foreach ($categories as $category)
                                            <li>
                                                <a href="#"
                                                    class="category-link {{ request('category') == $category->id ? 'active' : '' }}"
                                                    data-value="{{ $category->id }}">
                                                    {{ $category->ten_danh_muc }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="category" id="category-input"
                                        value="{{ request('category') }}">
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->

                        <div class="widget widget-price">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-body-2" role="button" aria-expanded="true"
                                    aria-controls="widget-body-2">Giá</a>
                            </h3>

                            <div class="collapse show" id="widget-body-2">
                                <div class="widget-body pb-0">
                                    <div class="price-slider-wrapper">
                                        <div id="price-slider"></div><!-- End #price-slider -->
                                    </div><!-- End .price-slider-wrapper -->

                                    <div
                                        class="filter-price-action d-flex align-items-center justify-content-between flex-wrap">
                                        <div class="filter-price-text">
                                            Price:
                                            <span id="filter-price-range"></span>
                                        </div><!-- End .filter-price-text -->

                                        {{-- Hidden form values --}}
                                        <input type="hidden" name="price_min" id="price_min"
                                            value="{{ request('price_min', $minPrice) }}">
                                        <input type="hidden" name="price_max" id="price_max"
                                            value="{{ request('price_max', $maxPrice) }}">
                                    </div><!-- End .filter-price-action -->
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->

                        <div class="widget widget-color">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-body-3" role="button" aria-expanded="true"
                                    aria-controls="widget-body-3">Màu sắc</a>
                            </h3>
                            <div class="collapse show" id="widget-body-3">
                                <div class="widget-body pb-0">
                                    <ul class="cat-list">
                                        @foreach ($colors as $color)
                                            <li>
                                                <a href="#"
                                                    class="color-link {{ request('color') == $color->id ? 'active' : '' }}"
                                                    data-value="{{ $color->id }}">
                                                    {{ $color->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="color" id="color-input" value="{{ request('color') }}">
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->

                        <div class="widget">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-body-4" role="button" aria-expanded="true"
                                    aria-controls="widget-body-4">Kích thước</a>
                            </h3>
                            <div class="collapse show" id="widget-body-4">
                                <div class="widget-body">
                                    <ul class="cat-list">
                                        @foreach ($sizes as $size)
                                            <li>
                                                <a href="#" class="size-link {{ request('size') == $size->id ? 'active' : '' }}"
                                                    data-value="{{ $size->id }}">
                                                    {{ $size->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="size" id="size-input" value="{{ request('size') }}">
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->

                        <div class="widget-body">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-primary w-50 me-2" type="submit">Lọc</button>
                                <a href="{{ route('client.listproduct') }}" class="btn btn-outline-secondary w-50">Đặt lại</a>
                            </div>
                        </div>
                    </form>
                </div><!-- End .sidebar-wrapper -->
            </aside><!-- End .col-lg-3 -->
        </div><!-- End .row -->
    </div><!-- End .container -->
    <div class="modal fade" id="variantModal" tabindex="-1" role="dialog" aria-labelledby="variantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="variantModalLabel">Chọn biến thể sản phẩm</h5>
                    
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="modalProductImage" src="" alt="Product" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h6 id="modalProductName" class="mb-3"></h6>
                            
                            <form id="variantForm">
                                @csrf
                                <input type="hidden" id="modalProductId" name="product_id">
                                <input type="hidden" id="modalVariantId" name="variant_id">
                                
                                <div class="form-group mb-3">
                                    <label class="form-label mb-2 fw-bold">Màu sắc:</label>
                                    <div class="d-flex flex-wrap gap-2" id="colorOptions">
                                        </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label mb-2 fw-bold">Kích thước:</label>
                                    <div class="d-flex flex-wrap gap-2" id="sizeOptions">
                                        </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label mb-2 fw-bold">Tồn kho:</label>
                                    <span id="modalInventoryInfo" class="text-muted"></span>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <div id="modalDynamicPrice" class="fw-bold fs-5 text-primary"></div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label mb-2 fw-bold">Số lượng:</label>
                                    <div class="d-flex align-items-center" style="gap: 16px;">
                                        <div class="input-group" style="width: 140px;">
                                            <button type="button" class="btn btn-outline-secondary" id="modalQtyMinus">-</button>
                                            <input id="modalQuantityInput" type="number" name="quantity" value="1" min="1"
                                                class="form-control text-center" style="max-width: 60px;" readonly>
                                            <button type="button" class="btn btn-outline-secondary" id="modalQtyPlus">+</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <button type="button" class="btn btn-dark" id="modalAddToCartBtn" disabled>
                        <i class="icon-shopping-cart"></i> THÊM VÀO GIỎ HÀNG
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Price slider functionality
            const priceSlider = document.getElementById("price-slider");
            const priceMinHidden = document.getElementById("price_min");
            const priceMaxHidden = document.getElementById("price_max");
            const filterPriceRange = document.getElementById("filter-price-range");

            if (priceSlider && typeof noUiSlider !== 'undefined') {
                noUiSlider.create(priceSlider, {
                    start: [{{ request('price_min', $minPrice) }}, {{ request('price_max', $maxPrice) }}],
                    connect: true,
                    range: {
                        'min': {{ $minPrice }},
                        'max': {{ $maxPrice }}
                    },
                    step: 1000
                });

                priceSlider.noUiSlider.on('update', function (values, handle) {
                    const min = Math.round(values[0]);
                    const max = Math.round(values[1]);

                    priceMinHidden.value = min;
                    priceMaxHidden.value = max;

                    filterPriceRange.textContent = min.toLocaleString('vi-VN') + "₫ - " + max.toLocaleString('vi-VN') + "₫";
                });
            } else if (priceSlider) {
                // Fallback for when noUiSlider is not available
                const minInput = document.createElement('input');
                const maxInput = document.createElement('input');
                minInput.type = 'range';
                maxInput.type = 'range';
                minInput.min = {{ $minPrice }};
                maxInput.min = {{ $minPrice }};
                minInput.max = {{ $maxPrice }};
                maxInput.max = {{ $maxPrice }};
                minInput.step = 1000;
                maxInput.step = 1000;
                minInput.value = {{ request('price_min', $minPrice) }};
                maxInput.value = {{ request('price_max', $maxPrice) }};

                priceSlider.appendChild(minInput);
                priceSlider.appendChild(maxInput);

                function updatePriceDisplay() {
                    const min = parseInt(minInput.value);
                    const max = parseInt(maxInput.value);

                    priceMinHidden.value = min;
                    priceMaxHidden.value = max;

                    filterPriceRange.textContent = min.toLocaleString('vi-VN') + "₫ - " + max.toLocaleString('vi-VN') + "₫";
                }

                minInput.addEventListener('input', updatePriceDisplay);
                maxInput.addEventListener('input', updatePriceDisplay);
                updatePriceDisplay();
            }

            // Sidebar toggle functionality
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar-shop');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    sidebar.classList.toggle('opened');
                    sidebarOverlay.classList.toggle('opened');
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function () {
                    sidebar.classList.remove('opened');
                    sidebarOverlay.classList.remove('opened');
                });
            }

            // Category click
            document.querySelectorAll('.category-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById('category-input').value = this.dataset.value;
                    this.closest('form').submit();
                });
            });
            // Color click (text link)
            document.querySelectorAll('.color-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById('color-input').value = this.dataset.value;
                    this.closest('form').submit();
                });
            });
            // Size click
            document.querySelectorAll('.size-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById('size-input').value = this.dataset.value;
                    this.closest('form').submit();
                });
            });

            // Form submission debug
            const filterForm = document.querySelector('aside form');
            if (filterForm) {
                filterForm.addEventListener('submit', function (e) {
                    console.log('Form submitted with data:', new FormData(this));
                });
            }
             // Khởi tạo modal
        variantModal = new bootstrap.Modal(document.getElementById('variantModal'));
        
        // Event listeners cho modal
        document.getElementById('modalQtyMinus').onclick = function() {
            const qtyInput = document.getElementById('modalQuantityInput');
            let v = parseInt(qtyInput.value) || 1;
            if (v > 1) qtyInput.value = v - 1;
        };

        document.getElementById('modalQtyPlus').onclick = function() {
            const qtyInput = document.getElementById('modalQuantityInput');
            let v = parseInt(qtyInput.value) || 1;
            if (!qtyInput.max || v < parseInt(qtyInput.max)) qtyInput.value = v + 1;
        };

        document.getElementById('modalAddToCartBtn').onclick = function() {
            // Kiểm tra đăng nhập trước
    @if (!auth()->check())
        window.location.href = "{{ route('login') }}";
        return;
    @endif
            if (!selectedModalColor || !selectedModalSize) {
                showAlert('Vui lòng chọn màu sắc và kích thước trước khi thêm vào giỏ hàng!', 'error');
                return;
            }

            const formData = new FormData(document.getElementById('variantForm'));
            const productId = document.getElementById('modalProductId').value;
            fetch(`/client/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    variantModal.hide();
                    showAlert(data.message || 'Đã thêm vào giỏ hàng!', 'success');
                    if (typeof updateCartCount === 'function') updateCartCount();
                } else {
                    showAlert(data.message || 'Có lỗi khi thêm vào giỏ hàng!', 'error');
                }
            })
            .catch(err => {
                console.error('Lỗi khi thêm vào giỏ hàng:', err);
                showAlert('Lỗi hệ thống!', 'error');
            });
        };
        
        // Lắng nghe sự kiện mở modal của Bootstrap
        document.getElementById('variantModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productImage = button.getAttribute('data-product-image');
            const variantsString = button.getAttribute('data-variants');
            
            document.getElementById('modalProductImage').src = productImage || '/assets/images/no-image.png';
            
            try {
                const variants = JSON.parse(variantsString);
                // Gọi hàm điền dữ liệu vào modal
                populateVariantModal(productId, productName, variants);
            } catch (error) {
                console.error("Lỗi khi phân tích cú pháp JSON variants:", error);
            } 
        });

        // Hàm điền dữ liệu vào modal
        function populateVariantModal(productId, productName, variants) {
            currentVariants = variants || [];
            selectedModalColor = null;
            selectedModalSize = null;
            
            // Cập nhật thông tin cơ bản
            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductName').textContent = productName;
            
            // Xóa bỏ phần fetch ảnh không cần thiết vì đã có ảnh từ data-product-image
            
            // Xử lý màu sắc
            const colorOptions = document.getElementById('colorOptions');
            colorOptions.innerHTML = '';
            const uniqueColorIds = [...new Set(variants.map(v => v.color_id))].filter(id => id);
            uniqueColorIds.forEach(colorId => {
                const variant = variants.find(v => v.color_id === colorId);
                const color = variant?.color || { id: colorId, name: `Màu ${colorId}` };
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'color-btn demo-style-btn';
                btn.dataset.color = color.id;
                btn.textContent = color.name || `Màu ${colorId}`;
                btn.onclick = () => selectModalColor(color.id);
                colorOptions.appendChild(btn);
            });

            // Xử lý kích thước
            const sizeOptions = document.getElementById('sizeOptions');
            sizeOptions.innerHTML = '';
            const uniqueSizeIds = [...new Set(variants.map(v => v.size_id))].filter(id => id);
            uniqueSizeIds.forEach(sizeId => {
                const variant = variants.find(v => v.size_id === sizeId);
                const size = variant?.size || { id: sizeId, name: `Size ${sizeId}` };
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'size-btn demo-style-btn';
                btn.dataset.size = size.id;
                btn.textContent = size.name || `Size ${sizeId}`;
                btn.onclick = () => selectModalSize(size.id);
                sizeOptions.appendChild(btn);
            });

            // Reset các giá trị khác
            document.getElementById('modalQuantityInput').value = 1;
            document.getElementById('modalInventoryInfo').textContent = '';
            document.getElementById('modalDynamicPrice').innerHTML = '';
            document.getElementById('modalAddToCartBtn').disabled = true;
        }

        function selectModalColor(colorId) {
            selectedModalColor = colorId;
            document.querySelectorAll('#colorOptions .color-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`#colorOptions .color-btn[data-color="${colorId}"]`).classList.add('active');
            updateModalVariant();
        }

        function selectModalSize(sizeId) {
            selectedModalSize = sizeId;
            document.querySelectorAll('#sizeOptions .size-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`#sizeOptions .size-btn[data-size="${sizeId}"]`).classList.add('active');
            updateModalVariant();
        }

        function updateModalVariant() {
            const variant = currentVariants.find(v =>
                v.color_id == selectedModalColor && v.size_id == selectedModalSize
            );
            const qtyInput = document.getElementById('modalQuantityInput');
            const inventoryInfo = document.getElementById('modalInventoryInfo');
            const dynamicPrice = document.getElementById('modalDynamicPrice');
            const addToCartBtn = document.getElementById('modalAddToCartBtn');
            if (variant) {
                document.getElementById('modalVariantId').value = variant.id;
                qtyInput.max = variant.quantity;
                qtyInput.value = Math.min(parseInt(qtyInput.value) || 1, variant.quantity);
                qtyInput.removeAttribute('readonly');
                inventoryInfo.textContent = `${variant.quantity} sản phẩm`;
                addToCartBtn.disabled = variant.quantity <= 0;
                if (variant.sale_price && variant.sale_price < variant.price) {
                    dynamicPrice.innerHTML = `<del style="font-size:16px;color:#bbb;font-weight:600;margin-right:10px;">₫${parseInt(variant.price).toLocaleString('vi-VN')}</del><span style="font-size:20px;font-weight:700;color:#222;">₫${parseInt(variant.sale_price).toLocaleString('vi-VN')}</span>`;
                } else {
                    dynamicPrice.innerHTML = `<span style="font-size:20px;font-weight:700;color:#222;">₫${parseInt(variant.price).toLocaleString('vi-VN')}</span>`;
                }
            } else {
                document.getElementById('modalVariantId').value = '';
                qtyInput.value = 1;
                qtyInput.setAttribute('readonly', true);
                inventoryInfo.textContent = '';
                dynamicPrice.innerHTML = '';
                addToCartBtn.disabled = true;
            }
        }

        function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'custom-alert';
    alertDiv.setAttribute('data-type', type);
    alertDiv.innerHTML = `
        <div class="alert-content">
            <div class="alert-header">
                <span class="icon-warning"><i class="fas fa-check"></i></span>
                <div class="alert-title">Success</div>
            </div>
            <div class="alert-message">${message}</div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
            <span aria-hidden="true">&times;</span>
        </button>
    `;

    let alertStack = document.getElementById('alert-stack');
    if (!alertStack) {
        alertStack = document.createElement('div');
        alertStack.id = 'alert-stack';
        document.body.appendChild(alertStack);
    }

    alertStack.appendChild(alertDiv);
    setTimeout(() => { alertDiv.remove(); }, 3500);
}
    
        });
    </script>
@endsection
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
    
</style>
    <div class="category-banner-container bg-gray">
        <div class="container">
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
                                            $minVariantPrice = $product->variants->min('price');
                                            $maxVariantPrice = $product->variants->max('price');
                                        @endphp
                                        @if ($minVariantPrice)
                                            @if($minVariantPrice != $maxVariantPrice)
                                                <span class="product-price">{{ number_format($minVariantPrice, 0, ',', '.') }}₫ -
                                                    {{ number_format($maxVariantPrice, 0, ',', '.') }}₫</span>
                                            @else
                                                <span class="product-price">{{ number_format($minVariantPrice, 0, ',', '.') }}₫</span>
                                            @endif
                                        @endif
                                    </div><!-- End .price-box -->
                                    <div class="product-action">
                                        <a href="#" class="btn-icon-wish" title="Yêu thích"><i class="icon-heart"></i></a>
                                        <a href="{{ route('client.product.detail', ['id' => $product->id]) }}"
                                            class="btn-icon btn-add-cart product-type-simple">
                                            <i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span>
                                        </a>
                                        <a href="#" class="btn-quickview" title="Xem nhanh">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
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
        });
    </script>
@endsection
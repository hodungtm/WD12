@extends('Client.Layouts.ClientLayout')

@section('main')
    <!-- breadcrumb -->
    <nav class="breadcrumb-section theme1 bg-lighten2 pt-110 pb-110">
        <div class="container text-center">
            <h2 class="title text-dark text-capitalize">Sản phẩm</h2>
        </div>
    </nav>
    <!-- danh sách sản phẩm -->
    <div class="product-tab bg-white pt-80 pb-50">
        <div class="container">
            <div class="row">
                <!-- SIDEBAR -->
                <div class="col-lg-3 mb-30 order-lg-first">
                    <aside class="blog-left-sidebar">
                        <form action="{{ route('client.listproduct') }}" method="GET">
                            <div class="sidebar-widget theme1 mb-30">
                                <h3 class="post-title">Search</h3>
                                <div class="blog-search-form">
                                    <input class="form-control rounded" name="keyword" type="text"
                                        value="{{ request('keyword') }}" placeholder="Tên sản phẩm...">
                                </div>
                            </div>

                            <div class="sidebar-widget mb-30">
                                <h3 class="post-title">Categories</h3>
                                <ul class="blog-links">
                                    @foreach ($categories as $category)
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }}>
                                                {{ $category->ten_danh_muc }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="check-box-inner mt-10">
                                <h4 class="sub-title">Khoảng Giá</h4>
                                <div class="price-filter mt-10">
                                    <div class="price-slider-amount d-flex gap-2 align-items-center">
                                        <span id="price-display" class="fw-semibold text-primary"></span>
                                    </div>

                                    {{-- Hidden form values --}}
                                    <input type="hidden" name="price_min" id="price_min"
                                        value="{{ request('price_min', $minPrice) }}">
                                    <input type="hidden" name="price_max" id="price_max"
                                        value="{{ request('price_max', $maxPrice) }}">

                                    {{-- Thanh trượt --}}
                                    <div class="d-flex align-items-center mt-2">
                                        <input type="range" id="range_min" class="form-range w-50 me-2"
                                            min="{{ $minPrice }}" max="{{ $maxPrice }}" step="1000"
                                            value="{{ request('price_min', $minPrice) }}">
                                        <input type="range" id="range_max" class="form-range w-50" min="{{ $minPrice }}"
                                            max="{{ $maxPrice }}" step="1000" value="{{ request('price_max', $maxPrice) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="sidebar-widget mb-30">
                                <h3 class="post-title">Colors</h3>
                                <ul class="product-tag d-flex flex-wrap">
                                    @foreach ($colors as $color)
                                        <li class="me-2 mb-1">
                                            <label>
                                                <input type="radio" name="color" value="{{ $color->id }}" {{ request('color') == $color->id ? 'checked' : '' }}>
                                                {{ $color->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="sidebar-widget mb-30">
                                <h3 class="post-title">Sizes</h3>
                                <ul class="product-tag d-flex flex-wrap">
                                    @foreach ($sizes as $size)
                                        <li class="me-2 mb-1">
                                            <label>
                                                <input type="radio" name="size" value="{{ $size->id }}" {{ request('size') == $size->id ? 'checked' : '' }}>
                                                {{ $size->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-primary w-50 me-2" type="submit">Filter</button>
                                <a href="{{ route('client.listproduct') }}" class="btn btn-outline-secondary w-50">Reset</a>
                            </div>
                        </form>
                    </aside>
                </div>

                <!-- DANH SÁCH SẢN PHẨM -->
                <div class="col-lg-9 mb-30">
                    <div class="grid-nav-wraper bg-lighten2 px-3 py-2 mb-30">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0 d-flex align-items-center gap-2">
                                <div class="shop-grid-nav">
                                    <ul class="nav nav-pills align-items-center gap-2" id="pills-tab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" href="#"><i
                                                    class="fa fa-th"></i></a></li>
                                        <li class="nav-item"><a class="nav-link" href="#"><i class="fa fa-list"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <span class="ms-3 text-muted">Có {{ $products->total() }} sản phẩm.</span>
                            </div>

                            <div class="col-md-6 d-flex justify-content-md-end align-items-center">
                                <form method="GET" action="{{ route('client.listproduct') }}"
                                    class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                    <input type="hidden" name="color" value="{{ request('color') }}">
                                    <input type="hidden" name="size" value="{{ request('size') }}">
                                    <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                                    <input type="hidden" name="price_max" value="{{ request('price_max') }}">

                                    <label for="sort" class="me-2 fw-semibold">Sort by:</label>
                                    <select name="sort" id="sort" class="form-select form-select-sm rounded-pill px-3"
                                        onchange="this.form.submit()">
                                        <option value="">Relevance</option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest
                                        </option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name
                                            A-Z</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name
                                            Z-A</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price
                                            Low to High</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                            Price High to Low</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-12 col-sm-6 col-xl-4 mb-30">
                                <div class="card h-100 border-0">
                                    @foreach($product->images as $img)
                                        <a href="#" class="position-relative overflow-hidden d-block">
                                            <img src="{{ asset('storage/' . $img->image) }}">
                                        </a>
                                    @endforeach
                                    <div class="card-body text-start px-0">
                                        <h6 class="text-muted small mb-1">{{ $product->category->ten_danh_muc ?? 'Danh mục' }}
                                        </h6>
                                        <h5 class="fw-bold mb-2">{{ $product->name }}</h5>
                                        @php
                                            $minVariantPrice = $product->variants->min('price');
                                        @endphp
                                        @if ($minVariantPrice)
                                            <p class="text-danger fw-bold">{{ number_format($minVariantPrice, 0, ',', '.') }}₫</p>
                                        @endif
                                        <p class="text-muted small">{{ Str::limit($product->description, 100) }}</p>

                                        <a href="#" class="btn btn-sm btn-outline-primary mt-2">Xem chi tiết</a>

                                        <a href="{{ route('client.product.detail', ['id' => $product->id]) }}" class="btn btn-sm btn-outline-primary mt-2">Xem chi tiết</a>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pagination mt-4">
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const minInput = document.getElementById("range_min");
        const maxInput = document.getElementById("range_max");
        const priceMinHidden = document.getElementById("price_min");
        const priceMaxHidden = document.getElementById("price_max");
        const display = document.getElementById("price-display");

        function updateDisplay() {
            let min = parseInt(minInput.value);
            let max = parseInt(maxInput.value);

            // Nếu kéo nhầm thì hoán đổi lại
            if (min > max) [min, max] = [max, min];

            priceMinHidden.value = min;
            priceMaxHidden.value = max;

            display.textContent = min.toLocaleString('vi-VN') + "₫ - " + max.toLocaleString('vi-VN') + "₫";
        }

        minInput.addEventListener("input", updateDisplay);
        maxInput.addEventListener("input", updateDisplay);

        // Khởi tạo hiển thị ban đầu
        updateDisplay();
    });
</script>

@endsection
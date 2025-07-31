@extends('Client.Layouts.ClientLayout')

@section('main')
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.listproduct') }}">Sản phẩm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </div>
        </nav>
        <div class="container">
            <div class="product-single-container product-single-default">
                <div class="cart-message d-none">
                    <strong class="single-cart-notice">"{{ $product->name }}"</strong>
                    <span>đã được thêm vào giỏ hàng.</span>
                </div>
                <div class="row">
                    <!-- Product Gallery (Left) -->
                    <div class="col-lg-5 col-md-6 product-single-gallery">
                        <div class="product-slider-container">
                            <div class="label-group">
                                @if($product->variants->first() && $product->variants->first()->sale_price && $product->variants->first()->sale_price < $product->variants->first()->price)
                                    <div class="product-label label-sale">
                                        SALE
                                    </div>
                                @endif
                                @if($loop->first ?? false)
                                    <div class="product-label label-hot">HOT</div>
                                @endif
                            </div>
                            <div class="product-single-carousel owl-carousel owl-theme show-nav-hover">
                                @foreach($product->images as $img)
                                    <div class="product-item">
                                        <img class="product-single-image" id="mainImage"
                                            src="{{ asset('storage/' . $img->image) }}" alt="Product Image"
                                            style="width:100%;max-width:468px;" />
                                    </div>
                                @endforeach
                            </div>
                            <span class="prod-full-screen">
                                <i class="icon-plus"></i>
                            </span>
                        </div>
                        <div class="prod-thumbnail owl-dots mt-3 d-flex flex-wrap gap-2">
                            @foreach($product->images as $img)
                                <div class="owl-dot">
                                    <img src="{{ asset('storage/' . $img->image) }}" class="img-thumbnail thumbnail-img"
                                        width="80" onclick="changeImage(this)">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Product Details (Right) -->
                    <div class="col-lg-7 col-md-6 product-single-details">
                        <h1 class="product-title">{{ $product->name }}</h1>
                        <div class="product-nav">
                            <div class="product-prev">
                                <a href="#">
                                    <span class="product-link"></span>
                                    <span class="product-popup">
                                        <span class="box-content">
                                            <img alt="product" width="150" height="150"
                                                src="{{ asset('assets/images/demoes/demo27/products/product-3.jpg') }}"
                                                style="padding-top: 0px;">
                                            <span>Sản phẩm trước</span>
                                        </span>
                                    </span>
                                </a>
                            </div>
                            <div class="product-next">
                                <a href="#">
                                    <span class="product-link"></span>
                                    <span class="product-popup">
                                        <span class="box-content">
                                            <img alt="product" width="150" height="150"
                                                src="{{ asset('assets/images/demoes/demo27/products/product-4.jpg') }}"
                                                style="padding-top: 0px;">
                                            <span>Sản phẩm tiếp</span>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="ratings-container">
                            <div class="product-ratings">
                                @php
                                    $rating = $product->reviews->avg('so_sao') ?? 0;
                                @endphp
                                <span class="ratings" style="width:{{ $rating * 20 }}%"></span>
                                <span class="tooltiptext tooltip-top"></span>
                            </div>
                            <a href="#product-reviews-content" class="rating-link">({{ $reviews->count() }} Đánh giá)</a>
                        </div>
                        <hr class="short-divider">
                        <div class="price-box">
                            @php
                                $variant = $product->variants->first();
                            @endphp
                            @if($variant)
                                @if($variant->sale_price && $variant->sale_price < $variant->price)
                                    <div class="product-price">₫{{ number_format($variant->sale_price) }} –
                                        ₫{{ number_format($variant->price) }}</div>
                                @else
                                    <div class="product-price">₫{{ number_format($variant->price) }}</div>
                                @endif
                            @else
                                <div class="product-price">Liên hệ</div>
                            @endif
                        </div>
                        <ul class="single-info-list">
                            <li>
                                SKU: <strong>{{ $product->product_code }}</strong>
                            </li>
                            <li>
                                Danh mục: <strong>{{ $product->category->ten_danh_muc ?? 'N/A' }}</strong>
                            </li>
                        </ul>
                        <form action="{{ route('client.cart.add', $product->id) }}" method="POST" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" id="selected_variant_id"
                                value="{{ $product->variants->first()->id ?? '' }}">
                            <div class="product-filters-container mb-3">
                                <div class="product-single-filter-row">
                                    <label class="form-label mb-0">Màu sắc:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($product->variants->pluck('color')->unique('id') as $color)
                                            @if ($color)
                                                <button type="button" class="color-btn demo-style-btn"
                                                    data-color="{{ $color->id }}">{{ $color->name }}</button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="product-single-filter-row">
                                    <label class="form-label mb-0">Kích thước:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($product->variants->pluck('size')->unique('id') as $size)
                                            @if ($size)
                                                <button type="button" class="size-btn demo-style-btn"
                                                    data-size="{{ $size->id }}">{{ $size->name }}</button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="product-single-filter-row">
                                    <label class="form-label mb-0">Tồn kho:</label>
                                    <span id="inventory-info"
                                        class="text-muted">{{ $product->variants->first()->quantity ?? '0' }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <span id="dynamic-price" class="fw-bold fs-5 text-primary"></span>
                            </div>
                            <div class="d-flex align-items-center mb-3" style="gap: 16px;">

                                <div class="input-group" style="width: 140px;">
                                    <button type="button" class="btn btn-outline-secondary" id="qty-minus">-</button>
                                    <input id="quantity-input" type="number" name="quantity" value="1" min="1"
                                        class="form-control text-center" style="max-width: 60px;">
                                    <button type="button" class="btn btn-outline-secondary" id="qty-plus">+</button>
                                </div>
                                <button type="submit" class="btn btn-dark fw-bold px-4" id="add-to-cart-btn">
                                    <i class="icon-shopping-cart"></i> THÊM VÀO GIỎ HÀNG
                                </button>
                                <a href="{{ route('client.cart.index') }}" class="btn btn-outline-dark fw-bold px-4 d-none"
                                    id="view-cart-btn" style="background:#f7f7f7;">XEM GIỎ HÀNG</a>
                            </div>


                        </form>
                        <div class="product-single-share mb-3 mt-4">
                            <div class="d-flex align-items-center" style="gap: 12px;">
                                <a href="#" class="social-icon social-facebook icon-facebook" title="Facebook"></a>
                                <a href="#" class="social-icon social-twitter icon-twitter" title="Twitter"></a>
                                <a href="#" class="social-icon social-linkedin fab fa-linkedin-in" title="Linkedin"></a>
                                <a href="#" class="social-icon social-gplus fab fa-google-plus-g" title="Google +"></a>
                                <a href="#" class="social-icon social-mail icon-mail-alt" title="Mail"></a>

                                <div class="product-action">
                                    <a href="#" class="btn-icon-wish add-wishlist" title="Yêu thích"
                                        onclick="event.preventDefault(); document.getElementById('add-wishlist-{{ $product->id }}').submit();">
                                        <i class="icon-heart"></i> <span>YÊU THÍCH</span>
                                    </a>
                                    <form id="add-wishlist-{{ $product->id }}"
                                        action="{{ route('client.wishlist.add', $product->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-single-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-tab-desc" data-toggle="tab" href="#product-desc-content"
                            role="tab" aria-controls="product-desc-content" aria-selected="true">Mô tả</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="product-tab-size" data-toggle="tab" href="#product-size-content" role="tab"
                            aria-controls="product-size-content" aria-selected="true">Thông tin kỹ thuật</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="product-tab-tags" data-toggle="tab" href="#product-tags-content" role="tab"
                            aria-controls="product-tags-content" aria-selected="false">Thông tin bổ sung</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="product-tab-reviews" data-toggle="tab" href="#product-reviews-content"
                            role="tab" aria-controls="product-reviews-content" aria-selected="false">Đánh giá
                            ({{ $reviews->count() }})</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="product-tab-comments" data-toggle="tab" href="#product-comments-content"
                            role="tab" aria-controls="product-comments-content" aria-selected="false">Bình luận
                            ({{ $comments->count() }})</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel"
                        aria-labelledby="product-tab-desc">
                        <div class="product-desc-content font2">
                            <p>{{ $product->description }}</p>
                            <ul>
                                <li>Sản phẩm chất lượng cao</li>
                                <li>Bảo hành chính hãng</li>
                                <li>Giao hàng toàn quốc</li>
                                <li>Hỗ trợ đổi trả trong 30 ngày</li>
                                <li>Thanh toán an toàn</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="product-size-content" role="tabpanel" aria-labelledby="product-tab-size">
                        <div class="product-size-content">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-size">
                                        <thead>
                                            <tr>
                                                <th>THÔNG SỐ</th>
                                                <th>CHI TIẾT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Tên sản phẩm</td>
                                                <td>{{ $product->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Danh mục</td>
                                                <td>{{ $product->category->ten_danh_muc ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td>SKU</td>
                                                <td>{{ $product->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>Trạng thái</td>
                                                <td>{{ $product->status ? 'Còn hàng' : 'Hết hàng' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="product-tags-content" role="tabpanel" aria-labelledby="product-tab-tags">
                        <table class="table table-striped mt-2">
                            <tbody>
                                <tr>
                                    <th>Danh mục</th>
                                    <td>{{ $product->category->ten_danh_muc ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Màu sắc có sẵn</th>
                                    <td>
                                        @foreach($product->variants->groupBy('color_id') as $colorId => $variantsByColor)
                                            @php $color = $variantsByColor->first()->color; @endphp
                                            {{ $color->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kích thước có sẵn</th>
                                    <td>
                                        @foreach($product->variants->groupBy('size_id') as $sizeId => $variantsBySize)
                                            @php $size = $variantsBySize->first()->size; @endphp
                                            {{ $size->name ?? 'N/A' }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="product-reviews-content" role="tabpanel"
                        aria-labelledby="product-tab-reviews">
                        <div class="product-reviews-content">
                            <h3 class="reviews-title">{{ $reviews->count() }} đánh giá cho {{ $product->name }}</h3>

                            <div class="comment-list">
                                @forelse($reviews as $review)

                                    <div class="comments">
                                        <figure class="img-thumbnail text-center">
                                            <img src="{{ asset('assets/images/blog/author.jpg') }}" alt="author" width="80"
                                                height="80">
                                            <div class="mt-2 font-weight-bold">
                                                {{ $review->user?->name ?? 'Ẩn danh' }}
                                            </div>
                                        </figure>
                                        <div class="comment-block">
                                            <div class="comment-header">
                                                <div class="comment-arrow"></div>
                                                <div class="ratings-container float-sm-right">
                                                    <div class="product-ratings">
                                                        <span class="ratings" style="width:{{ $review->so_sao * 20 }}%"></span>
                                                        <span class="tooltiptext tooltip-top"></span>
                                                    </div>
                                                </div>
                                                <span class="comment-by">
                                                    <strong> {{ $review->user?->name ?? 'Ẩn danh' }}</strong> -
                                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="comment-content">
                                                <p>{{ $review->noi_dung }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                                @endforelse
                            </div>

                            <div class="divider"></div>

                            <div class="add-product-review">
                                <h3 class="review-title">Thêm đánh giá</h3>

                                @auth
                                @if (!$hasPurchased)
                                <div class="alert alert-info">
                                    <p>Bạn cần mua sản phẩm này trước khi có thể đánh giá.</p>
                                </div>
                            @elseif (!$canReview)
                                <div class="alert alert-info">
                                    <p>Bạn đã đánh giá hết lượt cho sản phẩm này.</p>
                                </div>
                            @else
                                        <form action="{{ route('client.product.review', $product->id) }}" method="POST"
                                            class="comment-form m-0">
                                            @csrf

                                            <div class="rating-form">
                                                <label for="rating">Đánh giá của bạn <span class="required">*</span></label>
                                                <span class="rating-stars">
                                                    <a class="star-1" href="#">1</a>
                                                    <a class="star-2" href="#">2</a>
                                                    <a class="star-3" href="#">3</a>
                                                    <a class="star-4" href="#">4</a>
                                                    <a class="star-5" href="#">5</a>
                                                </span>
                                                <select name="so_sao" id="rating" required style="display: none;">
                                                    <option value="">Đánh giá…</option>
                                                    <option value="5">Hoàn hảo</option>
                                                    <option value="4">Tốt</option>
                                                    <option value="3">Trung bình</option>
                                                    <option value="2">Không tệ</option>
                                                    <option value="1">Rất tệ</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Nội dung đánh giá <span class="required">*</span></label>
                                                <textarea name="noi_dung" cols="5" rows="6" class="form-control form-control-sm"
                                                    required placeholder="Viết đánh giá của bạn về sản phẩm này..."></textarea>
                                            </div>

                                            <input type="submit" class="btn btn-primary" value="Gửi đánh giá">
                                        </form>
                                    @endif
                                @else
                                    <div class="alert alert-info">
                                        <p class="mb-2">Bạn cần <a href="{{ route('login') }}" class="alert-link">đăng nhập</a>
                                            để đánh giá.</p>
                                        <p class="mb-0">Chưa có tài khoản? <a href="{{ route('register') }}"
                                                class="alert-link">Đăng ký ngay</a></p>
                                    </div>
                                @endauth
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="product-comments-content" role="tabpanel"
                        aria-labelledby="product-tab-comments">
                        <div class="product-comments-content">
                            <h3 class="comments-title">{{ $comments->count() }} bình luận cho {{ $product->name }}</h3>

                            <div class="comment-list">
                                @forelse($comments as $comment)
                                    <div class="comments">
                                        <figure class="img-thumbnail text-center">
                                            <img src="{{ asset('assets/images/blog/author.jpg') }}" alt="author" width="80"
                                                height="80">

                                        </figure>
                                        <div class="comment-block">
                                            <div class="comment-header">
                                                <div class="comment-arrow"></div>
                                                <span class="comment-by">
                                                    <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong> –
                                                    {{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i') }}
                                                </span>
                                            </div>
                                            <div class="comment-content">
                                                <p>{{ $comment->noi_dung }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>Chưa có bình luận nào cho sản phẩm này.</p>
                                @endforelse
                            </div>

                            <div class="divider"></div>

                            <div class="add-product-comment">
                                <h3 class="comment-title">Thêm bình luận</h3>

                                @auth
                                    <form action="{{ route('client.product.comment', $product->id) }}" method="POST"
                                        class="comment-form m-0">
                                        @csrf
                                        <div class="form-group">
                                            <label>Nội dung bình luận <span class="required">*</span></label>
                                            <textarea name="noi_dung" cols="5" rows="6" class="form-control form-control-sm"
                                                required placeholder="Viết bình luận của bạn về sản phẩm này..."></textarea>
                                        </div>

                                        <input type="submit" class="btn btn-primary" value="Gửi bình luận">
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        <p class="mb-2">Bạn cần <a href="{{ route('login') }}" class="alert-link">đăng nhập</a>
                                            để bình luận.</p>
                                        <p class="mb-0">Chưa có tài khoản? <a href="{{ route('register') }}"
                                                class="alert-link">Đăng ký ngay</a></p>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="products-section pt-0">
                <h2 class="section-title m-b-4 border-0">Sản phẩm liên quan</h2>

                <div class="row">
                    <div class="products-slider 5col owl-carousel owl-theme dots-top dots-small mb-0" data-owl-options="{
                                                'margin': 0
                                            }">
                        @foreach($relatedProducts ?? [] as $relatedProduct)
                            <div class="product-default">
                                <figure>
                                    <a href="{{ route('client.product.detail', $relatedProduct->id) }}">
                                        <img src="{{ $relatedProduct->images->first() ? asset('storage/' . $relatedProduct->images->first()->image) : asset('assets/images/no-image.png') }}"
                                            width="280" height="280" alt="product">
                                    </a>
                                    <div class="label-group">
                                        @if($relatedProduct->variants->first() && $relatedProduct->variants->first()->sale_price && $relatedProduct->variants->first()->sale_price < $relatedProduct->variants->first()->price)
                                            <div class="product-label label-sale">
                                                -{{ 100 - round($relatedProduct->variants->first()->sale_price / $relatedProduct->variants->first()->price * 100) }}%
                                            </div>
                                        @endif
                                    </div>
                                </figure>
                                <div class="product-details">
                                    <div class="category-list">
                                        <a href="#"
                                            class="product-category">{{ $relatedProduct->category->ten_danh_muc ?? '' }}</a>
                                    </div>
                                    <h3 class="product-title">
                                        <a
                                            href="{{ route('client.product.detail', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            @php
                                                $rating = $relatedProduct->reviews->avg('so_sao') ?? 0;
                                            @endphp
                                            <span class="ratings" style="width:{{ $rating * 20 }}%"></span>
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                    </div>
                                    <div class="price-box">
                                        @php
                                            $variant = $relatedProduct->variants->first();
                                        @endphp
                                        @if($variant)
                                            @if($variant->sale_price && $variant->sale_price < $variant->price)
                                                <span class="old-price">₫{{ number_format($variant->price) }}</span>
                                                <span class="product-price">₫{{ number_format($variant->sale_price) }}</span>
                                            @else
                                                <span class="product-price">₫{{ number_format($variant->price) }}</span>
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
                                        <form id="add-wishlist-{{ $product->id }}"
                                            action="{{ route('client.wishlist.add', $product->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                        </form>
                                        <form action="{{ route('client.cart.add', $product->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="variant_id"
                                                value="{{ $product->variants->first()->id ?? '' }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn-icon btn-add-cart">

                                                <i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span>
                                            </button>
                                        </form>
                                        <a href="{{ route('client.product.detail', $relatedProduct->id) }}"
                                            class="btn-quickview" title="Xem nhanh"><i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div id="alert-stack" style="position: fixed; top: 80px; right: 24px; z-index: 9999;"></div>
    </main>

    <style>
        /* Nút chọn màu & size: vuông góc hoàn toàn, cao 40px, padding ngang 16px, min-width 40px, rộng tự động theo chữ */
        .color-btn.demo-style-btn,
        .size-btn.demo-style-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 16px;
            border: 2px solid #ddd;
            border-radius: 0;
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

        :root {
            --main-color: #4DB7B3;
        }

        .color-btn.demo-style-btn.active,
        .size-btn.demo-style-btn.active {
            border-color: var(--main-color);
            background: var(--main-color);
            color: #fff;
        }

        .color-btn.demo-style-btn:hover,
        .size-btn.demo-style-btn:hover {
            border-color: var(--main-color);
            color: var(--main-color);
            background: #e6f8fa;
        }

        /* Label và tồn kho nhỏ lại */
        .product-single-filter-row label {
            min-width: 90px;
            font-weight: 600;
            color: #222;
            font-size: 15px;
            align-self: center;
        }

        #inventory-info {
            font-size: 15px;
            color: #888;
            margin-left: 8px;
            font-weight: 600;
        }

        /* Đảm bảo các ô chọn nằm ngang hàng label */
        .product-single-filter-row>div {
            display: flex;
            align-items: center;
        }

        /* Nút số lượng nhỏ, vuông góc */
        .input-group {
            border-radius: 0;
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
            border-radius: 0;
        }

        .input-group .btn:active,
        .input-group .btn:focus {
            background: #f4f4f4;
            color: #28a745;
        }

        .input-group .form-control {
            border: none;
            box-shadow: none;
            font-size: 16px;
            font-weight: 700;
            color: #222;
            background: #fff;
            height: 40px;
            width: 40px;
            padding: 0;
            border-radius: 0;
        }

        /* Nút thêm vào giỏ hàng và xem giỏ hàng nhỏ, vuông góc */
        #add-to-cart-btn,
        #view-cart-btn {
            border-radius: 0;
            font-size: 15px;
            font-weight: 700;
            padding: 0 18px;
            min-width: 140px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0.5px;
            background: #222;
            color: #fff;
            border: 2px solid #222;
            transition: all 0.2s;
        }

        #add-to-cart-btn:hover:not(:disabled),
        #add-to-cart-btn.added,
        #view-cart-btn:hover,
        #view-cart-btn.added {
            background: var(--main-color) !important;
            border-color: var(--main-color) !important;
            color: #fff !important;
        }

        #add-to-cart-btn:disabled {
            background: #888;
            border-color: #888;
            color: #fff;
            cursor: not-allowed;
        }

        #view-cart-btn {
            background: #fff;
            color: #222;
            border: 2px solid #222;
        }

        #view-cart-btn:hover,
        #view-cart-btn.added {
            background: var(--main-color) !important;
            color: #fff !important;
            border-color: var(--main-color) !important;
        }

        #dynamic-price {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }

        .product-action button {
            min-width: 140px;
        }

        .product-action .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .product-action .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .product-action .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .product-action .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .product-single-filter-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 10px;
        }

        .product-single-filter-row label {
            min-width: 90px;
            margin-bottom: 0;
            font-weight: 600;
            color: #222;
            font-size: 15px;
            flex-shrink: 0;
        }

        .product-single-filter-row .d-flex {
            flex-wrap: wrap;
            gap: 8px;
            flex: 1;
        }

        /* Label cho các dòng thuộc tính */
        .product-single-filter-row label,
        .single-info-list li,
        ul.single-info-list li {
            color: #888;
            font-size: 13px;
            font-weight: 400;
            letter-spacing: 0.01em;
            text-transform: uppercase;
        }

        /* Giá trị nổi bật */
        .product-single-filter-row label+div,
        .single-info-list strong,
        ul.single-info-list strong {
            color: #222;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .product-default .btn-add-cart i {
            display: inline-block !important;
        }
        
    </style>

    <script>

        const variants = @json($product->variants);
        let selectedColor = null;
        let selectedSize = null;

        // ĐÃ XÓA ĐOẠN submit trùng lặp trong DOMContentLoaded
        // Giữ lại đoạn này để xử lý AJAX duy nhất cho form thêm vào giỏ hàng
        function updateVariant() {
            const variant = variants.find(v =>
                v.color_id == selectedColor && v.size_id == selectedSize
            );

            const qtyInput = document.getElementById('quantity-input');
            const inventoryInfo = document.getElementById('inventory-info');
            const dynamicPrice = document.getElementById('dynamic-price');

            if (variant) {
                document.getElementById('selected_variant_id').value = variant.id;

                qtyInput.max = variant.quantity;
                qtyInput.value = 1;
                inventoryInfo.innerText = `${variant.quantity}`;

                if (variant.sale_price && variant.sale_price < variant.price) {
                    dynamicPrice.innerHTML = `<del style="font-size:18px;color:#bbb;font-weight:600;margin-right:10px;">₫${parseInt(variant.price).toLocaleString('vi-VN')}</del><span style="font-size:22px;font-weight:700;color:#222;">₫${parseInt(variant.sale_price).toLocaleString('vi-VN')}</span>`;
                } else {
                    dynamicPrice.innerHTML = `<span style="font-size:22px;font-weight:700;color:#222;">₫${parseInt(variant.price).toLocaleString('vi-VN')}</span>`;
                }

            } else {
                document.getElementById('selected_variant_id').value = '';
                qtyInput.max = 1;
                qtyInput.value = 1;
                inventoryInfo.innerText = ``;
                dynamicPrice.innerText = '';
            }
        }

        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedColor = btn.dataset.color;
                document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                updateVariant();
            });
        });

        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedSize = btn.dataset.size;
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                updateVariant();
            });
        });

        function changeImage(img) {
            document.getElementById('mainImage').src = img.src;
        }

        function showAlert(message, type = 'success') {
            const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>';
            const alertDiv = document.createElement('div');
            alertDiv.className = 'custom-alert';
            alertDiv.innerHTML = `<span class="icon-warning">${icon}</span> ${message} <button type="button" class="close" onclick="this.parentElement.remove()"><span aria-hidden="true">&times;</span></button>`;
            document.getElementById('alert-stack').appendChild(alertDiv);
            setTimeout(() => { alertDiv.remove(); }, 3500);
        }

        function buyNow() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            fetch("{{ route('client.cart.add', $product->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "{{ route('client.checkout.show') }}?from=buynow";
                    } else {
                        showAlert(data.message || 'Có lỗi khi mua hàng!', 'error');
                    }
                });
        }

        // Thêm JS cho tăng giảm số lượng
        const qtyInput = document.getElementById('quantity-input');
        document.getElementById('qty-minus').onclick = function () {
            let v = parseInt(qtyInput.value) || 1;
            if (v > 1) qtyInput.value = v - 1;
        };
        document.getElementById('qty-plus').onclick = function () {
            let v = parseInt(qtyInput.value) || 1;
            if (!qtyInput.max || v < parseInt(qtyInput.max)) qtyInput.value = v + 1;
        };
        qtyInput.addEventListener('input', () => {
            const max = parseInt(qtyInput.max) || 1;
            let val = parseInt(qtyInput.value) || 1;

            if (val > max) {
                qtyInput.value = max;
            } else if (val < 1) {
                qtyInput.value = 1;
            }
        });
        // Thêm JS cho Add to Cart AJAX và hiện View Cart
        const addToCartForm = document.getElementById('add-to-cart-form');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const viewCartBtn = document.getElementById('view-cart-btn');
        addToCartForm.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(addToCartForm);
            fetch(addToCartForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(res => {
                    console.log('Fetch response:', res);
                    return res.json();
                })
                .then(data => {
                    console.log('Data nhận được:', data);
                    if (data.success) {
                        addToCartBtn.innerHTML = '<i class="icon-shopping-cart"></i> ĐÃ THÊM ✓';
                        addToCartBtn.classList.add('added');
                        viewCartBtn.classList.remove('d-none');
                        viewCartBtn.classList.add('added');
                        showAlert(data.message || 'Đã thêm vào giỏ hàng!', 'success');
                        if (typeof updateCartCount === 'function') updateCartCount();
                    } else {
                        showAlert(data.message || 'Có lỗi khi thêm vào giỏ hàng!', 'error');
                    }
                })
                .catch(err => {
                    console.error('Lỗi khi parse JSON hoặc fetch:', err);
                    showAlert('Lỗi hệ thống hoặc dữ liệu trả về không hợp lệ!', 'error');
                });
            return false;
        };
    </script>
@endsection
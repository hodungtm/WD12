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
                            @foreach($product->images as $image)
                            <div class="product-item">
                                <img class="product-single-image"
                                    src="{{ asset('storage/' . $image->image) }}"
                                    data-zoom-image="{{ asset('storage/' . $image->image) }}"
                                    width="468" height="468" alt="{{ $product->name }}" />
                            </div>
                            @endforeach
                        </div>
                        <!-- End .product-single-carousel -->
                        <span class="prod-full-screen">
                            <i class="icon-plus"></i>
                        </span>
                    </div>

                    <div class="prod-thumbnail owl-dots">
                        @foreach($product->images as $image)
                        <div class="owl-dot">
                            <img src="{{ asset('storage/' . $image->image) }}" width="110"
                                height="110" alt="product-thumbnail" />
                        </div>
                        @endforeach
                    </div>
                </div><!-- End .product-single-gallery -->

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
                                <div class="product-price">₫{{ number_format($variant->sale_price) }} – ₫{{ number_format($variant->price) }}</div>
                            @else
                                <div class="product-price">₫{{ number_format($variant->price) }}</div>
                            @endif
                        @else
                            <div class="product-price">Liên hệ</div>
                        @endif
                    </div>

                    <ul class="single-info-list">
                        <li>
                            SKU: <strong>{{ $product->id }}</strong>
                        </li>
                        <li>
                            Danh mục: <strong>{{ $product->category->ten_danh_muc ?? 'N/A' }}</strong>
                        </li>
                    </ul>

                    <form action="{{ route('client.cart.add', $product->id) }}" method="POST">
                        @csrf
                        <div class="product-filters-container">
                            @if($product->variants->groupBy('color_id')->count() > 0)
                            <div class="product-single-filter">
                                <label>Màu sắc:</label>
                                <ul class="config-size-list config-color-list config-filter-list">
                                    @foreach($product->variants->groupBy('color_id') as $colorId => $variantsByColor)
                                        @php $color = $variantsByColor->first()->color; @endphp
                                        <li>
                                            <a href="javascript:;" class="filter-color color-option" 
                                               data-color-id="{{ $colorId }}"
                                               title="{{ $color->name }}">
                                                {{ $color->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if($product->variants->groupBy('size_id')->count() > 0)
                            <div class="product-single-filter">
                                <label>Kích thước:</label>
                                <ul class="config-size-list">
                                    @foreach($product->variants->groupBy('size_id') as $sizeId => $variantsBySize)
                                        @php $size = $variantsBySize->first()->size; @endphp
                                        <li>
                                            <a href="javascript:;" class="size-option" data-size-id="{{ $sizeId }}">
                                                {{ $size->name ?? 'N/A' }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="product-single-filter">
                                <label></label>
                                <a class="font1 text-uppercase clear-btn" href="javascript:;" onclick="clearSelection()">Xóa lựa chọn</a>
                            </div>
                        </div>

                        <div class="product-action">
                            <div class="price-box product-filtered-price d-none">
                                <del class="old-price"><span id="old-price-display"></span></del>
                                <span class="product-price" id="selected-price-display"></span>
                            </div>

                            <div class="product-single-qty">
                                <input class="horizontal-quantity form-control" type="text" name="quantity" value="1" min="1" id="quantity-input">
                            </div>

                            <input type="hidden" name="variant_id" id="selected_variant_id">
                            
                            <button type="submit" class="btn btn-dark add-cart mr-2" title="Thêm vào giỏ hàng" id="add-to-cart-btn" disabled>
                                Thêm vào giỏ hàng
                            </button>

                            <a href="{{ route('client.cart.index') }}" class="btn btn-gray view-cart d-none">Xem giỏ hàng</a>
                        </div>
                    </form>

                    <hr class="divider mb-0 mt-0">

                    <div class="product-single-share mb-3">
                        <label class="sr-only">Chia sẻ:</label>

                        <div class="social-icons mr-2">
                            <a href="#" class="social-icon social-facebook icon-facebook" target="_blank" title="Facebook"></a>
                            <a href="#" class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
                            <a href="#" class="social-icon social-linkedin fab fa-linkedin-in" target="_blank" title="Linkedin"></a>
                            <a href="#" class="social-icon social-gplus fab fa-google-plus-g" target="_blank" title="Google +"></a>
                            <a href="#" class="social-icon social-mail icon-mail-alt" target="_blank" title="Mail"></a>
                        </div>

                        <a href="#" class="btn-icon-wish add-wishlist" title="Thêm vào yêu thích">
                            <i class="icon-wishlist-2"></i><span>Thêm vào yêu thích</span>
                        </a>
                    </div>
                </div><!-- End .product-single-details -->
            </div><!-- End .row -->
        </div><!-- End .product-single-container -->

        <div class="product-single-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="product-tab-desc" data-toggle="tab" href="#product-desc-content" role="tab" aria-controls="product-desc-content" aria-selected="true">Mô tả</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="product-tab-size" data-toggle="tab" href="#product-size-content" role="tab" aria-controls="product-size-content" aria-selected="true">Thông tin kỹ thuật</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="product-tab-tags" data-toggle="tab" href="#product-tags-content" role="tab" aria-controls="product-tags-content" aria-selected="false">Thông tin bổ sung</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="product-tab-reviews" data-toggle="tab" href="#product-reviews-content" role="tab" aria-controls="product-reviews-content" aria-selected="false">Đánh giá ({{ $reviews->count() }})</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="product-tab-comments" data-toggle="tab" href="#product-comments-content" role="tab" aria-controls="product-comments-content" aria-selected="false">Bình luận ({{ $comments->count() }})</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel" aria-labelledby="product-tab-desc">
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

                <div class="tab-pane fade" id="product-reviews-content" role="tabpanel" aria-labelledby="product-tab-reviews">
                    <div class="product-reviews-content">
                        <h3 class="reviews-title">{{ $reviews->count() }} đánh giá cho {{ $product->name }}</h3>

                        <div class="comment-list">
                            @forelse($reviews as $review)
                            
                            <div class="comments">
                                <figure class="img-thumbnail text-center">
                                    <img src="{{ asset('assets/images/blog/author.jpg') }}" alt="author" width="80" height="80">
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
                                            <strong> {{ $review->user?->name ?? 'Ẩn danh' }}</strong> - {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}
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
                                @if($canReview)
                                    <form action="{{ route('client.product.review', $product->id) }}" method="POST" class="comment-form m-0">
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
                                            <textarea name="noi_dung" cols="5" rows="6" class="form-control form-control-sm" required placeholder="Viết đánh giá của bạn về sản phẩm này..."></textarea>
                                        </div>
                                        <input type="submit" class="btn btn-primary" value="Gửi đánh giá">
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        <p>Bạn đã đánh giá sản phẩm này.</p>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <p class="mb-2">Bạn cần <a href="{{ route('login') }}" class="alert-link">đăng nhập</a> để đánh giá.</p>
                                    <p class="mb-0">Chưa có tài khoản? <a href="{{ route('register') }}" class="alert-link">Đăng ký ngay</a></p>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="product-comments-content" role="tabpanel" aria-labelledby="product-tab-comments">
                    <div class="product-comments-content">
                        <h3 class="comments-title">{{ $comments->count() }} bình luận cho {{ $product->name }}</h3>

                        <div class="comment-list">
                            @forelse($comments as $comment)
                            <div class="comments">
                                <figure class="img-thumbnail text-center">
                                    <img src="{{ asset('assets/images/blog/author.jpg') }}" alt="author" width="80" height="80">
                                    
                                </figure>
                                <div class="comment-block">
                                    <div class="comment-header">
                                        <div class="comment-arrow"></div>
                                        <span class="comment-by">
                                            <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong> – {{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i') }}
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
                                <form action="{{ route('client.product.comment', $product->id) }}" method="POST" class="comment-form m-0">
                                    @csrf
                                    <div class="form-group">
                                        <label>Nội dung bình luận <span class="required">*</span></label>
                                        <textarea name="noi_dung" cols="5" rows="6" class="form-control form-control-sm" required placeholder="Viết bình luận của bạn về sản phẩm này..."></textarea>
                                    </div>

                                    <input type="submit" class="btn btn-primary" value="Gửi bình luận">
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <p class="mb-2">Bạn cần <a href="{{ route('login') }}" class="alert-link">đăng nhập</a> để bình luận.</p>
                                    <p class="mb-0">Chưa có tài khoản? <a href="{{ route('register') }}" class="alert-link">Đăng ký ngay</a></p>
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
                                <img src="{{ $relatedProduct->images->first() ? asset('storage/' . $relatedProduct->images->first()->image) : asset('assets/images/no-image.png') }}" width="280" height="280" alt="product">
                            </a>
                            <div class="label-group">
                                @if($relatedProduct->variants->first() && $relatedProduct->variants->first()->sale_price && $relatedProduct->variants->first()->sale_price < $relatedProduct->variants->first()->price)
                                <div class="product-label label-sale">-{{ 100 - round($relatedProduct->variants->first()->sale_price / $relatedProduct->variants->first()->price * 100) }}%</div>
                                @endif
                            </div>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">{{ $relatedProduct->category->ten_danh_muc ?? '' }}</a>
                            </div>
                            <h3 class="product-title">
                                <a href="{{ route('client.product.detail', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a>
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
                                <a href="#" class="btn-icon-wish" title="Yêu thích"><i class="icon-heart"></i></a>
                                <a href="{{ route('client.product.detail', $relatedProduct->id) }}" class="btn-icon btn-add-cart product-type-simple"><i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span></a>
                                <a href="{{ route('client.product.detail', $relatedProduct->id) }}" class="btn-quickview" title="Xem nhanh"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.color-option,
.size-option {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    padding: 2px 8px;
    border-radius: 4px;
    display: inline-block;
}

.color-option.active,
.size-option.active {
    border: 2px solid #20b2aa; /* màu xanh dương nhạt/turquoise */
    background: #f8f8f8;
}

.color-option.active:hover,
.size-option.active:hover {
    border: 2.5px solid #20b2aa;
    background: #f8f8f8;
    /* Giữ nguyên border khi hover nếu đã active */
}

.color-option:hover:not(.active),
.size-option:hover:not(.active) {
    border: 2px solid #aaa;
    background: #f0f0f0;
}

.product-filtered-price {
    display: block !important;
}
.color-option.active:after,
.color-option.active:before {
    content: none !important;
    display: none !important;
}
.color-option.disabled,
.size-option.disabled {
    opacity: 0.4;
    pointer-events: none;
    filter: grayscale(60%);
}
</style>

<script>
    @php
        $variantArray = $product->variants->map(function($v) {
            return [
                'id' => $v->id,
                'color_id' => $v->color_id,
                'size_id' => $v->size_id,
                'price' => $v->sale_price ?? $v->price,
                'old_price' => $v->sale_price && $v->sale_price < $v->price ? $v->price : null,
                'stock' => $v->quantity,
            ];
        });
    @endphp
    window.variants = @json($variantArray);

    document.addEventListener('DOMContentLoaded', function() {
        let selectedColorId = null;
        let selectedSizeId = null;

        // Chọn màu
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.color-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                selectedColorId = this.dataset.colorId;

                // Cập nhật trạng thái size
                updateSizeOptions();
                updateVariantSelection();
            });
        });

        // Chọn size
        document.querySelectorAll('.size-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.size-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                selectedSizeId = this.dataset.sizeId;

                // Cập nhật trạng thái color
                updateColorOptions();
                updateVariantSelection();
            });
        });

        function updateSizeOptions() {
            // Nếu đã chọn màu, chỉ enable size có tồn tại với màu đó
            if (selectedColorId) {
                document.querySelectorAll('.size-option').forEach(option => {
                    const sizeId = option.dataset.sizeId;
                    // Kiểm tra có variant với colorId và sizeId này không
                    const exists = window.variants.some(v =>
                        String(v.color_id) === String(selectedColorId) && String(v.size_id) === String(sizeId)
                    );
                    if (exists) {
                        option.classList.remove('disabled');
                    } else {
                        option.classList.add('disabled');
                        option.classList.remove('active');
                        if (selectedSizeId === sizeId) selectedSizeId = null;
                    }
                });
            } else {
                // Nếu chưa chọn màu, enable hết
                document.querySelectorAll('.size-option').forEach(option => {
                    option.classList.remove('disabled');
                });
            }
        }

        function updateColorOptions() {
            // Nếu đã chọn size, chỉ enable color có tồn tại với size đó
            if (selectedSizeId) {
                document.querySelectorAll('.color-option').forEach(option => {
                    const colorId = option.dataset.colorId;
                    // Kiểm tra có variant với colorId và sizeId này không
                    const exists = window.variants.some(v =>
                        String(v.size_id) === String(selectedSizeId) && String(v.color_id) === String(colorId)
                    );
                    if (exists) {
                        option.classList.remove('disabled');
                    } else {
                        option.classList.add('disabled');
                        option.classList.remove('active');
                        if (selectedColorId === colorId) selectedColorId = null;
                    }
                });
            } else {
                // Nếu chưa chọn size, enable hết
                document.querySelectorAll('.color-option').forEach(option => {
                    option.classList.remove('disabled');
                });
            }
        }

        function updateVariantSelection() {
            if (selectedColorId && selectedSizeId) {
                const variant = window.variants.find(v =>
                    String(v.color_id) === String(selectedColorId) && String(v.size_id) === String(selectedSizeId)
                );
                if (variant) {
                    document.getElementById('selected-price-display').textContent = '₫' + parseInt(variant.price).toLocaleString('vi-VN');
                    if (variant.old_price) {
                        document.getElementById('old-price-display').textContent = '₫' + parseInt(variant.old_price).toLocaleString('vi-VN');
                    } else {
                        document.getElementById('old-price-display').textContent = '';
                    }
                    document.getElementById('selected_variant_id').value = variant.id;
                    document.getElementById('quantity-input').setAttribute('max', variant.stock);
                    document.querySelector('.product-filtered-price').classList.remove('d-none');
                    document.getElementById('add-to-cart-btn').disabled = false;
                } else {
                    document.querySelector('.product-filtered-price').classList.add('d-none');
                    document.getElementById('add-to-cart-btn').disabled = true;
                    document.getElementById('selected_variant_id').value = '';
                }
            } else {
                document.querySelector('.product-filtered-price').classList.add('d-none');
                document.getElementById('add-to-cart-btn').disabled = true;
                document.getElementById('selected_variant_id').value = '';
            }
        }

        // Xóa lựa chọn
        window.clearSelection = function() {
            document.querySelectorAll('.color-option').forEach(o => o.classList.remove('active', 'disabled'));
            document.querySelectorAll('.size-option').forEach(o => o.classList.remove('active', 'disabled'));
            selectedColorId = null;
            selectedSizeId = null;
            document.querySelector('.product-filtered-price').classList.add('d-none');
            document.getElementById('add-to-cart-btn').disabled = true;
            document.getElementById('selected_variant_id').value = '';
        };

        // Rating stars
        document.querySelectorAll('.rating-stars a').forEach((star, index) => {
            star.addEventListener('click', function(e) {
                e.preventDefault();
                const rating = index + 1;
                document.getElementById('rating').value = rating;
                document.querySelectorAll('.rating-stars a').forEach((s, i) => {
                    if (i < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
    });
</script>
@endsection
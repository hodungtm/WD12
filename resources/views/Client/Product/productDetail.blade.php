@extends('Client.Layouts.ClientLayout')
@section('main')
    <section class="product-single theme3 pt-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="position-relative">
                        <span class="badge badge-danger top-right">New</span>
                    </div>
                    <div class="product-sync-init mb-20">
                        <div class="single-product">
                            <div class="product-thumb">
                                @if($product->images->count())
    {{-- Ảnh chính --}}
    <div class="d-flex justify-content-center mb-3">
    <img id="mainProductImage"
         src="{{ asset('storage/' . $product->images->first()->image) }}"
         alt="{{ $product->name }}"
         style="width: auto; height: 400px;"
         class="img-fluid border rounded shadow-sm">
</div>

    {{-- Ảnh phụ --}}
    <div class="d-flex justify-content-center flex-wrap gap-2">
        @foreach($product->images as $img)
            <img src="{{ asset('storage/' . $img->image) }}"
                 alt="Ảnh phụ"
                 class="thumbnail border rounded shadow-sm"
                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                 onclick="document.getElementById('mainProductImage').src = this.src">
        @endforeach
    </div>
@else
    <p>Không có ảnh</p>
@endif
                            </div>
                        </div>
                        
                    </div>
                   
                </div>
                <div class="col-lg-6 mt-5 mt-md-0">
                    <div class="single-product-info">
                        <div class="single-product-head">
                            <h2 class="title mb-20">{{ $product->name }}</h2>
                            <div class="star-content mb-20">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star-on"><i class="ion-ios-star"></i></span>
                                @endfor
                                <a href="#pills-contact"><span class="me-2"><i class="far fa-comment-dots"></i></span> Đọc
                                    đánh giá ({{ $reviews->count() }})</a>
                            </div>
                        </div>
                        <div class="product-body mb-40">
                            <h6 class="product-price me-2">
                                <span
                                    class="onsale">{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫</span>
                                @if($product->sale_price)
                                    <del class="del">{{ number_format($product->price, 0, ',', '.') }}₫</del>
                                @endif
                            </h6>
                            <p>{{ $product->description }}</p>
                        </div>
                        <div class="product-footer">
                            <form action="" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Màu sắc:</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach($product->variants->groupBy('color_id') as $colorId => $variantsByColor)
                                            @php $color = $variantsByColor->first()->color; @endphp
                                            <label class="color-option position-relative btn btn-outline-dark"
                                                style="cursor: pointer;">
                                                <input type="radio" name="selected_color" value="{{ $colorId }}"
                                                    class="d-none color-radio">

                                                <div class="text-center small mt-1">{{ $color->name }}</div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Kích thước:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($product->variants as $variant)
                                            <label
                                                class="size-option d-none color-{{ $variant->color_id }} btn btn-outline-dark"
                                                data-variant-id="{{ $variant->id }}"
                                                data-price="{{ $variant->price ?? $variant->price }}"
                                                data-stock="{{ $variant->quantity }}">
                                                {{ $variant->size->name ?? 'N/A' }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div id="variant-info" class="d-none mt-3">
                                    <div class="mb-2 text-muted">
                                        <span>Giá: </span>
                                        <span id="selected-price" class="fw-bold text-danger"></span>
                                        <span id="selected-stock" class="ms-3">(Còn <span id="stock-count"></span>
                                            SP)</span>
                                    </div>
                                    <label for="quantity" class="form-label">Số lượng:</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control mb-3" value="1"
                                        min="1" style="width: 100px;">
                                </div>

                                <input type="hidden" name="selected_variant_id" id="selected_variant_id">

                                <div class="mb-4">
                                    <button type="submit" class="btn theme-btn--dark3 btn--xl mt-5 mt-sm-0 rounded-5">
                                        <span class="me-2"><i class="ion-android-add"></i></span>
                                        Add to cart
                                    </button>
                                </div>
                            </form>
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
    </section>
    <!-- product-single end -->
    <!-- product tab start -->
    <div class="product-tab theme3 bg-white pt-60 pb-80">
        <div class="container">
            <div class="product-tab-nav">
                <div class="row align-items-center">
                    <div class="col-12">
                        <nav class="product-tab-menu single-product">
                            <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home"
                                        role="tab" aria-controls="pills-home" aria-selected="true">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile"
                                        role="tab" aria-controls="pills-profile" aria-selected="false">Product Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact"
                                        role="tab" aria-controls="pills-contact" aria-selected="false">Reviews</a>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="pills-comment-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-comment" type="button" role="tab"
                                        aria-controls="pills-comment" aria-selected="false">
                                        Comments
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- product-tab-nav end -->
            <div class="row">
                <div class="col-12">
                    <div class="tab-content" id="pills-tabContent">
                        <!-- first tab-pane -->
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <div class="single-product-desc">
                                <ul>
                                    <li>
                                        Block out the haters with the fresh adidas® Originals Kaval Windbreaker Jacket.
                                    </li>
                                    <li>
                                        Part of the Kaval Collection.
                                    </li>
                                    <li>
                                        Regular fit is eased, but not sloppy, and perfect for any activity.
                                    </li>
                                    <li>
                                        Plain-woven jacket specifically constructed for freedom of movement.
                                    </li>
                                    <li>
                                        Soft fleece lining delivers lightweight warmth.
                                    </li>
                                    <li>
                                        Attached drawstring hood.
                                    </li>
                                    <li>
                                        Full-length zip closure.
                                    </li>
                                    <li>
                                        Long sleeves with stretch cuffs.
                                    </li>
                                    <li>
                                        Side hand pockets.
                                    </li>
                                    <li>
                                        Brand graphics at left chest and back.
                                    </li>
                                    <li>
                                        Straight hem.
                                    </li>
                                    <li>
                                        Shell: 100% nylon;<br>Lining: 100% polyester.
                                    </li>
                                    <li>
                                        Machine wash, tumble dry.
                                    </li>
                                    <li>
                                        Imported.
                                    </li>
                                    <li>
                                        <div>Product measurements were taken using size MD. Please note that
                                            measurements may vary by size.</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- second tab-pane -->
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="single-product-desc">
                                <div class="studio-thumb">
                                    <a href="#"><img class="mb-30" src="assets/img/stodio.jpg" alt="studio-thumb"></a>
                                    <h6 class="mb-2">Reference <small>demo_1</small> </h6>
                                    <h6>In stock <small>300 Items</small> </h6>
                                    <h3>Data sheet</h3>
                                </div>
                                <div class="product-features">
                                    <ul>
                                        <li><span>Compositions</span></li>
                                        <li><span>Cotton</span></li>
                                        <li><span>Paper Type</span></li>
                                        <li><span>Doted</span></li>
                                        <li><span>Color</span></li>
                                        <li><span>Black</span></li>
                                        <li><span>Size</span></li>
                                        <li><span>L</span></li>
                                        <li><span>Frame Size</span></li>
                                        <li><span>40x60cm</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- third tab-pane -->
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="single-product-desc">
                                @foreach($reviews as $review)
                                    <div class="grade-content mb-4">
                                        <span class="grade">{{ $review->nguoi_dung }}</span>
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="star-on">
                                                <i class="ion-ios-star{{ $i <= $review->so_sao ? '' : '-outline' }}"></i>
                                            </span>
                                        @endfor
                                        <h6 class="sub-title">{{ $review->nguoi_dung }}</h6>
                                        <p>{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}</p>
                                        <h4 class="title">{{ $review->tieu_de }}</h4>
                                        <p>{{ $review->noi_dung }}</p>

                                    </div>
                                    <hr class="hr">

                                @endforeach

                                {{-- Nút mở modal để gửi đánh giá --}}
                                <a href="#" class="btn theme-btn--dark3 theme-btn--dark3-sm btn--sm rounded-5 mt-15"
                                    data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Viết đánh giá sản phẩm !</a>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-comment" role="tabpanel" aria-labelledby="pills-comment-tab">
                            <div class="single-product-desc">
                                @forelse($comments as $comment)
                                    <div class="grade-content">
                                        <h6 class="sub-title">{{ $comment->tac_gia }}</h6>
                                        <p>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}</p>
                                        <p>{{ $comment->noi_dung }}</p>
                                    </div>
                                    <hr class="hr">
                                @empty
                                    <p>Chưa có bình luận nào cho sản phẩm này.</p>
                                @endforelse

                                <!-- Nút mở modal bình luận -->
                                <a href="#" class="btn theme-btn--dark3 theme-btn--dark3-sm btn--sm rounded-5 mt-15"
                                    data-bs-toggle="modal" data-bs-target="#commentModal">
                                    Viết bình luận
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Đánh giá -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('client.product.review', $product->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Viết đánh giá của bạn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên</label>
                            <input type="text" name="ten_nguoi_danh_gia" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Số sao (1-5)</label>
                            <input type="number" name="so_sao" class="form-control" min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label>Nội dung</label>
                            <textarea name="noi_dung" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal bình luận -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('client.product.comment', $product->id) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Viết bình luận của bạn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="tac_gia">Tên của bạn</label>
                        <input type="text" class="form-control" name="tac_gia" required>
                    </div>
                    <div class="form-group">
                        <label for="noi_dung">Nội dung bình luận</label>
                        <textarea class="form-control" name="noi_dung" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn theme-btn--dark3 btn--sm rounded-5">Gửi bình luận</button>
                </div>
            </form>
        </div>
    </div>

    </div>

    </div>


    <style>
        .color-option input:checked+span {
            border: 2px solid #000;
        }

        .size-option.active {
            background-color: #000 !important;
            color: #fff !important;
        }
    </style>

    <script>
        document.querySelectorAll('.color-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                const colorId = this.value;
                document.querySelectorAll('.size-option').forEach(btn => {
                    btn.classList.add('d-none');
                    btn.classList.remove('active');
                });
                document.querySelectorAll('.color-' + colorId).forEach(btn => {
                    btn.classList.remove('d-none');
                });
                document.getElementById('variant-info').classList.add('d-none');
            });
        });

        document.querySelectorAll('.size-option').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.size-option').forEach(el => el.classList.remove('active'));
                this.classList.add('active');

                const variantId = this.dataset.variantId;
                const price = parseInt(this.dataset.price).toLocaleString('vi-VN') + '₫';
                const stock = this.dataset.stock;

                document.getElementById('selected-price').textContent = price;
                document.getElementById('stock-count').textContent = stock;
                document.getElementById('quantity').setAttribute('max', stock);
                document.getElementById('selected_variant_id').value = variantId;
                document.getElementById('variant-info').classList.remove('d-none');
            });
        });
    </script>
    </section>
@endsection
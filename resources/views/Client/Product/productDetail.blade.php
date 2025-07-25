@extends('Client.Layouts.ClientLayout')

@section('main')
<main class="main">
    <div class="container my-5">
        <div class="row">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-6">
                <div class="product-gallery">
                    <img src="{{ asset('storage/' . $product->images->first()->image) }}" class="img-fluid" id="mainImage" alt="Product Image">
                    <div class="mt-3 d-flex flex-wrap gap-2">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img->image) }}" class="img-thumbnail thumbnail-img" width="80" onclick="changeImage(this)">
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <h2>{{ $product->name }}</h2>
                <p class="text-muted">{{ $product->category->name ?? '' }}</p>

                <div id="variant-info" class="mb-3">
                    <strong>Giá: </strong>
                    <span id="variant-price" class="text-danger fs-4">
                        {{ number_format($product->variants->first()->price ?? 0, 0, ',', '.') }}₫
                    </span>
                </div>

                <form action="{{ route('client.cart.add', $product->id) }}" method="POST">

                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selected_variant_id" value="{{ $product->variants->first()->id ?? '' }}">

                    <!-- Chọn màu -->
                    <div class="mb-3">
                        <label class="form-label">Màu sắc:</label><br>
                        @foreach($product->variants->pluck('color')->unique('id') as $color)
                            @if ($color)
                                <button type="button" class="btn btn-outline-dark btn-sm color-btn me-1 mb-1"
                                    data-color="{{ $color->id }}">{{ $color->name }}</button>
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
                            
                            <button type="submit" class="btn btn-dark add-cart mr-2" id="add-to-cart-btn" disabled>
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

                    <!-- Chọn size -->
                    <div class="mb-3">
                        <label class="form-label">Kích thước:</label><br>
                        <div id="size-container">
                            @foreach($product->variants->pluck('size')->unique('id') as $size)
                                @if ($size)
                                    <button type="button" class="btn btn-outline-secondary btn-sm size-btn me-1 mb-1"
                                        data-size="{{ $size->id }}">{{ $size->name }}</button>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Số lượng -->
                    <div class="mb-3">
                        <label>Số lượng:</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 100px;">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                        <button type="button" onclick="buyNow()" class="btn btn-danger">Mua ngay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const variants = @json($product->variants);
    let selectedColor = null;
    let selectedSize = null;

    function updateVariant() {
        const variant = variants.find(v =>
            v.color_id == selectedColor && v.size_id == selectedSize
        );

        if (variant) {
            document.getElementById('selected_variant_id').value = variant.id;
            document.getElementById('variant-price').innerText = parseInt(variant.price).toLocaleString('vi-VN') + '₫';
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
                  alert("Có lỗi khi mua hàng!");
              }
          });
    }
</script>
@endsection

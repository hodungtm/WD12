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
                        @endforeach
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

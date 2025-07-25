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
                <div class="product-category">
                    <img src="{{ asset('storage/' . $category->anh) }}" alt="icon" width="60" height="60">
                    <div class="category-content">
                        <h3 class="font2 ls-0 text-uppercase mb-0">{{ $category->ten_danh_muc }}</h3>
                    </div>
                </div>
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
                                <a href="#" class="btn-icon-wish" title="wishlist"><i class="icon-heart"></i></a>
                                <a href="{{ route('client.product.detail', $product->id) }}" class="btn-icon btn-add-cart product-type-simple"><i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span></a>
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
                                <a href="#" class="btn-icon-wish" title="wishlist"><i class="icon-heart"></i></a>
                                <a href="{{ route('client.product.detail', $product->id) }}" class="btn-icon btn-add-cart product-type-simple"><i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span></a>
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
                <div class="col-12">
                    <div class="blog-init slick-nav">
                        @foreach($posts as $post)
                        <div class="slider-item">
                            <div class="single-blog">
                                <a class="blog-thumb mb-20 zoom-in d-block overflow-hidden" href="#">
                                    <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/images/no-image.png') }}" alt="{{ $post->title }}"
                                        style="height: 200px; object-fit: cover;">
                                </a>
                                <div class="blog-post-content">
                                    <a class="blog-link theme-color d-inline-block mb-10 text-uppercase"
                                        href="#">Tin tức</a>
                                    <h3 class="title text-capitalize mb-15">
                                        <a href="#">{{ $post->title }}</a>
                                    </h3>
                                    <h5 class="sub-title">
                                        Đăng bởi <a class="blog-link theme-color mx-1" href="#">Quản trị viên</a>
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
</main>
<!-- Nút Robot -->
  <div class="chatbot-toggle">
   <button class="btn rounded-circle p-3 text-white" style="background-color: #4DB7B3;" onclick="toggleChat()">
      <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png" width="30" height="30" alt="Chatbot">
    </button>
  </div>

  <!-- Khung Chat -->
  <div class="chatbot-container" id="chatbox">
    <div class="card h-100 shadow d-flex flex-column">
      <div class="card-header  text-white" style="background-color: #4DB7B3;" >
        🤖 Hỗ trợ khách hàng 
      </div>
      <div class="card-body flex-grow-1 overflow-auto" id="chat-body">
        <!-- Nội dung chat -->
      </div>
      <div class="card-footer p-2">
        <div class="input-group">
          <input type="text" id="message" class="form-control" placeholder="Nhập câu hỏi...">
         <button class="btn text-white" style="background-color: #4DB7B3;" onclick="sendMessage()">Gửi</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap + JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  let isFirstOpen = true; // đánh dấu lần mở đầu tiên

  function toggleChat() {
    const chatbox = document.getElementById('chatbox');
    const isHidden = (chatbox.style.display === 'none' || chatbox.style.display === '');

    if (isHidden) {
      chatbox.style.display = 'block';

      // 👉 Chào khách hàng chỉ lần đầu
      if (isFirstOpen) {
        appendMessage('bot', '🤖 Xin chào! Mình có thể hỗ trợ gì cho bạn hôm nay?');
        isFirstOpen = false;
      }
    } else {
      chatbox.style.display = 'none';
    }
  }

  function appendMessage(sender, text) {
    const chatBody = document.getElementById('chat-body');
    const div = document.createElement('div');
    div.className = 'mb-2 text-' + (sender === 'user' ? 'end text-primary' : 'start text-dark');
    div.innerText = text;
    chatBody.appendChild(div);
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  function sendMessage() {
    const input = document.getElementById('message');
    const message = input.value.trim();
    if (!message) return;

    appendMessage('user', message);
    input.value = '';

    fetch('/chatbot', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => appendMessage('bot', data.reply))
    .catch(() => appendMessage('bot', 'Đã xảy ra lỗi, vui lòng thử lại.'));
  }
</script>
@endsection
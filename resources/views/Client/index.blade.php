@extends('Client.Layouts.ClientLayout')
@section('main')
<main class="main">
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
    <section class="popular-products">
        <div class="container">
            <h2 class="section-title appear-animate" data-animation-name="fadeInUpShorter"
                data-animation-delay="200"><a href="{{ route('client.products.featured') }}" target="_blank">Sản phẩm nổi bật</a></h2>
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
                                <button type="button" class="btn-icon btn-add-cart" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#variantModal"
                                        data-product-id="{{ $product->id }}" 
                                        data-product-name="{{ $product->name }}" 
                                        data-variants='@json($product->variants)'>
                                    <i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span>
                                </button>
                                <a href="{{ route('client.product.detail', $product->id) }}" class="btn-quickview" title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section class="trendy-section mb-2">
        <div class="container">
            <h2 class="section-title appear-animate" data-animation-name="fadeInUpShorter"
                data-animation-delay="200"><a href="{{ route('client.products.best_sellers') }}" target="_blank">Sản phẩm bán chạy</a></h2>
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
                                <button type="button" class="btn-icon btn-add-cart" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#variantModal"
                                        data-product-id="{{ $product->id }}" 
                                        data-product-name="{{ $product->name }}" 
                                        data-variants='@json($product->variants)'>
                                    <i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span>
                                </button>
                                <a href="{{ route('client.product.detail', $product->id) }}" class="btn-quickview" title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            </div>
    </section>
    
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

<div class="chatbot-toggle">
   <button class="btn rounded-circle p-3 text-white" style="background-color: #4DB7B3;" onclick="toggleChat()">
      <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png" width="30" height="30" alt="Chatbot">
    </button>
  </div>

  <div class="chatbot-container" id="chatbox">
    <div class="card h-100 shadow d-flex flex-column">
      <div class="card-header  text-white" style="background-color: #4DB7B3;" >
        🤖 Hỗ trợ khách hàng 
      </div>
      <div class="card-body flex-grow-1 overflow-auto" id="chat-body">
        </div>
      <div class="card-footer p-2">
        <div class="input-group">
          <input type="text" id="message" class="form-control" placeholder="Nhập câu hỏi...">
         <button class="btn text-white" style="background-color: #4DB7B3;" onclick="sendMessage()">Gửi</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Khai báo biến global
    let isFirstOpen = true; // đánh dấu lần mở đầu tiên
    let currentVariants = []; // lưu trữ variants hiện tại
    let selectedModalColor = null;
    let selectedModalSize = null;
    
    // Cần khai báo biến này ngoài DOMContentLoaded để các hàm khác có thể truy cập
    let variantModal;

    document.addEventListener('DOMContentLoaded', function() {
        const chatbox = document.getElementById('chatbox');
        
        function toggleChat() {
            const isHidden = (chatbox.style.display === 'none' || chatbox.style.display === '');
            if (isHidden) {
                chatbox.style.display = 'block';
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
            const variantsString = button.getAttribute('data-variants');
            
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
            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductName').textContent = productName;
            fetch(`/api/product/${productId}/image`)
                .then(res => res.json())
                .then(data => {
                    if (data.image) {
                        document.getElementById('modalProductImage').src = data.image;
                    }
                })
                .catch(err => {
                    document.getElementById('modalProductImage').src = '/assets/images/no-image.png';
                });
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
            const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>';
            const alertDiv = document.createElement('div');
            alertDiv.className = 'custom-alert';
            alertDiv.innerHTML = `<span class="icon-warning">${icon}</span> ${message} <button type="button" class="close" onclick="this.parentElement.remove()"><span aria-hidden="true">&times;</span></button>`;
            let alertStack = document.getElementById('alert-stack');
            if (!alertStack) {
                alertStack = document.createElement('div');
                alertStack.id = 'alert-stack';
                alertStack.style.cssText = 'position: fixed; top: 80px; right: 24px; z-index: 9999;';
                document.body.appendChild(alertStack);
            }
            alertStack.appendChild(alertDiv);
            setTimeout(() => { alertDiv.remove(); }, 3500);
        }
    });
</script>
  
  <div id="alert-stack" style="position: fixed; top: 80px; right: 24px; z-index: 9999;"></div>
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

/* Modal styles */
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

/* Custom alert styles */
.custom-alert {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 12px 16px;
    margin-bottom: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 8px;
    max-width: 300px;
    animation: slideInRight 0.3s ease;
}

.custom-alert .icon-warning {
    color: #28a745;
}

.custom-alert .close {
    background: none;
    border: none;
    font-size: 18px;
    color: #999;
    cursor: pointer;
    margin-left: auto;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
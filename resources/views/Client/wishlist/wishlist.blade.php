@extends('Client.Layouts.ClientLayout')


@section('css')
    <style>
        .container {
            max-width: 1360px;
        }

        /* Giao diện tổng thể bảng */
        .table-wishlist {
            background-color: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .table-wishlist thead {
            background-color: #f8f9fa;
            color: #333;
        }

        .table-wishlist th {
            padding: 15px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid #dee2e6;
        }

        /* Dòng sản phẩm */
        .table-wishlist .product-row {
            transition: background-color 0.3s ease;
        }

        .table-wishlist .product-row:hover {
            background-color: #fdfdfd;
        }

        /* Cột ảnh */
        .product-image-container {
            width: 90px;
            height: 90px;
            overflow: hidden;
            border-radius: 6px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Tên sản phẩm */
        .product-title a {
            color: #333;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
        }

        .product-title a:hover {
            color: #38bcb2;
        }

        /* Size, màu */
        .product-row p {
            margin-bottom: 2px;
            font-size: 13px;
            color: #666;
        }

        /* Giá */
        .price-box {
            font-size: 15px;
            font-weight: 500;
            color: #222;
        }

        .price-box del {
            color: #aaa;
            font-size: 13px;
        }

        /* Tồn kho */
        .stock-status {
            font-size: 14px;
            font-weight: 500;
        }

        /* Button hành động */
        td.action {
            gap: 8px;
            justify-content: flex-start;
            height: auto !important;
        }

        td.action form {
            margin: 0;
        }

        td.action .btn {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .btn.btn-add-cart {
            background-color: #38bcb2;
            border: none;
            color: #fff;
        }

        .btn.btn-add-cart:hover {
            background-color: #2fa7a0;
        }

        .btn.btn-quickview {
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            color: #333;
        }

        .btn.btn-quickview:hover {
            background-color: #e2e6ea;
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
@endsection
@section('main')


    <div class="page-wrapper">

        <main class="main">
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
            <section class="popular-products">
                <div class="container">
                    <div class="row">
                        <div class="products-slider 5col owl-carousel owl-theme appear-animate" data-owl-options="{
            'margin': 0
        }" data-animation-name="fadeInUpShorter" data-animation-delay="200">
                            @foreach ($wishlists as $wishlist)
                                @php
                                    $product = $wishlist->product;
                                    $image = $product->images->first()->image ?? 'product_images/default.jpg';
                                    $name = $product->name ?? 'Sản phẩm';
                                    $variants = $product->variants;
                                    $minPrice = $variants->min('price');
                                    $minSale = $variants->min('sale_price');
                                    $discountPercent = $minSale > 0 ? round((($minPrice - $minSale) / $minPrice) * 100) : 0;
                                @endphp

                                <div class="product-default">
                                    <figure>
                                        <a href="{{ route('client.product.detail', $product->id) }}">
                                            <img src="{{ asset('storage/' . $image) }}" width="280" height="280"
                                                alt="{{ $name }}" style="object-fit: contain;">
                                        </a>
                                        @if ($discountPercent > 0)
                                            <div class="label-group">
                                                <div class="product-label label-hot">-{{ $discountPercent }}%</div>
                                            </div>
                                        @endif
                                    </figure>

                                    <div class="product-details">
                                        <div class="category-list">
                                            <a href="#" class="product-category">Yêu thích</a>
                                        </div>
                                        <h3 class="product-title">
                                            <a href="{{ route('client.product.detail', $product->id) }}">{{ $name }}</a>
                                        </h3>

                                        <div class="ratings-container">
                                            <div class="product-ratings">
                                                <span class="ratings" style="width:80%"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                        </div>

                                        <div class="price-box">
                                            @if ($minSale > 0)
                                                <del class="text-muted">{{ number_format($minPrice, 0, ',', '.') }}₫</del>
                                                <span class="product-price text-danger">{{ number_format($minSale, 0, ',', '.')
                                                    }}₫</span>
                                            @else
                                                <span class="product-price">{{ number_format($minPrice, 0, ',', '.') }}₫</span>
                                            @endif
                                        </div>

                                        <div class="product-action">
                                            <a href="#" class="btn-icon-wish" title="Xoá yêu thích"
                                                onclick="event.preventDefault(); submitDeleteForm('remove-wishlist-{{ $wishlist->id }}')">
                                                <i class="icon-heart" style="color: #e74c3c;"></i>
                                            </a>
                                            <form id="remove-wishlist-{{ $wishlist->id }}"
                                                action="{{ route('client.wishlist.remove', $wishlist->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>


                                            {{-- Thêm vào giỏ hàng --}}
                                            <form action="{{ route('client.cart.add', $product->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id ?? '' }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="button" class="btn-icon btn-add-cart" 
        data-bs-toggle="modal" 
        data-bs-target="#variantModal"
        data-product-id="{{ $product->id }}" 
        data-product-name="{{ $product->name }}" 
        data-variants='@json($product->variants)'>
    <i class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ</span>
</button>
                                            </form>
                                            

                                            <a href="{{ route('client.product.detail', $product->id) }}" class="btn-quickview" title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            </section>
        </main><!-- End .main -->

    </div><!-- End .page-wrapper -->
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
@endsection

@section('js')

    <script>
        function submitForm(formId) {
            const form = document.getElementById(formId);
            if (form) form.submit();
        }

        function submitDeleteForm(formId) {
            if (confirm("Bạn có chắc chắn muốn bỏ sản phẩm ra khỏi danh sách yêu thích không?")) {
                submitForm(formId);
            }
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
            const productImage = button.closest('.product-default').querySelector('figure img').src;
            const variantsString = button.getAttribute('data-variants');
            
            // Cập nhật ảnh ngay khi mở modal
            document.getElementById('modalProductImage').src = productImage;
            
            try {
                const variants = JSON.parse(variantsString);
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
            
            // Xóa bỏ phần fetch ảnh cũ
            // fetch(`/api/product/${productId}/image`)...;
            
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

        // Sửa lại hàm showAlert
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


    </script>


@endsection
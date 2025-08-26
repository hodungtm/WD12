@extends('Client.Layouts.ClientLayout')

@section('main')
    <style>
        .order-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .order-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .order-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .order-header .order-code {
            font-size: 1.1rem;
            opacity: 0.9;
            font-family: 'Courier New', monospace;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f2f5;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }

        .info-card h5 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #3498db;
            font-size: 1.2rem;
        }

        .status-badges {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .status-badge {
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }

        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
            border: 2px solid #74b9ff;
        }

        .status-shipping {
            background: #cce5ff;
            color: #004085;
            border: 2px solid #0984e3;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
            border: 2px solid #00b894;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #e17055;
        }

        .payment-paid {
            background: #d4edda;
            color: #155724;
            border: 2px solid #00b894;
        }

        .payment-pending {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }

        .receiver-info {
            background: #f8f9fa;
            padding: 1.2rem;
            border-radius: 12px;
            border-left: 4px solid #3498db;
        }

        .receiver-info p {
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .receiver-info .name {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c3e50;
        }

        .products-table {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f2f5;
        }

        .products-table .table {
            margin-bottom: 0;
        }

        .products-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .products-table th {
            padding: 1.2rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .products-table td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }

        .products-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #f0f2f5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .variant-info {
            background: #e3f2fd;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #1565c0;
            font-weight: 500;
        }

        .quantity-badge {
            background: #f0f2f5;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            color: #495057;
            text-align: center;
        }

        .price-text {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .total-price {
            font-weight: 700;
            color: #e74c3c;
            font-size: 1rem;
        }

        .order-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #2c3e50;
            padding: 2rem;
            border-radius: 16px;
            margin-top: 2rem;
            border: 2px solid #dee2e6;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .order-summary h4 {
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #dee2e6;
            color: #495057;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        .back-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
            text-decoration: none;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .order-detail-container {
                padding: 1rem;
            }

            .order-header {
                padding: 1.5rem;
            }

            .order-header h1 {
                font-size: 1.5rem;
            }

            .status-badges {
                flex-direction: column;
            }

            .status-badge {
                justify-content: center;
            }

            .product-info {
                flex-direction: column;
                text-align: center;
            }

            .products-table {
                font-size: 0.85rem;
            }

            .products-table th,
            .products-table td {
                padding: 0.8rem 0.5rem;
            }

            .summary-row {
                font-size: 0.9rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <main class="order-detail-container">
        <!-- Order Header -->
        <div class="order-header">
            <h1><i class="fas fa-receipt me-2"></i>Chi tiết đơn hàng</h1>
            <div class="order-code">{{ $order->order_code }}</div>
        </div>

        <!-- Order Status and Info Grid -->
        <div class="info-grid">
            <!-- Order Status Card -->
            <div class="info-card">
                <h5><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                <div class="status-badges">
                    @php
                        $statusClass = '';
                        $statusIcon = '';
                        switch ($order->status) {
                            case 'Đang chờ':
                                $statusClass = 'status-pending';
                                $statusIcon = 'fas fa-clock';
                                break;
                            case 'Xác nhận đơn':
                                $statusClass = 'status-confirmed';
                                $statusIcon = 'fas fa-check-circle';
                                break;
                            case 'Đang giao hàng':
                                $statusClass = 'status-shipping';
                                $statusIcon = 'fas fa-shipping-fast';
                                break;
                            case 'Hoàn thành':
                                $statusClass = 'status-completed';
                                $statusIcon = 'fas fa-check-double';
                                break;
                            case 'Đã hủy':
                                $statusClass = 'status-cancelled';
                                $statusIcon = 'fas fa-times-circle';
                                break;
                            default:
                                $statusClass = 'status-pending';
                                $statusIcon = 'fas fa-question-circle';
                        }

                        $paymentClass = '';
                        $paymentIcon = '';
                        switch ($order->payment_status) {
                            case 'Đã thanh toán':
                                $paymentClass = 'payment-paid';
                                $paymentIcon = 'fas fa-credit-card';
                                break;
                            default:
                                $paymentClass = 'payment-pending';
                                $paymentIcon = 'fas fa-hourglass-half';
                        }
                    @endphp

                    <div class="status-badge {{ $statusClass }}">
                        <i class="{{ $statusIcon }}"></i>
                        {{ $order->status }}
                    </div>

                    <div class="status-badge {{ $paymentClass }}">
                        <i class="{{ $paymentIcon }}"></i>
                        {{ $order->payment_status }}
                    </div>
                </div>

                <p><strong><i class="fas fa-calendar-alt me-2"></i>Ngày đặt:</strong>
                    {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
            </div>

            <!-- Receiver Info Card -->
            <div class="info-card">
                <h5><i class="fas fa-user-circle me-2"></i>Thông tin người nhận</h5>
                <div class="receiver-info">
                    <p class="name"><i class="fas fa-user me-2"></i>{{ $order->receiver_name }}</p>
                    <p><i class="fas fa-phone me-2"></i>{{ $order->receiver_phone }}</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>{{ $order->receiver_address }}</p>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="info-card">
            <h5><i class="fas fa-shopping-cart me-2"></i>Danh sách sản phẩm</h5>
            <div class="products-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-box me-2"></i>Sản phẩm</th>
                            <th><i class="fas fa-tags me-2"></i>Biến thể</th>
                            <th><i class="fas fa-sort-numeric-up me-2"></i>Số lượng</th>
                            <th><i class="fas fa-money-bill me-2"></i>Đơn giá</th>
                            <th><i class="fas fa-calculator me-2"></i>Thành tiền</th>
                            <th><i class="fas fa-star me-2"></i>Đánh giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="{{ asset('storage/' . $item->product_image) }}"
                                            alt="{{ $item->product_name }}" class="product-image"
                                            onerror="this.src='{{ asset('assets/images/no-image.png') }}'">
                                        <div class="product-name">{{ $item->product_name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="variant-info">{{ $item->variant_name }}</span>
                                </td>
                                <td>
                                    <span class="quantity-badge">{{ $item->quantity }}</span>
                                </td>
                                <td>
                                    <span class="price-text">{{ number_format($item->price, 0, ',', '.') }}₫</span>
                                </td>
                                <td>
                                    <span class="total-price">{{ number_format($item->total_price, 0, ',', '.') }}₫</span>
                                </td>
                                <td class="text-center">
                                    @if($order->status === 'Hoàn thành')  
                                        <button type="button"
                                                class="btn btn-outline-warning btn-sm btn-open-review-modal"
                                                data-product-id="{{ $item->product_id ?? '' }}"
                                                data-order-item-id="{{ $item->id ?? '' }}"
                                                data-product-name="{{ $item->product_name ?? '' }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reviewModal"
                                                title="Đánh giá sản phẩm">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h4><i class="fas fa-file-invoice-dollar me-2"></i>Tổng kết đơn hàng</h4>
            <div class="summary-row">
                <span>Tổng tiền hàng:</span>
                <span>{{ number_format($items->sum('total_price'), 0, ',', '.') }}₫</span>
            </div>
            @php
                $shippingFee = ($items->sum('total_price') >= 300000) ? 0 : $order->shippingMethod->fee;
            @endphp

            <div class="summary-row">
                <span>Phí vận chuyển:</span>
                @if($shippingFee == 0)
                    <span>Miễn phí</span>
                @else
                    <span>{{ number_format($shippingFee, 0, ',', '.') }}₫</span>
                @endif
            </div>
            <div class="summary-row">
                <span>Giảm giá:</span>
                <span>- {{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>

            </div>
            <div class="summary-row">
                <span><i class="fas fa-money-check-alt me-2"></i>THANH TOÁN:</span>
                <span>{{ number_format($order->final_amount, 0, ',', '.') }}₫</span>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="{{ route('user.dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Quay lại danh sách đơn hàng
            </a>
        </div>
    </main>

    <!-- Add Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="reviewForm" method="POST" action="{{ route('client.product.review', 0) }}" class="comment-form m-0">
                    @csrf
                    <input type="hidden" name="product_id" id="reviewProductId">
                    <input type="hidden" name="order_item_id" id="reviewOrderItemId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">
                            <i class="fas fa-star me-2"></i>Đánh giá sản phẩm
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="rating-form mb-3">
                            <label for="modal-rating">Đánh giá của bạn <span class="required text-danger">*</span></label>
                            <span class="rating-stars d-block mt-2" id="modal-rating-stars" role="radiogroup" aria-label="Đánh giá">
                                <style>
                                    .rating-star { color: #ccc; font-size: 1.5rem; background: transparent; border: none; padding: 0; margin-right: 5px; cursor: pointer; line-height: 1; }
                                    .rating-star:focus { outline: none; }
                                </style>
                                <button type="button" class="rating-star" data-value="1" aria-label="1 sao">★</button>
                                <button type="button" class="rating-star" data-value="2" aria-label="2 sao">★</button>
                                <button type="button" class="rating-star" data-value="3" aria-label="3 sao">★</button>
                                <button type="button" class="rating-star" data-value="4" aria-label="4 sao">★</button>
                                <button type="button" class="rating-star" data-value="5" aria-label="5 sao">★</button>
                            </span>
                            <input type="hidden" name="so_sao" id="modal-rating" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="modal-noi-dung">Nội dung đánh giá <span class="required text-danger">*</span></label>
                            <textarea name="noi_dung" id="modal-noi-dung" cols="5" rows="6" class="form-control" required placeholder="Viết đánh giá của bạn về sản phẩm này..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Gửi đánh giá">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Open review modal per product
            document.querySelectorAll('.btn-open-review-modal').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var productId = btn.getAttribute('data-product-id');
                    var orderItemId = btn.getAttribute('data-order-item-id');
                    var form = document.getElementById('reviewForm');

                    if (!productId) {
                        alert('Không xác định được sản phẩm để đánh giá.');
                        return;
                    }

                    form.reset();
                    form.action = "{{ route('client.product.review', 'PRODUCT_ID') }}".replace('PRODUCT_ID', productId);
                    document.getElementById('reviewProductId').value = productId;
                    document.getElementById('reviewOrderItemId').value = orderItemId || '';

                    // reset stars
                    document.getElementById('modal-rating').value = '';
                    document.querySelectorAll('.rating-star').forEach(s => s.style.color = '#ccc');
                });
            });

            // Star rating interactions
            const starContainer = document.getElementById('modal-rating-stars');
            const ratingInput = document.getElementById('modal-rating');
            if (starContainer && ratingInput) {
                starContainer.addEventListener('click', function (e) {
                    const btn = e.target.closest('.rating-star');
                    if (!btn) return;
                    const v = parseInt(btn.dataset.value, 10) || 0;
                    ratingInput.value = v;
                    document.querySelectorAll('.rating-star').forEach(s => s.style.color = '#ccc');
                    for (let i = 1; i <= v; i++) {
                        const s = starContainer.querySelector(`.rating-star[data-value="${i}"]`);
                        if (s) s.style.color = '#FFD700';
                    }
                });

                starContainer.addEventListener('mouseover', function (e) {
                    const btn = e.target.closest('.rating-star');
                    if (!btn) return;
                    const v = parseInt(btn.dataset.value, 10) || 0;
                    document.querySelectorAll('.rating-star').forEach(s => s.style.color = '#ccc');
                    for (let i = 1; i <= v; i++) {
                        const s = starContainer.querySelector(`.rating-star[data-value="${i}"]`);
                        if (s) s.style.color = '#FFD700';
                    }
                });

                starContainer.addEventListener('mouseout', function () {
                    const current = parseInt(ratingInput.value, 10) || 0;
                    document.querySelectorAll('.rating-star').forEach(s => s.style.color = '#ccc');
                    for (let i = 1; i <= current; i++) {
                        const s = starContainer.querySelector(`.rating-star[data-value="${i}"]`);
                        if (s) s.style.color = '#FFD700';
                    }
                });
            }
        });
    </script>
@endsection
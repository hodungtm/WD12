@extends('Client.Layouts.ClientLayout')

@section('main')
    <main class="main container my-5">
        <h3>Theo dõi đơn hàng</h3>

        @php
            $statuses = [
                '' => 'Tất cả',
                'Đang chờ' => 'Đang chờ',
                'Đang giao hàng' => 'Đang giao hàng',
                'Hoàn thành' => 'Hoàn thành',
                'Đã hủy' => 'Đã hủy',
            ];
        @endphp

        <ul class="nav nav-tabs mb-4">
            @foreach($statuses as $key => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $status == $key ? 'active' : '' }}"
                        href="{{ route('client.orders.index', ['status' => $key]) }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>

        @if($orders->count())
            @foreach($orders as $order)
                <div class="card mb-3 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Mã đơn:</strong> {{ $order->order_code }}<br>
                            <small>Ngày đặt: {{ $order->order_date }}</small>
                        </div>
                        <div>
                            <span class="badge bg-primary">{{ $order->status }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $firstItem = $order->orderItems->first();
                            $productExists = $firstItem ? \App\Models\Products::find($firstItem->product_id) : null;
                        @endphp
                    
                        @if($firstItem)
                            <div class="d-flex">
                                @if($productExists)
                                    <a href="{{ route('client.product.detail', $firstItem->product_id) }}">
                                        <img src="{{ asset('storage/' . $firstItem->product_image) }}" alt="" width="80" height="80"
                                            class="me-3 rounded border">
                                    </a>
                                @else
                                    <img src="{{ asset('storage/' . $firstItem->product_image) }}" alt="" width="80" height="80"
                                        class="me-3 rounded border">
                                @endif
                    
                                <div class="flex-grow-1">
                                    @if($productExists)
                                        <h6>
                                            <a href="{{ route('client.product.detail', $firstItem->product_id) }}"
                                                class="text-dark text-decoration-none">
                                                {{ $firstItem->product_name }}
                                            </a>
                                        </h6>
                                    @else
                                        <h6 class="text-muted">{{ $firstItem->product_name }}</h6>
                                    @endif
                    
                                    <small>Phân loại: {{ $firstItem->variant_name ?? '-' }}</small><br>
                                    <small>x{{ $firstItem->quantity }}</small>
                                </div>
                    
                                <div class="text-end">
                                    
                                    <strong>{{ number_format($firstItem->price, 0, ',', '.') }}₫</strong>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                Đơn hàng này không có sản phẩm nào.
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <strong>Thành tiền: {{ number_format($order->final_amount, 0, ',', '.') }}₫</strong>
                        <div>
                            @if($order->status === 'Đang chờ')
                                <form action="{{ route('client.orders.cancel', $order) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hủy đơn hàng này?')">Hủy</button>
                                </form>
                            @endif
                            @if($order->status === 'Hoàn thành' && $firstItem)
                                <button type="button" class="btn btn-sm btn-primary btn-open-review-modal"
                                    data-product-id="{{ $firstItem->product_id }}" data-order-item-id="{{ $firstItem->id }}">
                                    Đánh giá
                                </button>
                            @endif

                            <a href="{{ route('client.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">Xem chi
                                tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $orders->links() }}
        @else
            <p>Không có đơn hàng nào.</p>
        @endif
    </main>
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="reviewForm" method="POST" action="{{ route('client.product.review', 0) }}"
                    class="comment-form m-0">
                    @csrf
                    <input type="hidden" name="product_id" id="modalProductId">
                    <input type="hidden" name="order_item_id" id="modalOrderItemId">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="reviewModalLabel">Đánh giá sản phẩm</h5>
                    </div>
                    <div class="modal-body pt-2">
                        <div class="rating-form mb-3">
                            <label for="modal-rating">Đánh giá của bạn <span class="required">*</span></label>
                            <span class="rating-stars" id="modal-rating-stars">
                                <a class="star-1" href="#">★</a>
                                <a class="star-2" href="#">★</a>
                                <a class="star-3" href="#">★</a>
                                <a class="star-4" href="#">★</a>
                                <a class="star-5" href="#">★</a>
                            </span>
                            <input type="hidden" name="so_sao" id="modal-rating" required>
                            
                            </input>
                        </div>

                        <div class="form-group mb-3">
                            <label for="modal-noi-dung">Nội dung đánh giá <span class="required">*</span></label>
                            <textarea name="noi_dung" id="modal-noi-dung" cols="5" rows="6"
                                class="form-control form-control-sm" required
                                placeholder="Viết đánh giá của bạn về sản phẩm này..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer px-0 border-0">
                        <input type="submit" class="btn btn-primary" value="Gửi đánh giá">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Khi bấm nút Đánh giá
            document.querySelectorAll('.btn-open-review-modal').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var productId = btn.getAttribute('data-product-id');
                    var orderItemId = btn.getAttribute('data-order-item-id');
                    // Set action cho form (nếu route cần product_id)
                    var form = document.getElementById('reviewForm');
                    form.action = "{{ route('client.product.review', 'PRODUCT_ID') }}".replace('PRODUCT_ID', productId);
                    document.getElementById('modalProductId').value = productId;
                    document.getElementById('modalOrderItemId').value = orderItemId;
                    // Reset form
                    form.reset();
                    // Hiện modal (Bootstrap 5)
                    var modal = new bootstrap.Modal(document.getElementById('reviewModal'));
                    modal.show();
                });
            });

            // Hiệu ứng chọn sao cho modal
            const stars = document.querySelectorAll('#modal-rating-stars a');
            const ratingInput = document.getElementById('modal-rating');

            stars.forEach((star, idx) => {
                star.addEventListener('click', function (e) {
                    e.preventDefault();
                    stars.forEach(s => s.classList.remove('active'));
                    for (let i = 0; i <= idx; i++) {
                        stars[i].classList.add('active');
                    }
                    ratingInput.value = idx + 1; // set đúng số sao
                });
            });
        });
    </script>
    <style>
        /* Bo góc cho modal */
        .modal-content {
            border-radius: 18px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border: none;
        }

        /* Bo góc cho textarea và input */
        .modal-content textarea,
        .modal-content input,
        .modal-content select {
            border-radius: 8px !important;
        }

        /* Bo góc cho nút gửi */
        .modal-content .btn-primary {
            border-radius: 8px !important;
            font-weight: bold;
            padding: 10px 32px;
            font-size: 1.1rem;
        }

        /* Căn lại modal-footer cho nút đều hơn */
        .modal-footer {
            justify-content: flex-end;
            border: none;
            padding-right: 2.5rem;
            padding-bottom: 2rem;
            background: transparent;
        }

        /* Căn padding cho modal-body */
        .modal-body {
            padding: 2rem 2.5rem 1rem 2.5rem;
        }

        /* Căn lại label và các thành phần cho đều */
        .rating-form label,
        .form-group label {
            font-weight: 600;
            font-size: 1.08rem;
            margin-bottom: 0.5rem;
        }

        .rating-stars a {
            /* font-size: 28px; */
            color: #ccc;
            cursor: pointer;
            text-decoration: none;
            margin-right: 4px;
            transition: color 0.2s;
            display: inline-block;
            line-height: 1;
            vertical-align: middle;
            padding: 0;
            height: auto;
        }

        .rating-stars a.active,
        .rating-stars a:hover,
        .rating-stars a:hover~a {
            color: #FFD700;
        }

        .rating-stars a.active {
            color: #FFD700;

        }

        #modal-rating {
            opacity: 0 !important;
            position: absolute !important;
            left: -9999px !important;
        }
    </style>
@endsection
@extends('Client.Layouts.ClientLayout')

@section('main')
    <main class="main container my-5">
        <h3 class="mb-4">Theo dõi đơn hàng</h3>

        @php
            $statuses = [
                '' => 'Tất cả',
                'Đang chờ' => 'Đang chờ',
                'Đang giao hàng' => 'Đang giao hàng',
                'Hoàn thành' => 'Hoàn thành',
                'Đã hủy' => 'Đã hủy',
            ];
            $statusColors = [
                'Đang chờ' => 'bg-warning text-dark',
                'Đang giao hàng' => 'bg-info text-white',
                'Hoàn thành' => 'bg-success text-white',
                'Đã hủy' => 'bg-danger text-white',
            ];
            $statusIcons = [
                'Đang chờ' => 'fas fa-clock',
                'Đang giao hàng' => 'fas fa-truck',
                'Hoàn thành' => 'fas fa-check-circle',
                'Đã hủy' => 'fas fa-times-circle',
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
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="fs-5">Mã đơn: {{ $order->order_code }}</strong>
                        </div>
                        <div>
                            <span class="badge {{ $statusColors[$order->status] ?? 'bg-primary' }} fs-6 py-2 px-3">
                                <i class="{{ $statusIcons[$order->status] ?? 'fas fa-info-circle' }} me-1"></i>
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($order->orderItems->isEmpty())
                            <div class="alert alert-warning mb-0">
                                Đơn hàng này không có sản phẩm nào.
                            </div>
                        @else
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                <div class="mb-2 mb-sm-0">
                                    <strong>Ngày đặt:</strong> {{ $order->order_date }}<br>
                                    <strong>Thành tiền:</strong> {{ number_format($order->final_amount, 0, ',', '.') }}₫
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-end align-items-center">
                        <div class="action-buttons">
                            @if ($order->status === 'Đang chờ')
                                <button type="button" class="btn btn-danger btn-sm btn-cancel-order me-2" data-bs-toggle="modal"
                                    data-bs-target="#cancelModal" data-order-id="{{ $order->id }}">
                                    <i class="fas fa-ban me-1"></i> Hủy đơn
                                </button>
                            @endif
                            @if($order->status === 'Hoàn thành' && $order->orderItems->first())
                                <button type="button" class="btn btn-primary btn-sm btn-open-review-modal me-2"
                                    data-product-id="{{ $order->orderItems->first()->product_id }}"
                                    data-order-item-id="{{ $order->orderItems->first()->id }}">
                                    <i class="fas fa-star me-1"></i> Đánh giá
                                </button>
                            @endif
                            <a href="{{ route('client.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-eye me-1"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $orders->links() }}
        @else
            <div class="alert alert-info text-center">
                Không có đơn hàng nào.
            </div>
        @endif
    </main>

    <!-- Modal đánh giá -->
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

    <!-- Modal hủy đơn -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="cancelForm" method="POST" action="{{ route('client.orders.cancel') }}">
                @csrf
                <input type="hidden" name="order_id" id="order_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">Lý do hủy đơn</h5>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <textarea name="cancel_reason" id="cancel_reason" rows="4" class="form-control" maxlength="255" required
                            placeholder="Vui lòng nhập lý do hủy đơn (tối đa 255 ký tự)"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Khi bấm nút Đánh giá
            document.querySelectorAll('.btn-open-review-modal').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var productId = btn.getAttribute('data-product-id');
                    var orderItemId = btn.getAttribute('data-order-item-id');
                    var form = document.getElementById('reviewForm');
                    form.action = "{{ route('client.product.review', 'PRODUCT_ID') }}".replace('PRODUCT_ID', productId);
                    document.getElementById('modalProductId').value = productId;
                    document.getElementById('modalOrderItemId').value = orderItemId;
                    form.reset();
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
                    ratingInput.value = idx + 1;
                });
            });

            // Khi bấm nút Hủy đơn
            document.querySelectorAll('.btn-cancel-order').forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    document.getElementById('order_id').value = orderId;
                    document.getElementById('cancel_reason').value = '';
                    var modal = new bootstrap.Modal(document.getElementById('cancelModal'));
                    modal.show();
                });
            });
        });
    </script>

    <style>
        /* General styles */
        .card {
            border-radius: 16px !important;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
        }
        .card-header, .card-footer {
            background-color: #f8f9fa;
            border: none;
        }
        .card-header {
            padding: 1.25rem 1.5rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-footer {
            padding: 1rem 1.5rem;
        }
        .badge {
            font-size: 0.95rem;
            padding: 0.5rem 1.25rem;
            border-radius: 20px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }
        .action-buttons .btn {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-size: 0.9rem;
            transition: background-color 0.2s, transform 0.2s;
        }
        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }
        .action-buttons .btn-sm {
            min-width: 100px;
        }

        /* Modal styles */
        .modal-content {
            border-radius: 18px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border: none;
        }
        .modal-content textarea,
        .modal-content input,
        .modal-content select {
            border-radius: 8px !important;
        }
        .modal-content .btn-primary,
        .modal-content .btn-danger,
        .modal-content .btn-secondary {
            border-radius: 8px !important;
            font-weight: bold;
            padding: 10px 32px;
            font-size: 1.1rem;
        }
        .modal-footer {
            justify-content: flex-end;
            border: none;
            padding-right: 2.5rem;
            padding-bottom: 2rem;
            background: transparent;
        }
        .modal-body {
            padding: 2rem 2.5rem 1rem 2.5rem;
        }
        .rating-form label,
        .form-group label {
            font-weight: 600;
            font-size: 1.08rem;
            margin-bottom: 0.5rem;
        }
        .rating-stars a {
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
            font-size: 1.2rem;
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

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .card-header .badge {
                margin-top: 0.5rem;
            }
            .card-body .d-flex {
                flex-direction: column;
                align-items: flex-start;
            }
            .card-body .d-flex div {
                margin-bottom: 0.75rem;
            }
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .action-buttons .btn:last-child {
                margin-bottom: 0;
            }
        }
    </style>
@endsection
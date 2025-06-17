@extends('Admin.Layouts.AdminLayout')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Chi tiết sản phẩm: {{ $product->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Tên sản phẩm:</strong> {{ $product->name }}
                        </div>
                        <div class="mb-3">
                            <strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>Giá cơ sở:</strong> {{ number_format($product->price, 0, ',', '.') }} VNĐ
                        </div>
                        <div class="mb-3">
                            <strong>Mô tả:</strong> {{ $product->description }}
                        </div>
                        <div class="mb-3">
                            <strong>Hình ảnh chính:</strong>
                            @if ($product->image)
                                <div>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 200px;">
                                </div>
                            @else
                                Không có hình ảnh chính
                            @endif
                        </div>

                        <hr>

                        <h5>Biến thể sản phẩm</h5>
                        @if ($product->variants->isNotEmpty())
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tên biến thể</th>
                                        <th>SKU</th>
                                        <th>Giá</th>
                                        <th>Giá khuyến mãi</th>
                                        <th>Số lượng</th>
                                        <th>Trạng thái kho</th>
                                        <th>Mô tả biến thể</th>
                                        <th>Hình ảnh biến thể</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants as $variant)
                                        <tr>
                                            <td>{{ $variant->attribute_text }}</td>
                                            <td>{{ $variant->sku }}</td>
                                            <td>{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $variant->sale_price ? number_format($variant->sale_price, 0, ',', '.') . ' VNĐ' : 'N/A' }}</td>
                                            <td>{{ $variant->quantity }}</td>
                                            <td>{{ $variant->stock_status == 'in_stock' ? 'Còn hàng' : 'Hết hàng' }}</td>
                                            <td>{{ $variant->description ?? 'N/A' }}</td>
                                            <td>
                                                @if ($variant->image)
                                                    <img src="{{ asset('storage/' . $variant->image) }}" alt="Variant Image" style="max-width: 80px;">
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Sản phẩm này không có biến thể nào.</p>
                        @endif

                        <hr>

                        {{-- Phần hiển thị đánh giá --}}
                        <h5>Đánh giá sản phẩm</h5>
                        @if ($product->reviews->isNotEmpty())
                            <div class="list-group">
                                @foreach ($product->reviews as $review)
                                    <div class="list-group-item mb-2">
                                        <strong>Người đánh giá:</strong> {{ $review->user->name ?? 'Khách (ID: ' . ($review->user_id ?? 'N/A') . ')' }}<br>
                                        <strong>Xếp hạng:</strong> {{ $review->rating }} sao<br>
                                        <strong>Bình luận:</strong> {{ $review->comment }}<br>
                                        <small class="text-muted">Ngày: {{ $review->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                        @endif

                        <hr>

                        {{-- Phần hiển thị bình luận (nếu có riêng) --}}
                        <h5>Bình luận sản phẩm</h5>
                        @if ($product->comments->isNotEmpty())
                            <div class="list-group">
                                @foreach ($product->comments as $comment)
                                    <div class="list-group-item mb-2">
                                        <strong>Người bình luận:</strong> {{ $comment->user->name ?? 'Khách (ID: ' . ($comment->user_id ?? 'N/A') . ')' }}<br>
                                        <strong>Nội dung:</strong> {{ $comment->content }}<br>
                                        <small class="text-muted">Ngày: {{ $comment->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Chưa có bình luận nào cho sản phẩm này.</p>
                        @endif

                        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
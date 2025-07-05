@extends('admin.layouts.AdminLayout')

@section('main')

<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Chi tiết sản phẩm: {{ $product->name }}</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('products.index') }}"><div class="text-tiny">Sản phẩm</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Chi tiết</div></li>
            </ul>
        </div>

        <div class="wg-box">

            <div class="title-box">
                <i class="icon-coffee"></i>
                <div class="body-text">Thông tin sản phẩm</div>
            </div>

            <div class="flex flex-wrap gap20 mb-4">
                <div class="w-full md:w-1/2">
                    <label class="body-title">Tên sản phẩm:</label>
                    <p>{{ $product->name }}</p>
                </div>
                <div class="w-full md:w-1/2">
                    <label class="body-title">Danh mục:</label>
                    <p>{{ $product->category->ten_danh_muc ?? 'Không có danh mục' }}</p>
                </div>
                <div class="w-full md:w-1/2">
                    <label class="body-title">Ngày tạo:</label>
                    <p>{{ $product->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="w-full">
                    <label class="body-title">Mô tả:</label>
                    <p>{!! nl2br(e($product->description)) !!}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap20 mb-4">
                <label class="body-title w-full">Ảnh sản phẩm:</label>
                @if($product->images->isNotEmpty())
                    <div class="flex gap10 flex-wrap">
                        @foreach($product->images as $img)
                            <div class="border rounded shadow-sm overflow-hidden w-24 h-24">
                                <img src="{{ asset('storage/' . $img->image) }}" alt="" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Không có ảnh</p>
                @endif
            </div>

            @if($product->variants->isNotEmpty())
                <div class="mt-4">
                    <label class="body-title">Biến thể:</label>
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Size</th>
                                    <th>Màu sắc</th>
                                    <th>Giá</th>
                                    <th>Giá Sale</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $index => $variant)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $variant->size->name ?? '-' }}</td>
                                        <td>{{ $variant->color->name ?? '-' }}</td>
                                        <td>{{ number_format($variant->price, 0, ',', '.') }} VND</td>
                                        <td>{{ number_format($variant->sale_price, 0, ',', '.') }} VND</td>
                                        <td>{{ $variant->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Reviews --}}
            <div class="mt-6">
                <div class="title-box">
                    <i class="icon-coffee"></i>
                    <div class="body-text">Đánh giá (Reviews)</div>
                </div>
                @if($product->reviews->isNotEmpty())
                    <ul class="flex flex-col gap10 mt-3">
                        @foreach($product->reviews as $review)
                            <li class="border p-3 rounded flex flex-col md:flex-row md:justify-between">
                                <div>
                                    ⭐ <strong>{{ $review->so_sao }}/5</strong> —
                                    <span>{{ $review->noi_dung }}</span>
                                </div>
                                <div class="text-sm text-muted">
                                    Người dùng: {{ $review->user->name ?? 'Ẩn danh' }} | {{ $review->created_at->format('d/m/Y') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mt-2">Chưa có đánh giá</p>
                @endif
            </div>

            {{-- Comments --}}
            <div class="mt-6">
                <div class="title-box">
                    <i class="icon-coffee"></i>
                    <div class="body-text">Bình luận (Comments)</div>
                </div>
                @if($product->comments->isNotEmpty())
                    <ul class="flex flex-col gap10 mt-3">
                        @foreach($product->comments as $comment)
                            <li class="border p-3 rounded flex flex-col md:flex-row md:justify-between">
                                <div>
                                    ✍ <strong>{{ $comment->tac_gia }}</strong> —
                                    <span>{{ $comment->noi_dung }}</span>
                                </div>
                                <div class="text-sm text-muted">
                                    {{ $comment->created_at->format('d/m/Y') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mt-2">Chưa có bình luận</p>
                @endif
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('products.edit', $product) }}" class="tf-button style-1 me-2">
                    <i class="icon-edit-3"></i> Chỉnh sửa
                </a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                    class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="tf-button style-1 red">
                        <i class="icon-trash"></i> Xóa
                    </button>
                </form>
                <a href="{{ route('products.index') }}" class="tf-button style-1">
                    <i class="icon-arrow-left"></i> Quay lại
                </a>
            </div>

        </div>

    </div>
</div>

@endsection

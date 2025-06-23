<<<<<<< HEAD
@extends('admin.layouts.Adminlayout')

@section('main')
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item">Sản phẩm</li>
      <li class="breadcrumb-item active">Chi tiết sản phẩm</li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Chi tiết sản phẩm: {{ $product->name }}</h3>

        <div class="row">
    

          <div class="form-group col-md-6">
            <label class="control-label">Đánh giá:</label>
            <p>{{ $product->rating }}/5</p>
          </div>

          <div class="form-group col-md-6">
            <label class="control-label">Danh mục:</label>
            <p>{{ $product->category->name ?? 'Không có danh mục' }}</p>
          </div>

          <div class="form-group col-md-6">
            <label class="control-label">Ngày tạo:</label>
            <p>{{ $product->created_at->format('d/m/Y H:i') }}</p>
          </div>

          <div class="form-group col-md-12">
            <label class="control-label">Mô tả:</label>
            <div class="border p-3" style="background: #f9f9f9;">
              {!! nl2br(e($product->description)) !!}
            </div>
          </div>

          <div class="form-group col-md-12 mt-4">
            <label class="control-label">Ảnh sản phẩm:</label>
            @if($product->images->isNotEmpty())
              <div class="row g-3 mt-2">
                @foreach($product->images as $img)
                  <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="border rounded shadow-sm overflow-hidden">
                      <img src="{{ asset('storage/' . $img->image) }}" class="img-fluid" style="object-fit: cover; width: 100%; height: 150px;">
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <p class="text-muted mt-2">Không có ảnh</p>
            @endif
          </div>
@if($product->variants->isNotEmpty())
    <div class="form-group col-md-12 mt-4">
        <label class="control-label">Danh sách biến thể:</label>
        <div class="table-responsive mt-2">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Size</th>
                        <th>Màu sắc</th>
                        <th>Giá</th>
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
                            <td>{{ $variant->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

        </div>

        <div class="mt-4">
          <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Chỉnh sửa</a>
          <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        </div>

      </div>
    </div>
  </div>
=======
@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Chi tiết sản phẩm: {{ $product->name }}</h4>
        </div>
        <div class="card-body">

            {{-- Thông tin sản phẩm --}}
            <div class="row mb-4">
                <div class="col-md-6 mb-2"><strong>Tên sản phẩm:</strong> {{ $product->name }}</div>
                <div class="col-md-6 mb-2"><strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'N/A' }}</div>
                <div class="col-md-6 mb-2"><strong>Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}</div>
                <div class="col-md-6 mb-2"><strong>Giá cơ sở:</strong> {{ number_format($product->price, 0, ',', '.') }} VNĐ</div>
                <div class="col-md-12 mb-2"><strong>Mô tả:</strong> {{ $product->description }}</div>
                <div class="col-md-12 mb-3">
                    <strong>Hình ảnh chính:</strong><br>
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                    @else
                        <span class="text-muted">Không có hình ảnh chính</span>
                    @endif
                </div>
            </div>

            <hr>

            {{-- Biến thể sản phẩm --}}
            <h5 class="mt-4 border-bottom pb-2">Biến thể sản phẩm</h5>
            @if ($product->variants->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-dark">
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
                                    <td>
                                        @if ($variant->stock_status === 'in_stock')
                                            <span class="badge bg-success">Còn hàng</span>
                                        @else
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td>{{ $variant->description ?? 'N/A' }}</td>
                                    <td>
                                        @if ($variant->image)
                                            <img src="{{ asset('storage/' . $variant->image) }}" alt="Variant Image" class="img-thumbnail" style="max-width: 80px;">
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Sản phẩm này không có biến thể nào.</p>
            @endif

            <hr>

           <h5 class="mt-4">Đánh giá sản phẩm</h5>
                @if ($product->reviews->isNotEmpty())
                    <div class="list-group">
                        @foreach ($product->reviews as $review)
                            <div class="list-group-item mb-2">
                                <strong>Người đánh giá:</strong> {{ $review->user->name ?? 'Khách (ID: ' . ($review->ma_nguoi_dung ?? 'N/A') . ')' }}<br>
                                <strong>Xếp hạng:</strong> {{ $review->so_sao }} sao<br>
                                <strong>Bình luận:</strong> {{ $review->noi_dung }}<br>
                                <small class="text-muted">Ngày: {{ $review->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                @endif

                <hr>

                <h5 class="mt-4">Bình luận sản phẩm</h5>
                @if ($product->comments->isNotEmpty())
                    <div class="list-group">
                        @foreach ($product->comments as $comment)
                            <div class="list-group-item mb-2">
                                <strong>Người bình luận:</strong> {{ $comment->tac_gia ?? 'Khách' }}<br>
                                <strong>Nội dung:</strong> {{ $comment->noi_dung }}<br>
                                <small class="text-muted">Ngày: {{ $comment->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Chưa có bình luận nào cho sản phẩm này.</p>
                @endif

            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary mt-4">Quay lại danh sách</a>
        </div>
    </div>
</div>
>>>>>>> main
@endsection

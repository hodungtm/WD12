@extends('admin.layouts.Adminlayout')

@section('main')
    <div class="app-title d-flex justify-content-between align-items-center mb-3">
        <ul class="app-breadcrumb breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item active">Chi tiết sản phẩm</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile card shadow-sm rounded-3 border-0">
                <div class="tile-body p-4">
                    <h3 class="tile-title mb-4">Chi tiết sản phẩm: {{ $product->name }}</h3>

                    <div class="row">
                        <!-- Đánh giá -->
                        <div class="form-group col-md-6">
                            <label class="control-label">Đánh giá:</label>
                            <p class="mb-0">{{ $product->rating }}/5</p>
                        </div>

                        <!-- Danh mục -->
                        <div class="form-group col-md-6">
                            <label class="control-label">Danh mục:</label>
                            <p class="mb-0">{{ $product->category->ten_danh_muc ?? 'Không có danh mục' }}</p>
                        </div>

                        <!-- Ngày tạo -->
                        <div class="form-group col-md-6">
                            <label class="control-label">Ngày tạo:</label>
                            <p class="mb-0">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group col-md-12">
                            <label class="control-label">Mô tả:</label>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>

                        <!-- Ảnh sản phẩm -->
                        <div class="form-group col-md-12 mt-4">
                            <label class="control-label">Ảnh sản phẩm:</label>
                            @if($product->images->isNotEmpty())
                                <div class="row g-3 mt-2">
                                    @foreach($product->images as $img)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                            <div class="border rounded shadow-sm overflow-hidden">
                                                <img src="{{ asset('storage/' . $img->image) }}"
                                                     class="img-fluid"
                                                     style="object-fit: cover; width: 100%; height: 150px;">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mt-2">Không có ảnh</p>
                            @endif
                        </div>

                        <!-- Biến thể -->
                        @if($product->variants->isNotEmpty())
                            <div class="form-group col-md-12 mt-4">
                                <label class="control-label">Danh sách biến thể:</label>
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
                    </div>

                    <!-- Nút -->
                    <div class="mt-4 text-end">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa
                        </a>
                          <form action="{{ route('products.destroy', $product->id) }}" method="POST" 
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm mb-1" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

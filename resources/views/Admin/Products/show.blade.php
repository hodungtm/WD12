@extends('Admin.Layouts.AdminLayout')
@section('main')
<div class="container py-4">
    <h2 class="mb-4 text-primary">Chi tiết sản phẩm: <span class="fw-bold">{{ $product->name }}</span></h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Ảnh chính</strong>
                </div>
                <div class="card-body text-center">
                    @if($product->image_product)
                        <img src="{{ Storage::url($product->image_product) }}" class="img-fluid rounded mb-2" style="max-height:220px;">
                    @else
                        <span class="text-muted">Chưa có ảnh</span>
                    @endif
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Ảnh phụ</strong>
                </div>
                <div class="card-body">
                    @if($product->images->count())
                        @foreach($product->images as $img)
                            <img src="{{ Storage::url($img->image_path) }}" class="img-thumbnail me-2 mb-2" style="width:70px; height:70px; object-fit:cover;">
                        @endforeach
                    @else
                        <span class="text-muted">Chưa có ảnh phụ</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <strong>Thông tin sản phẩm</strong>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="160">Tên sản phẩm</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                         <tr>
                            <th width="160">Tên slug</th>
                            <td>{{ $product->slug }}</td>
                        </tr>
                        <tr>
                            <th>Thương hiệu</th>
                            <td>{{ $product->brand }}</td>
                        </tr>
                        <tr>
                            <th>Loại</th>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($product->type) }}</span></td>
                        </tr>
                        <tr>
                            <th>Giá</th>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                                @if($product->sale_price > 0)
                                    <span class="ms-2 text-danger text-decoration-line-through">{{ number_format($product->sale_price, 0, ',', '.') }} VNĐ</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Danh mục</th>
                            <td>{{ optional($product->category)->ten_danh_muc }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                @if($product->status == 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Mô tả</th>
                            <td>{!! nl2br(e($product->description)) !!}</td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td>{{ $product->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Biến thể sản phẩm</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Size</th>
                                <th>Màu</th>
                                <th>Số lượng</th>
                                <th>Giá biến thể</th>
                                <th>Giá khuyến mãi biến thể</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->variants as $v)
                                <tr>
                                    <td>{{ $v->size }}</td>
                                    <td>
                                        <span class="d-inline-block rounded-circle border" style="width:22px; height:22px; background:{{ $v->color }}; border:1px solid #ccc;" title="{{ $v->color }}"></span>
                                        <span class="ms-1">{{ ucfirst($v->color) }}</span>
                                    </td>
                                    <td>{{ $v->quantity }}</td>
                                    <td>{{ number_format($v->variant_price, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ number_format($v->variant_sale_price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Không có biến thể</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('Admin.products.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('Admin.products.edit', $product->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Sửa sản phẩm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
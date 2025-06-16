@extends('Admin.Layouts.AdminLayout')
@section('main')

<div class="container">
    <h2>Chi tiết sản phẩm: {{ $product->name }}</h2>

    <div class="row mt-4">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h4>{{ $product->name }}</h4>
            <p><strong>Mô tả:</strong> {{ $product->description }}</p>
            <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} đ</p>
            <p><strong>Trạng thái:</strong>
                @if($product->status == 'active')
                <span class="badge bg-success">Đang bán</span>
                @else
                <span class="badge bg-secondary">Ngừng bán</span>
                @endif
            </p>
            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary">Chỉnh sửa</a>
            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>

    <hr class="my-5">

    <h4>Các biến thể sản phẩm</h4>
    @if ($product->variants->count() > 0)
    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Thuộc tính</th>
                <th>SKU</th>
                <th>Giá</th>
                <th>Giá khuyến mãi</th>
                <th>Trạng thái</th>
                <th>Hình ảnh</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product->variants as $index => $variant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @foreach ($variant->attributeValues as $val)
                    <span class="badge bg-info">{{ $val->value }}</span>
                    @endforeach
                </td>
                <td>{{ $variant->sku }}</td>
                <td>{{ number_format($variant->price, 0, ',', '.') }} đ</td>
                <td>{{ number_format($variant->sale_price, 0, ',', '.') }} đ</td>
                <td>
                    @if ($variant->stock_status === 'in_stock')
                    <span class="text-success">Còn hàng</span>
                    @else
                    <span class="text-danger">Hết hàng</span>
                    @endif
                </td>
                <td>
                    @if ($variant->image)
                    <img src="{{ asset('storage/' . $variant->image) }}" width="60">
                    @else
                    Không có ảnh
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-muted">Không có biến thể nào.</p>
    @endif
</div>

@endsection
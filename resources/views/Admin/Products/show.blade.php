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
@endsection

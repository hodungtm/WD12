@extends('admin.layouts.Adminlayout')

@section('main')
  <div class="main-content-inner">
    <div class="main-content-wrap">
      <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Chi tiết sản phẩm: {{ $product->name }}</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="#"><div class="text-tiny">Sản phẩm</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Chi tiết sản phẩm</div></li>
        </ul>
      </div>

      <div class="wg-box">
        <fieldset>
          <div class="body-title mb-10">Đánh giá</div>
          <div class="body-text">{{ $product->rating }}/5</div>
        </fieldset>

        <fieldset>
          <div class="body-title mb-10">Danh mục</div>
          <div class="body-text">{{ $product->category->ten_danh_muc ?? 'Không có danh mục' }}</div>
        </fieldset>

        <fieldset>
          <div class="body-title mb-10">Ngày tạo</div>
          <div class="body-text">{{ $product->created_at->format('d/m/Y H:i') }}</div>
        </fieldset>

        <fieldset>
          <div class="body-title mb-10">Mô tả sản phẩm</div>
          <div class="border p-3 bg-light rounded">
            {!! nl2br(e($product->description)) !!}
          </div>
        </fieldset>
      </div>

      <div class="wg-box">
        <div class="body-title mb-10">Ảnh sản phẩm</div>
        @if($product->images->isNotEmpty())
          <div class="flex flex-wrap gap10 mt-2">
            @foreach($product->images as $img)
              <div class="border rounded overflow-hidden">
                <img src="{{ asset('storage/' . $img->image) }}" style="object-fit: cover; width: 150px; height: 150px;" class="rounded">
              </div>
            @endforeach
          </div>
        @else
          <div class="text-muted mt-2">Không có ảnh</div>
        @endif
      </div>

      @if($product->variants->isNotEmpty())
        <div class="wg-box">
          <div class="body-title mb-3">Danh sách biến thể</div>
          <div class="table-responsive">
            <table class="table table-bordered text-center">
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

      <div class="cols gap10 mt-4">
        <a href="{{ route('products.edit', $product) }}" class="tf-button w-full">Chỉnh sửa</a>
        <a href="{{ route('products.index') }}" class="tf-button style-1 w-full">Quay lại danh sách</a>
      </div>
    </div>
  </div>
@endsection

@extends('Admin.Layouts.AdminLayout')

@section('main')
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item">Sản phẩm yêu thích</li>
      <li class="breadcrumb-item active">Chi tiết sản phẩm yêu thích</li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Chi tiết sản phẩm yêu thích #{{ $wishlist->id }}</h3>

        <div class="row">
          <div class="form-group col-md-6 mt-3">
            <label class="control-label">Tên sản phẩm:</label>
            <p>{{ $wishlist->product->name ?? 'N/A' }}</p>
          </div>

          <div class="form-group col-md-6 mt-3">
            <label class="control-label">Giá sản phẩm:</label>
            <p>{{ isset($wishlist->product->price) ? number_format($wishlist->product->price, 0, ',', '.') . ' đ' : 'N/A' }}</p>
          </div>

          <div class="form-group col-md-12 mt-3">
            <label class="control-label">Mô tả sản phẩm:</label>
            <div class="border p-3" style="background: #f9f9f9;">
              {!! isset($wishlist->product->description) ? nl2br(e($wishlist->product->description)) : 'N/A' !!}
            </div>
          </div>

          <div class="form-group col-md-6 mt-3">
            <label class="control-label">Ảnh sản phẩm:</label><br>
            @if (!empty($wishlist->product->image))
              <img src="{{ asset('storage/uploads/products/' . $wishlist->product->image) }}" width="300" alt="Ảnh sản phẩm">
            @else
              <p>Không có ảnh</p>
            @endif
          </div>

          <div class="form-group col-md-6 mt-3">
            <label class="control-label">Ngày thêm yêu thích:</label>
            <p>{{ $wishlist->created_at ? $wishlist->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
          </div>
        </div>

        <div class="mt-4">
          <a href="{{ route('admin.wishlists.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
      </div>
    </div>
  </div>

@endsection

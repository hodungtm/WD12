@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item active"><a href="#"><b>Sửa Đánh giá {{ $review->id }}</b></a></li>
    </ul>
    <div>
      <a href="{{ route('Admin.reviews.index') }}" class="btn btn-outline-primary btn-sm">
        ← Quay lại danh sách đánh giá
      </a>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="tile">
    <div class="tile-body">
      <form action="{{ route('Admin.reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="user_id" class="form-label">ID Người dùng</label>
          <input type="number" name="user_id" id="user_id" value="{{ old('user_id', $review->user_id) }}" class="form-control" placeholder="ID người dùng (có thể để trống)">
        </div>

        <div class="mb-3">
          <label for="product_id" class="form-label">ID Sản phẩm</label>
          <input type="number" name="product_id" id="product_id" value="{{ old('product_id', $review->product_id) }}" class="form-control" placeholder="ID sản phẩm (có thể để trống)">
        </div>

        <div class="mb-3">
          <label for="so_sao" class="form-label">Số sao (1-5)</label>
          <input type="number" name="so_sao" id="so_sao" value="{{ old('so_sao', $review->so_sao) }}" class="form-control" min="1" max="5" required>
        </div>

        <div class="mb-3">
          <label for="noi_dung" class="form-label">Nội dung đánh giá</label>
          <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5" required>{{ old('noi_dung', $review->noi_dung) }}</textarea>
        </div>

        <div class="form-check mb-3">
          <input type="hidden" name="trang_thai" value="0">
          <input type="checkbox" name="trang_thai" id="trang_thai" value="1" class="form-check-input" {{ old('trang_thai', $review->trang_thai) ? 'checked' : '' }}>
          <label for="trang_thai" class="form-check-label">Hiển thị đánh giá</label>
        </div>

        <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
        <a href="{{ route('Admin.reviews.index') }}" class="btn btn-secondary">Hủy</a>
      </form>
    </div>
  </div>
</main>



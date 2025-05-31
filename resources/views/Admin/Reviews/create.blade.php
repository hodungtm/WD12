@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item active"><a href="#"><b>Thêm mới Đánh giá</b></a></li>
    </ul>
    <div>
      <a href="{{ route('Admin.reviews.index') }}" class="btn btn-outline-primary btn-sm">
        ← Quay lại danh sách đánh giá
      </a>
    </div>
  </div>

  <div class="tile">
    <div class="tile-body">
      <form action="{{ route('Admin.reviews.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="user_id" class="form-label">ID Người dùng</label>
          <input type="number" name="user_id" id="user_id" value="{{ old('user_id') }}" class="form-control"
            placeholder="ID người dùng (có thể để trống)">
          @error('user_id')
            <div style="color: red;">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="product_id" class="form-label">ID Sản phẩm</label>
          <input type="number" name="product_id" id="product_id" value="{{ old('product_id') }}" class="form-control"
            placeholder="ID sản phẩm (có thể để trống)">
          @error('product_id')
            <div style="color: red;">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="so_sao" class="form-label">Số sao (1-5)</label>
          <input type="number" name="so_sao" id="so_sao" value="{{ old('so_sao') }}" class="form-control" min="1"
            max="5" >
          @error('so_sao')
            <div style="color: red;">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="noi_dung" class="form-label">Nội dung đánh giá</label>
          <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5"
            >{{ old('noi_dung') }}</textarea>
          @error('noi_dung')
            <div style="color: red;">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-check mb-3">
          <input type="hidden" name="trang_thai" value="0">
          <input type="checkbox" name="trang_thai" value="1" {{ old('trang_thai') ? 'checked' : '' }}>
          <label for="trang_thai" class="form-check-label">Hiển thị đánh giá</label>
          @error('trang_thai')
            <div style="color: red;">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-success me-2">Thêm mới</button>
        <a href="{{ route('Admin.reviews.index') }}" class="btn btn-secondary">Hủy</a>
      </form>
    </div>
  </div>
</main>



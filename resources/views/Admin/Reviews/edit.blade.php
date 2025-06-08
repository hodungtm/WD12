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

  <div class="tile">
    <div class="tile-body">
      <form action="{{ route('Admin.reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="so_sao" class="form-label">Số sao (1-5)</label>
          <input type="number" name="so_sao" id="so_sao" value="{{ old('so_sao', $review->so_sao) }}" class="form-control" min="1" max="5">
          @error('so_sao')
            <div style="color: red;">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="noi_dung" class="form-label">Nội dung đánh giá</label>
          <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5">{{ old('noi_dung', $review->noi_dung) }}</textarea>
          @error('noi_dung')
            <div style="color: red;">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="trang_thai" class="form-label">Trạng thái hiển thị</label>
          <select name="trang_thai" id="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror">
            <option value="0" {{ old('trang_thai', $review->trang_thai) == 0 ? 'selected' : '' }}>Ẩn</option>
            <option value="1" {{ old('trang_thai', $review->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
          </select>
          @error('trang_thai')
            <div style="color: red;">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
        <a href="{{ route('Admin.reviews.index') }}" class="btn btn-secondary">Hủy</a>
      </form>
    </div>
  </div>
</main>


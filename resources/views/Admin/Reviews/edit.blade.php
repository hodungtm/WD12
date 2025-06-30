@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề + breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Sửa đánh giá #{{ $review->id }}</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('Admin.reviews.index') }}"><div class="text-tiny">Đánh giá</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Sửa đánh giá</div></li>
      </ul>
    </div>

    <!-- Form chỉnh sửa -->
    <div class="wg-box">
      <form action="{{ route('Admin.reviews.update', $review->id) }}" method="POST" class="grid gap-4">
        @csrf
        @method('PUT')

        <fieldset class="mb-3">
          <div class="body-title mb-10">Số sao (1-5)</div>
          <input type="number" name="so_sao" class="form-control" min="1" max="5"
            value="{{ old('so_sao', $review->so_sao) }}">
          @error('so_sao')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </fieldset>

        <fieldset class="mb-3">
          <div class="body-title mb-10">Nội dung đánh giá</div>
          <textarea name="noi_dung" class="form-control" rows="4">{{ old('noi_dung', $review->noi_dung) }}</textarea>
          @error('noi_dung')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </fieldset>

        <fieldset class="mb-3">
          <div class="body-title mb-10">Trạng thái hiển thị</div>
          <select name="trang_thai" class="form-select">
            <option value="0" {{ old('trang_thai', $review->trang_thai) == 0 ? 'selected' : '' }}>Ẩn</option>
            <option value="1" {{ old('trang_thai', $review->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
          </select>
          @error('trang_thai')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </fieldset>

        <div class="cols gap10 mt-4">
          <button type="submit" class="tf-button w-full">Cập nhật</button>
          <a href="{{ route('Admin.reviews.index') }}" class="tf-button style-1 w-full">Hủy</a>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection

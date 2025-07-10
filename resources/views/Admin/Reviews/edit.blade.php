@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner" style="padding-top: 10px; margin-top: 0;">
  <div class="main-content-wrap" style="padding-top: 0; margin-top: 0;">
    <div class="flex items-center flex-wrap justify-between gap20 mb-30">
      <h3>Sửa đánh giá #{{ $review->id }}</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Bảng điều khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('Admin.reviews.index') }}"><div class="text-tiny">Đánh giá</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Sửa đánh giá</div></li>
      </ul>
    </div>
    <div class="wg-box">
      <form action="{{ route('Admin.reviews.update', $review->id) }}" method="POST" class="form-style-1" style="max-width:520px; width:100%; margin-left:0; margin-right:auto;">
        @csrf
        @method('PUT')
        <fieldset class="mb-4 flex items-center gap20" style="align-items:center;">
          <div class="body-title" style="min-width:160px; max-width:200px; text-align:left;">Số sao</div>
          <div style="flex:1; min-width:0; max-width:120px;">
            <input type="number" class="form-control" value="{{ $review->so_sao }}" style="width:100%;" readonly disabled>
          </div>
        </fieldset>
        <fieldset class="mb-4 flex items-center gap20" style="align-items:center;">
          <div class="body-title" style="min-width:160px; max-width:200px; text-align:left;">Nội dung đánh giá</div>
          <div style="flex:1; min-width:0;">
            <textarea class="form-control" rows="4" style="width:100%; max-width:340px; min-width:180px; resize:vertical; text-align:left;" readonly disabled>{{ $review->noi_dung }}</textarea>
          </div>
        </fieldset>
        <fieldset class="mb-4 flex items-center gap20" style="align-items:center;">
          <div class="body-title" style="min-width:160px; max-width:200px; text-align:left;">Trạng thái hiển thị</div>
          <div style="flex:1; min-width:0; max-width:220px;">
            <select name="trang_thai" class="form-select" style="width:100%; text-align:left;">
              <option value="1" {{ old('trang_thai', $review->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
              <option value="0" {{ old('trang_thai', $review->trang_thai) == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
            @error('trang_thai')
              <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>
        </fieldset>
        <div class="bot mt-4 flex gap10 justify-end">
          <button type="submit" class="tf-button w208">Cập nhật</button>
          <a href="{{ route('Admin.reviews.index') }}" class="tf-button style-1 w208">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chỉnh sửa đánh giá</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('Admin.reviews.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('Admin.reviews.index') }}">
                            <div class="text-tiny">Đánh giá</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chỉnh sửa đánh giá</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box mb-30">
                <div class="title-box mb-20">
                    <i class="icon-edit-3"></i>
                    <div class="body-text">Cập nhật thông tin đánh giá</div>
                </div>
                <form action="{{ route('Admin.reviews.update', $review->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <div class="body-title mb-10">Người dùng</div>
                        <input type="text" class="form-control" value="{{ $review->user->name ?? 'N/A' }}" readonly style="background-color: #f8f9fa; color: #666;">
                    </div>

                    <div class="form-group mb-4">
                        <div class="body-title mb-10">Sản phẩm</div>
                        <input type="text" class="form-control" value="{{ $review->product->name ?? 'N/A' }}" readonly style="background-color: #f8f9fa; color: #666;">
                    </div>

                    <div class="form-group mb-4">
                        <div class="body-title mb-10">Số sao</div>
                        <input type="text" class="form-control" value="{{ $review->so_sao }} ⭐" readonly style="background-color: #f8f9fa; color: #666;">
                    </div>

                    <div class="form-group mb-4">
                        <div class="body-title mb-10">Nội dung đánh giá</div>
                        <textarea class="form-control" rows="4" readonly style="background-color: #f8f9fa; color: #666; min-height: 100px; resize: none;">{{ $review->noi_dung }}</textarea>
                    </div>

                    <div class="form-group mb-4">
                        <div class="body-title mb-10">Trạng thái hiển thị</div>
                        <select name="trang_thai" class="form-control" style="width: 100%;">
                            <option value="1" {{ old('trang_thai', $review->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ old('trang_thai', $review->trang_thai) == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        @error('trang_thai')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="flex justify-start gap-3 mt-4">
                        <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                            <i class="icon-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('Admin.reviews.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                            <i class="icon-x"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

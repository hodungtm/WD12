@extends('Admin.Layouts.AdminLayout')
@section('main')

        <h1>Thêm mới Đánh giá</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('Admin.reviews.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="user_id" class="form-label">ID Người dùng</label>
                <input type="number" name="user_id" id="user_id" value="{{ old('user_id') }}" class="form-control"
                    placeholder="ID người dùng (có thể để trống)">
            </div>

            <div class="mb-3">
                <label for="product_id" class="form-label">ID Sản phẩm</label>
                <input type="number" name="product_id" id="product_id" value="{{ old('product_id') }}" class="form-control"
                    placeholder="ID sản phẩm (có thể để trống)">
            </div>

            <div class="mb-3">
                <label for="so_sao" class="form-label">Số sao (1-5)</label>
                <input type="number" name="so_sao" id="so_sao" value="{{ old('so_sao') }}" class="form-control" min="1"
                    max="5" required>
            </div>

            <div class="mb-3">
                <label for="noi_dung" class="form-label">Nội dung đánh giá</label>
                <textarea name="noi_dung" id="noi_dung" class="form-control" rows="5" required>{{ old('noi_dung') }}</textarea>
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="trang_thai" value="0">
                <input type="checkbox" name="trang_thai" id="trang_thai" value="1" class="form-check-input" {{ old('trang_thai') ? 'checked' : '' }}>
                <label for="trang_thai" class="form-check-label">Hiển thị đánh giá</label>
            </div>

            <button type="submit" class="btn btn-success">Thêm mới</button>
            <a href="{{ route('Admin.reviews.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
@endsection
   
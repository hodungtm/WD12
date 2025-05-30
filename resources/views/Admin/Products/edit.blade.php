@extends('Admin.Layouts.AdminLayout')
@section('main')
<div class="app-title">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href=""><b>Sửa sản phẩm</b></a></li>
    </ul>
    <div id="clock"></div>
</div>


    <h2>Sửa sản phẩm</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi:</strong>
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

   <form action="{{ route("Admin.products.update", $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Cột bên trái: Nhập thông tin sản phẩm -->
        <div class="col-md-6">
            <div class="mb-3">
                <label>Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
            </div>

            <div class="mb-3">
                <label>Danh mục</label>
                <select name="category_id" class="form-control">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected($cat->id == $product->category_id)>{{ $cat->ten_danh_muc }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Giá</label>
                <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
            </div>

            <div class="mb-3">
                <label>Loại</label>
                <select name="type" class="form-control" required>
                    <option value="clothes">Quần áo</option>
                    <option value="shoes">Giày</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Thương hiệu</label>
                <input type="text" name="brand" value="{{ $product->brand }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="active">Hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                </select>
            </div>

            {{-- <div class="mb-3">
                <label>Màu sắc</label>
                <input type="text" name="colors[]" class="form-control mb-2" placeholder="VD: Đỏ">
                <input type="text" name="colors[]" class="form-control mb-2" placeholder="VD: Xanh">
            </div>

            <div class="mb-3">
                <label>Size</label>
                <input type="text" name="sizes[]" class="form-control mb-2" placeholder="VD: S">
                <input type="text" name="sizes[]" class="form-control mb-2" placeholder="VD: M">
            </div> --}}

            <div class="mb-3">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
            </div>
        </div>

        <!-- Cột bên phải: Ảnh chính và ảnh phụ -->
        <div class="col-md-6">
            <div class="mb-3">
                <label>Ảnh chính cũ: </label> <br>
               <img src="{{Storage::URL($product->image_product)}}" alt="" width="70%" height="70%">
            </div>

            <div class="mb-3">
                <label>Ảnh chính mới: </label>
                <input type="file" name="image_product" class="form-control" required>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route("Admin.products.index") }}" class="btn btn-secondary">Hủy</a>
   </form>
@endsection

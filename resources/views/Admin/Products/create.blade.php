@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="container">
    <h2>Thêm sản phẩm mới</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('Admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <!-- Tên -->
                <div class="mb-3">
                    <label>Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <!-- Giá -->
                <div class="mb-3">
                    <label>Giá</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price') }}">
                    @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <!-- Giá khuyến mãi -->
                <div class="mb-3">
                    <label>Giá khuyến mãi</label>
                    <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
                    @error('sale_price') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <!-- Loại -->
                <div class="mb-3">
                    <label>Loại sản phẩm</label>
                    <select name="type" class="form-control">
                        <option value="">-- Chọn loại --</option>
                        <option value="shoes" {{ old('type') == 'shoes' ? 'selected' : '' }}>Giày thể thao</option>
                        <option value="shirt" {{ old('type') == 'shirt' ? 'selected' : '' }}>Áo</option>
                        <option value="pants" {{ old('type') == 'pants' ? 'selected' : '' }}>Quần</option>
                    </select>
                    @error('type') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <!-- Thương hiệu -->
                <div class="mb-3">
                    <label>Thương hiệu</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                    @error('brand') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <!-- Slug -->
                <div class="mb-3">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                    @error('slug') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <!-- Danh mục -->
                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->ten_danh_muc }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <!-- Trạng thái -->
                <div class="mb-3">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <!-- Ảnh chính -->
                <div class="mb-3">
                    <label>Ảnh chính</label>
                    <input type="file" name="image_product" class="form-control">
                    @error('image_product') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <!-- Ảnh phụ -->
                <div class="mb-3">
                    <label>Ảnh phụ</label>
                    <input type="file" name="image_path[]" class="form-control" multiple>
                    @foreach($errors->get('image_path.*') as $messages)
                        @foreach($messages as $msg) <div class="text-danger">{{ $msg }}</div> @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Biến thể -->
        <hr>
        <h4>Biến thể sản phẩm</h4>
        <div id="variant-container">
            <div class="variant-group row border p-3 mb-3 position-relative">
                <button type="button" class="btn btn-danger btn-sm btn-remove-variant" style="position:absolute;top:5px;right:5px;">&times;</button>

                <!-- Size -->
                <div class="col-md-4">
                    <label>Size</label>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        @foreach($sizes as $size)
                            <button type="button" class="btn btn-outline-secondary size-btn" data-value="{{ $size->name }}">{{ $size->name }}</button>
                        @endforeach
                    </div>
                </div>

                <!-- Màu -->
                <div class="col-md-4">
                    <label>Màu</label>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        @foreach($colors as $color)
                            <div class="color-circle border" data-value="{{ $color->code }}"
                                 style="width:30px; height:30px; border-radius:50%; background:{{ $color->code }}; cursor:pointer;"></div>
                        @endforeach
                    </div>
                </div>

                <!-- Số lượng -->
                <div class="col-md-4">
                    <label>Số lượng</label>
                    <input type="number" name="variant_quantity[]" class="form-control mt-1" min="1">
                </div>

                <!-- Giá và khuyến mãi -->
                <div class="col-md-6 mt-3">
                    <label>Giá biến thể</label>
                    <input type="number" name="variant_price[]" class="form-control">
                </div>
                <div class="col-md-6 mt-3">
                    <label>Giá khuyến mãi biến thể</label>
                    <input type="number" name="variant_sale_price[]" class="form-control">
                </div>

                <!-- Hidden -->
                <input type="hidden" name="selected_size[]" class="selected_size">
                <input type="hidden" name="selected_color[]" class="selected_color">
            </div>
        </div>

        <button type="button" class="btn btn-primary mb-3" id="add-variant-btn">+ Thêm biến thể</button>

        <!-- Mô tả -->
        <div class="mb-3">
            <label>Mô tả sản phẩm</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('Admin.products.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Slug tự động
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.querySelector('input[name="slug"]');
    nameInput.addEventListener('input', function () {
        let slug = nameInput.value.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugInput.value = slug;
    });

    // Chọn size
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('size-btn')) {
            const group = e.target.closest('.variant-group');
            group.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
            group.querySelector('.selected_size').value = e.target.dataset.value;
        }
    });

    // Chọn màu
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('color-circle')) {
            const group = e.target.closest('.variant-group');
            group.querySelectorAll('.color-circle').forEach(c => c.classList.remove('border-primary'));
            e.target.classList.add('border-primary');
            group.querySelector('.selected_color').value = e.target.dataset.value;
        }
    });

    // Thêm biến thể
    document.getElementById('add-variant-btn').addEventListener('click', function () {
        const container = document.getElementById('variant-container');
        const group = container.querySelector('.variant-group');
        const clone = group.cloneNode(true);

        clone.querySelectorAll('input').forEach(input => input.value = '');
        clone.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
        clone.querySelectorAll('.color-circle').forEach(c => c.classList.remove('border-primary'));

        container.appendChild(clone);
    });

    // Xóa biến thể
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-variant')) {
            const container = document.getElementById('variant-container');
            const all = container.querySelectorAll('.variant-group');
            if (all.length > 1) {
                e.target.closest('.variant-group').remove();
            }
        }
    });
});
</script>
@endsection

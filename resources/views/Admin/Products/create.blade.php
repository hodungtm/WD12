@extends('admin.layouts.AdminLayout')

@section('main')

<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Thêm sản phẩm mới</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="#"><div class="text-tiny">Ecommerce</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Thêm sản phẩm</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="title-box">
                <i class="icon-coffee"></i>
                <div class="body-text">Điền thông tin chi tiết sản phẩm</div>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="form-add-product">
                @csrf

                <div class="flex flex-wrap gap20">
                    <!-- Tên sản phẩm -->
                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Danh mục -->
                    <div class="form-group w-full md:w-1/2">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" class="form-control">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->ten_danh_muc }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Mô tả -->
                    <div class="form-group w-full">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Hình ảnh -->
                    <div class="form-group w-full">
                        <label class="form-label">Hình ảnh sản phẩm</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        @error('images[]')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Size -->
                    <div class="form-group w-full">
                        <label class="form-label">Chọn Size</label>
                        <div class="flex flex-wrap gap10">
                            <label><input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)"> Chọn tất cả</label>
                            @foreach ($sizes as $size)
                                <label><input type="checkbox" name="sizes[]" value="{{ $size->id }}"> {{ $size->name }}</label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Màu -->
                    <div class="form-group w-full">
                        <label class="form-label">Chọn Màu</label>
                        <div class="flex flex-wrap gap10">
                            <label><input type="checkbox" id="select_all_colors" onchange="toggleAll('colors[]', this)"> Chọn tất cả</label>
                            @foreach ($colors as $color)
                                <label><input type="checkbox" name="colors[]" value="{{ $color->id }}"> {{ $color->name }}</label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Giá chung / Sale / Số lượng -->
                    <div class="form-group w-full md:w-1/3">
                        <label class="form-label">Giá chung</label>
                        <input type="number" id="common_price" class="form-control" placeholder="VNĐ">
                    </div>

                    <div class="form-group w-full md:w-1/3">
                        <label class="form-label">Giá sale</label>
                        <input type="number" id="common_sale_price" class="form-control" placeholder="VNĐ">
                    </div>

                    <div class="form-group w-full md:w-1/3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" id="common_quantity" class="form-control" placeholder="Số lượng">
                    </div>

                    <!-- Tạo biến thể -->
                    <div class="form-group w-full">
                        <button type="button" class="tf-button style-1" onclick="generateVariants()">
                            <i class="icon-plus"></i> Tạo biến thể
                        </button>
                        <div id="variant_error" class="text-danger mt-2 fw-bold"></div>
                    </div>

                    <!-- Bảng biến thể -->
                    <div class="form-group w-full">
                        <div class="wg-table table-product-list">
                            <ul class="table-title flex gap20 mb-14">
                                <li><input type="checkbox" id="select_all_variants" onchange="toggleAllVariants(this)"></li>
                                <li><div class="body-title">Size</div></li>
                                <li><div class="body-title">Màu</div></li>
                                <li><div class="body-title">Giá</div></li>
                                <li><div class="body-title">Giá Sale</div></li>
                                <li><div class="body-title">Số lượng</div></li>
                                <li><div class="body-title">Hành động</div></li>
                            </ul>
                            <ul id="variant_table" class="flex flex-column"></ul>
                        </div>
                    </div>

                    <!-- Nút lưu -->
                    <div class="form-group w-full text-end">
                        <button type="submit" class="tf-button style-1"><i class="icon-save"></i> Lưu sản phẩm</button>
                        <a href="{{ route('products.index') }}" class="tf-button style-2"><i class="icon-x"></i> Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleAll(name, source) {
        document.querySelectorAll(`input[name="${name}"]`).forEach(cb => cb.checked = source.checked);
    }

    function toggleAllVariants(source) {
        document.querySelectorAll('.variant_checkbox').forEach(cb => cb.checked = source.checked);
    }

    function deleteSelectedVariants() {
        document.querySelectorAll('.variant_checkbox:checked').forEach(cb => cb.closest('li').remove());
    }

    function generateVariants() {
        const errorBox = document.getElementById('variant_error');
        errorBox.innerText = '';

        const sizes = document.querySelectorAll('input[name="sizes[]"]:checked');
        const colors = document.querySelectorAll('input[name="colors[]"]:checked');
        const price = document.getElementById('common_price').value;
        const sale_price = document.getElementById('common_sale_price').value;
        const quantity = document.getElementById('common_quantity').value;
        const tableBody = document.getElementById('variant_table');

        if (sizes.length === 0) {
            errorBox.innerText = 'Vui lòng chọn ít nhất 1 size.';
            return;
        }

        if (colors.length === 0) {
            errorBox.innerText = 'Vui lòng chọn ít nhất 1 màu.';
            return;
        }

        if (parseFloat(sale_price) >= parseFloat(price)) {
            errorBox.innerText = 'Giá sale phải nhỏ hơn giá gốc.';
            return;
        }

        sizes.forEach(size => {
            colors.forEach(color => {
                if (!variantExists(size.value, color.value)) {
                    const li = document.createElement('li');
                    li.classList.add('product-item', 'gap14');
                    li.innerHTML = `
                        <div><input type="checkbox" class="variant_checkbox"></div>
                        <div>${size.nextSibling.nodeValue.trim()}<input type="hidden" name="variant_sizes[]" value="${size.value}"></div>
                        <div>${color.nextSibling.nodeValue.trim()}<input type="hidden" name="variant_colors[]" value="${color.value}"></div>
                        <div><input type="number" name="variant_prices[]" class="form-control" value="${price}"></div>
                        <div><input type="number" name="variant_sale_prices[]" class="form-control" value="${sale_price}"></div>
                        <div><input type="number" name="variant_quantities[]" class="form-control" value="${quantity}"></div>
                        <div><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('li').remove()"><i class="icon-trash"></i> Xóa</button></div>
                    `;
                    tableBody.appendChild(li);
                }
            });
        });
    }

    function variantExists(sizeId, colorId) {
        return Array.from(document.querySelectorAll('#variant_table li')).some(li => {
            return li.querySelector('input[name="variant_sizes[]"]').value == sizeId &&
                   li.querySelector('input[name="variant_colors[]"]').value == colorId;
        });
    }
</script>

@endsection

@extends('admin.layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Chỉnh sửa sản phẩm</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('products.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('products.index') }}">
                        <div class="text-tiny">Ecommerce</div>
                    </a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Chỉnh sửa sản phẩm</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">

            @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="flex gap20 flex-wrap">
                    <div class="flex-grow">
                        <label class="body-title">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
                    </div>
                    <div>
                        <label class="body-title">Danh mục</label>
                        <select name="category_id" class="form-control">
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id)==$category->id ? 'selected' : '' }}>
                                {{ $category->ten_danh_muc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="body-title">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $product->status==1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ $product->status==0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="body-title">Mô tả</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label class="body-title">Thêm ảnh mới</label>
                    <input type="file" name="images[]" class="form-control" multiple>
                </div>

                <div class="flex gap20 flex-wrap">
                    <div>
                        <label class="body-title">Giá mặc định</label>
                        <input type="number" id="default_price" class="form-control">
                    </div>
                    <div>
                        <label class="body-title">Giá sale mặc định</label>
                        <input type="number" id="default_saleprice" class="form-control">
                    </div>
                    <div>
                        <label class="body-title">Số lượng mặc định</label>
                        <input type="number" id="default_quantity" class="form-control">
                    </div>
                </div>

                <div>
                    <label class="body-title">Chọn Size</label>
                    <div class="flex flex-wrap gap10">
                        <label>
                            <input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)"> Chọn tất cả
                        </label>
                        @foreach ($sizes as $size)
                        <label>
                            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" {{ in_array($size->id, $selectedSizes ?? []) ? 'checked' : '' }}>
                            {{ $size->name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="body-title">Chọn Màu</label>
                    <div class="flex flex-wrap gap10">
                        <label>
                            <input type="checkbox" id="select_all_colors" onchange="toggleAll('colors[]', this)"> Chọn tất cả
                        </label>
                        @foreach ($colors as $color)
                        <label>
                            <input type="checkbox" name="colors[]" value="{{ $color->id }}" {{ in_array($color->id, $selectedColors ?? []) ? 'checked' : '' }}>
                            {{ $color->name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <button type="button" class="tf-button style-1" onclick="generateVariants()">
                        <i class="icon-plus"></i> Tạo biến thể
                    </button>
                </div>

                <div>
                    <h5 class="body-title">Danh sách biến thể</h5>
                    <button type="button" class="tf-button style-2 small mb-2" onclick="deleteSelectedVariants()">
                        <i class="icon-trash"></i> Xoá các biến thể đã chọn
                    </button>
                    <div class="wg-table">
                        <table class="table-product-list">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all_variants" onchange="toggleAllVariants(this)"></th>
                                    <th>Size</th>
                                    <th>Màu</th>
                                    <th>Giá</th>
                                    <th>Giá Sale</th>
                                    <th>Số lượng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->variants as $variant)
                                <tr>
                                    <td><input type="checkbox" class="variant_checkbox"></td>
                                    <td><input type="hidden" name="variant_sizes[]" value="{{ $variant->size_id }}">{{ $variant->size->name ?? '' }}</td>
                                    <td><input type="hidden" name="variant_colors[]" value="{{ $variant->color_id }}">{{ $variant->color->name ?? '' }}</td>
                                    <td><input type="number" name="variant_prices[]" class="form-control" value="{{ $variant->price }}"></td>
                                    <td><input type="number" name="variant_sale_prices[]" class="form-control" value="{{ $variant->sale_price }}"></td>
                                    <td><input type="number" name="variant_quantities[]" class="form-control" value="{{ $variant->quantity }}"></td>
                                    <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">Xoá</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end gap10 mt-3">
                    <button type="submit" class="tf-button style-1"><i class="icon-save"></i> Lưu sản phẩm</button>
                    <a href="{{ route('products.index') }}" class="tf-button style-2"><i class="icon-x"></i> Hủy bỏ</a>
                </div>
            </form>
        </div>

        @if ($product->images->isNotEmpty())
        <div class="wg-box mt-3">
            <h5 class="body-title">Ảnh hiện tại</h5>
            <div class="flex flex-wrap gap10">
                @foreach ($product->images as $img)
                <div class="border rounded p-1 text-center">
                    <img src="{{ asset('storage/' . $img->image) }}" style="width: 100px; height: 100px; object-fit: cover;" class="mb-1">
                    <form action="{{ route('products.image.destroy', $img->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa ảnh này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="tf-button style-2 small">Xoá</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<script>
function toggleAll(name, sourceCheckbox) {
    document.querySelectorAll(`input[name="${name}"]`).forEach(cb => cb.checked = sourceCheckbox.checked);
}

function generateVariants() {
    let selectedSizes = document.querySelectorAll('input[name="sizes[]"]:checked');
    let selectedColors = document.querySelectorAll('input[name="colors[]"]:checked');
    let price = document.getElementById('default_price').value;
    let saleprice = document.getElementById('default_saleprice').value;
    let quantity = document.getElementById('default_quantity').value;
    let tableBody = document.querySelector('#variant_table tbody');

    selectedSizes.forEach(size => {
        selectedColors.forEach(color => {
            if (!variantExists(size.value, color.value)) {
                let row = `
                <tr>
                    <td><input type="checkbox" class="variant_checkbox"></td>
                    <td><input type="hidden" name="variant_sizes[]" value="${size.value}">${size.nextSibling.nodeValue.trim()}</td>
                    <td><input type="hidden" name="variant_colors[]" value="${color.value}">${color.nextSibling.nodeValue.trim()}</td>
                    <td><input type="number" name="variant_prices[]" class="form-control" value="${price}"></td>
                    <td><input type="number" name="variant_sale_prices[]" class="form-control" value="${saleprice}"></td>
                    <td><input type="number" name="variant_quantities[]" class="form-control" value="${quantity}"></td>
                    <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">Xoá</button></td>
                </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            }
        });
    });
}

function variantExists(sizeId, colorId) {
    let rows = document.querySelectorAll('#variant_table tbody tr');
    for (let row of rows) {
        let sizeVal = row.querySelector('input[name="variant_sizes[]"]').value;
        let colorVal = row.querySelector('input[name="variant_colors[]"]').value;
        if (sizeVal == sizeId && colorVal == colorId) return true;
    }
    return false;
}

function toggleAllVariants(sourceCheckbox) {
    document.querySelectorAll('.variant_checkbox').forEach(cb => cb.checked = sourceCheckbox.checked);
}

function deleteSelectedVariants() {
    document.querySelectorAll('.variant_checkbox:checked').forEach(cb => cb.closest('tr').remove());
}
</script>
@endsection

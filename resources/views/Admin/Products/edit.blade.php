@extends('admin.layouts.AdminLayout')

@section('main')

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
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
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="wg-box mb-30">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên sản phẩm</div>
                        <input type="text" name="name" class="form-control mb-10"
                            value="{{ old('name', $product->name) }}">
                    </fieldset>
                    <fieldset class="category">
                        <div class="body-title mb-10">Danh mục</div>
                        <select name="category_id" class="form-control">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->ten_danh_muc }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="status">
                        <div class="body-title mb-10">Trạng thái</div>
                        <select name="status" class="form-control">
                            <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </fieldset>
                </div>

                <div class="wg-box mb-30">
                    <fieldset class="description">
                        <div class="body-title mb-10">Mô tả</div>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                    </fieldset>
                    <fieldset class="upload">
                        <div class="body-title mb-10">Thêm ảnh mới <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item up-load">
                                <label class="uploadfile h250" for="images">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="body-text">Kéo thả hoặc chọn <span class="tf-color">tải ảnh lên</span></span>
                                    <input type="file" id="images" name="images[]" accept="image/*" multiple onchange="previewImages(event)">
                                </label>
                                <div style="margin-top: 10px; text-align: center;">
                                    <div id="preview-images" style="display:flex; gap:10px; flex-wrap:wrap;"></div>
                                </div>
                            </div>
                        </div>
                        @error('images.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>

                <div class="wg-box mb-30">
                    <fieldset>
                        <div class="body-title mb-10">Chọn Size</div>
                        <div class="flex flex-wrap gap10">
                            <label><input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)"> Chọn
                                tất cả</label>
                            @foreach ($sizes as $size)
                                <label><input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                                        {{ in_array($size->id, $selectedSizes ?? []) ? 'checked' : '' }}>
                                    {{ $size->name }}</label>
                            @endforeach
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="body-title mb-10">Chọn Màu</div>
                        <div class="flex flex-wrap gap10">
                            <label><input type="checkbox" id="select_all_colors" onchange="toggleAll('colors[]', this)">
                                Chọn tất cả</label>
                            @foreach ($colors as $color)
                                <label><input type="checkbox" name="colors[]" value="{{ $color->id }}"
                                        {{ in_array($color->id, $selectedColors ?? []) ? 'checked' : '' }}>
                                    {{ $color->name }}</label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div id="bulk-inputs" class="mt-3" style="display:none">
                        <div class="cols-lg gap22">
                            <fieldset>
                                <div class="body-title mb-10">Giá mặc định</div>
                                <input type="number" id="default_price" class="form-control">
                            </fieldset>
                            <fieldset>
                                <div class="body-title mb-10">Giá sale mặc định</div>
                                <input type="number" id="default_saleprice" class="form-control">
                            </fieldset>
                            <fieldset>
                                <div class="body-title mb-10">Số lượng mặc định</div>
                                <input type="number" id="default_quantity" class="form-control">
                            </fieldset>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="tf-button w380" onclick="applyDefaultToVariants()"><i
                                    class="icon-check"></i> Áp dụng cho tất cả biến thể</button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="tf-button w380" onclick="showBulkInputsAndGenerateVariants()"><i
                                class="icon-plus"></i> Tạo biến thể</button>
                    </div>
                </div>

                <div class="wg-box mb-30">
                    <div class="body-title mb-10">Danh sách biến thể</div>
                    <button type="button" class="tf-button style-3 btn-sm w-auto px-3 py-2 mb-2"
                        onclick="deleteSelectedVariants()">
                        <i class="icon-trash"></i> Xoá các biến thể đã chọn
                    </button>
                    <div class="wg-table">
                        <table class="table-product-list" id="variant_table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all_variants" onchange="toggleAllVariants(this)">
                                    </th>
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
                                        <td>
                                            <input type="hidden" name="variant_sizes[]"
                                                value="{{ $variant->size_id }}">
                                            {{ $variant->size->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="variant_colors[]"
                                                value="{{ $variant->color_id }}">
                                            {{ $variant->color->name ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <input type="number" name="variant_prices[]" class="form-control"
                                                value="{{ $variant->price }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="variant_sale_prices[]" class="form-control"
                                                value="{{ $variant->sale_price }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="variant_quantities[]" class="form-control"
                                                value="{{ $variant->quantity }}" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="this.closest('tr').remove()">Xoá</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-start gap-3 mt-4">
                    <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                        <i class="icon-save"></i> Lưu sản phẩm
                    </button>
                    <a href="{{ route('products.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                        <i class="icon-x"></i> Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
    @if ($product->images->isNotEmpty())
        <div class="wg-box mt-3">
            <h5 class="body-title">Ảnh hiện tại</h5>
            <div class="flex flex-wrap gap10">
                @foreach ($product->images as $img)
                    <div class="border rounded p-1 text-center">
                        <img src="{{ asset('storage/' . $img->image) }}"
                            style="width: 100px; height: 100px; object-fit: cover;" class="mb-1">
                        <form action="{{ route('products.image.destroy', $img->id) }}" method="POST"
                            onsubmit="return confirm('Bạn chắc chắn muốn xóa ảnh này?')">
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
        function toggleAll(name, source) {
            document.querySelectorAll(`input[name="${name}"]`).forEach(cb => cb.checked = source.checked);
        }

        function toggleAllVariants(source) {
            document.querySelectorAll('.variant_checkbox').forEach(cb => cb.checked = source.checked);
        }

        function deleteSelectedVariants() {
            document.querySelectorAll('.variant_checkbox:checked').forEach(cb => cb.closest('tr').remove());
        }

        function showBulkInputsAndGenerateVariants() {
            const sizes = document.querySelectorAll('input[name="sizes[]"]:checked');
            const colors = document.querySelectorAll('input[name="colors[]"]:checked');
            if (!sizes.length || !colors.length) return alert("Vui lòng chọn ít nhất một Size và một Màu để tạo biến thể.");
            document.getElementById('bulk-inputs').style.display = 'block';
            generateVariants();
        }

        function generateVariants() {
            const sizes = document.querySelectorAll('input[name="sizes[]"]:checked');
            const colors = document.querySelectorAll('input[name="colors[]"]:checked');
            const tbody = document.querySelector('#variant_table tbody');
            sizes.forEach(size => {
                colors.forEach(color => {
                    if (!variantExists(size.value, color.value)) {
                        tbody.insertAdjacentHTML('beforeend', `
<tr>
<td><input type="checkbox" class="variant_checkbox"></td>
<td><input type="hidden" name="variant_sizes[]" value="${size.value}">${size.parentElement.textContent.trim()}</td>
<td><input type="hidden" name="variant_colors[]" value="${color.value}">${color.parentElement.textContent.trim()}</td>
<td><input type="number" name="variant_prices[]" class="form-control" placeholder="Giá" required></td>
<td><input type="number" name="variant_sale_prices[]" class="form-control" placeholder="Giá Sale" required></td>
<td><input type="number" name="variant_quantities[]" class="form-control" placeholder="Số lượng" required></td>
<td><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">Xoá</button></td>
</tr>`);
                    }
                });
            });
        }

        function variantExists(sizeId, colorId) {
            return [...document.querySelectorAll('#variant_table tbody tr')].some(row => {
                return row.querySelector('input[name="variant_sizes[]"]').value == sizeId &&
                    row.querySelector('input[name="variant_colors[]"]').value == colorId;
            });
        }

        function applyDefaultToVariants() {
            const p = +document.getElementById('default_price').value,
                s = +document.getElementById('default_saleprice').value,
                q = +document.getElementById('default_quantity').value;
            if ([p, s, q].some(v => isNaN(v))) return alert("Vui lòng nhập đầy đủ thông tin.");
            if (s >= p) return alert("Giá sale phải nhỏ hơn giá gốc.");
            document.querySelectorAll('#variant_table tbody tr').forEach(row => {
                row.querySelector('input[name="variant_prices[]"]').value = p;
                row.querySelector('input[name="variant_sale_prices[]"]').value = s;
                row.querySelector('input[name="variant_quantities[]"]').value = q;
            });
        }
    </script>

@endsection

@section('scripts')
<script>
    function previewImages(event) {
        const input = event.target;
        const preview = document.getElementById('preview-images');
        preview.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '120px';
                    img.style.maxHeight = '120px';
                    img.style.borderRadius = '8px';
                    img.style.border = '1px solid #eee';
                    img.style.marginRight = '8px';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endsection

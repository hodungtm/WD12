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


            @if ($product->images->isNotEmpty())
                <div class="wg-box mb-3">
                    <h5 class="body-title">Ảnh hiện tại</h5>
                    <div class="flex flex-wrap gap10">
                        @foreach ($product->images as $img)
                            <div class="border rounded p-1 text-center"
                                style="position: relative; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ asset('storage/' . $img->image) }}"
                                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px;">

                                <form action="{{ route('products.image.destroy', $img->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn chắc chắn muốn xóa ảnh này?')"
                                    style="position: absolute; top: 4px; right: 4px; margin: 0; padding: 0; background: none; border: none;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="background: rgba(255,255,255,0.8); border: none; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px #0001; cursor: pointer;">
                                        <i class="icon-x" style="color: #e74c3c; font-size: 16px;"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
<div class="wg-box mb-30">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên sản phẩm</div>
                        <input type="text" name="name" class="form-control mb-10" value="{{ old('name', $product->name) }}">
                        @error('name')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset class="upload">
                        <div class="body-title mb-10">Thêm ảnh sản phẩm mới <span class="tf-color-1">*</span></div>
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <div class="upload-image" style="flex: 0 0 auto;">
                                <div class="item up-load">
                                    <label class="uploadfile h250" for="images"
                                        style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 200px; width: 200px; border: 1px dashed #ccc; border-radius: 8px; text-align: center;">
                                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                                        <span class="body-text" style="font-size: 13px;">Kéo thả hoặc chọn <span
                                                    class="tf-color">tải ảnh lên</span></span>
                                        <input type="file" id="images" name="images[]" accept="image/*" multiple
                                            onchange="previewImages(event)" style="display: none;">
                                    </label>
                                </div>
                            </div>

                            <div class="preview-image" style="flex: 1;">
                                <div id="preview-images" style="display: flex; gap: 6px; flex-wrap: wrap;"></div>
                            </div>
                        </div>

                        @error('images.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>

                <div class="wg-box mb-30">
                    <fieldset class="category">
                        <div class="body-title mb-10">Danh mục</div>
                        <select name="category_id" class="form-control" required>
                            <option value="" disabled>
                                -- Chọn danh mục --
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
{{ $category->ten_danh_muc }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    <fieldset class="description">
                        <div class="body-title mb-10">Mô tả</div>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    <fieldset class="status">
                        <div class="body-title mb-10">Trạng thái</div>
                        <select name="status" class="form-control">
                            <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </fieldset>
                </div>

                <div id="variant-section" class="wg-box mb-30">
                    <button type="button" class="tf-button btn-sm w-auto px-3 py-2 mb-3" onclick="toggleVariantCreationUI()">
                        <i class="icon-plus"></i>
                        <span id="variant-toggle-text">Tạo biến thể</span> 
                    </button>

                    <div id="variant-creation-inputs" class="d-none"> 
                        <fieldset>
                            <div class="body-title mb-10">Chọn Size</div>
                            <div class="flex flex-wrap gap10">
                                <label><input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)">
                                    Chọn tất cả</label>
                                @foreach ($sizes as $size)
                                    <label><input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                                        {{ in_array($size->id, $selectedSizes ?? []) ? 'checked' : '' }}>
                                        {{ $size->name }}</label>
                                @endforeach
                                @error('sizes')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset><br>

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
                                @error('colors')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset> <br>

                        <div class="mt-3"> 
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
                            <br>
                            <div class="mt-2">
                                <button type="button" class="tf-button w370" onclick="applyDefaultToVariants()">
                                    <i class="icon-check"></i> Áp dụng & Tạo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="variant-list-box" class="wg-box mb-30 d-none">
                    <div class="body-title mb-10">Danh sách biến thể</div>
                    <button type="button" class="tf-button style-3 btn-sm w-auto px-3 py-2 mb-2"
                        onclick="deleteSelectedVariants()">
                        <i class="icon-trash"></i> Xoá các biến thể đã chọn
                    </button>
                    <div class="wg-table">
                        <table class="table-product-list" id="variant_table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all_variants"
onchange="toggleAllVariants(this)"></th>
                                    <th>Size</th>
                                    <th>Màu</th>
                                    <th>Giá</th>
                                    <th>Giá Sale</th>
                                    <th>Số lượng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
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

<script>
    const allSizes = @json($sizes);
    const allColors = @json($colors);
    const productVariants = @json($product->variants);

    // Hàm ẩn/hiện phần chọn và nhập biến thể
    function toggleVariantCreationUI() {
        const variantCreationInputs = document.getElementById('variant-creation-inputs');
        const toggleButtonText = document.getElementById('variant-toggle-text');

        if (variantCreationInputs.classList.contains('d-none')) {
            variantCreationInputs.classList.remove('d-none');
            toggleButtonText.textContent = 'Ẩn tùy chọn biến thể'; 
            window.scrollTo({
                top: variantCreationInputs.offsetTop - 60,
                behavior: 'smooth'
            });
        } else {
            variantCreationInputs.classList.add('d-none');
            toggleButtonText.textContent = 'Tạo biến thể'; 
        }
    }

    function applyDefaultToVariants() {
        const price = +document.getElementById('default_price').value;
        const sale = +document.getElementById('default_saleprice').value;
        const qty = +document.getElementById('default_quantity').value;

        if ([price, sale, qty].some(v => isNaN(v))) {
            return alert("Vui lòng nhập đủ giá/giảm giá/số lượng.");
        }
        if (sale >= price) {
            return alert("Giá sale phải nhỏ hơn giá gốc.");
        }

        const sizes = document.querySelectorAll('input[name="sizes[]"]:checked');
        const colors = document.querySelectorAll('input[name="colors[]"]:checked');
        const tbody = document.querySelector('#variant_table tbody');

        if (sizes.length === 0 && colors.length === 0) {
            return alert("Vui lòng chọn ít nhất một Size hoặc một Màu.");
        }

        sizes.forEach(sizeInput => {
            colors.forEach(colorInput => {
                const sizeId = sizeInput.value;
const colorId = colorInput.value;

                if (!variantExists(sizeId, colorId)) {
                    const sizeName = sizeInput.parentElement.textContent.trim();
                    const colorName = colorInput.parentElement.textContent.trim();
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td><input type="checkbox" class="variant_checkbox"></td>
                            <td><input type="hidden" name="variant_sizes[]" value="${sizeId}">${sizeName}</td>
                            <td><input type="hidden" name="variant_colors[]" value="${colorId}">${colorName}</td>
                            <td><input type="number" name="variant_prices[]" class="form-control" value="${price}" required></td>
                            <td><input type="number" name="variant_sale_prices[]" class="form-control" value="${sale}" required></td>
                            <td><input type="number" name="variant_quantities[]" class="form-control" value="${qty}" required></td>
                            <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove(); checkVariantTableVisibility();">Xoá</button></td>
                        </tr>
                    `);
                }
            });
        });
        checkVariantTableVisibility();
    }

    function variantExists(sizeId, colorId) {
        return [...document.querySelectorAll('#variant_table tbody tr')].some(row => {
            return row.querySelector('input[name="variant_sizes[]"]').value == sizeId &&
                   row.querySelector('input[name="variant_colors[]"]').value == colorId;
        });
    }

    function toggleAll(name, source) {
        document.querySelectorAll(`input[name="${name}"]`).forEach(cb => cb.checked = source.checked);
    }

    function toggleAllVariants(source) {
        document.querySelectorAll('.variant_checkbox').forEach(cb => cb.checked = source.checked);
    }

    function deleteSelectedVariants() {
        document.querySelectorAll('.variant_checkbox:checked').forEach(cb => cb.closest('tr').remove());
        checkVariantTableVisibility();
    }

    function checkVariantTableVisibility() {
        const tbody = document.querySelector('#variant_table tbody');
        const variantListBox = document.getElementById('variant-list-box');
        if (tbody.children.length > 0) {
            variantListBox.classList.remove('d-none');
        } else {
            variantListBox.classList.add('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.querySelector('#variant_table tbody');

        if (productVariants && productVariants.length > 0) {

            productVariants.forEach(variant => {
                const sizeName = allSizes.find(s => s.id === variant.size_id)?.name || 'N/A';
const colorName = allColors.find(c => c.id === variant.color_id)?.name || 'N/A';

                tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td><input type="checkbox" class="variant_checkbox"></td>
                        <td><input type="hidden" name="variant_sizes[]" value="${variant.size_id}">${sizeName}</td>
                        <td><input type="hidden" name="variant_colors[]" value="${variant.color_id}">${colorName}</td>
                        <td><input type="number" name="variant_prices[]" class="form-control" value="${variant.price}" required></td>
                        <td><input type="number" name="variant_sale_prices[]" class="form-control" value="${variant.sale_price}" required></td>
                        <td><input type="number" name="variant_quantities[]" class="form-control" value="${variant.quantity}" required></td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove(); checkVariantTableVisibility();">Xoá</button></td>
                    </tr>
                `);
            });
            checkVariantTableVisibility(); 
        }
    });

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
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
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
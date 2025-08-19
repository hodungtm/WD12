@extends('admin.layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Thêm sản phẩm mới</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.products.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.products.index') }}">
                            <div class="text-tiny">Ecommerce</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Thêm sản phẩm mới</div>
                    </li>
                </ul>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf

                <div class="wg-box mb-30">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên sản phẩm</div>
                        <input type="text" name="name" class="form-control mb-10" value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset class="upload">
                        <div class="body-title mb-10">Thêm ảnh sản phẩm <span class="tf-color-1">*</span></div>
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <!-- Khung upload -->
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
<!-- Khung preview ảnh -->
                            <div class="preview-image" style="flex: 1;">
                                <div id="preview-images" style="display: flex; gap: 6px; flex-wrap: wrap;"></div>
                            </div>
                        </div>

                        @error('images.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <!-- Thêm phần chọn màu cho ảnh -->
                    <fieldset class="image-colors" id="image-colors-section" style="display: none;">
                        <div class="body-title mb-10">Chọn màu cho từng ảnh</div>
                        <div id="image-color-inputs" class="flex flex-wrap gap-2">
                            <!-- Các input chọn màu sẽ được tạo động bằng JavaScript -->
                        </div>
                    </fieldset>
                </div>

                <div class="wg-box mb-30">
                    <fieldset class="category">
                        <div class="body-title mb-10">Danh mục</div>
                        <select name="category_id" class="form-control" required>
                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>
                                -- Chọn danh mục --
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>

                <div id="variant-section" class="wg-box mb-30">
                    <button type="button" class="tf-button btn-sm w-auto px-3 py-2 mb-3" onclick="showAllVariantUI()">
                        <i class="icon-plus"></i> Tạo biến thể
                    </button>

                    <div id="variant-selects" class="d-none">
                        <fieldset>
                            <div class="body-title mb-10">Chọn Size <span class="tf-color-1">*</span></div>
                            <div class="flex flex-wrap gap10">
                                <label><input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)">
                                    Chọn tất cả</label>
                                @foreach ($sizes as $size)
                                    <label><input type="checkbox" name="sizes[]" value="{{ $size->id }}">
                                        {{ $size->name }}</label>
                                @endforeach
                        @error('variant_sizes')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset><br>

                        <fieldset>
                            <div class="body-title mb-10">Chọn Màu <span class="tf-color-1">*</span></div>
                            <div class="flex flex-wrap gap10">
                                <label><input type="checkbox" id="select_all_colors" onchange="toggleAll('colors[]', this)">
                                    Chọn tất cả</label>
                                @foreach ($colors as $color)
                                    <label><input type="checkbox" name="colors[]" value="{{ $color->id }}">
                                        {{ $color->name }}</label>
                                @endforeach
                                @error('variant_colors')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset> <br>

                        <div class="mt-3" id="bulk-inputs">
                            <div class="cols-lg gap22">
                                <fieldset>
                                    <div class="body-title mb-10">Giá mặc định</div>
                                    <input type="number" id="default_price" min="0" class="form-control">
                                </fieldset>
                                <fieldset>
                                    <div class="body-title mb-10">Giá sale mặc định</div>
                                    <input type="number" id="default_saleprice" min="0" class="form-control">
                                </fieldset>
                                <fieldset>
                                    <div class="body-title mb-10">Số lượng mặc định</div>
                                    <input type="number" id="default_quantity" min="1" class="form-control">
                                </fieldset>
                            </div>
                            <br>
                            <div class="mt-2">
                                <button type="button" class="tf-button w370" onclick="applyDefaultToVariants()">
                                    <i class="icon-check"></i> Tạo
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-start gap-3 mt-4">
                    <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                        <i class="icon-save"></i> Lưu sản phẩm
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                        <i class="icon-x"></i> Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>

<script>
        window.showAllVariantUI = function() {
            document.getElementById('variant-selects').classList.remove('d-none');
            document.getElementById('bulk-inputs').classList.remove('d-none');
            window.scrollTo({
                top: document.getElementById('variant-selects').offsetTop - 60,
                behavior: 'smooth'
            });
        }

        window.applyDefaultToVariants = function() {
            const price = +document.getElementById('default_price').value;
            const sale = +document.getElementById('default_saleprice').value;
            const qty = +document.getElementById('default_quantity').value;
            if ([price, sale, qty].some(v => isNaN(v))) return alert("Vui lòng nhập đủ giá/giảm giá/số lượng.");
            if (sale >= price) return alert("Giá sale phải nhỏ hơn giá gốc.");

            const sizes = document.querySelectorAll('input[name="sizes[]"]:checked');
            const colors = document.querySelectorAll('input[name="colors[]"]:checked');
            
            if (sizes.length === 0) {
                alert("Vui lòng chọn ít nhất một size.");
                return;
            }
            
            if (colors.length === 0) {
                alert("Vui lòng chọn ít nhất một màu.");
                return;
            }
            
            const tbody = document.querySelector('#variant_table tbody');

            sizes.forEach(size => {
                colors.forEach(color => {
                    if (!window.variantExists(size.value, color.value)) {
                        tbody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td><input type="checkbox" class="variant_checkbox"></td>
                            <td><input type="hidden" name="variant_sizes[]" value="${size.value}">${size.parentElement.textContent.trim()}</td>
                            <td><input type="hidden" name="variant_colors[]" value="${color.value}">${color.parentElement.textContent.trim()}</td>
                            <td><input type="number" min="0" name="variant_prices[]" class="form-control" value="${price}" required></td>
                            <td><input type="number" min="0" name="variant_sale_prices[]" class="form-control" value="${sale}" required></td>
                            <td><input type="number" min="1" name="variant_quantities[]" class="form-control" value="${qty}" required></td>
                            <td><button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">Xoá</button></td>
                        </tr>
                    `);
                    }
                });
            });

            document.getElementById('variant-list-box').classList.remove('d-none');
        }

        window.variantExists = function(sizeId, colorId) {
            return [...document.querySelectorAll('#variant_table tbody tr')].some(row => {
                return row.querySelector('input[name="variant_sizes[]"]').value == sizeId &&
                    row.querySelector('input[name="variant_colors[]"]').value == colorId;
            });
        }

        window.toggleAll = function(name, source) {
            document.querySelectorAll(`input[name="${name}"]`).forEach(cb => cb.checked = source.checked);
        }

        window.toggleAllVariants = function(source) {
            document.querySelectorAll('.variant_checkbox').forEach(cb => cb.checked = source.checked);
        }

        window.deleteSelectedVariants = function() {
            document.querySelectorAll('.variant_checkbox:checked').forEach(cb => cb.closest('tr').remove());
        }

        window.previewImages = function(event) {
            const input = event.target;
            const preview = document.getElementById('preview-images');
            const imageColorsSection = document.getElementById('image-colors-section');
            const imageColorInputs = document.getElementById('image-color-inputs');
            
            preview.innerHTML = '';
            imageColorInputs.innerHTML = '';
            
            if (input.files) {
                imageColorsSection.style.display = 'block';
                
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Tạo preview ảnh
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100px';
                        img.style.maxHeight = '100px';
                        img.style.borderRadius = '8px';
                        img.style.border = '1px solid #eee';
                        img.style.marginRight = '8px';
                        preview.appendChild(img);
                        
                        // Tạo input chọn màu cho ảnh này
                        const colorDiv = document.createElement('div');
                        colorDiv.className = 'image-color-item';
                        colorDiv.style.marginBottom = '10px';
                        colorDiv.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="${e.target.result}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <div>
                                    <label style="font-size: 12px; color: #666;">Ảnh ${index + 1}:</label>
                                    <select name="image_colors[]" class="form-control" style="width: 150px; font-size: 12px;">
                                        <option value="">-- Chọn màu --</option>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        `;
                        imageColorInputs.appendChild(colorDiv);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        window.validateForm = function() {
            // Kiểm tra ảnh
            const imageInput = document.getElementById('images');
            if (!imageInput.files || imageInput.files.length === 0) {
                alert('Vui lòng chọn ít nhất một ảnh sản phẩm.');
                return false;
            }

            // Kiểm tra variants
            const variantRows = document.querySelectorAll('#variant_table tbody tr');
            if (variantRows.length === 0) {
                alert('Vui lòng tạo ít nhất một biến thể sản phẩm.');
                return false;
            }

            // Kiểm tra dữ liệu trong từng variant
            for (let row of variantRows) {
                const sizeInput = row.querySelector('input[name="variant_sizes[]"]');
                const colorInput = row.querySelector('input[name="variant_colors[]"]');
                const priceInput = row.querySelector('input[name="variant_prices[]"]');
                const salePriceInput = row.querySelector('input[name="variant_sale_prices[]"]');
                const quantityInput = row.querySelector('input[name="variant_quantities[]"]');

                if (!sizeInput.value || !colorInput.value || !priceInput.value || !salePriceInput.value || !quantityInput.value) {
                    alert('Vui lòng điền đầy đủ thông tin cho tất cả biến thể.');
                    return false;
                }

                if (parseFloat(salePriceInput.value) >= parseFloat(priceInput.value)) {
                    alert('Giá sale phải nhỏ hơn giá gốc.');
                    return false;
                }
            }

            return true;
        }
</script>
@endsection
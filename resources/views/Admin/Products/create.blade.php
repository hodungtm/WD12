@extends('admin.layouts.Adminlayout')

@section('main')
    <style>
        .box-value-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 5px;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .box-value-item input {
            display: none;
        }

        .box-value-item input:checked+.body-text {
            background-color: #0066ff;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .body-text {
            font-size: 14px;
        }

        table.table,
        table.table th,
        table.table td {
            border: 1px solid #eee;
        }
    </style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thêm sản phẩm</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="#">
                            <div class="text-tiny">Ecommerce</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Thêm sản phẩm</div>
                    </li>
                </ul>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                class="tf-section-2 form-add-product">
                @csrf

                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên sản phẩm <span class="tf-color-1">*</span></div>
                        <input type="text" name="name" placeholder="Nhập tên sản phẩm" class="mb-10" required>
                    </fieldset>

                    <fieldset class="category">
                        <div class="body-title mb-10">Danh mục <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->ten_danh_muc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="description">
                        <div class="body-title mb-10">Mô tả sản phẩm <span class="tf-color-1">*</span></div>
                        <textarea name="description" class="mb-10" rows="4" placeholder="Mô tả sản phẩm..."
                            required></textarea>
                    </fieldset>
                </div>

                {{-- Size và màu --}}
                <div class="wg-box">
                    <div class="cols gap22 mt-3">
                        <fieldset>
                            <div class="body-title mb-10">Giá chung cho biến thể</div>
                            <input type="text" id="common_price" class="form-control" placeholder="Nhập giá VND">
                        </fieldset>
                        <fieldset>
                            <div class="body-title mb-10">Số lượng chung cho biến thể</div>
                            <input type="number" id="common_quantity" class="form-control" placeholder="Nhập số lượng">
                        </fieldset>
                    </div>
                    
                    <fieldset>
                        <div class="body-title mb-10">Chọn Size</div>
                        <div class="flex flex-wrap gap10">
                            <label class="circle-option">
                                <input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)">
                                <span>Chọn tất cả</span>
                            </label>
                            @foreach ($sizes as $size)
                                <label class="circle-option">
                                    <input type="checkbox" name="sizes[]" value="{{ $size->id }}">
                                    <span>{{ $size->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <fieldset class="mt-3">
                        <div class="body-title mb-10">Chọn Màu</div>
                        <div class="flex flex-wrap gap10">
                            <label class="circle-option">
                                <input type="checkbox" id="select_all_colors" onchange="toggleAll('colors[]', this)">
                                <span>Chọn tất cả</span>
                            </label>
                            @foreach ($colors as $color)
                                <label class="circle-option">
                                    <input type="checkbox" name="colors[]" value="{{ $color->id }}">
                                    <span>{{ $color->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>


                    

                    <button type="button" class="tf-button mt-3" onclick="generateVariants()">
                        <i class="fas fa-plus me-1"></i> Tạo biến thể
                    </button>
                </div>

                {{-- Upload ảnh --}}
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title mb-10">Tải lên ảnh sản phẩm</div>
                        <div class="upload-image mb-16">
                            <div class="item up-load">
                                <label class="uploadfile" for="product_images">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="text-tiny">Drop your images here or <span class="tf-color">click to
                                            browse</span></span>
                                    <input type="file" id="product_images" name="images[]" multiple>
                                    <div id="image_preview" class="flex flex-wrap gap10 mt-3"></div>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>

                {{-- Bảng biến thể --}}
                <div class="wg-box">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="mb-0">Danh sách biến thể</h5>
                        <button type="button" class="tf-button style-1" onclick="deleteSelectedVariants()">
                            <i class="fas fa-trash-alt me-1"></i> Xóa các biến thể đã chọn
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="variant_table">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="select_all_variants" onchange="toggleAllVariants(this)">
                                    </th>
                                    <th>Size</th>
                                    <th>Màu</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="cols gap10 mt-4">
                    <button type="submit" class="tf-button w-full">Lưu sản phẩm</button>
                    <a href="{{ route('products.index') }}" class="tf-button style-1 w-full">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>

    <div class="bottom-page">
        <div class="body-text">© 2024 Remos. Design by <a
                href="https://themeforest.net/user/themesflat/portfolio">Themesflat</a></div>
    </div>

    {{-- JavaScript --}}
    <script>
        function toggleAll(name, sourceCheckbox) {
            document.querySelectorAll(`input[name="${name}"]`).forEach(cb => cb.checked = sourceCheckbox.checked);
        }

        function generateVariants() {
            let sizes = document.querySelectorAll('input[name="sizes[]"]:checked');
            let colors = document.querySelectorAll('input[name="colors[]"]:checked');
            let price = document.getElementById('common_price').value;
            let quantity = document.getElementById('common_quantity').value;
            let tbody = document.querySelector('#variant_table tbody');

            sizes.forEach(size => {
                colors.forEach(color => {
                    if (!variantExists(size.value, color.value)) {
                        tbody.insertAdjacentHTML('beforeend', `
                                <tr>
                                    <td><input type="checkbox" class="variant_checkbox"></td>
                                    <td><input type="hidden" name="variant_sizes[]" value="${size.value}">${size.closest('label').innerText.trim()}</td>
                                    <td><input type="hidden" name="variant_colors[]" value="${color.value}">${color.closest('label').innerText.trim()}</td>
                                    <td><input type="text" name="variant_prices[]" class="form-control" value="${price}"></td>
                                    <td><input type="number" name="variant_quantities[]" class="form-control" value="${quantity}"></td>
                                    <td>
        <button type="button" class="btn btn-sm text-danger" style="font-size: 20px; line-height: 1;" onclick="this.closest('tr').remove()">
            &times;
        </button>
    </td>
                                </tr>
                            `);
                    }
                });
            });
        }

        function variantExists(sizeId, colorId) {
            return Array.from(document.querySelectorAll('#variant_table tbody tr')).some(row =>
                row.querySelector('input[name="variant_sizes[]"]').value == sizeId &&
                row.querySelector('input[name="variant_colors[]"]').value == colorId
            );
        }

        function toggleAllVariants(sourceCheckbox) {
            document.querySelectorAll('.variant_checkbox').forEach(cb => cb.checked = sourceCheckbox.checked);
        }

        function deleteSelectedVariants() {
            document.querySelectorAll('.variant_checkbox:checked').forEach(cb => cb.closest('tr').remove());
        }
        // Hiển thị ảnh xem trước khi tải lên
        document.getElementById('product_images').addEventListener('change', function (event) {
            const previewContainer = document.getElementById('image_preview');
            previewContainer.innerHTML = ''; // Clear cũ

            const files = event.target.files;
            if (files.length === 0) return;

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded border p-1';
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });


    </script>
@endsection
@extends('Admin.Layouts.AdminLayout')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Chỉnh sửa sản phẩm</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Thương hiệu</label>
                                <select class="form-select" id="brand_id" name="brand_id" required>
                                    <option value="">Chọn thương hiệu</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Giá (Giá cơ sở cho biến thể mặc định)</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả (Mô tả cơ sở cho biến thể mặc định)</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh sản phẩm chính</label>
                                @if ($product->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="image" name="image">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            {{-- Phần quản lý biến thể --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Biến thể sản phẩm</h5>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#productAttributeModal">
                                    <i class="bi bi-gear"></i> Quản lý thuộc tính
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên biến thể</th>
                                            <th>SKU</th>
                                            <th>Số lượng</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variant-table-body">
                                        {{-- Các hàng biến thể sẽ được JavaScript chèn vào đây --}}
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">Các biến thể có thể được chỉnh sửa chi tiết hơn bằng cách nhấp vào nút bút chì.</small>


                            <button type="submit" class="btn btn-primary mt-3">Cập nhật sản phẩm</button>
                            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary mt-3">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productAttributeModal" tabindex="-1" aria-labelledby="productAttributeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productAttributeModalLabel">Quản lý thuộc tính sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <h6>Thuộc tính có sẵn</h6>
                            <div id="attribute-list">
                                {{-- Danh sách thuộc tính sẽ được JS tải vào đây --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Thuộc tính đã chọn</h6>
                            <div id="selected-attributes">
                                {{-- Các thuộc tính đã chọn sẽ được JS hiển thị vào đây --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="handleSave()">Lưu và tạo biến thể</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVariantModal" tabindex="-1" aria-labelledby="editVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVariantModalLabel">Chỉnh sửa chi tiết biến thể</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-variant-form">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-variant-sku" class="form-label">SKU</label>
                            <input type="text" class="form-control" id="edit-variant-sku" name="sku">
                        </div>
                        <div class="mb-3">
                            <label for="edit-variant-quantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="edit-variant-quantity" name="quantity" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="edit-variant-price" class="form-label">Giá</label>
                            <input type="number" step="0.01" class="form-control" id="edit-variant-price" name="price">
                        </div>
                        <div class="mb-3">
                            <label for="edit-variant-sale_price" class="form-label">Giá khuyến mãi (tùy chọn)</label>
                            <input type="number" step="0.01" class="form-control" id="edit-variant-sale_price" name="sale_price">
                        </div>
                        <div class="mb-3">
                            <label for="edit-variant-stock_status" class="form-label">Trạng thái kho</label>
                            <select class="form-select" id="edit-variant-stock_status" name="stock_status">
                                <option value="in_stock">Còn hàng</option>
                                <option value="out_of_stock">Hết hàng</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-variant-description" class="form-label">Mô tả (tùy chọn)</label>
                            <textarea class="form-control" id="edit-variant-description" name="description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-variant-image" class="form-label">Hình ảnh biến thể (tùy chọn)</label>
                            <input type="file" class="form-control" id="edit-variant-image" name="image">
                            <div class="mt-2">
                                <img id="edit-variant-image-preview" src="" alt="Variant Image Preview" style="max-width: 100px; border: 1px solid #ddd; padding: 5px; {{ !$product->image ? 'display:none;' : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 <script>
        const attributesFromServer = @json($attributes); // Dữ liệu thuộc tính từ server
        let selectedAttributes = {}; // Sử dụng let để cho phép gán lại nếu cần
        let existingVariants = @json($product->variants ?? []); // Truyền các biến thể hiện có từ controller

        let currentEditingIndex = null; // Biến để theo dõi chỉ mục của variant đang chỉnh sửa

        // Hàm để điền selectedAttributes từ các biến thể hiện có
        // Hàm này cần xây dựng selectedAttributes dựa trên tên và giá trị thuộc tính thực tế
        function populateSelectedAttributesFromVariants() {
            if (existingVariants.length === 0 || (existingVariants.length === 1 && existingVariants[0].attribute_text === 'Mặc định')) {
                // Nếu không có biến thể hoặc chỉ có một biến thể mặc định, không có thuộc tính nào được chọn trước
                selectedAttributes = {};
                return;
            }

            const tempSelectedAttributes = {};
            existingVariants.forEach(variant => {
                if (variant.attribute_value_ids && Array.isArray(variant.attribute_value_ids)) {
                    variant.attribute_value_ids.forEach(valueId => {
                        // Tìm thuộc tính và giá trị của nó bằng valueId
                        attributesFromServer.forEach(attr => {
                            const foundValue = attr.values.find(v => v.id === valueId);
                            if (foundValue) {
                                if (!tempSelectedAttributes[attr.name]) {
                                    tempSelectedAttributes[attr.name] = new Set(); // Sử dụng Set cho các giá trị duy nhất
                                }
                                tempSelectedAttributes[attr.name].add(foundValue.value);
                            }
                        });
                    });
                }
            });

            // Chuyển đổi Sets trở lại thành Arrays cho selectedAttributes
            for (const attrName in tempSelectedAttributes) {
                selectedAttributes[attrName] = Array.from(tempSelectedAttributes[attrName]);
            }

            renderSelectedAttributes(); // Render ngay sau khi điền
        }

        // Hàm để tải và hiển thị các thuộc tính có sẵn
        function loadAttributes() {
            const container = document.getElementById('attribute-list');
            container.innerHTML = ''; // Xóa nội dung hiện có

            attributesFromServer.forEach(attribute => {
                const groupDiv = document.createElement('div');
                groupDiv.classList.add('mb-3', 'border', 'p-2');

                const headerDiv = document.createElement('div');
                headerDiv.classList.add('d-flex', 'justify-content-between', 'align-items-center');

                const title = document.createElement('div');
                title.classList.add('fw-bold');
                title.textContent = attribute.name;

                const btnGroup = document.createElement('button');
                btnGroup.classList.add('btn', 'btn-dark', 'btn-sm');
                btnGroup.innerHTML = 'Chọn tất cả <i class="bi bi-arrow-right"></i>';
                btnGroup.onclick = () => {
                    attribute.values.forEach(val => selectAttribute(attribute.name, val.value));
                };

                headerDiv.appendChild(title);
                headerDiv.appendChild(btnGroup);
                groupDiv.appendChild(headerDiv);

                attribute.values.forEach(value => {
                    const row = document.createElement('div');
                    row.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2', 'border-bottom', 'pb-1');

                    const valueText = document.createElement('div');
                    valueText.textContent = value.value;

                    const btn = document.createElement('button');
                    btn.classList.add('btn', 'btn-primary', 'btn-sm');
                    btn.innerHTML = 'Chọn <i class="bi bi-arrow-right"></i>';
                    btn.onclick = () => selectAttribute(attribute.name, value.value);

                    row.appendChild(valueText);
                    row.appendChild(btn);
                    groupDiv.appendChild(row);
                });

                container.appendChild(groupDiv);
            });
        }

        // Hàm để chọn một giá trị thuộc tính
        function selectAttribute(group, value) {
            if (!selectedAttributes[group]) selectedAttributes[group] = [];
            if (!selectedAttributes[group].includes(value)) {
                selectedAttributes[group].push(value);
                renderSelectedAttributes();
            }
        }

        // Hàm để xóa một giá trị thuộc tính
        function removeAttribute(group, value) {
            if (selectedAttributes[group]) {
                selectedAttributes[group] = selectedAttributes[group].filter(val => val !== value);
                if (selectedAttributes[group].length === 0) delete selectedAttributes[group];
                renderSelectedAttributes();
            }
        }

        // Hàm để hiển thị các thuộc tính đã chọn
        function renderSelectedAttributes() {
            const container = document.getElementById('selected-attributes');
            container.innerHTML = ''; // Xóa nội dung hiện có

            Object.keys(selectedAttributes).forEach(group => {
                const groupDiv = document.createElement('div');
                groupDiv.classList.add('mb-3', 'border', 'p-2');

                const title = document.createElement('div');
                title.classList.add('fw-bold', 'mb-2');
                title.textContent = group;
                groupDiv.appendChild(title);

                selectedAttributes[group].forEach(value => {
                    const row = document.createElement('div');
                    row.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-1');

                    const valueText = document.createElement('div');
                    valueText.textContent = value;

                    const btn = document.createElement('button');
                    btn.classList.add('btn', 'btn-danger', 'btn-sm');
                    btn.innerHTML = '<i class="bi bi-trash"></i>';
                    btn.onclick = () => removeAttribute(group, value);

                    row.appendChild(valueText);
                    row.appendChild(btn);
                    groupDiv.appendChild(row);
                });

                container.appendChild(groupDiv);
            });
        }

        // Hàm để tạo các kết hợp (biến thể) từ các thuộc tính đã chọn
        function generateCombinations(obj) {
            const keys = Object.keys(obj);
            if (keys.length === 0) return [];

            const combinations = [];

            function backtrack(index, currentCombo, currentAttrValueIds) {
                if (index === keys.length) {
                    combinations.push({
                        comboText: currentCombo.join(' - '),
                        attrValueIds: [...currentAttrValueIds] // Đảm bảo đó là một mảng mới
                    });
                    return;
                }

                const key = keys[index];
                for (const val of obj[key]) {
                    const correspondingAttrValue = attributesFromServer
                        .flatMap(attr => attr.values)
                        .find(v => v.value === val && attributesFromServer.find(a => a.id === v.attribute_id).name === key);

                    if (correspondingAttrValue) {
                        currentCombo.push(val);
                        currentAttrValueIds.push(correspondingAttrValue.id);
                        backtrack(index + 1, currentCombo, currentAttrValueIds);
                        currentAttrValueIds.pop();
                        currentCombo.pop();
                    }
                }
            }

            backtrack(0, [], []);
            return combinations;
        }

        // Hàm để tìm một biến thể hiện có phù hợp với kết hợp được tạo
        function findMatchingVariant(generatedVariantCombo) {
            const generatedAttrValueIds = generatedVariantCombo.attrValueIds.sort((a, b) => a - b).join(',');

            return existingVariants.find(v => {
                // Đảm bảo v.attribute_value_ids tồn tại và là một mảng
                const existingAttrValueIds = v.attribute_value_ids && Array.isArray(v.attribute_value_ids) ? v.attribute_value_ids.sort((a, b) => a - b).join(',') : '';
                return existingAttrValueIds === generatedAttrValueIds;
            });
        }

        // Hàm để xử lý lưu biến thể và cập nhật bảng
        // Hàm này sẽ điền các biến thể vào bảng, sử dụng dữ liệu hiện có nếu có.
        function handleSave() {
            const tbody = document.getElementById('variant-table-body');
            tbody.innerHTML = ''; // Xóa các hàng cũ trước khi thêm hàng mới

            let combinations = [];
            if (Object.keys(selectedAttributes).length === 0) {
                // Nếu không có thuộc tính nào được chọn, hãy kiểm tra xem có biến thể mặc định nào không
                const defaultVariantInExisting = existingVariants.find(v => v.attribute_text === 'Mặc định');
                if (defaultVariantInExisting) {
                    // Nếu có biến thể mặc định, chỉ sử dụng nó
                    combinations.push({
                        comboText: 'Mặc định',
                        attrValueIds: [] // Biến thể mặc định không có attribute_value_ids
                    });
                } else {
                    // Nếu không có biến thể mặc định nào trong existingVariants và không có thuộc tính nào được chọn
                    // Tạo một biến thể mặc định mới
                    combinations.push({
                        comboText: 'Mặc định',
                        attrValueIds: []
                    });
                }
            } else {
                // Nếu có thuộc tính được chọn, tạo các kết hợp dựa trên đó
                combinations = generateCombinations(selectedAttributes);
            }

            // Nếu không có kết hợp nào được tạo (ví dụ, sau khi xóa tất cả thuộc tính)
            if (combinations.length === 0 && existingVariants.length > 0) {
                const defaultVariant = existingVariants.find(v => v.attribute_text === 'Mặc định');
                if (defaultVariant) {
                    combinations.push({
                        comboText: 'Mặc định',
                        attrValueIds: []
                    });
                } else {
                    // Nếu không có mặc định, vẫn tạo một cái mới.
                    combinations.push({
                        comboText: 'Mặc định',
                        attrValueIds: []
                    });
                }
            }


            combinations.forEach((comboData, index) => {
                const tr = document.createElement('tr');
                tr.dataset.index = index;

                const matchingVariant = findMatchingVariant(comboData);

                const variantId = matchingVariant ? matchingVariant.id : '';
                const sku = matchingVariant ? matchingVariant.sku : `SKU-${Date.now()}-${index}`; // Fix: Use backticks
                const quantity = matchingVariant ? matchingVariant.quantity : 0;
                const price = matchingVariant ? matchingVariant.price : (document.getElementById('price').value || 0);
                const salePrice = matchingVariant ? (matchingVariant.sale_price || '') : '';
                const stockStatus = matchingVariant ? matchingVariant.stock_status : 'in_stock';
                const description = matchingVariant ? (matchingVariant.description || '') : (document.getElementById('description').value || '');
                const image = matchingVariant ? (matchingVariant.image || '') : ''; // Đường dẫn hình ảnh hiện có

                // Tạo một trường input file ẩn cho mỗi biến thể trong bảng
                // Điều này cho phép Laravel nhận tệp tải lên cho từng biến thể khi gửi biểu mẫu chính
                const fileInputId = `variant-image-upload-${index}`; // Fix: Use backticks
                const fileInputHtml = `<input type="file" name="variants[${index}][image]" class="d-none" id="${fileInputId}">`; // Fix: Use backticks

                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>
                        ${comboData.comboText}
                        <input type="hidden" name="variants[${index}][id]" value="${variantId}">
                        <input type="hidden" name="variants[${index}][attribute_text]" value="${comboData.comboText}">
                        <input type="hidden" name="variants[${index}][price]" value="${price}">
                        <input type="hidden" name="variants[${index}][sale_price]" value="${salePrice}">
                        <input type="hidden" name="variants[${index}][stock_status]" value="${stockStatus}">
                        <input type="hidden" name="variants[${index}][description]" value="${description}">
                        <input type="hidden" name="variants[${index}][image_path_only]" value="${image}"> ${fileInputHtml} ${comboData.attrValueIds.map(id => `<input type="hidden" name="variants[${index}][attribute_value_ids][]" value="${id}">`).join('')}
                    </td>
                    <td><input type="text" name="variants[${index}][sku]" class="form-control" value="${sku}"></td>
                    <td><input type="number" name="variants[${index}][quantity]" class="form-control" min="0" value="${quantity}"></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" onclick="editVariant(this)" data-index="${index}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        ${(combinations.length > 1 || (combinations.length === 1 && comboData.comboText !== 'Mặc định')) ? `<button type="button" class="btn btn-sm btn-danger ms-2" onclick="deleteVariantRow(this)"><i class="bi bi-trash"></i></button>` : ''}
                    </td>
                `; // Fix: Ensure complete template literal
                tbody.appendChild(tr);
            });

            $('#productAttributeModal').modal('hide');
            console.log('✔️ Các biến thể đã được tạo/cập nhật trong bảng.');
        }

        // Hàm để chỉnh sửa một biến thể
        function editVariant(button) {
            const index = button.dataset.index;
            currentEditingIndex = parseInt(index);

            const row = document.querySelector(`tr[data-index="${index}"]`); // Fix: Use backticks
            if (!row) return;

            // Điền các trường modal
            document.getElementById('edit-variant-sku').value = row.querySelector(`input[name="variants[${index}][sku]"]`).value; // Fix: Use backticks
            document.getElementById('edit-variant-quantity').value = row.querySelector(`input[name="variants[${index}][quantity]"]`).value; // Fix: Use backticks
            document.getElementById('edit-variant-price').value = row.querySelector(`input[name="variants[${index}][price]"]`).value; // Fix: Use backticks
            document.getElementById('edit-variant-sale_price').value = row.querySelector(`input[name="variants[${index}][sale_price]"]`).value; // Fix: Use backticks
            document.getElementById('edit-variant-stock_status').value = row.querySelector(`input[name="variants[${index}][stock_status]"]`).value; // Fix: Use backticks
            document.getElementById('edit-variant-description').value = row.querySelector(`input[name="variants[${index}][description]"]`).value; // Fix: Use backticks

            // Xử lý xem trước hình ảnh biến thể
            const imagePathOnlyInput = row.querySelector(`input[name="variants[${index}][image_path_only]"]`); // Fix: Use backticks
            const imagePreview = document.getElementById('edit-variant-image-preview');
            const imageFileInput = document.getElementById('edit-variant-image'); // Trường input file trong modal

            // Reset the file input in the modal
            imageFileInput.value = '';

            if (imagePathOnlyInput && imagePathOnlyInput.value && imagePathOnlyInput.value !== 'TEMP_NEW_IMAGE') {
                imagePreview.src = `/storage/${imagePathOnlyInput.value}`; // Fix: Use backticks
                imagePreview.style.display = 'block';
                // Show delete checkbox if there's an existing image
                document.getElementById('edit-variant-image-delete-container').style.display = 'block';
            } else {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                document.getElementById('edit-variant-image-delete-container').style.display = 'none';
            }
            document.getElementById('edit-variant-image-delete').checked = false; // Uncheck by default

            $('#editVariantModal').modal('show');
        }

        // Hàm để xóa một hàng biến thể khỏi bảng
        function deleteVariantRow(button) {
            const row = button.closest('tr');
            // Bạn có thể thêm một trường ẩn để đánh dấu biến thể này để xóa ở backend
            // Ví dụ: <input type="hidden" name="variants_to_delete[]" value="${variantId}">
            row.remove();
            console.log('Đã xóa một dòng biến thể khỏi DOM.');
        }


        document.addEventListener('DOMContentLoaded', function () {
            // Tải các thuộc tính có sẵn vào modal khi DOM được tải
            loadAttributes();

            // Tải ban đầu các biến thể hiện có vào bảng
            populateSelectedAttributesFromVariants(); // Điền selectedAttributes dựa trên existingVariants
            handleSave(); // Điền bảng với các biến thể, sử dụng existingVariants để điền trước dữ liệu

            const form = document.getElementById('edit-variant-form');

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (currentEditingIndex === null) return;

                const row = document.querySelector(`tr[data-index="${currentEditingIndex}"]`); // Fix: Use backticks
                if (!row) return;

                // Lấy dữ liệu từ modal
                const sku = document.getElementById('edit-variant-sku').value;
                const quantity = document.getElementById('edit-variant-quantity').value;
                const price = document.getElementById('edit-variant-price').value;
                const salePrice = document.getElementById('edit-variant-sale_price').value;
                const stockStatus = document.getElementById('edit-variant-stock_status').value;
                const description = document.getElementById('edit-variant-description').value;
                const newImageFile = document.getElementById('edit-variant-image').files[0];
                const deleteCurrentImage = document.getElementById('edit-variant-image-delete').checked;

                // Cập nhật các trường ẩn trong hàng bảng chính
                row.querySelector(`input[name="variants[${currentEditingIndex}][sku]"]`).value = sku; // Fix: Use backticks
                row.querySelector(`input[name="variants[${currentEditingIndex}][quantity]"]`).value = quantity; // Fix: Use backticks
                row.querySelector(`input[name="variants[${currentEditingIndex}][price]"]`).value = price; // Fix: Use backticks
                row.querySelector(`input[name="variants[${currentEditingIndex}][sale_price]"]`).value = salePrice; // Fix: Use backticks
                row.querySelector(`input[name="variants[${currentEditingIndex}][stock_status]"]`).value = stockStatus; // Fix: Use backticks
                row.querySelector(`input[name="variants[${currentEditingIndex}][description]"]`).value = description; // Fix: Use backticks

                // Xử lý hình ảnh:
                const imagePathOnlyInput = row.querySelector(`input[name="variants[${currentEditingIndex}][image_path_only]"]`); // Fix: Use backticks
                const fileInputForUpload = document.getElementById(`variant-image-upload-${currentEditingIndex}`); // Input file ẩn thực sự của hàng này // Fix: Use backticks

                if (deleteCurrentImage) {
                    // Nếu người dùng chọn xóa ảnh
                    imagePathOnlyInput.value = ''; // Xóa đường dẫn ảnh hiện tại
                    if (fileInputForUpload) {
                        fileInputForUpload.value = ''; // Đảm bảo input file rỗng
                    }
                    // Thêm một trường ẩn để báo hiệu cho backend biết cần xóa ảnh
                    let removeImageFlag = row.querySelector(`input[name="variants[${currentEditingIndex}][image_remove]"]`); // Fix: Use backticks
                    if (!removeImageFlag) {
                        removeImageFlag = document.createElement('input');
                        removeImageFlag.type = 'hidden';
                        removeImageFlag.name = `variants[${currentEditingIndex}][image_remove]`; // Fix: Use backticks
                        row.appendChild(removeImageFlag);
                    }
                    removeImageFlag.value = '1';
                } else if (newImageFile) {
                    // Nếu có tệp mới được chọn, gán nó vào input file ẩn của hàng
                    if (fileInputForUpload) {
                        // Tạo một DataTransfer để thêm tệp vào input type="file"
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(newImageFile);
                        fileInputForUpload.files = dataTransfer.files;
                    }
                    // Cập nhật đường dẫn ảnh tạm thời để hiển thị nếu cần (hoặc dựa vào backend)
                    // Ở đây, chúng ta đặt một cờ để biết có ảnh mới được chọn.
                    imagePathOnlyInput.value = 'TEMP_NEW_IMAGE'; // Đặt một giá trị tạm thời để biết có ảnh mới
                    // Đảm bảo cờ xóa không được đặt nếu có ảnh mới
                    let removeImageFlag = row.querySelector(`input[name="variants[${currentEditingIndex}][image_remove]"]`); // Fix: Use backticks
                    if (removeImageFlag) {
                        removeImageFlag.remove();
                    }
                } else {
                    // Không có tệp mới và không xóa, đảm bảo cờ xóa không được đặt và không có tệp nào được gán
                    let removeImageFlag = row.querySelector(`input[name="variants[${currentEditingIndex}][image_remove]"]`); // Fix: Use backticks
                    if (removeImageFlag) {
                        removeImageFlag.remove();
                    }
                    if (fileInputForUpload) {
                        fileInputForUpload.value = ''; // Đảm bảo không có tệp nào được gán nếu không chọn mới
                    }
                    // Giữ nguyên image_path_only hiện có nếu không có thay đổi
                }


                // Đóng modal và đặt lại biểu mẫu
                $('#editVariantModal').modal('hide');
                form.reset();
                document.getElementById('edit-variant-image').value = ''; // Xóa input tệp
                document.getElementById('edit-variant-image-preview').src = ''; // Xóa xem trước hình ảnh
                document.getElementById('edit-variant-image-preview').style.display = 'none'; // Ẩn xem trước
                document.getElementById('edit-variant-image-delete').checked = false;
                document.getElementById('edit-variant-image-delete-container').style.display = 'none';
                currentEditingIndex = null;

                console.log('✔️ Hàng biến thể đã được cập nhật từ modal.');
            });

            // Xử lý xem trước hình ảnh trong modal chỉnh sửa
            document.getElementById('edit-variant-image').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const preview = document.getElementById('edit-variant-image-preview');
                const deleteImageCheckboxContainer = document.getElementById('edit-variant-image-delete-container');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                    deleteImageCheckboxContainer.style.display = 'none'; // Ẩn checkbox xóa nếu chọn ảnh mới
                    document.getElementById('edit-variant-image-delete').checked = false; // Bỏ chọn xóa ảnh
                } else {
                    // Nếu không có tệp nào được chọn (người dùng hủy chọn tệp)
                    // Cố gắng hiển thị lại ảnh hiện có (nếu có)
                    const row = document.querySelector(`tr[data-index="${currentEditingIndex}"]`); // Fix: Use backticks
                    if (row) {
                        const imagePathOnlyInput = row.querySelector(`input[name="variants[${currentEditingIndex}][image_path_only]"]`); // Fix: Use backticks
                        if (imagePathOnlyInput && imagePathOnlyInput.value && imagePathOnlyInput.value !== 'TEMP_NEW_IMAGE') {
                            preview.src = `/storage/${imagePathOnlyInput.value}`; // Fix: Use backticks
                            preview.style.display = 'block';
                            deleteImageCheckboxContainer.style.display = 'block'; // Hiển thị checkbox xóa
                        } else {
                            preview.src = '';
                            preview.style.display = 'none';
                            deleteImageCheckboxContainer.style.display = 'none';
                        }
                    } else {
                        preview.src = '';
                        preview.style.display = 'none';
                        deleteImageCheckboxContainer.style.display = 'none';
                    }
                    document.getElementById('edit-variant-image-delete').checked = false; // Đảm bảo bỏ chọn
                }
            });

            // Xử lý sự kiện khi checkbox xóa ảnh được click
            document.getElementById('edit-variant-image-delete').addEventListener('change', function() {
                const preview = document.getElementById('edit-variant-image-preview');
                const fileInput = document.getElementById('edit-variant-image');
                if (this.checked) {
                    preview.src = '';
                    preview.style.display = 'none';
                    fileInput.value = ''; // Đảm bảo không có tệp nào được chọn
                } else {
                    // Nếu bỏ chọn xóa, hãy cố gắng hiển thị lại ảnh hiện có (nếu có)
                    const row = document.querySelector(`tr[data-index="${currentEditingIndex}"]`); // Fix: Use backticks
                    if (row) {
                        const imagePathOnlyInput = row.querySelector(`input[name="variants[${currentEditingIndex}][image_path_only]"]`); // Fix: Use backticks
                        if (imagePathOnlyInput && imagePathOnlyInput.value && imagePathOnlyInput.value !== 'TEMP_NEW_IMAGE') {
                            preview.src = `/storage/${imagePathOnlyInput.value}`; // Fix: Use backticks
                            preview.style.display = 'block';
                        }
                    }
                }
            });
        });

    </script>
@endsection


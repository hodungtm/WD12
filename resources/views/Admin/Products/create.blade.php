@extends('Admin.Layouts.AdminLayout')
@section('main')

<h1>Thêm sản phẩm</h1>

<form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Tên sản phẩm</label>
        <input id="name" name="name" class="form-control" value="{{ old('name') }}">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="brand_id" class="form-label">Thương hiệu</label>
        <select id="brand_id" name="brand_id" class="form-select">
            <option value="">-- Chọn --</option>
            @foreach ($brands as $brand)
            <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
            @endforeach
        </select>
        @error('brand_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Danh mục</label>
        <select id="category_id" name="category_id" class="form-select">
            <option value="">-- Chọn --</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>
                {{ $category->ten_danh_muc }}
            </option>
            @endforeach
        </select>
        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Giá</label>
        <input id="price" name="price" class="form-control" value="{{ old('price') }}">
        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    {{-- Đã XÓA DÒNG NÀY (Số lượng cho sản phẩm chính) vì nó sẽ được quản lý qua Variants --}}
    {{-- Nếu bạn muốn trường số lượng chính, hãy thêm lại nó và xử lý riêng trong controller --}}

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea id="description" name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Ảnh sản phẩm</label>
        <input id="image" name="image" class="form-control" type="file">
        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="card p-3 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#manageAttributeModal">
                <i class="bi bi-sliders"></i> Quản lý thuộc tính
            </button>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productAttributeModal"
                onclick="loadAttributes()">
                <i class="bi bi-gear"></i> Thuộc tính cho sản phẩm này
            </button>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Thuộc tính <i class="bi bi-question-circle" title="Các thuộc tính của biến thể"></i></th>
                    <th>Mã phiên bản (SKU)</th>
                    <th>Số lượng</th> {{-- THÊM CỘT SỐ LƯỢNG VÀO ĐÂY --}}
                    <th>Tác vụ</th>
                </tr>
            </thead>
            <tbody id="variant-table-body">
                {{-- Dòng mặc định này sẽ bị xóa/ghi đè khi có các biến thể được tạo --}}
                {{-- Nếu bạn muốn giữ lại 1 dòng mặc định, bạn cần đảm bảo nó có đủ các input hidden cần thiết --}}
                <tr data-index="0"> {{-- Thêm data-index để JS có thể tìm thấy --}}
                    <td>1</td> {{-- Thay đổi từ 0 thành 1 để STT bắt đầu từ 1 --}}
                    <td>Mặc định
                        <input type="hidden" name="variants[0][attribute_text]" value="Mặc định">
                        {{-- Cần thêm input hidden cho price, sale_price, quantity, etc. nếu muốn mặc định --}}
                        <input type="hidden" name="variants[0][price]" value="{{ old('variants.0.price') ?? 0 }}">
                        <input type="hidden" name="variants[0][sale_price]" value="{{ old('variants.0.sale_price') ?? null }}">
                        <input type="hidden" name="variants[0][stock_status]" value="{{ old('variants.0.stock_status') ?? 'in_stock' }}">
                        <input type="hidden" name="variants[0][description]" value="{{ old('variants.0.description') ?? '' }}">
                        <input type="hidden" name="variants[0][image]" value="{{ old('variants.0.image') ?? '' }}">
                        {{-- Thêm input hidden cho attribute_value_ids rỗng hoặc không có nếu là mặc định --}}
                        <input type="hidden" name="variants[0][attribute_value_ids][]" value="">
                    </td>
                    <td><input type="text" name="variants[0][sku]" class="form-control" value="{{ old('variants.0.sku') ?? '' }}"></td>
                    <td><input type="number" name="variants[0][quantity]" class="form-control" min="0" value="{{ old('variants.0.quantity') ?? 0 }}"></td> {{-- THÊM INPUT SỐ LƯỢNG CHO MẶC ĐỊNH --}}
                    <td>
                        {{-- Nút sửa/xóa cho dòng mặc định nếu cần --}}
                        <button type="button" class="btn btn-sm btn-primary" onclick="editDefaultVariant(this)" data-index="0">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <button class="btn btn-primary mt-4">Thêm sản phẩm</button>
</form>

{{-- MODAL QUẢN LÝ THUỘC TÍNH (nếu có, không có trong code bạn cung cấp nhưng nút có refer đến) --}}
{{-- Đảm bảo bạn có modal này trong thực tế --}}
<div class="modal fade" id="manageAttributeModal" tabindex="-1" aria-labelledby="manageAttributeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageAttributeModalLabel">Quản lý thuộc tính</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Nơi quản lý các thuộc tính chung của sản phẩm (ví dụ: Thêm/Sửa/Xóa thuộc tính và giá trị của chúng).</p>
                <p>Bạn sẽ cần triển khai AJAX hoặc form riêng cho chức năng này.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="productAttributeModal" tabindex="-1" aria-labelledby="productAttributeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thuộc tính cho sản phẩm này</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold">Danh sách thuộc tính</h6>
                        <div id="attribute-list">
                            {{-- Content generated by JS --}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Danh sách thuộc tính đã chọn</h6>
                        <div id="selected-attributes">
                            {{-- Content generated by JS --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="handleSave()">Lưu dữ liệu</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editVariantModal" tabindex="-1" role="dialog" aria-labelledby="editVariantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="edit-variant-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVariantModalLabel">Sửa phiên bản sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> {{-- Changed data-dismiss to data-bs-dismiss --}}
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Mã sản phẩm (SKU)</label>
                        <input type="text" class="form-control" name="sku" id="edit-variant-sku">
                    </div>
                    <div class="form-group mb-3">
                        <label>Giá thường (bắt buộc)</label>
                        <input type="number" class="form-control" name="price" id="edit-variant-price" min="0">
                    </div>
                    <div class="form-group mb-3">
                        <label>Giá ưu đãi</label>
                        <input type="number" class="form-control" name="sale_price" id="edit-variant-sale-price" min="0">
                    </div>
                    <div class="form-group mb-3">
                        <label>Số lượng</label> {{-- THÊM TRƯỜNG SỐ LƯỢNG VÀO MODAL --}}
                        <input type="number" class="form-control" name="quantity" id="edit-variant-quantity" min="0">
                    </div>
                    <div class="form-group mb-3">
                        <label>Trạng thái kho hàng</label>
                        <select class="form-control" name="stock_status" id="edit-variant-stock-status">
                            <option value="in_stock">Còn hàng</option>
                            <option value="out_of_stock">Hết hàng</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Mô tả</label>
                        <textarea class="form-control" name="description" id="edit-variant-description"></textarea>
                    </div>
                    <div class="form-group text-center mb-3">
                        <label>Ảnh phiên bản</label>
                        <div>
                            <img id="edit-variant-image-preview" src="" alt="Ảnh phiên bản"
                                style="max-width:120px;max-height:120px;display:block;margin:auto;">
                        </div>
                        <input type="file" class="form-control-file mt-2" name="image" id="edit-variant-image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button> {{-- Changed data-dismiss to data-bs-dismiss --}}
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const attributesFromServer = @json($attributes); // Dữ liệu thuộc tính từ server
    const selectedAttributes = {}; // Lưu trữ các thuộc tính đã chọn
    let currentEditingIndex = null; // Biến để theo dõi chỉ mục của variant đang chỉnh sửa

    // Hàm tải và hiển thị các thuộc tính có sẵn
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

    // Hàm chọn một giá trị thuộc tính
    function selectAttribute(group, value) {
        if (!selectedAttributes[group]) selectedAttributes[group] = [];
        if (!selectedAttributes[group].includes(value)) {
            selectedAttributes[group].push(value);
            renderSelectedAttributes();
        }
    }

    // Hàm xóa một giá trị thuộc tính
    function removeAttribute(group, value) {
        if (selectedAttributes[group]) {
            selectedAttributes[group] = selectedAttributes[group].filter(val => val !== value);
            if (selectedAttributes[group].length === 0) delete selectedAttributes[group];
            renderSelectedAttributes();
        }
    }

    // Hàm hiển thị các thuộc tính đã chọn
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

    // Hàm tạo ra các tổ hợp (biến thể) từ các thuộc tính đã chọn
    function generateCombinations(obj) {
        const keys = Object.keys(obj);
        if (keys.length === 0) return [];

        const combinations = [];

        function backtrack(index, current) {
            if (index === keys.length) {
                combinations.push([...current]);
                return;
            }

            const key = keys[index];
            for (const val of obj[key]) {
                current.push(`${val}`);
                backtrack(index + 1, current);
                current.pop();
            }
        }

        backtrack(0, []);
        return combinations;
    }

    // Hàm xử lý việc lưu các biến thể và cập nhật bảng
    function handleSave() {
        const tbody = document.getElementById('variant-table-body');
        tbody.innerHTML = ''; // Xóa các dòng cũ trước khi thêm mới

        const combinations = generateCombinations(selectedAttributes);

        // Nếu không có biến thể nào được chọn, hãy hiển thị dòng mặc định ban đầu
        if (combinations.length === 0) {
            const defaultTr = document.createElement('tr');
            defaultTr.dataset.index = 0; // Đảm bảo index 0 cho dòng mặc định

            // Lấy giá trị hiện có từ trường giá và mô tả chính
            const existingPrice = document.getElementById('price').value || 0;
            const existingDescription = document.getElementById('description').value || '';
            const existingQuantity = 0; // Mặc định là 0 khi không có biến thể

            defaultTr.innerHTML = `
                <td>1</td>
                <td>Mặc định
                    <input type="hidden" name="variants[0][attribute_text]" value="Mặc định">
                    <input type="hidden" name="variants[0][price]" value="${existingPrice}">
                    <input type="hidden" name="variants[0][sale_price]" value="">
                    <input type="hidden" name="variants[0][stock_status]" value="in_stock">
                    <input type="hidden" name="variants[0][description]" value="${existingDescription}">
                    <input type="hidden" name="variants[0][image]" value="">
                    <input type="hidden" name="variants[0][attribute_value_ids][]" value="">
                </td>
                <td><input type="text" name="variants[0][sku]" class="form-control" value="SKU-${Date.now()}-0"></td>
                <td><input type="number" name="variants[0][quantity]" class="form-control" min="0" value="${existingQuantity}"></td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" onclick="editDefaultVariant(this)" data-index="0">
                        <i class="bi bi-pencil"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(defaultTr);
        } else {
            combinations.forEach((combo, index) => {
                const tr = document.createElement('tr');
                tr.dataset.index = index;

                const tdIndex = document.createElement('td');
                tdIndex.textContent = index + 1;

                const tdAttr = document.createElement('td');
                const attrText = combo.join(' - ');
                tdAttr.textContent = attrText;
                const attrInput = document.createElement('input');
                attrInput.type = 'hidden';
                attrInput.name = `variants[${index}][attribute_text]`;
                attrInput.value = attrText;
                tdAttr.appendChild(attrInput);

                const tdSKU = document.createElement('td');
                const skuInput = document.createElement('input');
                skuInput.type = 'text';
                skuInput.classList.add('form-control');
                skuInput.name = `variants[${index}][sku]`;
                skuInput.value = `SKU-${Date.now()}-${index}`;
                tdSKU.appendChild(skuInput);

                const tdQuantity = document.createElement('td');
                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.classList.add('form-control');
                quantityInput.name = `variants[${index}][quantity]`;
                quantityInput.value = 0; // Mặc định số lượng là 0
                quantityInput.min = "0";
                tdQuantity.appendChild(quantityInput);

                // Thêm các input attribute_value_ids[] đúng chỗ
                const attrIds = combo.map(val => {
                    const match = attributesFromServer.flatMap(attr => attr.values)
                        .find(v => v.value === val);
                    return match ? match.id : null;
                }).filter(id => id !== null);

                attrIds.forEach(attrId => {
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = `variants[${index}][attribute_value_ids][]`;
                    idInput.value = attrId;
                    tdAttr.appendChild(idInput);
                });

                // Các hidden fields còn lại
                const hiddenFieldsContainer = document.createElement('div');
                hiddenFieldsContainer.innerHTML = `
                    <input type="hidden" name="variants[${index}][price]" value="${document.getElementById('price').value || 0}">
                    <input type="hidden" name="variants[${index}][sale_price]" value="">
                    <input type="hidden" name="variants[${index}][stock_status]" value="in_stock">
                    <input type="hidden" name="variants[${index}][description]" value="${document.getElementById('description').value || ''}">
                    <input type="hidden" name="variants[${index}][image]" value="">
                `;
                tdSKU.appendChild(hiddenFieldsContainer);

                const tdAction = document.createElement('td');
                const editBtn = document.createElement('button');
                editBtn.classList.add('btn', 'btn-sm', 'btn-primary');
                editBtn.innerHTML = '<i class="bi bi-pencil"></i>';
                editBtn.type = 'button';

                editBtn.onclick = () => {
                    currentEditingIndex = index;

                    // Lấy giá trị từ các input trong hàng hiện tại
                    document.getElementById('edit-variant-sku').value = skuInput.value;
                    document.getElementById('edit-variant-quantity').value = quantityInput.value;

                    const fields = ['price', 'sale_price', 'stock_status', 'description', 'image'];
                    fields.forEach(field => {
                        const hiddenInput = hiddenFieldsContainer.querySelector(`input[name="variants[${index}][${field}]"]`);
                        const modalInput = document.getElementById(`edit-variant-${field}`);
                        if (hiddenInput && modalInput) {
                            if (field === 'image') {
                                const imageUrl = hiddenInput.value;
                                document.getElementById('edit-variant-image-preview').src = imageUrl ? `/storage/${imageUrl}` : '';
                            } else {
                                modalInput.value = hiddenInput.value;
                            }
                        }
                    });

                    $('#editVariantModal').modal('show');
                };

                tdAction.appendChild(editBtn);

                const deleteBtn = document.createElement('button');
                deleteBtn.classList.add('btn', 'btn-sm', 'btn-danger', 'ms-2');
                deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
                deleteBtn.type = 'button';
                deleteBtn.onclick = () => tr.remove();
                tdAction.appendChild(deleteBtn);

                tr.appendChild(tdIndex);
                tr.appendChild(tdAttr);
                tr.appendChild(tdSKU);
                tr.appendChild(tdQuantity);
                tr.appendChild(tdAction);

                tbody.appendChild(tr);
            });
        }

        // Đóng modal sau khi xử lý xong
        $('#productAttributeModal').modal('hide');
        console.log('Đã sinh ra các variant:', combinations);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo giá trị mặc định
        const defaultSkuInput = document.querySelector('input[name="variants[0][sku]"]');
        if (defaultSkuInput && !defaultSkuInput.value) {
             defaultSkuInput.value = `SKU-${Date.now()}-0`;
        }
        const defaultQuantityInput = document.querySelector('input[name="variants[0][quantity]"]');
        if (defaultQuantityInput && !defaultQuantityInput.value) {
            defaultQuantityInput.value = 0;
        }

        const form = document.getElementById('edit-variant-form');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (currentEditingIndex === null) return;

            const row = document.querySelector(`tr[data-index="${currentEditingIndex}"]`);
            if (!row) return;

            const formData = new FormData(form);

            // Cập nhật trường SKU
            const skuInput = row.querySelector(`input[name="variants[${currentEditingIndex}][sku]"]`);
            if (skuInput) skuInput.value = formData.get('sku') || '';

            // Cập nhật trường Quantity
            const quantityInput = row.querySelector(`input[name="variants[${currentEditingIndex}][quantity]"]`);
            if (quantityInput) quantityInput.value = formData.get('quantity') || 0;

            // Cập nhật các hidden input khác
            const fields = ['price', 'sale_price', 'stock_status', 'description', 'image'];

            fields.forEach(field => {
                const input = row.querySelector(`input[name="variants[${currentEditingIndex}][${field}]"]`);
                if (!input) return;

                if (field === 'image') {
                    const file = formData.get('image');
                    if (file && file.name && file.size > 0) {
                        input.value = file.name;
                    }
                } else {
                    input.value = formData.get(field) || '';
                }
            });

            // Ẩn modal và reset form
            $('#editVariantModal').modal('hide');
            form.reset();
            document.getElementById('edit-variant-image-preview').src = '';
            currentEditingIndex = null;

            console.log('✔️ Đã cập nhật dòng variant:', Object.fromEntries(formData.entries()));
        });

        // Xử lý preview ảnh trong modal chỉnh sửa
        document.getElementById('edit-variant-image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('edit-variant-image-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('edit-variant-image-preview').src = '';
            }
        });
    });

    // Hàm chỉnh sửa dòng mặc định
    function editDefaultVariant(button) {
        const index = button.dataset.index;
        currentEditingIndex = parseInt(index);

        const row = document.querySelector(`tr[data-index="${index}"]`);
        if (!row) return;

        // Lấy giá trị từ các input trong hàng mặc định
        document.getElementById('edit-variant-sku').value = row.querySelector(`input[name="variants[${index}][sku]"]`).value;
        document.getElementById('edit-variant-quantity').value = row.querySelector(`input[name="variants[${index}][quantity]"]`).value;

        const fields = ['price', 'sale_price', 'stock_status', 'description', 'image'];
        fields.forEach(field => {
            const hiddenInput = row.querySelector(`input[name="variants[${index}][${field}]"]`);
            if (hiddenInput) {
                if (field === 'image') {
                    const imageUrl = hiddenInput.value;
                    document.getElementById('edit-variant-image-preview').src = imageUrl ? `/storage/${imageUrl}` : '';
                } else {
                    document.getElementById(`edit-variant-${field}`).value = hiddenInput.value;
                }
            }
        });

        $('#editVariantModal').modal('show');
    }

</script>

@endsection
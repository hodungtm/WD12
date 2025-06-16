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

    <div class="mb-3">
        <label for="quantity" class="form-label">Số lượng</label>
        <input id="quantity" name="quantity" class="form-control" value="{{ old('quantity') }}">
        @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

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
                    <th>Tác vụ</th>
                </tr>
            </thead>
            <tbody id="variant-table-body">
                <tr>
                    <td>0</td>
                    <td>Mặc định
                        <input type="hidden" name="variants[0][attribute_text]" value="Màu: Đỏ, Kích cỡ: L">
                    </td>
                    <td><input type="text" name="variants[0][sku]" class="form-control" value="SKU-{{ time() }}"></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <button class="btn btn-primary mt-4">Thêm sản phẩm</button>
</form>

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
                            </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Danh sách thuộc tính đã chọn</h6>
                        <div id="selected-attributes">
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

<div class="modal fade" id="editVariantModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="edit-variant-form">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa phiên bản sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mã sản phẩm</label>
                        <input type="text" class="form-control" name="sku" id="edit-variant-sku">
                    </div>
                    <div class="form-group">
                        <label>Giá thường (bắt buộc)</label>
                        <input type="number" class="form-control" name="price" id="edit-variant-price">
                    </div>
                    <div class="form-group">
                        <label>Giá ưu đãi</label>
                        <input type="number" class="form-control" name="sale_price" id="edit-variant-sale-price">
                    </div>
                    <div class="form-group">
                        <label>Trạng thái kho hàng</label>
                        <select class="form-control" name="stock_status" id="edit-variant-stock-status">
                            <option value="in_stock">Còn hàng</option>
                            <option value="out_of_stock">Hết hàng</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea class="form-control" name="description" id="edit-variant-description"></textarea>
                    </div>
                    <div class="form-group text-center">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const attributesFromServer = @json($attributes);
    const selectedAttributes = {};
    let currentEditingIndex = null;

    function loadAttributes() {
        const container = document.getElementById('attribute-list');
        container.innerHTML = '';

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

    function selectAttribute(group, value) {
        if (!selectedAttributes[group]) selectedAttributes[group] = [];
        if (!selectedAttributes[group].includes(value)) {
            selectedAttributes[group].push(value);
            renderSelectedAttributes();
        }
    }

    function removeAttribute(group, value) {
        if (selectedAttributes[group]) {
            selectedAttributes[group] = selectedAttributes[group].filter(val => val !== value);
            if (selectedAttributes[group].length === 0) delete selectedAttributes[group];
            renderSelectedAttributes();
        }
    }

    function renderSelectedAttributes() {
        const container = document.getElementById('selected-attributes');
        container.innerHTML = '';

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

function handleSave() {
    const tbody = document.getElementById('variant-table-body');
    tbody.innerHTML = '';

    const combinations = generateCombinations(selectedAttributes);

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

        // 🔥 Thêm các input attribute_value_ids[] đúng chỗ
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
            tdSKU.appendChild(idInput);
        });

        const hiddenFields = document.createElement('div');
        hiddenFields.innerHTML = `
            <input type="hidden" name="variants[${index}][price]">
            <input type="hidden" name="variants[${index}][sale_price]">
            <input type="hidden" name="variants[${index}][stock_status]">
            <input type="hidden" name="variants[${index}][description]">
            <input type="hidden" name="variants[${index}][image]">
        `;
        tdSKU.appendChild(hiddenFields);

        const tdAction = document.createElement('td');
        const editBtn = document.createElement('button');
        editBtn.classList.add('btn', 'btn-sm', 'btn-primary');
        editBtn.innerHTML = '<i class="bi bi-pencil"></i>';
        editBtn.type = 'button';

        editBtn.onclick = () => {
            currentEditingIndex = index;

            document.getElementById('edit-variant-sku').value = skuInput.value;
            const fields = ['price', 'sale_price', 'stock_status', 'description'];
            fields.forEach(field => {
                const hidden = hiddenFields.querySelector(`[name*="[${field}]"]`);
                const modalInput = document.getElementById(`edit-variant-${field}`);
                if (hidden && modalInput) {
                    modalInput.value = hidden.value;
                }
            });

            const imageInput = hiddenFields.querySelector(`[name*="[image]"]`);
            if (imageInput) {
                document.getElementById('edit-variant-image-preview').src = imageInput.value ? `/storage/${imageInput.value}` : '';
            }

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
        tr.appendChild(tdAction);

        tbody.appendChild(tr);
    });

    console.log('Đã sinh ra các variant:', combinations);
}


document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('edit-variant-form');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (currentEditingIndex === null) return;

            const row = document.querySelector(`tr[data-index="${currentEditingIndex}"]`);
            if (!row) return;

            const formData = new FormData(form);

            // Cập nhật trường SKU
            const skuInput = row.querySelector(`input[name="variants[${currentEditingIndex}][sku]"]`);
            skuInput.value = formData.get('sku') || '';

            // Cập nhật các hidden input khác
            const fields = ['price', 'sale_price', 'stock_status','description', 'image'];

            fields.forEach(field => {
                const input = row.querySelector(`input[name="variants[${currentEditingIndex}][${field}]"]`);
                if (!input) return;

                if (field === 'image') {
                    const file = formData.get('image');
                    if (file && file.name) {
                        input.value = file.name;

                        // Cập nhật lại ảnh preview nếu cần
                        const preview = document.getElementById('edit-variant-image-preview');
                        if (preview) {
                            preview.src = URL.createObjectURL(file);
                        }
                    } else {
                        input.value = '';
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

            // Log để kiểm tra
            console.log('✔️ Đã cập nhật dòng variant:', Object.fromEntries(formData.entries()));
        });
    });

    function addVariantRow(index, attributeText) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${index}</td>
        <td>${attributeText}</td>
        <td>
            <input type="text" name="variants[${index}][sku]" class="form-control" value="SKU-${Date.now()}-${index}">
            <input type="hidden" name="variants[${index}][attribute_text]" value="${attributeText}">
        </td>
        <td>
            </td>
    `;
    document.getElementById('variant-table-body').appendChild(tr);
}
</script>

@endsection
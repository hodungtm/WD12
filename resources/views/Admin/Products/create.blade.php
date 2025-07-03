@extends('admin.layouts.Adminlayout')

@section('main')
    <div class="app-title d-flex justify-content-between align-items-center mb-3">
        <ul class="app-breadcrumb breadcrumb mb-0">
            <li class="breadcrumb-item">Sản phẩm</li>
            <li class="breadcrumb-item active">Thêm sản phẩm mới</li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile card shadow-sm rounded-3 border-0">
                <div class="tile-body p-4">
                    <h3 class="tile-title mb-4">Thêm sản phẩm mới</h3>

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                        class="row">
                        @csrf

                        <!-- Tên sản phẩm -->
                        <div class="form-group col-md-6">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Danh mục -->
                        <div class="form-group col-md-6">
                            <label for="category_id" class="form-label">Danh mục</label>
                            <select name="category_id" id="category_id"
                                class="form-control">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group col-md-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Hình ảnh -->
                        <div class="form-group col-md-12">
                            <label class="form-label">Hình ảnh sản phẩm</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                            @error('images[]')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Size -->
                        <div class="form-group col-md-12">
                            <label class="form-label">Chọn Size</label>
                            <div>
                                <label class="me-3">
                                    <input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)"> Chọn
                                    tất cả
                                </label>
                                @foreach ($sizes as $size)
                                    <label class="me-3">
                                        <input type="checkbox" name="sizes[]" value="{{ $size->id }}">
                                        {{ $size->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Màu -->
                        <div class="form-group col-md-12">
                            <label class="form-label">Chọn Màu</label>
                            <div>
                                <label class="me-3">
                                    <input type="checkbox" id="select_all_colors" onchange="toggleAll('colors[]', this)">
                                    Chọn tất cả
                                </label>
                                @foreach ($colors as $color)
                                    <label class="me-3">
                                        <input type="checkbox" name="colors[]" value="{{ $color->id }}">
                                        {{ $color->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Giá chung -->
                        <div class="form-group col-md-4">
                            <label class="form-label">Giá Chung</label>
                            <input type="number" id="common_price" class="form-control" placeholder="Nhập giá VND">
                        </div>

                        <!-- Giá sale -->
                        <div class="form-group col-md-4">
                            <label class="form-label">Giá Sale Chung</label>
                            <input type="number" id="common_sale_price" class="form-control" placeholder="Nhập giá VND">
                        </div>

                        <!-- Số lượng -->
                        <div class="form-group col-md-4">
                            <label class="form-label">Số lượng Chung</label>
                            <input type="number" id="common_quantity" class="form-control" placeholder="Nhập số lượng">
                        </div>

                        <!-- Nút tạo biến thể -->
                        <div class="form-group col-md-12">
                            <button type="button" class="btn btn-outline-success mt-2" onclick="generateVariants()">
                                <i class="fas fa-plus me-1"></i> Tạo biến thể
                            </button>
                            <div id="variant_error" class="text-danger mt-2 fw-bold"></div>
                        </div>

                        <!-- Danh sách biến thể -->
                        <div class="form-group col-md-12 mt-3">
                            <h5>Danh sách biến thể:</h5>
                            <button type="button" class="btn btn-outline-danger btn-sm mb-2"
                                onclick="deleteSelectedVariants()">
                                <i class="fas fa-trash-alt me-1"></i> Xoá các biến thể đã chọn
                            </button>

                            <table class="table table-bordered" id="variant_table">
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

                        <!-- Nút lưu -->
                        <div class="form-group col-md-12 text-end mt-3">
                            <button type="submit" class="btn btn-outline-success"><i class="fas fa-save me-1"></i> Lưu sản
                                phẩm</button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-danger"><i
                                    class="fas fa-times me-1"></i> Hủy bỏ</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        function toggleAll(name, sourceCheckbox) {
            document.querySelectorAll(`input[name="${name}"]`).forEach(cb => {
                cb.checked = sourceCheckbox.checked;
            });
        }

       function generateVariants() {
    const errorBox = document.getElementById('variant_error');
    errorBox.innerText = ''; 

    let selectedSizes = document.querySelectorAll('input[name="sizes[]"]:checked');
    let selectedColors = document.querySelectorAll('input[name="colors[]"]:checked');
    let price = document.getElementById('common_price').value;
    let sale_price = document.getElementById('common_sale_price').value;
    let quantity = document.getElementById('common_quantity').value;
    let tableBody = document.getElementById('variant_table').querySelector('tbody');

    if (selectedSizes.length === 0) {
        errorBox.innerText = 'Vui lòng chọn ít nhất 1 size.';
        return;
    }

    if (selectedColors.length === 0) {
        errorBox.innerText = 'Vui lòng chọn ít nhất 1 màu.';
        return;
    }
    if (parseFloat(sale_price) >= parseFloat(price)) {
        errorBox.innerText = 'Giá sale phải nhỏ hơn giá gốc.';
        return;
    }

    selectedSizes.forEach(size => {
        selectedColors.forEach(color => {
            if (!variantExists(size.value, color.value)) {
                let row = `
                <tr>
                    <td><input type="checkbox" class="variant_checkbox"></td>
                    <td>
                        <input type="hidden" name="variant_sizes[]" value="${size.value}">
                        ${size.nextSibling.nodeValue.trim()}
                    </td>
                    <td>
                        <input type="hidden" name="variant_colors[]" value="${color.value}">
                        ${color.nextSibling.nodeValue.trim()}
                    </td>
                    <td>
                        <input type="number" name="variant_prices[]" class="form-control" value="${price}">
                    </td>
                    <td>
                        <input type="number" name="variant_sale_prices[]" class="form-control" value="${sale_price}">
                    </td>
                    <td>
                        <input type="number" name="variant_quantities[]" class="form-control" value="${quantity}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">
                            <i class="fas fa-trash-alt"></i> Xóa
                        </button>
                    </td>
                </tr>`;
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
                if (sizeVal == sizeId && colorVal == colorId) {
                    return true;
                }
            }
            return false;
        }

        function toggleAllVariants(sourceCheckbox) {
            document.querySelectorAll('.variant_checkbox').forEach(cb => {
                cb.checked = sourceCheckbox.checked;
            });
        }

        function deleteSelectedVariants() {
            document.querySelectorAll('.variant_checkbox:checked').forEach(cb => {
                cb.closest('tr').remove();
            });
        }
    </script>
@endsection

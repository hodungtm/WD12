@extends('Admin.Layouts.AdminLayout')
@section('main')
<<<<<<< HEAD

<!-- Breadcrumb -->
<div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
        <li class="breadcrumb-item active">Chỉnh sửa sản phẩm</li>
    </ul>
</div>

<!-- Form sửa sản phẩm -->
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Chỉnh sửa sản phẩm</h3>

            <!-- Hiển thị lỗi -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Tên sản phẩm -->
                    <div class="form-group col-md-6">
                        <label class="control-label">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <!-- Danh mục -->
                    <div class="form-group col-md-6">
                        <label class="control-label">Danh mục</label>
                        <select name="category_id" class="form-control" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mô tả -->
                    <div class="form-group col-md-12">
                        <label class="control-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Thêm ảnh mới -->
                    <div class="form-group col-md-12">
                        <label class="control-label">Thêm ảnh mới</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>

                    <!-- Size -->
                    <div class="form-group col-md-12">
                        <label class="control-label">Chọn Size</label>
                        <div>
                            <label class="me-3">
                                <input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)"> Chọn tất cả
                            </label>
                            @foreach ($sizes as $size)
                                <label class="me-3">
                                    <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                                    {{ in_array($size->id, $selectedSizes ?? []) ? 'checked' : '' }}>
                                    {{ $size->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Màu -->
                    <div class="form-group col-md-12">
                        <label class="control-label">Chọn Màu</label>
                        <div>
                            <label class="me-3">
                                <input type="checkbox" id="select_all_colors" onchange="toggleAll('colors[]', this)"> Chọn tất cả
                            </label>
                            @foreach ($colors as $color)
                                <label class="me-3">
                                    <input type="checkbox" name="colors[]" value="{{ $color->id }}"
                                    {{ in_array($color->id, $selectedColors ?? []) ? 'checked' : '' }}>
                                    {{ $color->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Giá & số lượng mặc định -->
                    <div class="form-group col-md-6">
                        <label class="control-label">Giá mặc định cho biến thể</label>
                        <input type="text" id="default_price" class="form-control" placeholder="Nhập giá VND">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Số lượng mặc định cho biến thể</label>
                        <input type="number" id="default_quantity" class="form-control" placeholder="Nhập số lượng">
                    </div>

                    <!-- Nút tạo biến thể -->
                    <div class="form-group col-md-12">
                        <button type="button" class="btn btn-outline-success" onclick="generateVariants()">Tạo biến thể</button>
                    </div>

                    <!-- Danh sách biến thể -->
                    <div class="form-group col-md-12">
                        <h5>Danh sách biến thể:</h5>
                        <button type="button" class="btn btn-outline-danger btn-sm mb-2" onclick="deleteSelectedVariants()">Xóa các biến thể đã chọn</button>

                        <table class="table table-bordered" id="variant_table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all_variants" onchange="toggleAllVariants(this)"></th>
                                    <th>Size</th>
                                    <th>Màu</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $variant)
                                    <tr>
                                        <td><input type="checkbox" class="variant_checkbox"></td>
                                        <td>
                                            <input type="hidden" name="variant_sizes[]" value="{{ $variant->size_id }}">
                                            {{ $variant->size->name ?? '' }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="variant_colors[]" value="{{ $variant->color_id }}">
                                            {{ $variant->color->name ?? '' }}
                                        </td>
                                        <td>
                                            <input type="text" name="variant_prices[]" class="form-control" value="{{ $variant->price }}">
                                        </td>
                                        <td>
                                            <input type="number" name="variant_quantities[]" class="form-control" value="{{ $variant->quantity }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Nút lưu -->
                    <div class="form-group col-md-12 text-end mt-3">
                        <button type="submit" class="btn btn-outline-success"><i class="fas fa-save me-1"></i> Cập nhật sản phẩm</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i> Hủy bỏ</a>
                    </div>

                </div> <!-- row -->

            </form>
        </div>
    </div>
</div>

<!-- Ảnh hiện tại -->
@if($product->images->isNotEmpty())
    <div class="tile mt-4">
        <h5>Ảnh hiện tại:</h5>
        <div class="row">
            @foreach($product->images as $img)
                <div class="col-3 col-md-2 mb-3">
                    <div class="border rounded shadow-sm overflow-hidden p-1 text-center">
                        <img src="{{ asset('storage/' . $img->image) }}" class="img-fluid mb-2" style="object-fit: cover; width: 100%; height: 100px;">
                        <form action="{{ route('product-images.destroy', $img->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ảnh này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 mt-1">Xóa</button>
                        </form>
                    </div>
                </div>
            @endforeach
=======
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Chỉnh sửa sản phẩm</h4>
                    </div>
                    <div class="card-body">
                        <form id="product-form" action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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
                                            {{ $category->ten_danh_muc }}
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
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#productAttributeModal" onclick="loadAttributes()">
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
                                        {{-- Các hàng biến thể sẽ được JavaScript chèn/cập nhật vào đây --}}
                                        {{-- Dữ liệu ban đầu sẽ được JS populate --}}
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

    {{-- Modal quản lý thuộc tính sản phẩm --}}
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

    {{-- Modal chỉnh sửa chi tiết biến thể --}}
    <div class="modal fade" id="editVariantModal" tabindex="-1" aria-labelledby="editVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVariantModalLabel">Chỉnh sửa chi tiết biến thể</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-variant-form"> {{-- Không cần data-index ở đây, sẽ được quản lý qua currentEditingIndex --}}
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
                            <input type="file" class="form-control" id="edit-variant-image" name="image_file_upload">
                            <div class="mt-2">
                                <img id="edit-variant-image-preview" src="" alt="Variant Image Preview" style="max-width: 100px; border: 1px solid #ddd; padding: 5px; display:none;">
                            </div>
                            <div id="edit-variant-image-delete-container" class="form-check mt-2" style="display: none;">
                                <input class="form-check-input" type="checkbox" id="edit-variant-image-delete" name="delete_image">
                                <label class="form-check-label" for="edit-variant-image-delete">
                                    Xóa ảnh hiện tại
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
>>>>>>> main
        </div>
    </div>
@endif

<<<<<<< HEAD
<!-- JS -->
<script>
    function toggleAll(name, sourceCheckbox) {
        document.querySelectorAll(`input[name="${name}"]`).forEach(cb => {
            cb.checked = sourceCheckbox.checked;
        });
    }

    function generateVariants() {
        let selectedSizes = document.querySelectorAll('input[name="sizes[]"]:checked');
        let selectedColors = document.querySelectorAll('input[name="colors[]"]:checked');
        let price = document.getElementById('default_price').value;
        let quantity = document.getElementById('default_quantity').value;
        let tableBody = document.getElementById('variant_table').querySelector('tbody');

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
                                <input type="text" name="variant_prices[]" class="form-control" value="${price}">
                            </td>
                            <td>
                                <input type="number" name="variant_quantities[]" class="form-control" value="${quantity}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">Xóa</button>
                            </td>
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
=======
<script>
    // Dữ liệu thuộc tính từ server (tất cả các thuộc tính và giá trị)
    const attributesFromServer = @json($attributes);
    // Dữ liệu biến thể của sản phẩm hiện tại từ server
    let existingVariants = @json($product->variants->map(function($variant) {
        $variant->attribute_values = $variant->attributeValues->map(function($av) {
            return ['id' => $av->id, 'value' => $av->value, 'attribute_id' => $av->attribute_id];
        })->toArray();
        return $variant;
    }));

    // selectedAttributes sẽ chứa các thuộc tính (ví dụ: Màu, Size) và các giá trị (ví dụ: Đỏ, Xanh; S, M, L) mà người dùng đã chọn
    // Nó được dùng để tạo ra các tổ hợp (biến thể)
    let selectedAttributes = {};

    // Biến để theo dõi chỉ mục của variant đang chỉnh sửa trong modal
    let currentEditingIndex = null;

    // Map để lưu trữ các File object của ảnh mới được chọn cho từng biến thể (key là index của hàng, value là File object)
    // Cần thiết vì File object không thể lưu vào hidden input
    const newVariantImages = new Map();

    // Set để lưu trữ ID của các biến thể đã bị xóa khỏi bảng để gửi lên backend khi submit form chính
    const variantsToDeleteOnSubmit = new Set();

    // Biến instance của Bootstrap 5 Modals
    let productAttributeModalInstance;
    let editVariantModalInstance;

    // --- Hàm khởi tạo Bootstrap Modals và dữ liệu ban đầu ---
    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo Bootstrap 5 modal instances
        const productAttributeModalElement = document.getElementById('productAttributeModal');
        const editVariantModalElement = document.getElementById('editVariantModal');

        if (productAttributeModalElement) {
            productAttributeModalInstance = new bootstrap.Modal(productAttributeModalElement);
        }
        if (editVariantModalElement) {
            editVariantModalInstance = new bootstrap.Modal(editVariantModalElement);
        }

        // 1. Điền selectedAttributes từ các biến thể hiện có (nếu có)
        // Điều này đảm bảo khi mở modal quản lý thuộc tính, các lựa chọn trước đó đã được tải
        populateSelectedAttributesFromVariants();

        // 2. Render các biến thể hiện có vào bảng chính khi trang tải
        // handleSave() sẽ dùng selectedAttributes để tạo lại bảng,
        // đồng thời sẽ ưu tiên dữ liệu từ existingVariants nếu khớp combo
        handleSave(); // Initial render of variants into the table

        // --- Event Listener cho form chỉnh sửa biến thể trong modal ---
        const form = document.getElementById('edit-variant-form');
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Ngăn chặn submit form mặc định của modal
            if (currentEditingIndex === null) return;

            const row = document.querySelector(`tr[data-index="${currentEditingIndex}"]`);
            if (!row) return;

            // Lấy dữ liệu từ các input trong modal
            const sku = document.getElementById('edit-variant-sku').value;
            const quantity = document.getElementById('edit-variant-quantity').value;
            const price = document.getElementById('edit-variant-price').value;
            const salePrice = document.getElementById('edit-variant-sale_price').value;
            const stockStatus = document.getElementById('edit-variant-stock_status').value;
            const description = document.getElementById('edit-variant-description').value;
            const newImageFile = document.getElementById('edit-variant-image').files[0]; // Lấy đối tượng File
            const deleteCurrentImage = document.getElementById('edit-variant-image-delete').checked;

            // Cập nhật các trường input ẩn trong hàng của bảng chính
            row.querySelector(`input[name="variants[${currentEditingIndex}][sku]"]`).value = sku;
            row.querySelector(`input[name="variants[${currentEditingIndex}][quantity]"]`).value = quantity;
            row.querySelector(`input[name="variants[${currentEditingIndex}][price]"]`).value = price;
            row.querySelector(`input[name="variants[${currentEditingIndex}][sale_price]"]`).value = salePrice;
            row.querySelector(`input[name="variants[${currentEditingIndex}][stock_status]"]`).value = stockStatus;
            row.querySelector(`input[name="variants[${currentEditingIndex}][description]"]`).value = description;

            const imagePathOnlyInput = row.querySelector(`input[name="variants[${currentEditingIndex}][image_path_only]"]`);
            // Tìm hoặc tạo input hidden để đánh dấu xóa ảnh
            let imageRemoveFlagInput = row.querySelector(`input[name="variants[${currentEditingIndex}][image_remove]"]`);
            if (!imageRemoveFlagInput) {
                imageRemoveFlagInput = document.createElement('input');
                imageRemoveFlagInput.type = 'hidden';
                imageRemoveFlagInput.name = `variants[${currentEditingIndex}][image_remove]`;
                row.appendChild(imageRemoveFlagInput);
            }

            if (deleteCurrentImage) {
                // Nếu người dùng chọn xóa ảnh hiện tại
                imagePathOnlyInput.value = ''; // Xóa đường dẫn ảnh cũ
                imageRemoveFlagInput.value = '1'; // Đặt cờ xóa ảnh
                newVariantImages.delete(currentEditingIndex); // Xóa khỏi map nếu có ảnh mới đang chờ
            } else if (newImageFile) {
                // Nếu có file ảnh mới được chọn
                newVariantImages.set(currentEditingIndex, newImageFile); // Lưu File object vào Map
                imagePathOnlyInput.value = 'TEMP_NEW_IMAGE'; // Đặt một placeholder để backend biết có ảnh mới
                imageRemoveFlagInput.value = '0'; // Đảm bảo không đặt cờ xóa
            } else {
                // Không có file mới và không chọn xóa -> giữ nguyên ảnh cũ (nếu có)
                imageRemoveFlagInput.value = '0';
                // imagePathOnlyInput giữ nguyên giá trị cũ của nó
            }

            // Đóng modal và reset form
            if (editVariantModalInstance) editVariantModalInstance.hide();
            form.reset();
            document.getElementById('edit-variant-image').value = ''; // Clear actual file input
            document.getElementById('edit-variant-image-preview').src = '';
            document.getElementById('edit-variant-image-preview').style.display = 'none';
            document.getElementById('edit-variant-image-delete').checked = false;
            document.getElementById('edit-variant-image-delete-container').style.display = 'none';
            currentEditingIndex = null;

            console.log('✔️ Hàng biến thể đã được cập nhật từ modal.');
        });

        // Event listener cho input file trong modal edit variant để hiển thị preview
        document.getElementById('edit-variant-image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('edit-variant-image-preview');
            const deleteImageCheckboxContainer = document.getElementById('edit-variant-image-delete-container');
            const deleteImageCheckbox = document.getElementById('edit-variant-image-delete');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
                deleteImageCheckboxContainer.style.display = 'none'; // Ẩn checkbox xóa nếu có ảnh mới
                deleteImageCheckbox.checked = false; // Bỏ chọn checkbox xóa
            } else {
                // Nếu người dùng hủy chọn file (nhấn Cancel trong dialog)
                // Phục hồi trạng thái ảnh cũ hoặc ẩn nếu không có ảnh
                const row = document.querySelector(`tr[data-index="${currentEditingIndex}"]`);
                const imagePathOnlyInput = row?.querySelector(`input[name="variants[${currentEditingIndex}][image_path_only]"]`);
                const imageRemoveFlag = row?.querySelector(`input[name="variants[${currentEditingIndex}][image_remove]"]`);

                if (imageRemoveFlag?.value === '1') { // Nếu trước đó đã được đánh dấu xóa
                    preview.src = '';
                    preview.style.display = 'none';
                    deleteImageCheckboxContainer.style.display = 'block';
                    deleteImageCheckbox.checked = true;
                } else if (newVariantImages.has(currentEditingIndex)) { // Nếu có ảnh mới đang chờ
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(newVariantImages.get(currentEditingIndex));
                    deleteImageCheckboxContainer.style.display = 'none';
                    deleteImageCheckbox.checked = false;
                } else if (imagePathOnlyInput && imagePathOnlyInput.value && imagePathOnlyInput.value !== 'TEMP_NEW_IMAGE') { // Nếu có ảnh cũ từ DB
                    preview.src = `/storage/${imagePathOnlyInput.value}`;
                    preview.style.display = 'block';
                    deleteImageCheckboxContainer.style.display = 'block';
                    deleteImageCheckbox.checked = false;
                } else { // Không có ảnh nào
                    preview.src = '';
                    preview.style.display = 'none';
                    deleteImageCheckboxContainer.style.display = 'none';
                    deleteImageCheckbox.checked = false;
                }
            }
        });

        // Event listener cho checkbox xóa ảnh trong modal
        document.getElementById('edit-variant-image-delete').addEventListener('change', function(event) {
            const preview = document.getElementById('edit-variant-image-preview');
            if (event.target.checked) {
                preview.src = '';
                preview.style.display = 'none';
            } else {
                // Nếu bỏ chọn xóa, khôi phục ảnh hiện tại (nếu có)
                const row = document.querySelector(`tr[data-index="${currentEditingIndex}"]`);
                const imagePathOnlyInput = row?.querySelector(`input[name="variants[${currentEditingIndex}][image_path_only]"]`);

                if (newVariantImages.has(currentEditingIndex)) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(newVariantImages.get(currentEditingIndex));
                } else if (imagePathOnlyInput && imagePathOnlyInput.value && imagePathOnlyInput.value !== 'TEMP_NEW_IMAGE') {
                    preview.src = `/storage/${imagePathOnlyInput.value}`;
                    preview.style.display = 'block';
                }
            }
        });
    });


    // --- Hàm điền selectedAttributes từ dữ liệu biến thể hiện có (tải lúc đầu) ---
    function populateSelectedAttributesFromVariants() {
        // Chỉ điền selectedAttributes nếu có biến thể TỰ ĐỊNH NGHĨA (không phải chỉ biến thể Mặc định)
        const hasDefinedVariants = existingVariants.some(v => v.attribute_text !== 'Mặc định');

        if (!hasDefinedVariants && existingVariants.length > 0) {
            // Nếu chỉ có biến thể mặc định hoặc không có biến thể, selectedAttributes sẽ trống,
            // để khi bấm "Quản lý thuộc tính", nó hiển thị danh sách thuộc tính rỗng
            selectedAttributes = {};
            return;
        }

        const tempSelectedAttributes = {};
        existingVariants.forEach(variant => {
            if (variant.attribute_values && Array.isArray(variant.attribute_values)) {
                variant.attribute_values.forEach(attrValue => {
                    const parentAttribute = attributesFromServer.find(attr =>
                        attr.values.some(val => val.id === attrValue.id)
                    );
                    if (parentAttribute) {
                        if (!tempSelectedAttributes[parentAttribute.name]) {
                            tempSelectedAttributes[parentAttribute.name] = new Set();
                        }
                        tempSelectedAttributes[parentAttribute.name].add(JSON.stringify({ // Store stringified object to use Set for uniqueness
                            valueName: attrValue.value,
                            valueId: attrValue.id
                        }));
                    }
                });
            }
        });

        for (const attrName in tempSelectedAttributes) {
            selectedAttributes[attrName] = Array.from(tempSelectedAttributes[attrName]).map(item => JSON.parse(item));
        }

        renderSelectedAttributes(); // Render ngay sau khi điền
    }


    // --- Hàm tải và hiển thị các thuộc tính có sẵn ---
    function loadAttributes() {
        const container = document.getElementById('attribute-list');
        container.innerHTML = '';

        if (attributesFromServer.length === 0) {
            container.innerHTML = '<div class="alert alert-warning">Chưa có thuộc tính nào được định nghĩa. Vui lòng thêm thuộc tính trong mục "Quản lý thuộc tính".</div>';
            return;
        }

        attributesFromServer.forEach(attribute => {
            const groupDiv = document.createElement('div');
            groupDiv.classList.add('mb-3', 'border', 'rounded', 'p-3', 'shadow-sm-sm');

            const headerDiv = document.createElement('div');
            headerDiv.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');

            const title = document.createElement('h6');
            title.classList.add('fw-bold', 'mb-0');
            title.textContent = attribute.name;

            const btnGroup = document.createElement('button');
            btnGroup.classList.add('btn', 'btn-outline-dark', 'btn-sm');
            btnGroup.innerHTML = 'Chọn tất cả <i class="bi bi-arrow-right"></i>';
            btnGroup.type = 'button';
            btnGroup.onclick = () => {
                attribute.values.forEach(val => selectAttribute(attribute.name, val.value, val.id));
            };

            headerDiv.appendChild(title);
            headerDiv.appendChild(btnGroup);
            groupDiv.appendChild(headerDiv);

            if (attribute.values.length === 0) {
                const noValues = document.createElement('small');
                noValues.classList.add('text-muted');
                noValues.textContent = 'Chưa có giá trị nào.';
                groupDiv.appendChild(noValues);
            } else {
                attribute.values.forEach(value => {
                    const row = document.createElement('div');
                    row.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'py-1', 'border-bottom');

                    const valueText = document.createElement('span');
                    valueText.textContent = value.value;

                    const btn = document.createElement('button');
                    btn.classList.add('btn', 'btn-outline-primary', 'btn-sm');
                    btn.innerHTML = 'Chọn <i class="bi bi-arrow-right"></i>';
                    btn.type = 'button';
                    btn.onclick = () => selectAttribute(attribute.name, value.value, value.id);

                    row.appendChild(valueText);
                    row.appendChild(btn);
                    groupDiv.appendChild(row);
                });
            }
            container.appendChild(groupDiv);
        });
    }

    // --- Hàm để chọn một giá trị thuộc tính (updated to include valueId) ---
    function selectAttribute(groupName, valueName, valueId) {
        if (!selectedAttributes[groupName]) {
            selectedAttributes[groupName] = [];
        }
        if (!selectedAttributes[groupName].some(item => item.valueName === valueName && item.valueId === valueId)) {
            selectedAttributes[groupName].push({ valueName, valueId });
            renderSelectedAttributes();
        } else {
            console.log(`Attribute "${valueName}" in group "${groupName}" is already selected.`);
        }
    }

    // --- Hàm để xóa một giá trị thuộc tính (updated to remove by ID as well for precision) ---
    function removeAttribute(groupName, valueName, valueIdToRemove) {
        if (selectedAttributes[groupName]) {
            selectedAttributes[groupName] = selectedAttributes[groupName].filter(item => !(item.valueName === valueName && item.valueId === valueIdToRemove));
            if (selectedAttributes[groupName].length === 0) {
                delete selectedAttributes[groupName];
            }
            renderSelectedAttributes();
        }
    }

    // --- Hàm để hiển thị các thuộc tính đã chọn (updated to pass valueId to removeAttribute) ---
    function renderSelectedAttributes() {
        const container = document.getElementById('selected-attributes');
        container.innerHTML = '';

        const groups = Object.keys(selectedAttributes);
        if (groups.length === 0) {
            container.innerHTML = '<div class="alert alert-info text-center">Chưa có thuộc tính nào được chọn.</div>';
            return;
        }

        groups.forEach(groupName => {
            const groupDiv = document.createElement('div');
            groupDiv.classList.add('mb-3', 'border', 'rounded', 'p-3', 'bg-light');

            const title = document.createElement('h6');
            title.classList.add('fw-bold', 'mb-2');
            title.textContent = groupName;
            groupDiv.appendChild(title);

            selectedAttributes[groupName].forEach(item => {
                const row = document.createElement('div');
                row.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'py-1', 'border-bottom');

                const valueText = document.createElement('span');
                valueText.textContent = item.valueName;

                const btn = document.createElement('button');
                btn.classList.add('btn', 'btn-danger', 'btn-sm');
                btn.innerHTML = '<i class="bi bi-trash"></i> Hủy';
                btn.type = 'button';
                btn.onclick = () => removeAttribute(groupName, item.valueName, item.valueId);
                row.appendChild(valueText);
                row.appendChild(btn);
                groupDiv.appendChild(row);
            });
            container.appendChild(groupDiv);
        });
    }

    // --- Hàm tạo ra các tổ hợp (biến thể) từ các thuộc tính đã chọn ---
    function generateCombinations(obj) {
        const keys = Object.keys(obj);
        if (keys.length === 0) return []; // Nếu không có thuộc tính nào được chọn, trả về mảng rỗng

        const combinations = [];

        function backtrack(index, currentComboNames, currentComboIds) {
            if (index === keys.length) {
                combinations.push({
                    comboText: currentComboNames.join(' - '),
                    attrValueIds: [...currentComboIds]
                });
                return;
            }

            const key = keys[index];
            for (const item of obj[key]) { // item is now {valueName, valueId}
                currentComboNames.push(item.valueName);
                currentComboIds.push(item.valueId);
                backtrack(index + 1, currentComboNames, currentComboIds);
                currentComboNames.pop();
                currentComboIds.pop();
            }
        }
        backtrack(0, [], []);
        return combinations;
    }
>>>>>>> main

    // --- MODIFIED: Hàm xử lý việc lưu các biến thể và cập nhật bảng chính ---
    function handleSave() {
        const tbody = document.getElementById('variant-table-body');
        const currentTableRows = Array.from(tbody.querySelectorAll('tr')); // Lấy các hàng hiện tại

        const combinationsToRender = [];

        // Nếu không có thuộc tính nào được chọn, chỉ có một biến thể "Mặc định"
        if (Object.keys(selectedAttributes).length === 0) {
            // Tìm biến thể "Mặc định" trong dữ liệu ban đầu hoặc trong các hàng hiện có
            const defaultVariantData = existingVariants.find(v => v.attribute_text === 'Mặc định') ||
                                       currentTableRows.map(row => { // Check current table rows for a default
                                           const attrText = row.querySelector(`input[name$="[attribute_text]"]`)?.value;
                                           if (attrText === 'Mặc định') {
                                               return collectRowData(row);
                                           }
                                           return null;
                                       }).filter(Boolean)[0]; // Get the first default variant from current rows

            combinationsToRender.push({
                comboText: 'Mặc định',
                attrValueIds: [],
                existingData: defaultVariantData || null
            });
        } else {
            // Nếu có thuộc tính được chọn, tạo tổ hợp
            const generated = generateCombinations(selectedAttributes);
            generated.forEach(combo => {
                // Ưu tiên tìm biến thể khớp trong các hàng hiện tại của bảng (để giữ các chỉnh sửa tạm thời)
                const matchingInCurrentTable = currentTableRows.map(row => {
                    const collectedData = collectRowData(row);
                    if (collectedData && compareAttributeIds(collectedData.attribute_value_ids, combo.attrValueIds)) {
                        return collectedData;
                    }
                    return null;
                }).filter(Boolean)[0]; // Lấy biến thể khớp đầu tiên

                if (matchingInCurrentTable) {
                    combinationsToRender.push({
                        comboText: combo.comboText,
                        attrValueIds: combo.attrValueIds,
                        existingData: matchingInCurrentTable
                    });
                } else {
                    // Nếu không tìm thấy trong bảng hiện tại, tìm trong existingVariants ban đầu
                    const matchingInExisting = findMatchingVariantByAttributeIds(combo.attrValueIds);
                    combinationsToRender.push({
                        comboText: combo.comboText,
                        attrValueIds: combo.attrValueIds,
                        existingData: matchingInExisting
                    });
                }
            });
        }

        // Tái tạo lại toàn bộ tbody
        tbody.innerHTML = '';
        let currentMaxIndex = -1; // Để đảm bảo index mới luôn duy nhất và tăng dần

        combinationsToRender.forEach((comboData, i) => {
            const variantId = comboData.existingData ? comboData.existingData.id : '';
            // Gán một index mới duy nhất nếu đây là biến thể mới hoặc không có ID
            let rowIndex = comboData.existingData && comboData.existingData.id ?
                            currentTableRows.findIndex(row => row.dataset.variantId == comboData.existingData.id) : -1;

            if (rowIndex === -1) { // New or re-indexed variant (not found by ID in current rows)
                rowIndex = ++currentMaxIndex; // Use a continuously incrementing index
            } else { // Existing variant found in current rows, keep its original index
                rowIndex = parseInt(currentTableRows[rowIndex].dataset.index);
            }

            // Cập nhật currentMaxIndex nếu rowIndex hiện tại lớn hơn
            if (rowIndex > currentMaxIndex) {
                currentMaxIndex = rowIndex;
            }


            const sku = comboData.existingData ? comboData.existingData.sku : `SKU-${Date.now()}-${rowIndex}`;
            const quantity = comboData.existingData ? comboData.existingData.quantity : 0;
            const price = comboData.existingData ? comboData.existingData.price : (document.getElementById('price').value || 0);
            const salePrice = comboData.existingData ? (comboData.existingData.sale_price || '') : '';
            const stockStatus = comboData.existingData ? comboData.existingData.stock_status : 'in_stock';
            const description = comboData.existingData ? (comboData.existingData.description || '') : (document.getElementById('description').value || '');
            const imagePath = comboData.existingData ? (comboData.existingData.image || '') : ''; // Path từ DB
            const imageRemoveFlag = comboData.existingData ? (comboData.existingData.image_remove ? '1' : '0') : '0';


            const tr = document.createElement('tr');
            tr.dataset.index = rowIndex; // Dùng rowIndex đã được tính
            if (variantId) {
                tr.dataset.variantId = variantId; // Chỉ thêm variantId nếu có
            }


            tr.innerHTML = `
                <td>${rowIndex + 1}</td>
                <td>
                    ${comboData.comboText}
                    <input type="hidden" name="variants[${rowIndex}][id]" value="${variantId}">
                    <input type="hidden" name="variants[${rowIndex}][attribute_text]" value="${comboData.comboText}">
                    <input type="hidden" name="variants[${rowIndex}][price]" value="${price}">
                    <input type="hidden" name="variants[${rowIndex}][sale_price]" value="${salePrice}">
                    <input type="hidden" name="variants[${rowIndex}][stock_status]" value="${stockStatus}">
                    <input type="hidden" name="variants[${rowIndex}][description]" value="${description}">
                    <input type="hidden" name="variants[${rowIndex}][image_path_only]" value="${imagePath}">
                    <input type="hidden" name="variants[${rowIndex}][image_remove]" value="${imageRemoveFlag}">
                    ${comboData.attrValueIds.map(id => `<input type="hidden" name="variants[${rowIndex}][attribute_value_ids][]" value="${id}">`).join('')}
                </td>
                <td><input type="text" name="variants[${rowIndex}][sku]" class="form-control" value="${sku}"></td>
                <td><input type="number" name="variants[${rowIndex}][quantity]" class="form-control" min="0" value="${quantity}"></td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" onclick="editVariant(this)" data-index="${rowIndex}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    ${(combinationsToRender.length > 1 || comboData.comboText === 'Mặc định') ? `<button type="button" class="btn btn-sm btn-danger ms-2" onclick="deleteVariantRow(this)"><i class="bi bi-trash"></i></button>` : ''}
                </td>
            `;
            tbody.appendChild(tr);

            // Nếu có ảnh mới chờ upload cho biến thể này, cập nhật giá trị placeholder
            if (newVariantImages.has(rowIndex)) {
                 row.querySelector(`input[name="variants[${rowIndex}][image_path_only]"]`).value = 'TEMP_NEW_IMAGE';
            }
        });

        // Cập nhật lại currentMaxIndex để nó sẵn sàng cho lần thêm biến thể mới tiếp theo
        // (Đây chỉ quan trọng khi bạn có thể thêm biến thể mà không cần qua modal thuộc tính)
        // Nếu chỉ thêm qua modal thuộc tính, currentMaxIndex = nextIndex của generate_combinations
        // Sau khi render xong tất cả các hàng, maxIndex sẽ là số lượng hàng - 1
        const finalRows = document.querySelectorAll('#variant-table-body tr');
        currentMaxIndex = finalRows.length > 0 ? Array.from(finalRows).reduce((max, row) => Math.max(max, parseInt(row.dataset.index)), -1) : -1;


        // Đóng modal bằng instance của Bootstrap 5
        if (productAttributeModalInstance) productAttributeModalInstance.hide();
        console.log('✔️ Các biến thể đã được tạo/cập nhật trong bảng.');
    }


    // --- Hàm để chỉnh sửa một biến thể từ bảng chính (qua modal edit) ---
    function editVariant(button) {
        const row = button.closest('tr');
        const index = parseInt(row.dataset.index);
        currentEditingIndex = index;

        // Điền các trường modal từ các hidden input trong hàng
        document.getElementById('edit-variant-sku').value = row.querySelector(`input[name="variants[${index}][sku]"]`).value;
        document.getElementById('edit-variant-quantity').value = row.querySelector(`input[name="variants[${index}][quantity]"]`).value;
        document.getElementById('edit-variant-price').value = row.querySelector(`input[name="variants[${index}][price]"]`).value;
        document.getElementById('edit-variant-sale_price').value = row.querySelector(`input[name="variants[${index}][sale_price]"]`).value;
        document.getElementById('edit-variant-stock_status').value = row.querySelector(`input[name="variants[${index}][stock_status]"]`).value;
        document.getElementById('edit-variant-description').value = row.querySelector(`input[name="variants[${index}][description]"]`).value;

        const imagePathOnlyInput = row.querySelector(`input[name="variants[${index}][image_path_only]"]`);
        const imagePreview = document.getElementById('edit-variant-image-preview');
        const imageFileInput = document.getElementById('edit-variant-image');
        const imageDeleteContainer = document.getElementById('edit-variant-image-delete-container');
        const imageDeleteCheckbox = document.getElementById('edit-variant-image-delete');

        // IMPORTANT: Reset modal's file input AND its internal state
        imageFileInput.value = ''; // Clears selected file in browser UI
        imageDeleteCheckbox.checked = false; // Uncheck delete checkbox by default

        // Check if there's a new image already selected for this variant from a previous modal edit
        const newFileForThisVariant = newVariantImages.get(index);
        // Check the current image_remove flag value from the hidden input
        const isMarkedForRemoval = row.querySelector(`input[name="variants[${index}][image_remove]"]`)?.value === '1';


        if (isMarkedForRemoval) {
            imagePreview.src = '';
            imagePreview.style.display = 'none';
            imageDeleteCheckbox.checked = true;
            imageDeleteContainer.style.display = 'block';
        } else if (newFileForThisVariant) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                imageDeleteContainer.style.display = 'none';
            };
            reader.readAsDataURL(newFileForThisVariant);
        } else if (imagePathOnlyInput && imagePathOnlyInput.value && imagePathOnlyInput.value !== 'TEMP_NEW_IMAGE') {
            imagePreview.src = `/storage/${imagePathOnlyInput.value}`;
            imagePreview.style.display = 'block';
            imageDeleteContainer.style.display = 'block';
        } else {
            imagePreview.src = '';
            imagePreview.style.display = 'none';
            imageDeleteContainer.style.display = 'none';
        }

        if (editVariantModalInstance) editVariantModalInstance.show();
    }

    // --- Hàm để xóa một hàng biến thể khỏi bảng ---
    function deleteVariantRow(button) {
        const row = button.closest('tr');
        const index = parseInt(row.dataset.index); // Get the index of the row
        const variantId = row.dataset.variantId; // Get the variant ID if it exists

        // Nếu có ảnh mới đang chờ upload cho biến thể này, hãy xóa nó khỏi map
        newVariantImages.delete(index);

        // Nếu đây là biến thể đã tồn tại trong database (có ID), thêm vào danh sách xóa
        if (variantId) {
            variantsToDeleteOnSubmit.add(variantId);
            // Thêm một input hidden vào form chính để backend biết các ID cần xóa
            const form = document.getElementById('product-form');
            const deletedInput = document.createElement('input');
            deletedInput.type = 'hidden';
            deletedInput.name = 'variants_to_delete[]';
            deletedInput.value = variantId;
            form.appendChild(deletedInput);
        }

        row.remove(); // Xóa hàng khỏi DOM
        console.log('Đã xóa một dòng biến thể khỏi DOM.');
        // Sau khi xóa, bạn có thể gọi lại handleSave() để cập nhật lại bảng cho phù hợp
        // hoặc để logic này được xử lý khi submit form chính.
        // Tuy nhiên, việc xóa khỏi DOM là cần thiết ngay lập tức.
    }

    // --- Helper functions ---

    // So sánh hai mảng IDs (không quan tâm thứ tự)
    function compareAttributeIds(arr1, arr2) {
        if (!arr1 || !arr2) return false;
        if (arr1.length !== arr2.length) return false;
        const sortedArr1 = [...arr1].sort();
        const sortedArr2 = [...arr2].sort();
        return sortedArr1.every((val, index) => val === sortedArr2[index]);
    }

    // Thu thập dữ liệu từ một hàng cụ thể trong bảng
    function collectRowData(row) {
        const index = parseInt(row.dataset.index);
        return {
            id: row.querySelector(`input[name="variants[${index}][id]"]`)?.value || null,
            sku: row.querySelector(`input[name="variants[${index}][sku]"]`)?.value || '',
            quantity: row.querySelector(`input[name="variants[${index}][quantity]"]`)?.value || 0,
            price: row.querySelector(`input[name="variants[${index}][price]"]`)?.value || 0,
            sale_price: row.querySelector(`input[name="variants[${index}][sale_price]"]`)?.value || '',
            stock_status: row.querySelector(`input[name="variants[${index}][stock_status]"]`)?.value || 'in_stock',
            description: row.querySelector(`input[name="variants[${index}][description]"]`)?.value || '',
            attribute_text: row.querySelector(`input[name="variants[${index}][attribute_text]"]`)?.value || '',
            image: row.querySelector(`input[name="variants[${index}][image_path_only]"]`)?.value || '',
            image_remove: row.querySelector(`input[name="variants[${index}][image_remove]"]`)?.value === '1',
            attribute_value_ids: Array.from(row.querySelectorAll(`input[name="variants[${index}][attribute_value_ids][]"]`)).map(input => parseInt(input.value))
        };
    }

    // Tìm một biến thể hiện có từ `existingVariants` dựa trên attribute_value_ids
    function findMatchingVariantByAttributeIds(ids) {
        const sortedIds = [...ids].sort((a, b) => a - b).join(',');
        return existingVariants.find(v => {
            const variantAttrIds = v.attribute_values && Array.isArray(v.attribute_values) ?
                                    v.attribute_values.map(val => val.id).sort((a, b) => a - b).join(',') :
                                    '';
            return variantAttrIds === sortedIds;
        });
    }

    // --- Xử lý Submit form chính để thêm các file ảnh mới ---
    document.getElementById('product-form').addEventListener('submit', function(event) {
        // Tạo FormData mới từ form hiện có
        const formData = new FormData(this);

        // Thêm các file ảnh biến thể mới từ Map vào FormData
        newVariantImages.forEach((file, index) => {
            // Đảm bảo tên trường khớp với cách bạn xử lý trong Controller
            // Ví dụ: variants[index][image_file_upload]
            formData.append(`variants[${index}][image_file_upload]`, file, file.name);
        });

        // Nếu bạn cần gửi FormData qua AJAX, bạn sẽ làm ở đây:
        // event.preventDefault(); // Ngăn chặn submit mặc định
        // fetch(this.action, {
        //     method: this.method,
        //     body: formData
        // })
        // .then(response => response.json())
        // .then(data => console.log(data))
        // .catch(error => console.error('Error:', error));

        // Nếu bạn muốn submit form bình thường (có enctype="multipart/form-data"),
        // bạn cần thay thế form hiện tại bằng FormData này.
        // Cách đơn giản nhất là đảm bảo các file input trong modal chỉnh sửa
        // biến thể cũng có tên tương ứng để tự động được thêm vào FormData của form cha.
        // Với setup hiện tại, chúng ta đang ghi đè giá trị của input hidden
        // `variants[${index}][image_path_only]` thành 'TEMP_NEW_IMAGE',
        // và lưu `File` object trong `newVariantImages`.
        // Controller sẽ cần logic để nhận biết 'TEMP_NEW_IMAGE' và tìm file tương ứng.

        // Nếu bạn vẫn submit form qua HTML, bạn cần đảm bảo Laravel nhận được file:
        // CÁCH 1: DÙNG AJAX THAY THẾ (phức tạp hơn)
        // CÁCH 2: Thay đổi input type="file" trong modal thành một phần của form chính.
        //         HOẶC tạo một input file ẩn trong form chính và gán file cho nó
        //         trước khi submit.
        //         Cách hiện tại của bạn là tốt nhất cho việc submit form HTML thông thường:
        //         các input file trong modal thực sự là một phần của form đó,
        //         nhưng giá trị của chúng không được giữ lại khi modal đóng.
        //         Chúng ta đang lưu File object trong JS và cần append thủ công.

        // Vì bạn đang submit form chính, đoạn JS này không thực sự thay đổi cách form submit file.
        // Bạn sẽ cần xử lý `variants_to_delete` và các file `image_file_upload`
        // trong ProductController@update.

        // Để gửi các File objects, bạn sẽ cần một cách để gán chúng vào FormData của form chính.
        // Cách đơn giản nhất là khi bạn chỉnh sửa biến thể trong modal,
        // thay vì chỉ lưu tên file vào hidden input, bạn sẽ có một input file thật
        // trong hàng của bảng chính (ẩn đi) và gán File object đó vào.
        // Nhưng làm vậy sẽ phức tạp DOM.

        // Giải pháp hiện tại của bạn với `newVariantImages` Map là dành cho AJAX submit.
        // Nếu bạn vẫn muốn submit form HTML, bạn cần tạo các input file động:
        /*
        newVariantImages.forEach((file, index) => {
            const inputFile = document.createElement('input');
            inputFile.type = 'file';
            inputFile.name = `variants[${index}][image_file_upload]`;
            // Cần một DataTransfer object hoặc tương tự để gán File object vào input file
            // Điều này không dễ dàng với HTML form submission thông thường.
            // Nếu bạn muốn gửi file qua form HTML, bạn cần giữ input type="file" trong DOM
            // và đảm bảo nó được submit. Hoặc chuyển sang AJAX.
        });
        */

        // Vì `enctype="multipart/form-data"`, Laravel sẽ tự động xử lý file upload từ các input `type="file"`.
        // Vấn đề là input file trong modal không nằm trong form chính.
        // Một cách hacky là tạo input file ẩn trong form chính và gán file cho nó.
        // HOẶC: Thay đổi ProductController để nhận file ảnh biến thể theo một cách khác.
        // (Ví dụ: tách riêng endpoint upload ảnh, hoặc dùng AJAX cho toàn bộ form submit).

        // ĐỂ ĐƠN GIẢN HÓA VỚI FORM SUBMIT HTML, CHÚNG TA SẼ GIỮ CÁCH CỦA BẠN:
        // image_path_only='TEMP_NEW_IMAGE' và backend sẽ tìm file.
        // Backend cần biết `currentEditingIndex` để khớp file.
        // THƯỜNG THÌ, việc upload file riêng lẻ cho từng variant là phức tạp với FORM HTML submit.
        // NÊN CHUYỂN QUA AJAX CHO FORM CHÍNH.
        // Nhưng nếu vẫn dùng form HTML, bạn phải xử lý file upload trong controller theo kiểu
        // duyệt qua $request->allFiles() để tìm file cho từng variant.
    });

</script>
@endsection

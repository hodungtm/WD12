@extends('Admin.Layouts.AdminLayout')

@section('main')

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

                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Tên sản phẩm -->
                        <div class="form-group col-md-6">
                            <label class="control-label">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}"
                                required>
                        </div>

                        <!-- Danh mục -->
                        <div class="form-group col-md-6">
                            <label class="control-label">Danh mục</label>
                            <select name="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group col-md-12">
                            <label class="control-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="4"
                                required>{{ old('description', $product->description) }}</textarea>
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
                                    <input type="checkbox" id="select_all_sizes" onchange="toggleAll('sizes[]', this)"> Chọn
                                    tất cả
                                </label>
                                @foreach ($sizes as $size)
                                    <label class="me-3">
                                        <input type="checkbox" name="sizes[]" value="{{ $size->id }}" {{ in_array($size->id, $selectedSizes ?? []) ? 'checked' : '' }}>
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
                                    <input type="checkbox" id="select_all_colors" onchange="toggleAll('colors[]', this)">
                                    Chọn tất cả
                                </label>
                                @foreach ($colors as $color)
                                    <label class="me-3">
                                        <input type="checkbox" name="colors[]" value="{{ $color->id }}" {{ in_array($color->id, $selectedColors ?? []) ? 'checked' : '' }}>
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
                            <button type="button" class="btn btn-outline-success" onclick="generateVariants()">Tạo biến
                                thể</button>
                        </div>

                        <!-- Danh sách biến thể -->
                        <div class="form-group col-md-12">
                            <h5>Danh sách biến thể:</h5>
                            <button type="button" class="btn btn-outline-danger btn-sm mb-2"
                                onclick="deleteSelectedVariants()">Xóa các biến thể đã chọn</button>

                            <table class="table table-bordered" id="variant_table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all_variants"
                                                onchange="toggleAllVariants(this)"></th>
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
                                                <input type="text" name="variant_prices[]" class="form-control"
                                                    value="{{ $variant->price }}">
                                            </td>
                                            <td>
                                                <input type="number" name="variant_quantities[]" class="form-control"
                                                    value="{{ $variant->quantity }}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="this.closest('tr').remove()">
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
                            <button type="submit" class="btn btn-outline-success"><i class="fas fa-save me-1"></i> Cập nhật
                                sản phẩm</button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-danger"><i
                                    class="fas fa-times me-1"></i> Hủy bỏ</a>
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
                            <img src="{{ asset('storage/' . $img->image) }}" class="img-fluid mb-2"
                                style="object-fit: cover; width: 100%; height: 100px;">
                      // đi mà sửa ảnh       <form action="#" method="POST"  
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa ảnh này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100 mt-1">Xóa</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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

@endsection
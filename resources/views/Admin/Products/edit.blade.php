@extends('Admin.Layouts.AdminLayout')
@section('main')
<div class="app-title">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href=""><b>Sửa sản phẩm</b></a></li>
    </ul>
    <div id="clock"></div>
</div>

<div class="container">
    <h2>Sửa sản phẩm </h2>

    <form action="{{ route('Admin.products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Giá</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                    @error('price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Giá khuyến mãi</label>
                    <input type="number" name="sale_price" class="form-control"
                        value="{{ old('sale_price', $product->sale_price) }}">
                    @error('sale_price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Loại</label>
                    <select name="type" class="form-control" id="product_type">
                        <option value=""> -- Loại sản phẩm --</option>
                        <option value="shoes" {{ old('type', $product->type)=='shoes' ? 'selected' : '' }}>Giày thể thao
                        </option>
                        <option value="shirt" {{ old('type', $product->type)=='shirt' ? 'selected' : '' }}>Áo</option>
                        <option value="pants" {{ old('type', $product->type)=='pants' ? 'selected' : '' }}>Quần</option>
                    </select>
                    @error('type')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Thương hiệu</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                    @error('brand')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}">
                    @error('slug')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected' : '' }}>{{
                            $cat->ten_danh_muc }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Không hoạt động
                        </option>
                    </select>
                    @error('status')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Ảnh chính hiện tại</label><br>
                    @if($product->image_product)
                    <img src="{{ Storage::url($product->image_product) }}" alt="Ảnh chính" width="120"
                        style="margin-bottom:8px;">
                    @else
                    <span class="text-muted">Chưa có ảnh</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label>Chọn ảnh chính mới (nếu muốn thay)</label>
                    <input type="file" name="image_product" class="form-control">
                    @error('image_product')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Ảnh phụ hiện tại</label><br>
                    @if($product->images && count($product->images))
                    @foreach($product->images as $img)
                    <img src="{{ Storage::url($img->image_path) }}" alt="Ảnh phụ" width="80" style="margin:2px;">
                    @endforeach
                    @else
                    <span class="text-muted">Chưa có ảnh phụ</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label>Chọn ảnh phụ mới (nếu muốn thay)</label>
                    <input class="form-control" type="file" name="image_details[]" multiple>
                    @foreach($errors->get('image_details.*') as $messages)
                    @foreach($messages as $msg)
                    <div class="text-danger">{{ $msg }}</div>
                    @endforeach
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mb-3">
            <h5>Biến thể sản phẩm</h5>
            <div id="variant-container">
                @php
                $shirtSizes = ['S', 'M', 'L', 'XL', '2XL', '3XL'];
                $shoeSizes = ['37', '38', '39', '40', '41', '42', '43', '44', '45'];
                $colors = ['white', 'yellow', 'blue', 'red', 'black', 'green', 'pink'];
                $oldVariants = old('variant_quantity') !== null;
                @endphp

                @foreach($product->variants as $variantIndex => $variant)
                <div class="variant-group row mb-3 position-relative">
                    <!-- Size áo/quần -->
                    <div id="variant-size-text" class="col-md-4 mb-2 shirt-size-group" style="display:none">
                        <label><strong>Size:</strong></label>
                        <div class="d-flex flex-wrap gap-5 mt-1 me-3" style="gap: 15px">
                            @foreach($shirtSizes as $size)
                            @php
                            $selectedSize = $oldVariants
                            ? old('selected_size.' . $variantIndex)
                            : $variant->size;
                            $isActive = strtoupper($selectedSize) == strtoupper($size);
                            @endphp
                            <button type="button" class="btn btn-outline-secondary{{ $isActive ? ' active' : '' }}">
                                {{ $size }}
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="selected_size[]" class="selected_size"
                            value="{{ $oldVariants ? old('selected_size.' . $variantIndex) : $variant->size }}">
                        @error('selected_size.' . $variantIndex)
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Size giày -->
                    <div id="variant-size-shoes" class="col-md-4 mb-2 shoe-size-group" style="display:none">
                        <label><strong>Kích thước:</strong></label>
                        <div class="d-flex flex-wrap gap-5 mt-1 me-3" style="gap: 15px">
                            @foreach($shoeSizes as $size)
                            @php
                            $selectedSize = $oldVariants
                            ? old('selected_size.' . $variantIndex)
                            : $variant->size;
                            $isActive = $selectedSize == $size;
                            @endphp
                            <button type="button" class="btn btn-outline-secondary{{ $isActive ? ' active' : '' }}">
                                {{ $size }}
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="selected_size[]" class="selected_size"
                            value="{{ $oldVariants ? old('selected_size.' . $variantIndex) : $variant->size }}">
                        @error('selected_size.' . $variantIndex)
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Màu sắc -->
                    <div id="variant-color" class="col-md-4 mb-2">
                        <label><strong>Màu sắc:</strong></label>
                        <div class="d-flex flex-wrap gap-2 mt-1" style="gap: 15px">
                            @foreach($colors as $color)
                            @php
                            $selectedColor = $oldVariants
                            ? old('selected_color.' . $variantIndex)
                            : $variant->color;
                            $isSelected = strtolower($selectedColor) == strtolower($color);
                            @endphp
                            <div class="rounded-circle border"
                                style="width:30px; height:30px; background:{{ $color }}; cursor:pointer;{{ $isSelected ? 'border:3px solid #000;' : 'border:1px solid #ccc;' }}">
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="selected_color[]" class="selected_color"
                            value="{{ $oldVariants ? old('selected_color.' . $variantIndex) : $variant->color }}">
                        @error('selected_color.' . $variantIndex)
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Số lượng -->
                    @php $variantIndex = 0; @endphp
                    <div id="variant-quantity" class="col-md-4 mb-2">
                        <label><strong>Số lượng</strong></label>
                        <input type="number" name="variant_quantity[]" class="variant_quantity form-control mt-1"
                            value="{{ $oldVariants ? old('variant_quantity.' . $variantIndex) : $variant->quantity }}"
                            min="1">
                        @error('variant_quantity.' . $variantIndex)
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="variant-chest-waist" class="col-md-6 chest-waist-group" style="gap: 15px">
                        <div class="mb-3 chest-size" style="display: none">
                            <label><strong>Giá biến thể</strong></label>
                            <input type="number" name="variant_price[]" class="form-control"
                                value="{{ $oldVariants ? old('variant_price.' . $variantIndex) : $variant->variant_price }}">
                            @error('variant_price.' . $variantIndex)
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 waist-size" style="display: none">
                            <label><strong>Giá khuyến mã biến thể</strong></label>
                            <input type="number" name="variant_sale_price[]" class="form-control"
                                value="{{ $oldVariants ? old('variant_sale_price.' . $variantIndex) : $variant->variant_sale_price }}">
                            @error('variant_sale_price.' . $variantIndex)
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-variant-btn" class="btn btn-primary mb-3">Thêm biến thể</button>
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"
                rows="4">{{ old('description', $product->description) }}</textarea>
            @error('description')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('Admin.products.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>


<script>
    document.getElementById('add-variant-btn').addEventListener('click', function() {
    const container = document.getElementById('variant-container');
    const variantGroups = container.getElementsByClassName('variant-group');
    if (variantGroups.length > 0) {
        // Clone khối đầu tiên
        const newVariant = variantGroups[0].cloneNode(true);

        // Xóa dữ liệu input bên trong clone để người dùng nhập mới
        const inputs = newVariant.querySelectorAll('input');
        inputs.forEach(input => {
            if(input.type === 'number' || input.type === 'text' || input.type === 'hidden') {
                input.value = '';
            }
        });

        // Nếu bạn có button chọn size, color... bạn có thể reset hoặc xử lý thêm ở đây

        container.appendChild(newVariant);
    }
});
    document.addEventListener("DOMContentLoaded", function () {
        const productTypeSelect = document.getElementById("product_type");

        // Các nhóm cần điều khiển hiển thị
        const elements = {
            shirtSizeGroup: document.querySelectorAll(".shirt-size-group"),
            shoeSizeGroup: document.querySelectorAll(".shoe-size-group"),
            footLength: document.querySelectorAll(".foot-length"),
            chestSize: document.querySelectorAll(".chest-size"),
            waistSize: document.querySelectorAll(".waist-size"),
            hipSize: document.querySelectorAll(".hip-size")
        };

        function showAllVariants() {
            for (const key in elements) {
                if (elements[key]) {
                    elements[key].forEach(element => {
                        element.style.display = (element.classList.contains("d-flex")) ? "flex" : "block";
                    });
                }
            }
        }

        function hideAllVariants() {
            for (const key in elements) {
                if (elements[key]) {
                    elements[key].forEach(element => {
                        element.style.display = "none";
                    });
                }
            }
        }

        function setDisplay(els, value) {
            els.forEach(el => {
                if (el.classList.contains("d-flex")) {
                    el.style.display = (value === "block") ? "flex" : value;
                } else {
                    el.style.display = value;
                }
            });
        }

        function updateVariantVisibility(type) {
            if (!type) {
                
                showAllVariants();
                return;
            }

            
            hideAllVariants();

            switch (type) {
                case "shoes":
                    setDisplay(elements.shoeSizeGroup, "block");
                    setDisplay(elements.chestSize, "block");
                    setDisplay(elements.waistSize, "block");
                    break;
                case "shirt":
                    setDisplay(elements.shirtSizeGroup, "block");
                    setDisplay(elements.chestSize, "block");
                    setDisplay(elements.waistSize, "block");
                    break;
                case "pants":
                    setDisplay(elements.shirtSizeGroup, "block");
                    setDisplay(elements.chestSize, "block");
                    setDisplay(elements.waistSize, "block");
                    break;
            }
        }

       
        productTypeSelect.addEventListener("change", function () {
            updateVariantVisibility(this.value);
        });

        
        updateVariantVisibility(productTypeSelect.value);
    });

    document.addEventListener('DOMContentLoaded', function () {
    
    document.querySelectorAll('#variant-container').forEach(function (container) {
        container.addEventListener('click', function (e) {
            if (e.target.matches('.variant-group .btn-outline-secondary')) {
                
                e.target.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                
                e.target.closest('.variant-group').querySelector('.selected_size').value = e.target.textContent.trim();
            }
            if (e.target.matches('.rounded-circle')) {
                
                e.target.parentElement.querySelectorAll('.rounded-circle').forEach(c => c.style.border = '1px solid #ccc');
                e.target.style.border = '3px solid black';
                
                e.target.closest('.variant-group').querySelector('.selected_color').value = e.target.style.backgroundColor;
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.querySelector('input[name="slug"]');
    if(nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            let slug = nameInput.value.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') 
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-') 
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        });
    }
});
</script>
@endsection
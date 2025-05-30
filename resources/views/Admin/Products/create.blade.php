@extends('Admin.Layouts.AdminLayout')
@section('main')
<div class="app-title">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href=""><b>Thêm sản phẩm mới</b></a>
        </li>
    </ul>
    <div id="clock"></div>
</div>

<div class="container">
    <h2>Thêm sản phẩm mới</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Lỗi:</strong>
        <ul>
            @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('Admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Giá</label>
                    <input type="number" name="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Loại</label>
                    <select name="type" class="form-control" id="product_type" required>
                        <option value=""> -- Loại sản phẩm --</option>
                        <option value="shoes">Giày thể thao</option>
                        <option value="shirt">Áo</option>
                        <option value="pants">Quần</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Thương hiệu</label>
                    <input type="text" name="brand" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->ten_danh_muc }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Không hoạt động</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Ảnh chính</label>
                    <input type="file" name="image_product" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Ảnh phụ</label>
                    <input class="form-control" type="file" name="image_details[]" multiple>
                </div>

            </div>
        </div>
        <div class="mb-3">
            <h5>Biến thể sản phẩm</h5>
            <div id="variant-container">
                <div class="variant-group row mb-3 position-relative">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-variant"
                        style="position:absolute;top:0;right:0;z-index:2;">&times;</button>
                    <!-- Kích thước -->
                    <div id="variant-size-text" class="col-md-4 mb-2 shirt-size-group" style="display:none">
                        <label><strong>Size:</strong></label>
                        <div class="d-flex flex-wrap gap-5 mt-1 me-3" style="gap: 15px">
                            <button type="button" class="btn btn-outline-secondary">S</button>
                            <button type="button" class="btn btn-outline-secondary">M</button>
                            <button type="button" class="btn btn-outline-secondary">L</button>
                            <button type="button" class="btn btn-outline-secondary">XL</button>
                            <button type="button" class="btn btn-outline-secondary">2XL</button>
                            <button type="button" class="btn btn-outline-secondary">3XL</button>
                        </div>
                    </div>

                    <!-- Kích thước giày -->
                    <div id="variant-size-shoes" class="col-md-4 mb-2 shoe-size-group" style="display:none">
                        <label><strong>Kích thước:</strong></label>
                        <div class="d-flex flex-wrap gap-5 mt-1 me-3" style="gap: 15px">
                            <button type="button" class="btn btn-outline-secondary">37</button>
                            <button type="button" class="btn btn-outline-secondary">38</button>
                            <button type="button" class="btn btn-outline-secondary">39</button>
                            <button type="button" class="btn btn-outline-secondary">40</button>
                            <button type="button" class="btn btn-outline-secondary">41</button>
                            <button type="button" class="btn btn-outline-secondary">42</button>
                            <button type="button" class="btn btn-outline-secondary">43</button>
                            <button type="button" class="btn btn-outline-secondary">44</button>
                            <button type="button" class="btn btn-outline-secondary">45</button>
                        </div>
                    </div>

                    <!-- Màu sắc -->
                    <div id="variant-color" class="col-md-4 mb-2">
                        <label><strong>Màu sắc:</strong></label>
                        <div class="d-flex flex-wrap gap-2 mt-1" style="gap: 15px">
                            <div class="rounded-circle border"
                                style="width:30px; height:30px; background:white; cursor:pointer;"></div>
                            <div class="rounded-circle border"
                                style="width:30px; height:30px; background:yellow; cursor:pointer;"></div>
                            <div class="rounded-circle border"
                                style="width:30px; height:30px; background:blue; cursor:pointer;"></div>
                            <div class="rounded-circle border"
                                style="width:30px; height:30px; background:red; cursor:pointer;"></div>
                            <div class="rounded-circle border"
                                style="width:30px; height:30px; background:black; cursor:pointer;"></div>
                            <div class="rounded-circle border"
                                style="width:30px; height:30px; background:green; cursor:pointer;"></div>
                            <div class="rounded-circle border"
                                style="width:30px; height:30px; background:pink; cursor:pointer;"></div>
                        </div>
                    </div>
                    <input type="hidden" name="selected_size[]" class="selected_size">
                    <input type="hidden" name="selected_color[]" class="selected_color">
                    <!-- Số lượng -->
                    <div id="variant-quantity" class="col-md-4 mb-2">
                        <label><strong>Số lượng</strong></label>
                        <input type="number" name="variant_quantity[]" class="variant_quantity form-control mt-1"
                            value="" min="1">
                    </div>
                    <!-- Chiều dài bàn chân -->
                    <div id="variant-foot-length" class="col-md-6 foot-length" style="display: none">
                        <div class="mb-3">
                            <label>Chiều dài bàn chân (cm):</label>
                            <input type="number" name="foot_length" class="form-control">
                        </div>
                    </div>

                    <!-- Cỡ ngực, eo -->
                    <div id="variant-chest-waist" class="col-md-6 chest-waist-group" style="gap: 15px">
                        <div class="mb-3 chest-size" style="display: none">
                            <label>Cỡ ngực (cm):</label>
                            <input type="number" name="chest_size" class="form-control">
                        </div>
                        <div class="mb-3 waist-size" style="display: none">
                            <label>Cỡ eo (cm):</label>
                            <input type="number" name="waist_size" class="form-control">
                        </div>
                    </div>
                    <!-- Cỡ hông -->
                    <div id="variant-hip" class="col-md-6 hip-size" style="display: none">
                        <div class="mb-3">
                            <label>Cỡ hông (cm):</label>
                            <input type="number" name="hip_size" class="form-control">
                        </div>
                    </div>

                </div>
            </div>
            <button type="button" id="add-variant-btn" class="btn btn-primary mb-3">Thêm biến thể</button>
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
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
                    setDisplay(elements.footLength, "flex");
                    break;
                case "shirt":
                    setDisplay(elements.shirtSizeGroup, "block");
                    setDisplay(elements.chestSize, "block");
                    setDisplay(elements.waistSize, "block");
                    break;
                case "pants":
                    setDisplay(elements.shirtSizeGroup, "block");
                    setDisplay(elements.hipSize, "flex");
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
</script>





@endsection
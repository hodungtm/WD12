@extends('admin.layouts.Adminlayout')

@section('main')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="app-title d-flex justify-content-between align-items-center mb-3">
    <ul class="app-breadcrumb breadcrumb side mb-0">
        <li class="breadcrumb-item active"><a href="#"><b>Danh sách sản phẩm</b></a></li>
    </ul>
    <div id="clock"></div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile card shadow-sm rounded-3 border-0">
            <div class="tile-body p-4">

           
                    {{-- Các nút chức năng --}}
                    <div class="d-flex align-items-center justify-content-start mb-3" style="gap: 10px;">
                        <a href="{{ route('products.create') }}" class="btn btn-outline-success btn-sm d-flex align-items-center"
                            style="gap: 5px;">
                            <i class="fas fa-plus"></i> Tạo mới Sản Phẩm
                        </a>
                    </div>


                    <form method="GET" action="{{ route('products.index') }}" class="mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            {{-- Lọc theo trạng thái --}}
                            <div class="d-flex align-items-center">
                                <label class="me-2 mb-0">Trạng thái:</label>
                                <select name="status" class="form-control-sm" onchange="this.form.submit()">
                                    <option value=""> Tất cả </option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </div>

                            {{-- Số lượng hiển thị --}}
                            <div class="d-flex align-items-center">
                                <label class="me-2 mb-0"> Hiện: </label>
                                <select name="per_page" class="form-control-sm" onchange="this.form.submit()"
                                    style="width: auto;">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>   
                                <span class="ms-2">Sản Phẩm</span>
                            </div>
                            <div class="d-flex align-items-center">
                                {{-- Form tìm kiếm --}}
                                <form method="GET" action="{{ route('products.index') }}">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="keyword" class="form-control"
                                                placeholder="Nhập thông tin..." value="{{ request('keyword') }}"
                                                style="min-width: 200px; height: 40px;">
                                            <button style="min-width: 50px; min-height: 40px;" class="btn btn-outline-warning "
                                                type="submit">
                                                <i class="fas fa-search me-1">Tìm Kiếm</i>
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                    </form>
                </div>
                <!-- Form xóa mềm nhiều -->
                <form action="{{ route('products.delete.selected') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mềm các sản phẩm đã chọn?');">
                    @csrf
                    @method('DELETE')

                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead style="background-color: #f8f9fa; font-weight: bold;">
                            <tr>
                                <th><input type="checkbox" id="check_all"></th>
                                <th>STT</th>
                                <th>Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Giá</th>
                                <th>Giá Sale</th>
                                <th>Danh mục</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                @php $variant = $product->variants->first(); @endphp
                                <tr>
                                    <td><input type="checkbox" name="selected_products[]" value="{{ $product->id }}" class="check_item"></td>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->product_code }}</td>
                                    <td class="text-start">{{ $product->name }}</td>
                                    <td>
                                        @if ($product->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" width="50" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $variant ? number_format($variant->price, 0, ',', '.') . ' VND' : 'Chưa có giá' }}
                                    </td>
                                    <td>
                                        {{ $variant ? number_format($variant->sale_price, 0, ',', '.') . ' VND' : 'Chưa có giá KM' }}
                                    </td>
                                    <td>{{ $product->category->ten_danh_muc ?? 'Không có danh mục' }}</td>
                                    <td>
                                        @if ($product->status == 1)
                                            <span class="badge bg-success">Hiển thị</span>
                                        @else
                                            <span class="badge bg-danger">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info btn-sm" title="Xem"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm" title="Sửa"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('products.softDelete', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-outline-danger btn-sm mt-2">
                        <i class="fas fa-trash-alt me-1"></i> Xóa mềm các sản phẩm đã chọn
                    </button>
                </form>

                @if ($products->hasPages())
                    <div class="mt-3">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Script check all -->
<script>
    document.getElementById('check_all').addEventListener('change', function () {
        document.querySelectorAll('.check_item').forEach(cb => cb.checked = this.checked);
    });
</script>

@endsection

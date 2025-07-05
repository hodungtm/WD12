@extends('admin.layouts.AdminLayout')

@section('main')

    <div class="main-content-inner">
        <div class="main-content-wrap">

            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách sản phẩm</h3>
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
                        <div class="text-tiny">Danh sách sản phẩm</div>
                    </li>
                </ul>
            </div>

            <!-- product-list -->
            <div class="wg-box">

                <!-- Thông báo thành công -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="title-box">
                    <i class="icon-coffee"></i>
                    <div class="body-text">Tìm kiếm theo tên hoặc ID sản phẩm</div>
                </div>

                <div class="flex items-center justify-between gap10 flex-wrap">
                    <!-- Bộ lọc và tìm kiếm -->
                    <div class="wg-filter flex-grow">
                        <form action="{{ route('products.index') }}" method="GET" class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm sản phẩm..." name="search"
                                    value="{{ request('search') }}">
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <!-- Nút thêm sản phẩm -->
                    <a class="tf-button style-1 w208" href="{{ route('products.create') }}">
                        <i class="icon-plus"></i> Thêm sản phẩm
                    </a>
                </div>

                <!-- Bảng sản phẩm -->
                <div class="wg-table table-product-list mt-3">
                    <ul class="table-title flex gap20 mb-14">
                        <li>
                            <div class="body-title">Tên sản phẩm</div>
                        </li>
                        <li>
                            <div class="body-title">Mã SP</div>
                        </li>
                        <li>
                            <div class="body-title">Giá</div>
                        </li>
                        <li>
                            <div class="body-title">Danh mục</div>
                        </li>
                        <li>
                            <div class="body-title">Tồn kho</div>
                        </li>
                        <li>
                            <div class="body-title">Ngày tạo</div>
                        </li>
                        <li>
                            <div class="body-title">Thao tác</div>
                        </li>
                    </ul>

                    <ul class="flex flex-column">
                        @foreach($products as $product)
                            @php
                                $variant = $product->variants->first();
                            @endphp
                            <li class="product-item gap14">
                                <div class="image no-bg">
                                    @if($product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $product->images->first()->image) }}" alt="">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between gap20 flex-grow">
                                    <div class="name">
                                        <a href="#" class="body-title-2">{{ $product->name }}</a>
                                    </div>
                                    <div class="body-text">#SP-{{ $product->id }}</div>
                                    <div class="body-text">
                                        @if($variant)
                                            {{ number_format($variant->price, 0, ',', '.') }} VND
                                        @else
                                            <span class="text-muted">Chưa có giá</span>
                                        @endif
                                    </div>
                                    <div class="body-text">{{ $product->category->ten_danh_muc ?? 'Không có danh mục' }}</div>
                                    <div class="body-text">
                                        {{ $product->variants->sum('quantity') }} cái
                                    </div> {{-- Bạn có thể tính tồn kho ở đây --}}
                                    <div class="body-text">{{ optional($product->created_at)->format('d/m/Y') }}</div>
                                    <div class="list-icon-function">
                                        <a href="{{ route('products.show', $product->id) }}" class="item eye" title="Xem"><i
                                                class="icon-eye"></i></a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="item edit" title="Sửa"><i
                                                class="icon-edit-3"></i></a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="color: red" title="Xóa"
                                                ><i class="icon-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Phân trang -->
                <div class="divider mt-3"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">Tổng: {{ $products->total() }} sản phẩm</div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>



@endsection
@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Danh sách sản phẩm</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">Sản phẩm</div></li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="title-box">
                    <i class="icon-book-open"></i>
                    <div class="body-text">Tìm kiếm sản phẩm theo tên hoặc lọc theo danh mục.</div>
                </div>
                <div class="flex items-center justify-between gap10 flex-wrap mb-3">
                    <div class="wg-filter flex-grow">
                        <form method="GET" action="{{ route('products.index') }}" class="form-search mt-2">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm sản phẩm..." name="search" value="{{ request('search') }}">
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a href="{{ route('products.create') }}" class="tf-button style-1 w200">
                        <i class="icon-plus"></i> Thêm sản phẩm
                    </a>
                </div>
                <div class="wg-table table-product-list mt-3">
                    <ul class="table-title flex mb-14" style="gap: 2px;">
                        <li style="flex-basis: 40px;"><div class="body-title">ID</div></li>
                        <li style="flex-basis: 200px;"><div class="body-title">Tên sản phẩm</div></li>
                        <li style="flex-basis: 80px;"><div class="body-title">Ảnh</div></li>
                        <li style="flex-basis: 100px;"><div class="body-title">Mã sản phẩm</div></li>
                        <li style="flex-basis: 100px;"><div class="body-title">Giá gốc</div></li>
                        <li style="flex-basis: 100px;"><div class="body-title">Giá khuyến mãi</div></li>
                        <li style="flex-basis: 120px;"><div class="body-title">Danh mục</div></li>
                        <li style="flex-basis: 80px;"><div class="body-title">Trạng thái</div></li>
                        <li style="flex-basis: 120px;"><div class="body-title">Ngày tạo</div></li>
                        <li style="flex-basis: 120px;"><div class="body-title">Hành động</div></li>
                    </ul>
                    <ul class="flex flex-column">
                        @foreach ($products as $product)
                            <li class="wg-product item-row" style="gap: 2px;">
                                <div class="body-text mt-4" style="flex-basis: 40px;">#{{ $product->id }}</div>
                                <div class="title line-clamp-2 mb-0" style="flex-basis: 200px;">
                                    <a href="{{ route('products.show', $product->id) }}" class="body-text">{{ $product->name }}</a>
                                </div>
                                <div class="image" style="flex-basis: 80px;">
                                    @if ($product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $product->images->first()->image) }}" width="50" class="rounded" alt="Ảnh">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 100px;">{{ $product->product_code }}</div>
                                <div class="body-text mt-4" style="flex-basis: 100px;">
                                    {{ optional($product->variants->first())->price ? number_format($product->variants->first()->price, 0, ',', '.') . ' VND' : 'Chưa có giá' }}
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 100px;">
                                    {{ optional($product->variants->first())->sale_price ? number_format($product->variants->first()->sale_price, 0, ',', '.') . ' VND' : 'Chưa có KM' }}
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 120px;">{{ $product->category->ten_danh_muc ?? 'Không có danh mục' }}</div>
                                <div style="flex-basis: 80px;">
                                    <div class="{{ $product->status == 1 ? 'block-available' : 'block-stock' }} bg-1 fw-7">
                                        {{ $product->status == 1 ? 'Hiển thị' : 'Ẩn' }}
                                    </div>
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 120px;">{{ $product->created_at->format('d/m/Y H:i') }}</div>
                                <div class="list-icon-function" style="flex-basis: 120px;">
                                    <a href="{{ route('products.show', $product->id) }}" class="item eye"><i class="icon-eye"></i></a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="item edit"><i class="icon-edit-3"></i></a>
                                    <form action="{{ route('products.softDelete', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="color: red" title="Xóa sản phẩm">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">Hiển thị từ {{ $products->firstItem() }} đến {{ $products->lastItem() }} trong tổng số {{ $products->total() }} sản phẩm</div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.AdminLayout')
@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="tile card shadow-sm rounded-3 border-0">
                <h3>Danh sách sản phẩm đã xóa</h3>
                <div class="tile-body p-4">
                    {{-- XÓA ĐOẠN HIỂN THỊ LỖI/THÔNG BÁO Ở ĐÂY --}}

                    <div class="wg-box">
                        <div class="flex items-center justify-between gap10 flex-wrap">
                            <div class="wg-filter flex-grow">
                                <form action="{{ route('admin.products.index') }}" method="GET" class="form-search">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Tìm kiếm sản phẩm..." name="search"
                                            value="{{ request('search') }}">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button type="submit"><i class="icon-search"></i></button>
                                    </div>
                                </form>
                            </div>

                            <a class="tf-button style-1 w208 btn btn-outline-success btn-sm me-1 mb-1"
                                href="{{ route('admin.products.index') }}">
                                <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
                            </a>

                            <a href="{{ route('admin.products.trash') }}" class="item eye" title="Thùng rác">
                                <h3 style="color: red;">
                                    <i class="icon-trash-2"></i>
                                </h3>
                            </a>
                        </div>

                        <div class="wg-table table-product-list mt-3">
                            <ul class="table-title flex gap20 mb-14">
                                <li style="width: 200px;">
                                    <div class="body-title">Tên sản phẩm</div>
                                </li>
                                <li>
                                    <div class="body-title">Mã sản phẩm</div>
                                </li>
                                <li>
                                    <div class="body-title">Giá gốc</div>
                                </li>
                                <li>
                                    <div class="body-title">Giá khuyến mãi</div>
                                </li>
                                <li>
                                    <div class="body-title">Danh mục</div>
                                </li>
                                <li>
                                    <div class="body-title">Trạng thái</div>
                                </li>
                                <li>
                                    <div class="body-title">Ngày tạo</div>
                                </li>
                                <li>
                                    <div class="body-title">Hành động</div>
                                </li>
                            </ul>

                            <ul class="flex flex-column">
                                @foreach ($products as $product)
                                    @if ($product->status == 0)
                                        @php
                                            $variant = $product->variants->first();
                                        @endphp
                                        <li class="wg-product item-row flex" style="align-items: center;">
                                            <div style="width: 200px;">
                                                <div class="image" style="width: 40px; height: 40px; float: left; margin-right: 10px;">
                                                    @if ($product->images->isNotEmpty())
                                                        <img src="{{ asset('storage/' . $product->images->first()->image) }}"
                                                            alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                                    @else
                                                        <span class="text-muted">Không có ảnh</span>
                                                    @endif
                                                </div>
                                                <div class="title line-clamp-2 mb-0">
                                                    <a href="{{ route('admin.products.show', $product->id) }}" class="body-text">{{ $product->name }}</a>
                                                </div>
                                            </div>
                                            <div class="body-text mt-4">{{ $product->product_code }}</div>
                                            <div class="body-text mt-4">
                                                {{ $variant ? number_format($variant->price, 0, ',', '.') . ' VND' : 'Chưa có giá' }}
                                            </div>
                                            <div class="body-text mt-4">
                                                {{ $variant ? number_format($variant->sale_price, 0, ',', '.') . ' VND' : 'Không có KM' }}
                                            </div>
                                            <div class="body-text mt-4">
                                                {{ $product->category->ten_danh_muc ?? 'Không có danh mục' }}
                                            </div>
                                            <div>
                                                <div class="{{ $product->status == 1 ? 'block-available' : 'block-stock' }} bg-1 fw-7">
                                                    {{ $product->status == 1 ? 'Hiển thị' : 'Đã xóa' }}
                                                </div>
                                            </div>
                                            <div class="body-text mt-4">
                                                {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </div>
                                            <div class="list-icon-function">
                                                <a href="{{ route('restore', $product->id) }}" class="item restore" title="Khôi phục sản phẩm"
                                                   style="margin-right: 5px; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px;   ">
                                                    <i class="fas fa-undo" style="color: #28a745; font-size: 20px;"></i>
                                                </a>
                                                <form action="{{ route('admin.products.forceDelete', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi hệ thống vĩnh viễn?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="color: red"  title="Xóa vĩnh viễn">
                                                        <i class="icon-trash"style="color: red; font-size: 20px;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <div class="divider"></div>
                        <div class="flex items-center justify-between flex-wrap gap10">
                            <div class="text-tiny">Hiển thị từ {{ $products->firstItem() }} đến {{ $products->lastItem() }} trong tổng số {{ $products->total() }} sản phẩm đã xoá</div>
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

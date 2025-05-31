@extends('Admin.Layouts.AdminLayout')
@section('main')
<div class="app-title">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href="{{ route("Admin.products.index") }}"><b>Danh sách sản phẩm</b></a></li>
    </ul>
    <div id="clock"></div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">

                <div class="row element-button mb-3">
                    <div class="col-sm-2">
                        <a class="btn btn-add btn-sm" href="{{ route("Admin.products.create") }}" title="Thêm">
                            <i class="fas fa-plus"></i> Tạo mới sản phẩm
                        </a>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success"></div>
                @endif
                <form method="GET" action="" class="mb-4">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-6">
                            <div class="input-group shadow-sm">
                                <input type="text" name="search" class="form-control rounded-start"
                                    placeholder="🔍 Tìm kiếm mã giảm giá..." value="">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                        <div class="col-md-auto mt-2 mt-md-0">
                            <a href="" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Xóa bộ lọc
                            </a>
                        </div>
                    </div>
                </form>

                <table class="table table-hover table-bordered" id="discountTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Ảnh</th>
                            <th>Giá</th>
                            <th>Giá KM</th>
                            <th>Thương hiệu</th>
                            <th>Loại</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Tồn kho</th>
                            <th>Ngày tạo</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->name }}</td>
                            <td>
                                <img src="{{ Storage::url($p->image_product) }}" width="40" height="30">
                            </td>
                            <td>{{ number_format($p->price, 0, ',', '.') }} VNĐ</td>
                            <td>
                                @if($p->sale_price > 0)
                                    <span class="text-danger">{{ number_format($p->sale_price, 0, ',', '.') }} VNĐ</span>
                                @endif
                            </td>
                            <td>{{ $p->brand }}</td>
                            <td>{{ ucfirst($p->type) }}</td>
                            <td>{{ optional($p->category)->ten_danh_muc ?? 'Không có danh mục' }}</td>
                            <td>{{ $p->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}</td>
                            <td>{{ $p->variants->sum('quantity') }}</td>
                            <td>{{ $p->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a class="btn btn-info btn-sm" href="{{ route("Admin.products.show", $p->id) }}">Xem</a>
                                <a class="btn btn-primary btn-sm" href="{{ route("Admin.products.edit", $p->id) }}">Sửa</a>
                                <form action="{{ route("Admin.products.delete", $p->id) }}" method="POST"
                                    style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>
@endsection
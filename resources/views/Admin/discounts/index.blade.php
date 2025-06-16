@extends('Admin.Layouts.AdminLayout')
@section('main')
@section('main')
<div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách mã giảm giá</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">

                    <div class="row element-button mb-3">
                            <div class="col-sm-2">
                              <a class="btn btn-add btn-sm" href="{{ route('admin.discounts.create') }}" title="Thêm"><i class="fas fa-plus"></i>
                                Tạo mới Mã Giảm Giá</a>
                            </div>
                            <div class="col-sm-2">
                              <a class="btn btn-add btn-sm" href="{{ route('admin.discounts.report') }}" title="Thêm"><i class="fas fa-plus"></i>
                                Báo Cáo Sử Dụng Mã Giảm Giá</a>
                            </div>
                                <div class="col-sm-2">
                                    <a class="btn btn-excel btn-sm" href="{{ route('admin.discounts.exportExcel') }}" title="In"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                                </div>
                                <div class="col-sm-2">
                                    <form id="uploadForm" action="{{ route('discounts.importExcel') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="import_file" id="import_file" accept=".xlsx" style="display: none;" onchange="document.getElementById('uploadForm').submit();">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('import_file').click();">
                                            <i class="fas fa-upload mr-1"></i> Tải file lên
                                        </button>
                                    </form>
                                </div>
                                <div class="col-sm-2">
                                    <a href="{{ route('admin.discounts.trashed') }}" class="btn btn-warning">
                                        <i class="fas fa-trash-restore"></i> Thùng rác
                                    </a>
                            </div>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                     <form method="GET" action="{{ route('admin.discounts.index') }}" class="mb-4">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-6">
                            <div class="input-group shadow-sm">
                                <input type="text" name="search" class="form-control rounded-start" placeholder="🔍 Tìm kiếm mã giảm giá..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-start">

                            <div class="mb-4">
                                <button class="btn btn-outline-info dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Bộ lọc
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.discounts.index') }}">Tất cả</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.discounts.index', ['type' => 'order']) }}">Theo đơn hàng</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.discounts.index', ['type' => 'product']) }}">Theo sản phẩm</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.discounts.index', ['type' => 'shipping']) }}">Giảm phí ship</a></li>
                                </ul>
                            </div>
                            <div class="me-2 mb-2">
                                <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Xóa bộ lọc
                                </a>
                            </div>
                        </div>


            </form>




                    <table class="table table-hover table-bordered" id="discountTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>STT</th>
                                <th>Mã</th>
                                <th>Mô tả</th>
                                <th>Loại giảm</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Lượt tối đa</th>
                                <th>Đơn tối thiểu</th>
                                <th>Đơn tối Đa</th>
                                <th>Phân loại</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($discounts as $discount)
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="{{ $discount->id }}"></td>
                                    <td>{{ $discount->id }}</td>
                                    <td>{{ $discount->code }}</td>
                                    <td>{{ $discount->description }}</td>
                                    <td>
                                        @if ($discount->discount_amount)
                                            <span class="badge bg-info">{{ number_format($discount->discount_amount) }} VNĐ</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $discount->discount_percent }}%</span>
                                        @endif
                                    </td>
                                    <td>{{ $discount->start_date }}</td>
                                    <td>{{ $discount->end_date }}</td>
                                    <td>{{ $discount->max_usage }}</td>
                                    <td>{{ number_format($discount->min_order_amount) }} VNĐ</td>
                                    <td>
                                        @if ($discount->max_discount_amount)
                                            {{ number_format($discount->max_discount_amount) }} VNĐ
                                        @else
                                            <span class="text-muted">Không giới hạn</span>
                                        @endif
                                    </td>

                                    <td>
                                    @switch($discount->type)
                                        @case('order')
                                            <span class="badge bg-primary">Theo đơn hàng</span>
                                            @break

                                        @case('shipping')
                                            <span class="badge bg-success">Giảm phí ship</span>
                                            @break

                                        @case('product')
                                            <span class="badge bg-warning text-dark">Theo sản phẩm</span>
                                            @break

                                        @default
                                            <span class="badge bg-secondary">Không xác định</span>
                                    @endswitch
                                            </td>

                                    <td>
                                        <a href="{{ route('admin.discounts.show', $discount->id) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-primary btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                {{ $discounts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

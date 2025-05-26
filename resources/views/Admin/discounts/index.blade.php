@extends('Admin.Layouts.AdminLayout')
@section('main')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
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
                            <a class="btn btn-add btn-sm" href="{{ route('discounts.create') }}" title="Thêm">
                                <i class="fas fa-plus"></i> Tạo mã giảm giá
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-hover table-bordered" id="discountTable">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Mô tả</th>
                                <th>Loại giảm</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Lượt tối đa</th>
                                <th>Đơn tối thiểu</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($discounts as $discount)
                                <tr>
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
                                        <a href="{{ route('discounts.edit', $discount) }}" class="btn btn-primary btn-sm" title="Sửa">
<i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('discounts.destroy', $discount) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
            </div>
        </div>
    </div>
</div>
@endsection

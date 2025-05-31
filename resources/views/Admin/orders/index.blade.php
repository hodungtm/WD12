@extends('Admin.layouts.AdminLayout')
@section('main')
<div class="app-title">
    <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item active"><a href="#"><b>Danh sách đơn hàng</b></a></li>
    </ul>
    <div id="clock"></div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row element-button">
                    <div class="col-sm-2">
                        <a class="btn btn-add btn-sm" href="{{ route('admin.orders.create') }}" title="Thêm"><i class="fas fa-plus"></i> Tạo mới đơn hàng</a>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-delete btn-sm nhap-tu-file" title="Nhập"><i class="fas fa-file-upload"></i> Tải từ file</a>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-delete btn-sm print-file" title="In"><i class="fas fa-print"></i> In dữ liệu</a>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-delete btn-sm js-textareacopybtn" title="Sao chép"><i class="fas fa-copy"></i> Sao chép</a>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-excel btn-sm" href="#" title="Excel"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-delete btn-sm pdf-file" title="PDF"><i class="fas fa-file-pdf"></i> Xuất PDF</a>
                    </div>
                </div>

                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th width="10"><input type="checkbox" id="all"></th>
                            <th>ID đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Đơn hàng</th>
                            <th>Số lượng</th>
                            <th>Tổng tiền</th>
                            <th>PT Thanh toán</th>
                            <th>Tình trạng</th>
                            <th>Tính năng</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach($orders as $order)
    <tr>
        <td><input type="checkbox" name="check[]" value="{{ $order->id }}"></td>
        <td>{{ $order->id }}</td>
        <td>{{ $order->user->name ?? 'Không rõ' }}</td>

        {{-- Danh sách sản phẩm --}}
        <td>
            @foreach($order->orderItems as $item)
                {{ $item->product->name }}{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </td>

        {{-- Tổng số lượng --}}
        <td>
            {{ $order->orderItems->sum('quantity') }}
        </td>

        {{-- Tổng tiền tính từ order_items --}}
       <td>
    {{
        number_format(
            $order->orderItems->sum('total_price'),
            0, ',', '.'
        )
    }} đ
</td>



        <td>{{ $order->payment_method }}</td>
        <td>{{ $order->status }}</td>
        <td>
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button class="btn btn-primary btn-sm trash" title="Xóa"><i class="fas fa-trash-alt"></i></button>
            </form>
            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm edit" title="Xem"><i class="fa fa-eye"></i></a>
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary btn-sm edit" title="Sửa"><i class="fa fa-edit"></i></a>
            
        </td>
    </tr>
    @endforeach
                    </tbody>
                </table>

                {{-- Optional: Phân trang --}}
                {{-- {!! $orders->links() !!} --}}

            </div>
        </div>
    </div>
</div>
@endsection

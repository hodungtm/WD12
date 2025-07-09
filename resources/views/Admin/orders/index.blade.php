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
                <div class="row element-button mb-3">
                    <div class="col-sm-2">
                        <a class="btn btn-add btn-sm" href="{{ route('admin.orders.create') }}" title="Thêm"><i class="fas fa-plus"></i> Tạo mới đơn hàng</a>
                    </div>
                    {{-- Form Tìm kiếm theo mã đơn hàng --}}
                    <div class="col-sm-4"> {{-- Thay đổi kích thước cột để chứa ô tìm kiếm --}}
                        <form action="{{ route('admin.orders.index') }}" method="GET" class="form-inline">
                            <div class="input-group w-100">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm theo mã đơn hàng..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-info btn-sm" type="submit"><i class="fas fa-search"></i></button>
                                    @if(request('search'))
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Xóa</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- Các nút chức năng khác --}}
                    <div class="col-sm-6 text-right"> {{-- Căn phải các nút còn lại --}}
                        <a class="btn btn-delete btn-sm nhap-tu-file" title="Nhập"><i class="fas fa-file-upload"></i> Tải từ file</a>
                        <a class="btn btn-delete btn-sm print-file" title="In"><i class="fas fa-print"></i> In dữ liệu</a>
                        <a class="btn btn-delete btn-sm js-textareacopybtn" title="Sao chép"><i class="fas fa-copy"></i> Sao chép</a>
                        <a class="btn btn-excel btn-sm" href="#" title="Excel"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                        <a class="btn btn-delete btn-sm pdf-file" title="PDF"><i class="fas fa-file-pdf"></i> Xuất PDF</a>
                    </div>
                </div>

                {{-- Thông báo Flash Session --}}
                {{-- XÓA ĐOẠN HIỂN THỊ LỖI/THÔNG BÁO Ở ĐÂY --}}

                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th>Mã Đơn Hàng</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>PT Thanh toán</th>
                            <th>Vận chuyển</th>
                            <th>Trạng thái</th>
                            <th>Tính năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order) {{-- Sử dụng @forelse để xử lý trường hợp không có đơn hàng --}}
                            <tr>
                                <td>{{ $order->order_code }}</td>

                                {{-- Hiển thị tên người nhận nếu có, nếu không thì người đặt --}}
                                <td>
                                    {{ $order->receiver->name ?? $order->user->name ?? 'N/A' }}
                                </td>

                                {{-- Tổng tiền (đã bao gồm giảm giá + phí ship nếu có) --}}
                                <td>{{ number_format($order->final_amount ?? 0, 0, ',', '.') }}₫</td> {{-- Đã sửa ký hiệu tiền --}}

                                {{-- Phương thức thanh toán --}}
                                <td>
                                    @switch($order->payment_method)
                                        @case('Tiền mặt') Tiền mặt @break
                                        @case('Chuyển khoản ngân hàng') Chuyển khoản ngân hàng @break
                                        @case('Momo') Momo @break
                                        @case('ZaloPay') ZaloPay @break
                                        @default {{ ucfirst($order->payment_method) }}
                                    @endswitch
                                </td>

                                {{-- Phương thức vận chuyển --}}
                                <td>{{ $order->shippingMethod->name ?? '---' }}</td>

                                {{-- Trạng thái --}}
                                <td><span class="badge {{
                                    $order->status === 'Đang chờ' ? 'bg-warning' :
                                    ($order->status === 'Hoàn thành' ? 'bg-success' :
                                    ($order->status === 'Đã hủy' ? 'bg-danger' : 'bg-info'))
                                }}">{{ $order->status }}</span></td> {{-- Thêm màu sắc badge --}}

                                {{-- Tính năng --}}
                                <td>
                                    {{-- Form xóa với xác nhận --}}
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng {{ $order->order_code }} không? Thao tác này không thể hoàn tác và sẽ xóa tất cả chi tiết đơn hàng liên quan!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm trash" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm edit" title="Xem"><i class="fa fa-eye"></i></a> {{-- Đổi màu cho nút xem --}}
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary btn-sm edit" title="Sửa"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không tìm thấy đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Optional: Phân trang --}}
                {{-- {!! $orders->links() !!} --}}

            </div>
        </div>
    </div>
</div>
@endsection
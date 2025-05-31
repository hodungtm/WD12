@extends('Admin.layouts.AdminLayout')
@section('main')
<div class="app-title">
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item">Danh sách đơn hàng</li>
    <li class="breadcrumb-item"><a href="#">Xem chi tiết đơn hàng</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Chi tiết đơn hàng</h3>
      <div class="tile-body">
        {{-- THÔNG TIN CHUNG CỦA ĐƠN HÀNG --}}
        <form class="row">
          <div class="form-group col-md-4">
            <label>ID đơn hàng</label>
            <input class="form-control" type="text" value="{{ $order->id }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Tên khách hàng</label>
            <input class="form-control" type="text" value="{{ $order->user->name }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Số điện thoại</label>
            <input class="form-control" type="text" value="{{ $order->user->phone }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Địa chỉ</label>
            <input class="form-control" type="text" value="{{ $order->user->address }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Ngày đặt hàng</label>
            <input class="form-control" type="text" value="{{ $order->order_date }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Phương thức thanh toán</label>
            <input class="form-control" type="text" value="{{ $order->payment_method }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Tình trạng đơn hàng</label>
            <input class="form-control" type="text" value="{{ $order->status }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Tình trạng thanh toán</label>
            <input class="form-control" type="text" value="{{ $order->payment_status }}" readonly>
          </div>
          <div class="form-group col-md-12">
            <label>Ghi chú</label>
            <textarea class="form-control" readonly>{{ $order->note }}</textarea>
          </div>
        </form>
      </div>
    </div>

    {{-- PHẦN 2: DANH SÁCH SẢN PHẨM TRONG ĐƠN --}}
    <div class="tile mt-4">
      <h4 class="tile-title">Chi tiết sản phẩm</h4>
      <div class="tile-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên sản phẩm</th>
              <th>Ngày đặt hàng</th>
              <th>Số lượng</th>
              <th>Đơn giá</th>
              <th>Thành tiền</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->orderItems as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $order->order_date }}</td>
                <td>{{ $item->quantity }}</td>
                {{-- Lấy giá đã lưu trong OrderItem --}}
                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                {{-- Lấy tổng tiền đã lưu trong OrderItem --}}
                <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
              </tr>
            @endforeach
            <tr>
              <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
              {{-- Tính tổng tiền từ cột total_price của tất cả các OrderItem --}}
              <td><strong>{{ number_format($order->orderItems->sum('total_price'), 0, ',', '.') }}₫</strong></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="tile-footer">
        {{-- Đảm bảo route 'admin.orders.edit' có tồn tại và nhận ID --}}
        <a class="btn btn-primary" href="{{ route('admin.orders.edit', $order->id) }}">Cập nhật</a>
        <a class="btn btn-secondary" href="{{ route('admin.orders.index') }}">Trở lại</a>
      </div>
    </div>
  </div>
</div>
@endsection
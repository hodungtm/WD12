@extends('Admin.layouts.AdminLayout')
@section('main')
<div class="container-fluid">
  <div class="card mb-4">
    <div class="card-header border-bottom">
      <h5 class="mb-0">Chi tiết đơn hàng</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-md-4">
            <label>ID đơn hàng</label>
            <input class="form-control" value="{{ $order->id }}" readonly>
          </div>
          <div class="col-md-4">
            <label>Tên khách hàng</label>
            <input class="form-control" value="{{ $order->user->name }}" readonly>
          </div>
          <div class="col-md-4">
            <label>Số điện thoại</label>
            <input class="form-control" value="{{ $order->user->phone }}" readonly>
          </div>
          <div class="col-md-4 mt-3">
            <label>Địa chỉ</label>
            <input class="form-control" value="{{ $order->user->address }}" readonly>
          </div>
          <div class="col-md-4 mt-3">
            <label>Ngày đặt hàng</label>
            <input class="form-control" value="{{ $order->order_date }}" readonly>
          </div>
          <div class="col-md-4 mt-3">
            <label>Phương thức thanh toán</label>
            <input class="form-control" value="{{ $order->payment_method }}" readonly>
          </div>
          <div class="col-md-4 mt-3">
            <label>Tình trạng đơn hàng</label>
            <select class="form-control" name="status">
              <option value="Đang chờ" {{ $order->status === 'Đang chờ' ? 'selected' : '' }}>Đang chờ</option>
              <option value="Hoàn thành" {{ $order->status === 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
              <option value="Đã hủy" {{ $order->status === 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
            </select>
          </div>
          <div class="col-md-4 mt-3">
            <label>Tình trạng thanh toán</label>
            <select class="form-control" name="payment_status">
              <option value="Chờ thanh toán" {{ $order->payment_status === 'Chờ thanh toán' ? 'selected' : '' }}>Chờ thanh toán</option>
              <option value="Đã thanh toán" {{ $order->payment_status === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
            </select>
          </div>
          <div class="col-md-12 mt-3">
            <label>Ghi chú</label>
            <textarea class="form-control" rows="3" readonly>{{ $order->note }}</textarea>
          </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="mt-5">
          <h5 class="border-bottom pb-2">Chi tiết sản phẩm</h5>
          <div class="table-responsive">
            <table class="table table-bordered mt-3">
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
                  <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                  <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
          <td><strong>{{ number_format($order->orderItems->sum(function($item) { 
              return $item->price * $item->quantity; 
          }), 0, ',', '.') }}₫</strong></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        {{-- Nút --}}
        <div class="mt-4">
          <button type="submit" class="btn btn-warning">Cập nhật</button>
          <a href="{{ route('admin.orders.index') }}" class="btn btn-dark">Trở lại</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

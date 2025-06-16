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

    {{-- BẢNG 1: NGƯỜI ĐẶT & NGƯỜI NHẬN --}}
    <div class="tile">
      <h3 class="tile-title">Thông tin người đặt và người nhận</h3>
      <div class="tile-body">
        <form class="row">
          {{-- Người đặt --}}
          <div class="form-group col-md-4">
            <label>Người đặt</label>
            <input class="form-control" type="text" value="{{ $order->user->name ?? 'Không rõ' }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>SĐT người đặt</label>
            <input class="form-control" type="text" value="{{ $order->user->phone ?? '' }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Địa chỉ người đặt</label>
            <input class="form-control" type="text" value="{{ $order->user->address ?? '' }}" readonly>
          </div>

          {{-- Người nhận (nếu có) --}}
          <div class="form-group col-md-4">
            <label>Người nhận</label>
            <input class="form-control" type="text" value="{{ $order->receiver->name ?? 'Trùng người đặt' }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>SĐT người nhận</label>
            <input class="form-control" type="text" value="{{ $order->receiver->phone ?? '' }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Địa chỉ người nhận</label>
            <input class="form-control" type="text" value="{{ $order->receiver->address ?? '' }}" readonly>
          </div>
        </form>
      </div>
    </div>

    {{-- BẢNG 2: THÔNG TIN ĐƠN HÀNG --}}
    <div class="tile mt-4">
      <h3 class="tile-title">Thông tin đơn hàng</h3>
      <div class="tile-body">
        <form class="row">
          <div class="form-group col-md-4">
            <label>Mã đơn hàng</label>
            <input class="form-control" type="text" value="{{ $order->order_code }}" readonly>
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
            <label>Phương thức Vận Chuyển</label>
            <input class="form-control" type="text" value="{{ optional($order->shippingMethod)->name }}" readonly>
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

    {{-- BẢNG 3: DANH SÁCH SẢN PHẨM --}}
    <div class="tile mt-4">
      <h4 class="tile-title">Chi tiết sản phẩm</h4>
      <div class="tile-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên sản phẩm</th>
              <th>Số lượng</th>
              <th>Đơn giá</th>
              <th>Thành tiền</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->orderItems as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</td> {{-- Đảm bảo product có sẵn --}}
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->final_price ?? $item->price, 0, ',', '.') }}₫</td> {{-- Dùng final_price nếu có, không thì price --}}
                <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
              </tr>
            @endforeach
            <tr>
              <td colspan="4" class="text-end"><strong>Tổng tiền sản phẩm:</strong></td>
              <td><strong>{{ number_format($order->orderItems->sum('total_price'), 0, ',', '.') }}₫</strong></td>
            </tr>
            <tr>
              <td colspan="4" class="text-end"><strong>Phí vận chuyển:</strong></td>
              <td><strong>{{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫</strong></td>
            </tr>
            @if (($order->discount_amount ?? 0) > 0) {{-- Chỉ hiển thị nếu có giảm giá --}}
            <tr>
              <td colspan="4" class="text-end"><strong>Giảm giá:</strong></td>
              <td><strong>- {{ number_format($order->discount_amount, 0, ',', '.') }}₫</strong></td>
            </tr>
            @endif
            <tr>
              <td colspan="4" class="text-end"><strong>Tổng thanh toán:</strong></td>
              <td><strong>{{ number_format($order->final_amount, 0, ',', '.') }}₫</strong></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="tile-footer">
        <a class="btn btn-primary" href="{{ route('admin.orders.edit', $order->id) }}">Cập nhật</a>
        <a class="btn btn-secondary" href="{{ route('admin.orders.index') }}">Trở lại</a>
      </div>
    </div>

  </div>
</div>

@endsection
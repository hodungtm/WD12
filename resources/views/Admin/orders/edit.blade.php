@extends('Admin.layouts.AdminLayout')
@section('main')
<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0">Chỉnh sửa đơn hàng</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- BẢNG 1: NGƯỜI ĐẶT & NGƯỜI NHẬN --}}
                <div class="tile">
                    <h3 class="tile-title">Thông tin người đặt và người nhận</h3>
                    <div class="tile-body">
                        <div class="row">
                            {{-- Người đặt (Editable) --}}
                            <div class="form-group col-md-4">
                                <label>Người đặt</label>
                                <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn Người Đặt --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>SĐT người đặt</label>
                                <input class="form-control" type="text" value="{{ $order->user->phone ?? '' }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Địa chỉ người đặt</label>
                                <input class="form-control" type="text" value="{{ $order->user->address ?? '' }}" readonly>
                            </div>

                            {{-- Người nhận (Editable) --}}
                            <div class="form-group col-md-4">
                                <label>Người nhận</label>
                                {{-- Đảm bảo name="receiver_id" và nó khớp với logic controller --}}
                                <select name="receiver_id" class="form-control @error('receiver_id') is-invalid @enderror">
                                    <option value="">-- Trùng người đặt --</option> {{-- Giá trị rỗng nếu người nhận trùng người đặt --}}
                                    @foreach($receivers as $receiver)
                                        <option value="{{ $receiver->id }}" {{ $order->receiver_id == $receiver->id ? 'selected' : '' }}>
                                            {{ $receiver->name }} ({{ $receiver->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('receiver_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>SĐT người nhận</label>
                                <input class="form-control" type="text" value="{{ $order->receiver->phone ?? '' }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Địa chỉ người nhận</label>
                                <input class="form-control" type="text" value="{{ $order->receiver->address ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BẢNG 2: THÔNG TIN ĐƠN HÀNG --}}
                <div class="tile mt-4">
                    <h3 class="tile-title">Thông tin đơn hàng</h3>
                    <div class="tile-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Mã đơn hàng</label>
                                <input class="form-control" type="text" value="{{ $order->order_code }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Ngày đặt hàng</label>
                                <input type="date" class="form-control @error('order_date') is-invalid @enderror" name="order_date" value="{{ $order->order_date }}" required>
                                @error('order_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Phương thức thanh toán</label>
                                <select class="form-control @error('payment_method') is-invalid @enderror" name="payment_method" required>
                                    <option value="Tiền mặt" {{ $order->payment_method === 'Tiền mặt' ? 'selected' : '' }}>Tiền mặt</option>
                                    <option value="Chuyển khoản ngân hàng" {{ $order->payment_method === 'Chuyển khoản ngân hàng' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                                    <option value="Momo" {{ $order->payment_method === 'Momo' ? 'selected' : '' }}>Momo</option>
                                    <option value="ZaloPay" {{ $order->payment_method === 'ZaloPay' ? 'selected' : '' }}>ZaloPay</option>
                                </select>
                                @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Phương thức Vận Chuyển</label>
                                <select name="shipping_method_id" id="shipping-method-edit" class="form-control @error('shipping_method_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn phương thức vận chuyển --</option>
                                    @foreach ($shippingMethods as $method)
                                        <option value="{{ $method->id }}" data-fee="{{ $method->fee }}" {{ $order->shipping_method_id == $method->id ? 'selected' : '' }}>
                                            {{ $method->name }} ({{ number_format($method->fee, 0, ',', '.') }}₫)
                                        </option>
                                    @endforeach
                                </select>
                                @error('shipping_method_id') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="form-text text-muted mt-2">
                                    <strong>Phí vận chuyển:</strong> <span id="shipping-fee-edit">
                                        {{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫
                                    </span>
                                </small>
                            </div>
                             <div class="form-group col-md-4">
                                <label>Mã giảm giá</label>
                                <select name="discount_id" class="form-control @error('discount_id') is-invalid @enderror">
                                    <option value="">-- Không có giảm giá --</option>
                                    @foreach ($discounts as $discount)
                                        <option value="{{ $discount->id }}" data-amount="{{ $discount->amount ?? 0 }}" {{ $order->discount_id == $discount->id ? 'selected' : '' }}>
                                            {{ $discount->name }} ({{ number_format($discount->amount ?? 0, 0, ',', '.') }}₫)
                                        </option>
                                    @endforeach
                                </select>
                                @error('discount_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Tình trạng đơn hàng</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status">
                                    <option value="Đang chờ" {{ $order->status === 'Đang chờ' ? 'selected' : '' }}>Đang chờ</option>
                                    <option value="Hoàn thành" {{ $order->status === 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="Đã hủy" {{ $order->status === 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tình trạng thanh toán</label>
                                <select class="form-control @error('payment_status') is-invalid @enderror" name="payment_status">
                                    <option value="Chờ thanh toán" {{ $order->payment_status === 'Chờ thanh toán' ? 'selected' : '' }}>Chờ thanh toán</option>
                                    <option value="Đã thanh toán" {{ $order->payment_status === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                </select>
                                @error('payment_status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group col-md-12 mt-3">
                                <label>Ghi chú</label>
                                <textarea class="form-control" rows="3" name="note">{{ $order->note }}</textarea>
                                @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BẢNG 3: DANH SÁCH SẢN PHẨM --}}
                <div class="tile mt-4">
                    <h4 class="tile-title">Chi tiết sản phẩm</h4>
                    <div class="tile-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mt-3">
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
                                        <td>{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->final_price ?? $item->price, 0, ',', '.') }}₫</td>
                                        <td>{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Tổng tiền sản phẩm:</strong></td>
                                        <td><strong>{{ number_format($order->orderItems->sum('total_price'), 0, ',', '.') }}₫</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                        <td><strong>{{ number_format(optional($order->shippingMethod)->fee ?? 0, 0, ',', '.') }}₫</strong></td>
                                    </tr>
                                    @if (($order->discount_amount ?? 0) > 0)
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Giảm giá:</strong></td>
                                        <td><strong>- {{ number_format($order->discount_amount, 0, ',', '.') }}₫</strong></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Tổng thanh toán:</strong></td>
                                        <td><strong>{{ number_format($order->final_amount, 0, ',', '.') }}₫</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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

<script>
    // JavaScript cho Phí vận chuyển (khi chỉnh sửa)
    document.addEventListener('DOMContentLoaded', function () {
        const shippingMethodSelect = document.getElementById('shipping-method-edit');
        const shippingFeeDisplay = document.getElementById('shipping-fee-edit');

        if (shippingMethodSelect) {
            shippingMethodSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const fee = parseFloat(selected.getAttribute('data-fee')) || 0; // Sử dụng parseFloat cho số thập phân
                shippingFeeDisplay.innerText = fee.toLocaleString('vi-VN') + '₫';
            });

            // Gọi lần đầu để đảm bảo phí hiển thị đúng khi tải trang
            const initialSelected = shippingMethodSelect.options[shippingMethodSelect.selectedIndex];
            const initialFee = parseFloat(initialSelected.getAttribute('data-fee')) || 0;
            shippingFeeDisplay.innerText = initialFee.toLocaleString('vi-VN') + '₫';
        }
    });

    // JavaScript cho Mã giảm giá (nếu bạn muốn hiển thị số tiền giảm giá động)
    // Tương tự, bạn có thể thêm logic cho trường giảm giá nếu bạn muốn cập nhật số tiền giảm giá động trên form
    // Ví dụ:
    // document.addEventListener('DOMContentLoaded', function () {
    //     const discountSelect = document.querySelector('select[name="discount_id"]');
    //     if (discountSelect) {
    //         discountSelect.addEventListener('change', function() {
    //             const selectedOption = this.options[this.selectedIndex];
    //             const discountAmount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
    //             // Cập nhật một span hiển thị số tiền giảm giá nếu bạn có nó
    //             // Ví dụ: document.getElementById('discount-amount-display').innerText = discountAmount.toLocaleString('vi-VN') + '₫';
    //             console.log('Discount selected:', discountAmount);
    //         });
    //     }
    // });
</script>

@endsection
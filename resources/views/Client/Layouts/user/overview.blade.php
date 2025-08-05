@extends('layoutsClient.Layouts.ClientLayout')

@section('main')
<div class="my-account pt-80 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="title text-capitalize mb-30 pb-25">my account</h3>
            </div>

            <!-- Menu trái -->
            <div class="col-lg-3 col-12 mb-30">
                <div class="myaccount-tab-menu nav" role="tablist">
                    <a href="#dashboad" data-bs-toggle="tab"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    <a href="#orders" data-bs-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders</a>
                    <a href="#download" data-bs-toggle="tab"><i class="fas fa-cloud-download-alt"></i> Download</a>
                    <a href="#payment-method" data-bs-toggle="tab"><i class="fa fa-credit-card"></i> Payment</a>
                    <a href="#address-edit" data-bs-toggle="tab"><i class="fa fa-map-marker"></i> Address</a>
                    <a href="#account-info" data-bs-toggle="tab" class="active"><i class="fa fa-user"></i> Account</a>
                    <a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                </div>
            </div>

            <!-- Nội dung bên phải -->
            <div class="col-lg-9 col-12 mb-30">
                <div class="tab-content" id="myaccountContent">

                    <!-- Dashboard -->
                    <div class="tab-pane fade" id="dashboad" role="tabpanel">
                        <div class="myaccount-content">
                            <h3>Dashboard</h3>
                            <div class="welcome mb-20">
                                <p>Hello, <strong>{{ $user->name }}</strong> (If Not <strong>{{ $user->name }}</strong>? <a href="{{ route('logout') }}" class="logout"> Logout</a>)</p>
                            </div>
                            <p class="mb-0">Bạn có thể xem đơn hàng, địa chỉ và chỉnh sửa tài khoản của mình tại đây.</p>
                        </div>
                    </div>

                    <!-- Orders -->
                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <div class="myaccount-content">
                            <h3>Orders</h3>
                            <div class="myaccount-table table-responsive text-center">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td>{{ ucfirst($order->status) }}</td>
                                                <td>{{ number_format($order->total_price) }}₫</td>
                                                <td><a href="#" class="ht-btn black-btn">View</a></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">Bạn chưa có đơn hàng nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Download -->
                    <div class="tab-pane fade" id="download" role="tabpanel">
                        <div class="myaccount-content">
                            <h3>Downloads</h3>
                            <p class="mb-0">Hiện tại bạn không có file tải nào.</p>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="tab-pane fade" id="payment-method" role="tabpanel">
                        <div class="myaccount-content">
                            <h3>Payment Method</h3>
                            <p class="saved-message">Bạn chưa lưu phương thức thanh toán.</p>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="tab-pane fade" id="address-edit" role="tabpanel">
                        <div class="myaccount-content">
                            <h3>Billing Address</h3>
                            @forelse($addresses as $address)
                                <address>
                                    <p><strong>{{ $address->name }}</strong></p>
                                    <p>{{ $address->address }}</p>
                                    <p>Mobile: {{ $address->phone }}</p>
                                </address>
                            @empty
                                <p>Bạn chưa thêm địa chỉ nhận hàng.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="tab-pane fade active show" id="account-info" role="tabpanel">
                        <div class="myaccount-content">
                            <h3>Account Details</h3>
                            <div class="account-details-form">
                                <form>
                                    <div class="row">
                                        <div class="col-lg-6 col-12 mb-30">
                                            <input placeholder="Họ và tên" type="text" value="{{ $user->name }}" disabled>
                                        </div>

                                        <div class="col-12 mb-30">
                                            <input placeholder="Email" type="email" value="{{ $user->email }}" disabled>
                                        </div>

                                        <div class="col-12 mb-30">
                                            <input placeholder="Số điện thoại" type="text" value="{{ $user->phone }}" disabled>
                                        </div>

                                        <div class="col-12 mb-30">
                                            <h4>Password change</h4>
                                            <p>Vui lòng vào mục đổi mật khẩu riêng để thay đổi.</p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Account Info -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

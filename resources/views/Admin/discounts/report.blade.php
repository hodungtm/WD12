@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="container">
    <h2>Báo cáo lượt sử dụng mã giảm giá</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã giảm giá</th>
                <th>Người dùng</th>
                <th>Mã đơn hàng</th>
                <th>Thời gian sử dụng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usages as $usage)
                <tr>
                    <td>{{ $usage->discount->code }}</td>
                    <td>{{ $usage->user->name ?? 'Khách' }}</td>
                    <td>{{ $usage->order_code ?? 'N/A' }}</td>
                    <td>{{ $usage->used_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $usages->links() }}
</div>
@endsection

@extends('Admin.Layouts.AdminLayout')

@section('title', 'Báo cáo sử dụng mã giảm giá')

@section('main')
    <h2>Báo cáo sử dụng mã giảm giá</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Mô tả</th>
                <th>Số lượt đã sử dụng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($discounts as $discount)
                <tr>
                    <td>{{ $discount->code }}</td>
                    <td>{{ $discount->description }}</td>
                    <td>{{ $discount->usages_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

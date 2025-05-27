<!DOCTYPE html>
<html>
<head>
    <title>Báo cáo sử dụng mã giảm giá</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #000; padding: 5px; font-size: 12px; }
    </style>
</head>
<body>
    <h3>Báo cáo sử dụng mã giảm giá</h3>
    <table>
        <thead>
            <tr>
                <th>Mã giảm giá</th>
                <th>Người dùng</th>
                <th>Mã đơn hàng</th>
                <th>Ngày sử dụng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usages as $usage)
                <tr>
                    <td>{{ $usage->discount->code }}</td>
                    <td>{{ optional($usage->user)->name ?? 'Khách' }}</td>
                    <td>{{ $usage->order_code }}</td>
                    <td>{{ $usage->used_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function overview()
    {
        // Dữ liệu demo, bạn có thể dùng model User, Order... nếu đã có
        $user = (object)[
            'name' => 'Nguyễn Văn A',
            'email' => 'vana@gmail.com',
            'phone' => '0909123456'
        ];

        $orders = collect([
            (object)[
                'id' => 1,
                'name' => 'Giày Thể Thao',
                'created_at' => '2025-06-10',
                'status' => 'Đang xử lý',
                'total_price' => 450000
            ],
            (object)[
                'id' => 2,
                'name' => 'Áo Thun',
                'created_at' => '2025-06-11',
                'status' => 'Hoàn thành',
                'total_price' => 199000
            ]
        ]);

        $addresses = collect([
            (object)[
                'name' => 'Nguyễn Văn A',
                'address' => '123 đường Lê Lợi, Quận 1, TP.HCM',
                'phone' => '0909123456'
            ]
        ]);

       return view('Client.Layouts.user.overview', compact('user', 'orders', 'addresses'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
public function dashboard()
    {
        // Dữ liệu giả lập
        $user = (object)[
            'name' => 'Tien Dung Pham',
            'purchased' => [],
            'favorites' => [],
        ];

        return view('client.users.dashboard', compact('user'));
    }

}




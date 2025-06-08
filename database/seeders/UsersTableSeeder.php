<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::truncate(); // Xóa dữ liệu cũ (cẩn thận khi dùng)

        User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'), // Mật khẩu test
            // Thêm các trường khác nếu cần
        ]);
    }
}

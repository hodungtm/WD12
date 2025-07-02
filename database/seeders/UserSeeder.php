<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'phone' => '0912345678',
            'gender' => 'male',
            'address' => '123 Đường Lê Lợi, TP.HCM',
            'dob' => '1990-01-01',
            'avatar' => 'avatars/user1.jpg',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Trần Thị B',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'gender' => 'female',
            'address' => '456 Đường Trần Hưng Đạo, Hà Nội',
            'dob' => '1995-05-15',
            'avatar' => 'avatars/user2.jpg',
            'is_active' => false,
        ]);
    }
}

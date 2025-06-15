<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Danh sách admins
        $admins = [
            [
                'name' => 'Admin One',
                'email' => 'admin1@example.com',
                'avatar' => 'admin1.jpg',
                'phone' => '0909000001',
                'gender' => 'male',
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'avatar' => 'admin2.jpg',
                'phone' => '0909000002',
                'gender' => 'female',
            ],
            [
                'name' => 'Admin Three',
                'email' => 'admin3@example.com',
                'avatar' => 'admin3.jpg',
                'phone' => '0909000003',
                'gender' => 'other',
            ],
        ];

        foreach ($admins as $admin) {
            User::create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => Hash::make('password'),
                'avatar' => $admin['avatar'],
                'phone' => $admin['phone'],
                'gender' => $admin['gender'],
                'is_active' => true,
                'role' => 'admin',
            ]);
        }

        // Danh sách users
        $users = [
            [
                'name' => 'User One',
                'email' => 'user1@example.com',
                'avatar' => 'user1.jpg',
                'phone' => '0911000001',
                'gender' => 'male',
            ],
            [
                'name' => 'User Two',
                'email' => 'user2@example.com',
                'avatar' => 'user2.jpg',
                'phone' => '0911000002',
                'gender' => 'female',
            ],
            [
                'name' => 'User Three',
                'email' => 'user3@example.com',
                'avatar' => 'user3.jpg',
                'phone' => '0911000003',
                'gender' => 'other',
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'avatar' => $user['avatar'],
                'phone' => $user['phone'],
                'gender' => $user['gender'],
                'is_active' => true,
                'role' => 'user',
            ]);
        }
    }
}

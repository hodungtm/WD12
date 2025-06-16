<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'super-admin', 'description' => 'Toàn quyền quản trị hệ thống'],
            ['name' => 'editor', 'description' => 'Chỉnh sửa nội dung'],
            ['name' => 'moderator', 'description' => 'Quản lý bình luận và người dùng'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}

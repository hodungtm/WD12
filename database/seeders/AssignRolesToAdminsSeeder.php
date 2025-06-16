<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;

class AssignRolesToAdminsSeeder extends Seeder
{
    public function run(): void
    {
        // Gán quyền mặc định (ví dụ: role_id = 1) cho tất cả admins
        $defaultRole = Role::first(); // Hoặc: Role::where('name', 'admin')->first();

        if (!$defaultRole) {
            $this->command->warn('Không tìm thấy role để gán.');
            return;
        }

        $admins = Admin::all();

        foreach ($admins as $admin) {
            $admin->roles()->syncWithoutDetaching([$defaultRole->id]);
            $this->command->info("Gán role '{$defaultRole->name}' cho admin: {$admin->email}");
        }
    }
}

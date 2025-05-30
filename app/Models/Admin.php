<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
   use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Quan hệ: 1 Admin có nhiều Roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_admin');
    }

    // Quan hệ: 1 Admin có nhiều log hoạt động
    public function activityLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // Kiểm tra quyền có trong Role
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    // Gán role cho admin
    public function assignRole($roleId)
    {
        $this->roles()->syncWithoutDetaching([$roleId]);
    }

    // Bỏ role
    public function removeRole($roleId)
    {
        $this->roles()->detach($roleId);
    }

    // Đặt mật khẩu mới
    public function setPassword(string $plainPassword)
    {
        $this->update(['password' => Hash::make($plainPassword)]);
    }

    // Ghi log hoạt động
    public function logAction(string $action, ?string $description = null)
    {
        $this->activityLogs()->create([
            'action' => $action,
            'description' => $description,
        ]);
    }

    // Khóa / Mở khóa tài khoản
    public function toggleStatus()
    {
        $this->is_active = !$this->is_active;
        $this->save();
    }
    
}

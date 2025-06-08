<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Hiển thị danh sách admin (có phân trang và tìm kiếm)
    public function index(Request $request)
    {
        $query = Admin::with('roles'); // thêm eager load roles

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

        $admins = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }


    // Hiển thị form tạo admin mới
    public function create()
    {
        $roles = Role::all();
        return view('admin.admins.create', compact('roles'));
    }

    // Xử lý lưu admin mới
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email',
        'password' => 'required|string|min:6|confirmed',
        'roles' => 'array',
        'roles.*' => 'exists:roles,id',
    ]);

    $admin = Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'is_active' => $request->input('is_active', true),
    ]);

    // Gán roles
    $admin->roles()->sync($request->input('roles', []));

    // Ghi log sau khi tạo thành công
    AuditLog::create([
        'admin_id' => Auth::id(),
        'action' => 'create',
        'target_type' => 'Admin',
        'target_id' => $admin->id,
        'description' => 'Tạo admin mới: ' . $admin->email,
        'ip_address' => request()->ip(),
    ]);

    return redirect()->route('admin.admins.index')->with('success', 'Tạo tài khoản admin thành công');
}


    // Hiển thị form chỉnh sửa admin
    public function edit(Admin $admin)
    {
        $roles = Role::all();
        $adminRoles = $admin->roles()->pluck('id')->toArray();
        return view('admin.admins.edit', compact('admin', 'roles', 'adminRoles'));
    }

    // Xử lý cập nhật admin
    public function update(Request $request, Admin $admin)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
        'password' => 'nullable|string|min:6|confirmed',
        'roles' => 'array',
        'roles.*' => 'exists:roles,id',
        'is_active' => 'required|boolean',
    ]);

    $admin->name = $request->name;
    $admin->email = $request->email;
    $admin->is_active = $request->is_active;

    if ($request->filled('password')) {
        $admin->password = Hash::make($request->password);
    }

    $admin->save();

    if ($request->has('roles')) {
        $admin->roles()->sync($request->roles);
    } else {
        $admin->roles()->detach();
    }

    AuditLog::create([
        'admin_id' => Auth::id(),
        'action' => 'update',
        'target_type' => 'Admin',
        'target_id' => $admin->id,
        'description' => 'Cập nhật admin: ' . $admin->email,
        'ip_address' => request()->ip(),
    ]);

    return redirect()->route('admin.admins.index')->with('success', 'Cập nhật tài khoản admin thành công');
}

    // Xóa tài khoản admin thật sự
 public function destroy(Admin $admin)
{
    $adminId = $admin->id;
    $adminEmail = $admin->email;

    $admin->roles()->detach();
    $admin->delete();

    AuditLog::create([
        'admin_id' => Auth::id(),
        'action' => 'delete',
        'target_type' => 'Admin',
        'target_id' => $adminId,
        'description' => 'Xóa admin: ' . $adminEmail,
        'ip_address' => request()->ip(),
    ]);

    return redirect()->route('admin.admins.index')->with('success', 'Xóa tài khoản admin thành công');
}
}

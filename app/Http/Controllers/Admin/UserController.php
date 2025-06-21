<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Lọc theo từ khóa tìm kiếm
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo vai trò
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderByDesc('id')->paginate(10);

        return view('Admin.Users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('Admin.Users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Chỉ cho phép sửa nếu là Admin
        if ($user->role !== 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Không thể chỉnh sửa tài khoản người dùng.');
        }

        return view('Admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Không thể cập nhật tài khoản người dùng.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'password' => 'nullable|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->gender = $request->gender;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật tài khoản thành công!');
    }



   public function toggleActive(User $user)
{
    // Không cho phép khóa tài khoản admin
    if ($user->role === 'admin') {
        return redirect()->back()->with('error', 'Không thể khóa tài khoản admin.');
    }

    $user->is_active = !$user->is_active;
    $user->save();

    AuditLog::create([
        'admin_id' => Auth::id(),
        'action' => 'update',
        'target_type' => 'User',
        'target_id' => $user->id,
        'description' => ($user->is_active ? 'Mở khóa' : 'Khóa') . ' tài khoản: ' . $user->email,
        'ip_address' => request()->ip(),
    ]);

    return redirect()->back()->with('success', 'Trạng thái tài khoản đã được cập nhật!');
}



    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa tài khoản thành công.');
    }
}

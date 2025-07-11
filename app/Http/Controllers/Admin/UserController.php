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

        // Mặc định chỉ lấy role = 'user' nếu không có filter
        if (!$request->filled('role')) {
            $query->where('role', 'user');
        }

        // Tìm kiếm theo tên hoặc email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Nếu có filter role (vd: admin), áp dụng tiếp
        if ($request->filled('role')) {
            $query->where('role', strtolower($request->role));
        }

        $users = $query->orderByDesc('id')->paginate(10)->appends($request->query());

        return view('Admin.Users.index', compact('users'));
    }


    public function show(User $user)
    {
        return view('Admin.Users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $auth = Auth::user();

        // Admin chỉ được sửa chính mình
        if ($auth->role === 'admin' && $auth->id === $user->id) {
            return view('Admin.Users.edit', compact('user'));
        }

        // Các trường hợp khác thì không cho phép
        return redirect()->route('admin.users.index')->with('error', 'Bạn không có quyền sửa tài khoản này.');
    }

    public function update(Request $request, User $user)
    {
        $auth = Auth::user();

        // Admin chỉ được cập nhật chính mình
        if (!($auth->id === $user->id && $auth->role === 'admin')) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn chỉ có thể cập nhật thông tin của chính mình.');
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

    public function editPassword(User $user)
    {
        if (Auth::id() !== $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn chỉ có thể đổi mật khẩu của chính mình.');
        }

        return view('Admin.Users.change_password', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        if (Auth::id() !== $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể đổi mật khẩu tài khoản khác.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.users.edit-password', $user)->with('success', 'Đổi mật khẩu thành công!');
    }

    public function verifyPasswordAjax(Request $request, User $user)
    {
        if (Auth::id() !== $user->id) {
            return response()->json(['verified' => false], 403);
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['verified' => true]);
        }

        return response()->json(['verified' => false]);
    }




    public function toggleActive(User $user)
    {
        $auth = Auth::user();

        // Super-admin không được tự khóa mình hoặc khóa super-admin khác
        if ($auth->role === 'super-admin') {
            if ($user->id === $auth->id) {
                return redirect()->back()->with('error', 'Bạn không thể tự khóa chính mình.');
            }
            if ($user->role === 'super-admin') {
                return redirect()->back()->with('error', 'Không thể khóa tài khoản super-admin khác.');
            }
        }

        // Admin thường không được khóa admin khác hoặc chính mình
        if ($auth->role === 'admin') {
            if ($user->role === 'admin' || $auth->id === $user->id) {
                return redirect()->back()->with('error', 'Bạn không thể khóa tài khoản admin.');
            }
        }

        $user->is_active = !$user->is_active;
        $user->save();

        // AuditLog::create([
        //     'user_id' => $auth->id,
        //     'action' => 'update',
        //     'target_type' => 'User',
        //     'target_id' => $user->id,
        //     'description' => ($user->is_active ? 'Mở khóa' : 'Khóa') . ' tài khoản: ' . $user->email,
        //     'ip_address' => request()->ip(),
        // ]);

        return redirect()->back()->with('success', 'Trạng thái tài khoản đã được cập nhật!');
    }
    public function destroy(User $user)
    {
        $auth = Auth::user();

        // Chỉ cho super-admin thực hiện
        if ($auth->role !== 'super-admin') {
            return redirect()->route('admin.users.index')->with('error', 'Chỉ super-admin mới có quyền xóa tài khoản.');
        }

        // Không được xóa chính mình
        if ($auth->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể xóa chính tài khoản của mình.');
        }

        // Chỉ được xóa admin thường, không được xóa super-admin
        if ($user->role !== 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Chỉ được xóa các tài khoản admin thông thường.');
        }

        // Thực hiện xóa
        $user->delete();

        // AuditLog::create([
        //     'user_id' => $auth->id,
        //     'action' => 'delete',
        //     'target_type' => 'User',
        //     'target_id' => $user->id,
        //     'description' => 'Xóa tài khoản admin: ' . $user->email,
        //     'ip_address' => request()->ip(),
        // ]);

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa tài khoản admin thành công!');
    }

}

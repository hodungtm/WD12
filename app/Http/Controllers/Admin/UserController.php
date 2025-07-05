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

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', strtolower($request->role)); // an toàn, dù URL gửi 'Admin' hay 'admin'
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
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn chỉ có thể sửa tài khoản admin của chính mình.');
        }

        return view('Admin.Users.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn chỉ có thể cập nhật tài khoản admin của chính mình.');
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
        // Không được khóa admin nào cả (kể cả chính mình)
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Không thể khóa tài khoản admin.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        AuditLog::create([
            'user_id' => Auth::id(),
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
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể xóa tài khoản admin của chính mình.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Xóa tài khoản thành công.');
    }


    // public function destroyMultiple(Request $request)
    // {
    //     $ids = $request->input('selected_users', []);
    //     if (!empty($ids)) {
    //         User::whereIn('id', $ids)->delete();
    //         return redirect()->route('admin.users.index')->with('success', 'Đã xóa các tài khoản được chọn!');
    //     }

    //     return redirect()->route('admin.users.index')->with('error', 'Không có tài khoản nào được chọn!');
    // }

    public function deleteSelected(Request $request)
{
    $ids = $request->input('selected_users');

    if (!$ids || count($ids) === 0) {
        return redirect()->back()->with('error', 'Bạn chưa chọn tài khoản nào để xóa.');
    }

    // Không xóa tài khoản có role là 'admin'
    $deletedCount = User::whereIn('id', $ids)
        ->where('role', '!=', 'admin')
        ->delete();

    return redirect()->route('admin.users.index')
        ->with('success', "Đã xóa $deletedCount tài khoản đã chọn thành công.");
}

}
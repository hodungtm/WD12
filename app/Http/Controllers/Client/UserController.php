<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
    use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // ✅ Cập nhật thông tin người dùng
    public function updateInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return back()->with('success_info', 'Cập nhật thông tin thành công!');
    }

    // ✅ Đổi mật khẩu người dùng
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success_pass', 'Đổi mật khẩu thành công!');
    }



public function saveAddressSession(Request $request)
{
    $validated = $request->validate([
        'detail' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'country' => 'required|string|max:100',
    ]);

    session([
        'address.detail' => $validated['detail'],
        'address.city' => $validated['city'],
        'address.country' => $validated['country'],
    ]);

    return back()->with('success_address', 'Cập nhật địa chỉ giao hàng thành công!');
}



}

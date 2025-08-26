<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = \Auth::user();
        $orderItems = $user->orderItems()->with(['order', 'product'])->latest('created_at')->get();
        $wishlists = $user->wishlist()->with([
            'product.images',
            'product.variants.size',
            'product.variants.color',
        ])->get();
        return view('Client.users.dashboard', compact('user', 'orderItems', 'wishlists'));
    }
 public function updateInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $user->avatar = '/storage/' . $path;
        }
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



public function saveAddress(Request $request)
{
    $validated = $request->validate([
        'address' => 'required|string|max:255',
    ]);

    /** @var User $user */
    $user = Auth::user();
    $user->address = $validated['address'];
    $user->save();

    return back()->with('success_address', 'Cập nhật địa chỉ giao hàng thành công!');
}

}

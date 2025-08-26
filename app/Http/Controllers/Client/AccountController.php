<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = \Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['orderItems.product.images', 'orderItems.variant'])
            ->orderBy('created_at', 'desc')
            ->get();
        $wishlists = $user->wishlist()
            ->with([
                'product.images',
                'product.variants.size',
                'product.variants.color',
            ])
            ->get();
        return view('Client.users.dashboard', compact('user', 'orders', 'wishlists'));
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
            $user->avatar = $path; // ✅ Chỉ lưu "avatars/xxx.jpg"
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

    /**
     * Continue MoMo payment for an existing order created earlier.
     * Sets checkout data in session and redirects to existing momo.payment flow.
     */
    public function continueMomo(Order $order)
    {
        $user = Auth::user();

        if (!$user || $order->user_id !== $user->id) {
            abort(403);
        }

        $paymentMethod = trim(mb_strtolower($order->payment_method ?? ''));
        $paymentStatus = trim(mb_strtolower($order->payment_status ?? ''));

        if (!str_contains($paymentMethod, 'momo')) {
            return back()->with('error', 'Phương thức thanh toán không phải MoMo.');
        }

        // Nếu đã rõ ràng là đã thanh toán thì không cho tiếp tục
        if (preg_match('/(đã\s*thanh\s*toán|paid|thành\s*công|success|completed)/iu', $paymentStatus)) {
            return back()->with('info', 'Đơn hàng đã được thanh toán.');
        }

        // lưu data cần thiết cho flow checkout momo
        session(['checkout_data' => [
            'order_id'     => $order->id,
            'final_amount' => $order->final_amount ?? $order->total_price ?? 0,
            'order_code'   => $order->order_code ?? null,
        ]]);

        return redirect()->route('momo.payment');
    }
}

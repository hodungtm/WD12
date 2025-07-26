<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;          // thêm ở đầu file nếu chưa có
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/products';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    /**
     * 1️⃣ Chỉ cho phép đăng nhập nếu is_active = 1
     */
    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['is_active' => 1]          // ràng buộc tài khoản còn hoạt động
        );
    }

    /**
     * 2️⃣ Trả về thông báo chi tiết khi đăng nhập thất bại
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = \App\Models\User::where($this->username(), $request->{$this->username()})->first();

        // Đúng email nhưng tài khoản bị khóa
        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([
                $this->username() => ['Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'],
            ]);
        }

        // Sai thông tin đăng nhập
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
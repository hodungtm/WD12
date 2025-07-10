<?php

namespace App\Http\Middleware;

use App\Models\Receiver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    if (!Auth::check() || !Auth::user()->isRoleAdmin()) {
        return redirect()->route('home')->withErrors('Bạn Không Có Quyền Đăng Nhập Trang Này');
    }

    return $next($request);
}

}

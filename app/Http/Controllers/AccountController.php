<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function dashboard()
{
    $user = \Auth::user();
    $orderItems = $user->orderItems()->with(['order', 'product'])->latest('created_at')->get();
    return view('Client.users.dashboard', compact('user', 'orderItems'));
}


}

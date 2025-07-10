<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function dashboard()
{
    $user = Auth::user();

    if ($user) {
        $user->loadMissing(['wishlist', 'orders']);
    }

    return view('client.users.dashboard', compact('user'));
}


}

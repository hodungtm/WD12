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
        $wishlists = $user->wishlist()->with([
            'product.images',
            'product.variants.size',
            'product.variants.color',
        ])->get();
        return view('Client.users.dashboard', compact('user', 'orderItems', 'wishlists'));
    }


}

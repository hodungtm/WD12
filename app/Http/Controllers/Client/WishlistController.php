<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

// Xong home
class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with([
            'product.images',
            'product.variants.size',
            'product.variants.color',
        ])
            ->where('user_id', Auth::id())
            ->get();
        $banners = Banner::with('hinhAnhBanner')
            ->where('loai_banner', 'slider')
            ->where('trang_thai', 'hien')
            ->latest()
            ->get();
        // dd($banners);
        return view('Client.wishlist.wishlist', compact('wishlists'));
    }

    public function add($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm sản phẩm vào yêu thích.');
        }

        $userId = Auth::id();
        $exists = Wishlist::where('user_id', $userId)
        ->where('product_id', $id)
        ->exists();
        
        if (!$exists) {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $id,
            ]);
        }

        return back()->with('success', 'Đã thêm vào danh sách yêu thích!');
    }


    public function remove($id)
    {
        // dd($id, auth()->id());
        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        // dd($wishlist);
        if ($wishlist) {
            $wishlist->delete();
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi yêu thích.');
    }
}

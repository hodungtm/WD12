<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Post;
// Xong home
class HomeController extends Controller
{
    public function index()
    {
        // Banner
        $banners = Banner::with('hinhAnhBanner')
            ->where('loai_banner', 'slider')
            ->where('trang_thai', 'hien')
            ->latest()
            ->get();

        // Danh mục + sản phẩm theo danh mục
        $categories = Category::where('tinh_trang', 1)
            ->with(['products' => function ($query) {
                $query->with('firstImage')
                    ->latest()
                    ->take(8);
            }])
            ->orderBy('ten_danh_muc')
            ->get();

        // Bài viết
        $posts = Post::where('status', 'published')->latest()->take(6)->get();

        return view('client.index', compact('banners', 'categories', 'posts'));
    }
}

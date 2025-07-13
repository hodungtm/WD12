<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Products;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Banner slider
        $banners = Banner::with('hinhAnhBanner')
            ->where('loai_banner', 'slider')
            ->where('trang_thai', 'hien')
            ->latest()
            ->get();

        // Danh mục
        $categories = Category::where('tinh_trang', 1)
            ->orderBy('ten_danh_muc')
            ->get();

        // Sản phẩm nổi bật (lấy 10 sản phẩm có đánh giá trung bình cao nhất)
        $products = Products::with(['images', 'variants', 'reviews'])
            ->where('status', 1)
            ->withAvg('reviews', 'so_sao')
            ->orderByDesc('reviews_avg_so_sao')
            ->take(10)
            ->get();

        // Sản phẩm trending (lấy 10 sản phẩm có nhiều lượt mua nhất)
        $trendingProducts = Products::with(['images', 'variants', 'reviews'])
            ->where('status', 1)
            ->orderByDesc('sold')
            ->take(10)
            ->get();

       
        

        // Blog/tin tức
        $posts = Post::where('status', 'published')->latest()->take(6)->get();

        // Banner nhỏ (footer, khuyến mãi...)
        $footerBanners = Banner::with('hinhAnhBanner')
            ->where('loai_banner', 'footer')
            ->where('trang_thai', 'hien')
            ->latest()
            ->get();

        // Discount (nếu muốn dùng cho banner khuyến mãi)
 

        return view('Client.index', compact(
            'banners',
            'categories',
            'products',
            'trendingProducts',
           
            'posts',
            'footerBanners'
            
        ));
    }
}

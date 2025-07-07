<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Comment;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Order_items;

class ProductDetailController extends Controller
{
    public function show($id)
    {
        $product = Products::with('images', 'variants.size', 'variants.color')->findOrFail($id);
        $reviews = Review::with('user')->where('product_id', $id)->latest()->get();
        $comments = Comment::where('product_id', $product->id)
            ->whereNull('deleted_at')
            ->where('trang_thai', 1)
            ->latest()
            ->get();
        $productVariants = ProductVariant::where('product_id', $product->id)
            ->with(['size', 'color'])
            ->get();

        $canReview = false;
        $user = Auth::user();
        if ($user) {
            $hasPurchased = Order::where('user_id', $user->id)
                ->where('status', 'Hoàn thành')
                ->whereHas('orderItems', function($q) use ($product) {
                    $q->where('product_id', $product->id);
                })->exists();
            if ($hasPurchased) {
                $review = Review::where('product_id', $product->id)
                    ->where('ma_nguoi_dung', $user->id)
                    ->first();
                if (!$review) {
                    $canReview = true;
                }
            }
        }

        // Lấy 5 sản phẩm liên quan cùng danh mục, mới nhất trước, loại trừ sản phẩm đang xem
        $relatedProducts = Products::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('id')
            ->take(5)
            ->with(['images', 'variants', 'category', 'reviews'])
            ->get();

        return view('Client.Product.productDetail', compact('product', 'reviews', 'comments', 'productVariants', 'canReview', 'relatedProducts'));
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string',
        ]);
        $userId = Auth::id();
        // Kiểm tra đã mua sản phẩm chưa
        $hasPurchased = Order::where('user_id', $userId)
            ->where('status', 'Hoàn thành')
            ->whereHas('orderItems', function($q) use ($id) {
                $q->where('product_id', $id);
            })->exists();
        if (!$hasPurchased) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá khi đã mua sản phẩm này.');
        }
        // Kiểm tra đã đánh giá chưa
        $review = Review::where('product_id', $id)
            ->where('ma_nguoi_dung', $userId)
            ->first();
        if ($review) {
            return back()->with('error', 'Bạn chỉ được đánh giá 1 lần cho mỗi sản phẩm.');
        }
        Review::create([
            'product_id' => $id,
            'ma_nguoi_dung' => $userId,
            'so_sao' => $request->so_sao,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => 1,
        ]);
        return back()->with('success', 'Đã gửi đánh giá');
    }

    public function submitComment(Request $request, $id)
    {
        $request->validate([
            'noi_dung' => 'required|string',
        ]);

        Comment::create([
            'product_id' => $id,
            'tac_gia' => Auth::id(),
            'noi_dung' => $request->noi_dung,
            'trang_thai' => 1, // chưa duyệt
        ]);

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi và đang chờ duyệt.');
    }
}

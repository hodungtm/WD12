<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Order_items;

class ProductDetailController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['images.color', 'variants.size', 'variants.color'])->findOrFail($id);
        $reviews = Review::with('user')->where('product_id', $id)->where('trang_thai', 1)->latest()->get();
        $comments = Comment::where('product_id', $product->id)
            ->whereNull('deleted_at')
            ->where('trang_thai', 1)
            ->latest()
            ->get();
        $productVariants = ProductVariant::where('product_id', $product->id)
            ->with(['size', 'color'])
            ->get();

            $user = Auth::user();

            $hasPurchased = false;
            $hasReviewed = false;
    
            $canReview = false;

    if ($user) {
        // Kiểm tra đã từng mua sản phẩm chưa
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'Hoàn thành')
            ->whereHas('orderItems', fn($q) => $q->where('product_id', $id))
            ->exists();

        // Kiểm tra còn lượt đánh giá không (order_item chưa reviewed)
        $canReview = Order_items::where('product_id', $id)
            ->whereHas('order', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'Hoàn thành');
            })
            ->where('reviewed', false)
            ->exists();
    }

        // Lấy 5 sản phẩm liên quan cùng danh mục, mới nhất trước, loại trừ sản phẩm đang xem
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('id')
            ->take(5)
            ->with(['images', 'variants', 'category', 'reviews'])
            ->get();
       
            return view('Client.Product.productDetail', compact(
                'product', 'reviews', 'comments', 'productVariants',
                'hasPurchased', 'canReview', 'relatedProducts'
            ));
    }

    public function submitReview(Request $request, $id)
{
    $request->validate([
        'so_sao' => 'required|integer|min:1|max:5',
        'noi_dung' => 'required|string',
        'order_item_id' => 'required|exists:order_items,id',
    ]);
    $userId = Auth::id();

    // Lấy đúng order_item cần đánh giá
    $orderItem = Order_items::where('id', $request->order_item_id)
        ->where('product_id', $id)
        ->where('reviewed', false)
        ->whereHas('order', function($q) use ($userId) {
            $q->where('user_id', $userId)->where('status', 'Hoàn thành');
        })
        ->first();

    if (!$orderItem) {
        return back()->with('error', 'Bạn không có quyền đánh giá cho sản phẩm này hoặc đã đánh giá rồi.');
    }

    // Tạo review mới
    Review::create([
        'product_id' => $id,
        'ma_nguoi_dung' => $userId,
        'so_sao' => $request->so_sao,
        'noi_dung' => $request->noi_dung,
        'trang_thai' => 1,
    ]);

    // Đánh dấu order_item đã đánh giá
    $orderItem->reviewed = true;
    $orderItem->save();

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

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi.');
    }
}

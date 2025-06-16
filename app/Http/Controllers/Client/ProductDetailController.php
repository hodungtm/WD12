<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Comment;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function show($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $reviews = Review::where('product_id', $id)->latest()->get();
        $comments = Comment::where('product_id', $product->id)
            ->whereNull('deleted_at')
            ->where('trang_thai', 1) // chỉ lấy bình luận đã duyệt
            ->latest()
            ->get();
        $productVariants = ProductVariant::where('product_id', $product->id)
            ->with(['size', 'color']) // assuming you've defined relationships
            ->get();
        return view('Client.Product.productDetail', compact('product', 'reviews', 'comments'));
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            // 'ten_nguoi_danh_gia' => 'required|string',
            'so_sao' => 'required|integer|min:1|max:5',
            
            'noi_dung' => 'required|string',
        ], [
            // 'ten_nguoi_danh_gia.required' => 'Vui lòng nhập tên',
            'so_sao.required' => 'Vui lòng nhập số sao đánh giá',
          
            'noi_dung.required' => 'Vui lòng nhập nội dung',
        ]);

        Review::create([
            'product_id' => $id,
            // 'ten_nguoi_danh_gia' => $request->ten_nguoi_danh_gia,
            'so_sao' => $request->so_sao,
           
            'noi_dung' => $request->noi_dung,
        ]);

        return back()->with('success', 'Đã gửi đánh giá');
    }

    public function submitComment(Request $request, $id)
    {
        $request->validate([
            'tac_gia' => 'required|string|max:255',
            'noi_dung' => 'required|string',
        ]);

        Comment::create([
            'product_id' => $id,
            'tac_gia' => $request->tac_gia,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => 1, // chưa duyệt
        ]);

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi và đang chờ duyệt.');
    }
}

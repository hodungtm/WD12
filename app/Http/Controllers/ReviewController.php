<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $reviews = Review::when($keyword, function ($q) use ($keyword) {
            $q->where('noi_dung', 'like', '%' . $keyword . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews', 'keyword'));
    }

    public function create()
    {
        return view('admin.reviews.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string',
            'trang_thai' => 'nullable|boolean',
        ]);

        $validated['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        Review::create($validated);

        return redirect()->route('Admin.reviews.index')->with('success', 'Thêm đánh giá thành công!');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'product_id' => 'nullable|exists:products,id',
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string',
            'trang_thai' => 'required|boolean',
        ]);

        $review->update($request->all());

        return redirect()->route('Admin.reviews.index')->with('success', 'Cập nhật đánh giá thành công!');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('Admin.reviews.index')->with('success', 'Xóa đánh giá thành công!');
    }
}

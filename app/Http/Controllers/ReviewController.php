<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $reviews = Review::with('product') // Nạp quan hệ product
            ->when($keyword, function ($q) use ($keyword) {
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
            'product_id' => 'required|integer|exists:products,id',
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string',
            'trang_thai' => 'nullable|boolean',
        ], [
            'user_id.integer' => 'User ID phải là số nguyên.',
            'user_id.exists' => 'User không tồn tại.',

            'product_id.integer' => 'Product ID phải là số nguyên.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',

            'so_sao.required' => 'Vui lòng nhập số sao đánh giá.',
            'so_sao.integer' => 'Số sao phải là số nguyên.',
            'so_sao.min' => 'Số sao tối thiểu là 1.',
            'so_sao.max' => 'Số sao tối đa là 5.',

            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá.',
            'noi_dung.string' => 'Nội dung đánh giá phải là chuỗi ký tự.',

            'trang_thai.boolean' => 'Trạng thái không hợp lệ.',
        ]);

        $validated['trang_thai'] = $request->input('trang_thai', 0);

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
            'user_id' => 'nullable|integer|exists:users,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string',
            'trang_thai' => 'required|boolean',
        ], [
            'user_id.integer' => 'User ID phải là số nguyên.',
            'user_id.exists' => 'User không tồn tại.',

            'product_id.integer' => 'Product ID phải là số nguyên.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',

            'so_sao.required' => 'Vui lòng nhập số sao đánh giá.',
            'so_sao.integer' => 'Số sao phải là số nguyên.',
            'so_sao.min' => 'Số sao tối thiểu là 1.',
            'so_sao.max' => 'Số sao tối đa là 5.',

            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá.',
            'noi_dung.string' => 'Nội dung đánh giá phải là chuỗi ký tự.',

            'trang_thai.required' => 'Vui lòng chọn trạng thái.',
            'trang_thai.boolean' => 'Trạng thái không hợp lệ.',
        ]);

        $review->update($request->all());

        return redirect()->route('Admin.reviews.index')->with('success', 'Cập nhật đánh giá thành công!');
    }

    public function show($id)
    {
        $review = Review::with('product')->findOrFail($id);
        return view('Admin.reviews.show', compact('review'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('Admin.reviews.index')->with('success', 'Xóa đánh giá thành công!');
    }
}

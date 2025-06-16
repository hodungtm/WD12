<?php

namespace App\Http\Controllers\Admin;;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $productName = $request->product_name;
        $so_sao = $request->so_sao;
        $trang_thai = $request->trang_thai;
        $sort = $request->sort;

        $reviews = Review::with('product', 'user')
            ->when($productName, function ($q) use ($productName) {
                // join bảng products để tìm tên sản phẩm tương ứng
                $q->whereHas('product', function ($query) use ($productName) {
                    $query->where('name', 'like', '%' . $productName . '%');
                });
            })
            ->when($so_sao, function ($q) use ($so_sao) {
                $q->where('so_sao', $so_sao);
            })
            ->when(isset($trang_thai), function ($q) use ($trang_thai) {
                $q->where('trang_thai', $trang_thai);
            })
            ->when($sort == 'newest', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort == 'oldest', function ($q) {
                $q->orderBy('created_at', 'asc');
            });

        if (!in_array($sort, ['newest', 'oldest'])) {
            $reviews = $reviews->orderBy('created_at', 'desc');
        }

        $reviews = $reviews->paginate(10)->appends($request->query());

        return view('admin.reviews.index', compact('reviews', 'productName', 'so_sao', 'trang_thai', 'sort'));
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
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string',
            'trang_thai' => 'required|boolean',
        ], [
            'so_sao.required' => 'Vui lòng nhập số sao đánh giá.',
            'so_sao.integer' => 'Số sao phải là số nguyên.',
            'so_sao.min' => 'Số sao tối thiểu là 1.',
            'so_sao.max' => 'Số sao tối đa là 5.',

            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá.',
            'noi_dung.string' => 'Nội dung đánh giá phải là chuỗi ký tự.',

            'trang_thai.required' => 'Vui lòng chọn trạng thái.',
            'trang_thai.boolean' => 'Trạng thái không hợp lệ.',
        ]);

        $review->update([
            'so_sao' => $request->so_sao,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => $request->trang_thai,
        ]);

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
        return redirect()->route('Admin.reviews.index')->with('success', 'Xóa đánh giá thành công (xóa mềm).');
    }

    // Danh sách bản ghi đã xóa mềm
    public function trash()
    {
        $reviews = Review::onlyTrashed()->paginate(10);
        return view('Admin.reviews.trash', compact('reviews'));
    }

    // Khôi phục
    public function restore($id)
    {
        $review = Review::onlyTrashed()->where('id', $id)->firstOrFail();
        $review->restore();
        return redirect()->route('Admin.reviews.trash')->with('success', 'Khôi phục đánh giá thành công.');
    }

    // Xóa vĩnh viễn
    public function forceDelete($id)
    {
        $review = Review::onlyTrashed()->where('id', $id)->firstOrFail();
        $review->forceDelete();
        return redirect()->route('Admin.reviews.trash')->with('success', 'Xóa đánh giá vĩnh viễn thành công.');
    }
}

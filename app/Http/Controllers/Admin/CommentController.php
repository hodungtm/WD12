<?php

namespace App\Http\Controllers\Admin;;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $trang_thai = $request->trang_thai;
        $sort = $request->sort ?? 'desc';

        $comments = Comment::with('product') // nạp quan hệ product để tìm sản phẩm
            ->when($keyword, function ($query) use ($keyword) {
                // Tìm bình luận theo tên sản phẩm chứa từ khóa
                $query->whereHas('product', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->when($trang_thai !== null && $trang_thai !== '', function ($query) use ($trang_thai) {
                $query->where('trang_thai', $trang_thai);
            })
            ->orderBy('created_at', $sort)
            ->paginate(10)
            ->appends($request->all());

        return view('admin.comments.index', compact('comments', 'keyword', 'trang_thai', 'sort'));
    }


    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        // $products = Product::all(); // comment tạm
        // return view('admin.comments.edit', compact('comment', 'products'));
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $request->validate([
            'trang_thai' => 'required|boolean',
        ], [
            'trang_thai.required' => 'Vui lòng chọn trạng thái.',
            'trang_thai.boolean' => 'Trạng thái không hợp lệ.',
        ]);

        $comment->trang_thai = $request->trang_thai;
        $comment->save();

        return redirect()->route('Admin.comments.index')->with('success', 'Cập nhật bình luận thành công!');
    }

    public function show($id)
    {
        $comment = Comment::with('product')->findOrFail($id); // nếu có quan hệ product
        return view('admin.comments.show', compact('comment'));
    }
}

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
            'noi_dung' => 'required|string',
            'trang_thai' => 'required|boolean',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung bình luận.',
            'noi_dung.string' => 'Nội dung phải là chuỗi ký tự.',

            'trang_thai.required' => 'Vui lòng chọn trạng thái.',
            'trang_thai.boolean' => 'Trạng thái không hợp lệ.',
        ]);

        $comment->noi_dung = $request->noi_dung;
        $comment->trang_thai = $request->trang_thai;

        $comment->save();

        return redirect()->route('Admin.comments.index')->with('success', 'Cập nhật bình luận thành công!');
    }

    public function show($id)
    {
        $comment = Comment::with('product')->findOrFail($id); // nếu có quan hệ product
        return view('admin.comments.show', compact('comment'));
    }

    public function approve(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->trang_thai = 1; // duyệt bình luận
        $comment->save();

        // Lấy page hiện tại gửi từ form hoặc mặc định 1
        $page = $request->input('page', 1);

        // Redirect về trang hiện tại giữ nguyên page
        return redirect()->route('Admin.comments.index', [
            'page' => $page,
            'keyword' => $request->input('keyword')
        ])->with('success', 'Đã duyệt bình luận.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('Admin.comments.index')->with('success', 'Xóa bình luận thành công (xóa mềm).');
    }

    // Thùng rác bình luận
    public function trash()
    {
        $comments = Comment::onlyTrashed()->paginate(10);
        return view('Admin.comments.trash', compact('comments'));
    }

    // Khôi phục bình luận
    public function restore($id)
    {
        $comment = Comment::onlyTrashed()->where('id', $id)->firstOrFail();
        $comment->restore();
        return redirect()->route('Admin.comments.trash')->with('success', 'Khôi phục bình luận thành công.');
    }

    // Xóa vĩnh viễn bình luận
    public function forceDelete($id)
    {
        $comment = Comment::onlyTrashed()->where('id', $id)->firstOrFail();
        $comment->forceDelete();
        return redirect()->route('Admin.comments.trash')->with('success', 'Xóa bình luận vĩnh viễn thành công.');
    }
}

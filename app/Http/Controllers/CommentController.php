<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $comments = Comment::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('noi_dung', 'like', "%$keyword%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.comments.index', compact('comments', 'keyword'));
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
            // 'product_id' => 'required|exists:products,id', // comment tạm
            'tac_gia' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'trang_thai' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        $comment->update($data);

        return redirect()->route('Admin.comments.index')->with('success', 'Cập nhật bình luận thành công!');
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

        return redirect()->route('Admin.comments.index')->with('success', 'Xóa bình luận thành công!');
    }
}

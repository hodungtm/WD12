<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    

   public function index(Request $request)
{
    $query = Post::query();

    // Lọc theo từ khóa trong tiêu đề nếu có
    if ($request->filled('keyword')) {
        $query->where('title', 'like', '%' . $request->keyword . '%');
    }

    // Lọc theo trạng thái nếu có
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Số lượng hiển thị mỗi trang, mặc định là 10
    $perPage = $request->input('per_page', 10);

    // Lấy danh sách bài viết với phân trang
    $posts = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

    // Trả về view với biến $posts
    return view('Admin.Posts.index', compact('posts'));
}



    public function create()
    {
        return view('Admin.posts.create');
    }

   public function store(Request $request)
{
  
   $data = $request->validate([
        'title'   => 'required|max:255',
        'status'  => 'required|in:published,draft,hidden',
        'content' => 'required',
        'image'   => 'nullable|image|max:2048',
    ], [
        // Thông báo lỗi tự viết:
        'title.required'   => 'Bạn chưa nhập tiêu đề bài viết.',
        'title.max'        => 'Tiêu đề không được dài quá 255 ký tự.',
        'status.required'  => 'Bạn phải chọn trạng thái.',
        'status.in'        => 'Trạng thái không hợp lệ.',
        'content.required' => 'Bạn chưa nhập nội dung bài viết.',
        'image.image'      => 'File tải lên phải là ảnh.',
        'image.max'        => 'Ảnh không được lớn hơn 2MB.',
    ]);

    // Nếu có ảnh thì lưu và gán đường dẫn
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('posts', 'public');
    }

    // Lưu bài viết vào database
    Post::create($data);

    // Redirect kèm thông báo
    return redirect()->route('posts.index')->with('success', 'Bài viết đã được tạo thành công.');
}

    public function show(Post $post)
    {
        return view('Admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('Admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
{
    // Validate dữ liệu đầu vào
    $data = $request->validate([
        'title'   => 'required|max:255',
        'content' => 'required',
        'image'   => 'nullable|image|max:2048',
        'status'  => 'required|in:draft,published,hidden', // thêm 'hidden' nếu có sử dụng
    ]);

    // Nếu người dùng upload ảnh mới
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu tồn tại
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        // Lưu ảnh mới và cập nhật đường dẫn
        $data['image'] = $request->file('image')->store('posts', 'public');
    }

    // Cập nhật bài viết
    $post->update($data);

    // Chuyển hướng về trang danh sách
    return redirect()->route('posts.index')->with('success', 'Bài viết đã được cập nhật thành công.');
}

    

public function destroy(Post $post)
{
    // Nếu bài viết có ảnh và ảnh tồn tại trong thư mục lưu trữ
    if ($post->image && Storage::disk('public')->exists($post->image)) {
        // Xóa ảnh khỏi storage
        Storage::disk('public')->delete($post->image);
    }

    // Xóa bài viết khỏi database
    $post->delete();

    // Chuyển hướng và thông báo
    return redirect()->route('posts.index')->with('success', 'Bài viết đã được xóa cùng với ảnh.');
}

}

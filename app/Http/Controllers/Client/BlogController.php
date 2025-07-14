<?php


namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {   
        $posts = Post::latest()->paginate(6); // 6 bài / trang, sắp xếp mới nhất
        $recentPosts = Post::latest()->take(5)->get();
        // dd($posts);
        return view('client.blog.blog', compact('posts', 'recentPosts'));
    }

    public function show($id) {
        $post = Post::find($id);
        $recentPosts = Post::latest()->take(5)->get();
        return view('client.blog.show', compact('post','recentPosts'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'nullable',
            'content' => 'required',
            'status' => 'required|in:draft,published'
        ]);

        $post = new Post();
        $post->author_id = auth()->id() ?? 1;
        $post->title = $request->title;
        $post->slug = Str::slug($request->title) . '-' . time();
        $post->excerpt = $request->excerpt;
        $post->content = $request->content;
        $post->status = $request->status;

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('public/posts');
            $post->thumbnail = str_replace('public/', 'storage/', $path);
        }

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Tạo bài viết thành công!');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'nullable',
            'content' => 'required',
            'status' => 'required|in:draft,published'
        ]);

        $post->title = $request->title;
        if ($post->title != $request->title) {
            $post->slug = Str::slug($request->title) . '-' . time();
        }
        $post->excerpt = $request->excerpt;
        $post->content = $request->content;
        $post->status = $request->status;

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('public/posts');
            $post->thumbnail = str_replace('public/', 'storage/', $path);
        }

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Xóa bài viết thành công!');
    }
}

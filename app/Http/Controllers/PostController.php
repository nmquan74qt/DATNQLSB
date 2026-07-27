<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::where('status', 'published')->latest()->paginate(9);

        return view('pages.blog', compact('posts'));
    }
}

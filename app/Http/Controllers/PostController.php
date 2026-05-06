<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::with(['category', 'user'])
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(10);

        return view('posts.feed', compact('posts'));
    }

    public function videoIndex()
    {
        $posts = \App\Models\Post::with(['category', 'user'])
            ->where('status', 'published')
            ->whereNotNull('video_url')
            ->latest('published_at')
            ->paginate(12);

        return view('posts.videos', compact('posts'));
    }

    public function articleIndex()
    {
        $posts = \App\Models\Post::with(['category', 'user'])
            ->where('status', 'published')
            ->whereNull('video_url')
            ->latest('published_at')
            ->paginate(10);

        return view('posts.articles', compact('posts'));
    }

    public function show($id)
    {
        $post = \App\Models\Post::with(['category', 'user'])->findOrFail($id);
        $post->increment('views_count');

        return view('posts.show', compact('post'));
    }
}
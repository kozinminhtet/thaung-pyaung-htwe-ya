<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = $this->publishedPosts($request)
            ->paginate(10)
            ->withQueryString();

        return view('feed.index', compact('posts'));
    }

    public function videoIndex(Request $request)
    {
        $posts = $this->publishedPosts($request)
            ->whereNotNull('video_url')
            ->paginate(12)
            ->withQueryString();

        return view('video.index', compact('posts'));
    }

    public function articleIndex(Request $request)
    {
        $posts = $this->publishedPosts($request)
            ->whereNull('video_url')
            ->paginate(10)
            ->withQueryString();

        return view('articles.index', compact('posts'));
    }

    public function show(Request $request, $id)
    {
        $post = Post::query()
            ->with(['category', 'user'])
            ->published()
            ->findOrFail($id);

        $this->recordView($request, $post);

        return view('posts.show', compact('post'));
    }

    private function publishedPosts(Request $request)
    {
        return Post::query()
            ->with(['category:id,name,slug', 'user:id,name'])
            ->published()
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->latest('published_at')
            ->latest();
    }

    private function recordView(Request $request, Post $post): void
    {
        $view = $post->views()->firstOrCreate([
            'ip_address' => $request->ip(),
            'view_date' => now()->toDateString(),
        ], [
            'user_id' => Auth::id(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        if ($view->wasRecentlyCreated) {
            $post->increment('views_count');
        }
    }
}

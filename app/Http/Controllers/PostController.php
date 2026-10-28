<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = $this->publishedPosts($request)
            ->paginate(10)
            ->withQueryString();

        return view('feed.index', compact('posts'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate(['q' => ['nullable', 'string', 'max:150']]);
        $term = trim($validated['q'] ?? '');

        $posts = $this->publishedPosts($request)
            ->when($term !== '', function ($query) use ($term) {
                $pattern = '%' . $term . '%';
                $query->where(function ($matches) use ($pattern) {
                    $matches->where('content', 'like', $pattern)
                        ->orWhereHas('user', fn ($author) => $author->where('name', 'like', $pattern));
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('feed.index', compact('posts', 'term'));
    }

    public function savedPosts(Request $request)
    {
        $userId = $request->user()->id;
        $posts = Post::query()
            ->with(['category:id,name,slug', 'user:id,name'])
            ->published()
            ->whereHas('interactions', fn ($interactions) => $interactions
                ->where('user_id', $userId)->where('type', 'save'))
            ->withExists([
                'interactions as viewer_liked' => fn ($interactions) => $interactions
                    ->where('user_id', $userId)->where('type', 'like'),
                'interactions as viewer_saved' => fn ($interactions) => $interactions
                    ->where('user_id', $userId)->where('type', 'save'),
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('profile.saved-posts', compact('posts'));
    }

    public function videoIndex(Request $request)
    {
        $posts = $this->publishedPosts($request)
            ->whereNotNull('video_url')
            ->when($request->filled('category_id'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('type', 'video')))
            ->paginate(12)
            ->withQueryString();

        return view('video.index', compact('posts'));
    }

    public function articleIndex(Request $request)
    {
        $posts = $this->publishedPosts($request)
            ->whereNull('video_url')
            ->when($request->filled('category_id'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('type', 'article')))
            ->paginate(10)
            ->withQueryString();

        return view('articles.index', compact('posts'));
    }

    public function show(Request $request, $id)
    {
        $query = Post::query()
            ->with(['category', 'user'])
            ->published();
        $post = $this->addViewerInteractionState($query, $request)->findOrFail($id);

        $this->recordView($request, $post);

        return view('posts.show', compact('post'));
    }

    private function publishedPosts(Request $request)
    {
        $query = Post::query()
            ->with(['category:id,name,slug', 'user:id,name'])
            ->published()
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->latest('published_at')
            ->latest();

        return $this->addViewerInteractionState($query, $request);
    }

    private function addViewerInteractionState(Builder $query, Request $request): Builder
    {
        if ($user = $request->user()) {
            $query->withExists([
                'interactions as viewer_liked' => fn ($interactions) => $interactions
                    ->where('user_id', $user->id)
                    ->where('type', 'like'),
                'interactions as viewer_saved' => fn ($interactions) => $interactions
                    ->where('user_id', $user->id)
                    ->where('type', 'save'),
            ]);
        }

        return $query;
    }

    private function recordView(Request $request, Post $post): void
    {
        $now = now();
        DB::transaction(function () use ($request, $post, $now) {
            $inserted = DB::table('views')->insertOrIgnore([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'ip_address' => $request->ip() ?: '0.0.0.0',
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'view_date' => $now->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($inserted === 1) {
                Post::query()->whereKey($post->id)->increment('views_count');
            }
        });
    }
}

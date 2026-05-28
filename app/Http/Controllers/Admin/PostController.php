<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()
            ->with(['category:id,name', 'user:id,name'])
            ->latest()
            ->paginate(10);

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('posts', 'public');
        }

        unset($data['image']);

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        return response()->json([
            'message' => 'Post created successfully.',
            'post' => $post->load(['category:id,name', 'user:id,name']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'post' => $post->load(['category:id,name', 'user:id,name']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): JsonResponse
    {
        return response()->json([
            'post' => $post->load(['category:id,name', 'user:id,name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($post->image_url) {
                Storage::disk('public')->delete($post->image_url);
            }

            $data['image_url'] = $request->file('image')->store('posts', 'public');
        }

        unset($data['image']);

        if (($data['status'] ?? $post->status) === 'published' && ! $post->published_at) {
            $data['published_at'] = now();
        }

        if (($data['status'] ?? $post->status) !== 'published') {
            $data['published_at'] = null;
        }

        $post->update($data);

        return response()->json([
            'message' => 'Post updated successfully.',
            'post' => $post->fresh(['category:id,name', 'user:id,name']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        if ($post->image_url) {
            Storage::disk('public')->delete($post->image_url);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
        ]);
    }
}

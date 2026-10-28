<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function comments(Request $request, Post $post): JsonResponse
    {
        abort_unless($post->status === 'published', 404);

        $validated = $request->validate([
            'before_id' => ['sometimes', 'integer', 'min:1'],
        ]);

        $query = $post->interactions()
            ->comments()
            ->whereNull('parent_id')
            ->with('user:id,name');

        if (isset($validated['before_id'])) {
            $query->where('id', '<', $validated['before_id']);
        }

        $rows = $query->latest('id')->limit(51)->get();
        $hasMore = $rows->count() > 50;
        if ($hasMore) {
            $rows->pop();
        }
        $nextBeforeId = $rows->last()?->id;

        $comments = $rows->sortBy('id')
            ->values()
            ->map(fn (Interaction $comment) => [
                'id' => $comment->id,
                'author' => $comment->user?->name ?? 'Deleted user',
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
            ]);

        return response()->json([
            'comments' => $comments,
            'has_more' => $hasMore,
            'next_before_id' => $nextBeforeId,
        ]);
    }

    public function like(Request $request, Post $post): JsonResponse
    {
        $state = $this->setUniqueInteraction($request, $post, 'like', true);

        return response()->json(['liked' => true, 'count' => $state['count']]);
    }

    public function unlike(Request $request, Post $post): JsonResponse
    {
        $state = $this->setUniqueInteraction($request, $post, 'like', false);

        return response()->json(['liked' => false, 'count' => $state['count']]);
    }

    public function save(Request $request, Post $post): JsonResponse
    {
        $state = $this->setUniqueInteraction($request, $post, 'save', true);

        return response()->json(['saved' => true, 'count' => $state['count']]);
    }

    public function unsave(Request $request, Post $post): JsonResponse
    {
        $state = $this->setUniqueInteraction($request, $post, 'save', false);

        return response()->json(['saved' => false, 'count' => $state['count']]);
    }

    public function comment(Request $request, Post $post): JsonResponse
    {
        abort_unless($post->status === 'published', 404);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $comment = DB::transaction(function () use ($request, $post, $validated) {
            $lockedPost = $this->lockPublishedPost($post);

            $comment = $lockedPost->interactions()->create([
                'user_id' => $request->user()->id,
                'type' => 'comment',
                'content' => $validated['content'],
            ]);

            $lockedPost->increment('comments_count');

            return $comment->load('user:id,name');
        });

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'author' => $comment->user?->name ?? 'Deleted user',
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
            ],
            'count' => $post->fresh()->comments_count,
        ], 201);
    }

    public function share(Request $request, Post $post): JsonResponse
    {
        abort_unless($post->status === 'published', 404);

        $request->validate([
            'platform' => ['required', 'in:facebook,telegram,copy'],
        ]);

        $count = DB::transaction(function () use ($request, $post) {
            $lockedPost = $this->lockPublishedPost($post);

            $lockedPost->interactions()->create([
                'user_id' => $request->user()->id,
                'type' => 'share',
            ]);

            $lockedPost->increment('shares_count');

            return $lockedPost->shares_count;
        });

        return response()->json(['count' => $count]);
    }

    /**
     * Set a per-user like/save state idempotently. Locking the parent post
     * serializes concurrent changes even though the legacy schema has no
     * conditional unique index that can exclude soft-deleted rows.
     */
    private function setUniqueInteraction(Request $request, Post $post, string $type, bool $active): array
    {
        abort_unless($post->status === 'published', 404);

        return DB::transaction(function () use ($request, $post, $type, $active) {
            $lockedPost = $this->lockPublishedPost($post);
            $interaction = Interaction::withTrashed()
                ->where('post_id', $lockedPost->id)
                ->where('user_id', $request->user()->id)
                ->where('type', $type)
                ->whereNull('parent_id')
                ->lockForUpdate()
                ->first();

            $counter = $type === 'like' ? 'likes_count' : 'saves_count';

            if ($active && ! $interaction) {
                $lockedPost->interactions()->create([
                    'user_id' => $request->user()->id,
                    'type' => $type,
                ]);
                $lockedPost->increment($counter);
            } elseif ($active && $interaction->trashed()) {
                $interaction->restore();
                $lockedPost->increment($counter);
            } elseif (! $active && $interaction && ! $interaction->trashed()) {
                $interaction->delete();
                Post::query()->whereKey($lockedPost->id)
                    ->where($counter, '>', 0)
                    ->decrement($counter);
                $lockedPost->refresh();
            }

            return ['count' => $lockedPost->fresh()->{$counter}];
        });
    }

    private function lockPublishedPost(Post $post): Post
    {
        return Post::query()
            ->whereKey($post->id)
            ->where('status', 'published')
            ->lockForUpdate()
            ->firstOrFail();
    }
}

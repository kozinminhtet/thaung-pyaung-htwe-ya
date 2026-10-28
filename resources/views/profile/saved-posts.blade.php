@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold text-primary mb-1"><i class="bi bi-bookmark me-2" aria-hidden="true"></i>Saved posts</h5>
        <p class="text-muted small mb-0">Posts you have bookmarked.</p>
    </div>
    <a href="{{ route('user.profile') }}" class="btn btn-sm btn-outline-secondary">Profile</a>
</div>

<div data-saved-posts-list data-explore-url="{{ route('feed.index') }}" data-current-page="{{ $posts->currentPage() }}" data-last-page="{{ $posts->lastPage() }}">
    @forelse($posts as $post)
        <article class="card mb-2" data-saved-post-card>
            @if($post->video_url)
                @if($post->video_embed_url)
                    <div class="ratio ratio-16x9"><iframe src="{{ $post->video_embed_url }}" allowfullscreen loading="lazy" title="{{ $post->display_title }}"></iframe></div>
                @elseif($post->has_video_file)
                    <div class="ratio ratio-16x9 bg-black"><video controls class="w-100 h-100" style="object-fit:contain"><source src="{{ $post->video_src }}" type="video/mp4">Your browser does not support the video tag.</video></div>
                @endif
            @elseif($post->image_src)
                <div class="media-wrapper" style="width:100%;aspect-ratio:16/9;overflow:hidden;background:#eee">
                    <img src="{{ $post->image_src }}" alt="Post image" style="width:100%;height:100%;object-fit:cover;display:block">
                </div>
            @endif
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $post->category?->name ?? 'General' }}</span>
                    <small class="text-muted">{{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}</small>
                </div>
                <h6 class="fw-bold mb-2">{{ $post->display_title }}</h6>
                @if($post->content)
                    <p class="text-secondary mb-2" style="font-size:.9rem;line-height:1.5">{!! nl2br(e(\Illuminate\Support\Str::limit($post->content, 120))) !!}</p>
                @endif
                <div class="mb-3">
                    <a href="{{ route('feed.show', $post) }}" class="text-decoration-none fw-semibold">Open post</a>
                    <small class="text-muted ms-2"><i class="far fa-eye me-1" aria-hidden="true"></i>{{ number_format($post->views_count) }} views</small>
                </div>
                @include('partials.post-interactions', ['post' => $post])
            </div>
        </article>
    @empty
        <div class="card mb-2" data-saved-empty>
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-bookmark display-6 d-block mb-2" aria-hidden="true"></i>
                You have no saved posts yet.
                <div class="mt-3"><a href="{{ route('feed.index') }}" class="btn btn-primary btn-sm">Explore posts</a></div>
            </div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4" data-saved-pagination>
    {{ $posts->links() }}
</div>
<div class="small mt-1" data-saved-page-feedback role="status" aria-live="polite"></div>
@endsection

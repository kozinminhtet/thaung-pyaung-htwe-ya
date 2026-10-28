@extends('layouts.app')

@section('content')

@if(isset($term))
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold text-primary mb-0">Search results</h5>
        @if($term !== '')
            <a class="small text-decoration-none" href="{{ route('feed.index') }}">Clear search</a>
        @endif
    </div>
    @if($term !== '')
        <p class="text-muted small mb-3">Results for “{{ $term }}”</p>
    @endif
@endif

@forelse($posts as $post)
<div class="card mb-2">
    @if($post->video_url)
    @if($post->video_embed_url)
    <div class="ratio ratio-16x9">
        <iframe src="{{ $post->video_embed_url }}" allowfullscreen loading="lazy"></iframe>
    </div>
    @elseif($post->has_video_file)
    <div class="ratio ratio-16x9 bg-black">
        <video controls class="w-100 h-100" style="object-fit: contain;">
            <source src="{{ $post->video_src }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    @endif
    @elseif($post->image_src)
    <div class="media-wrapper" style="width: 100%; aspect-ratio: 16/9; overflow: hidden; background: #eee;">
        <img src="{{ $post->image_src }}" alt="Post Image"
            style="width: 100%; height: 100%; object-fit: cover; display: block;">
    </div>
    @endif

    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                {{ $post->category?->name ?? 'General' }}
            </span>
            <small class="text-muted" style="font-size: 0.75rem;">
                <i
                    class="far fa-clock me-1"></i>{{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}
            </small>
        </div>

        <h6 class="fw-bold mb-2" style="line-height: 1.4;">
            {{ $post->display_title }}
        </h6>

        @if($post->content)
        <p class="text-secondary mb-2" style="font-size: 0.9rem; line-height: 1.5;">
            {!! nl2br(e(\Illuminate\Support\Str::limit($post->content, 120))) !!}
        </p>
        @endif

        @if(\Illuminate\Support\Str::length((string) $post->content) > 120)
        <div class="mb-3">
            <a href="{{ route('feed.show', $post->id) }}" class="text-primary text-decoration-none fw-bold"
                style="font-size: 0.85rem;">
                Read more...
            </a>
        </div>
        @endif

        <div class="mb-3">
            <small class="text-muted" style="font-size: 0.8rem;">
                <i class="far fa-eye me-1"></i>{{ number_format($post->views_count) }} views
            </small>
        </div>

        @include('partials.post-interactions', ['post' => $post])
    </div>
</div>
@empty
<div class="card mb-2">
    <div class="card-body text-center text-muted py-5">
        @if(isset($term) && $term !== '')
            No posts matched your search. Try another word or author name.
        @elseif(isset($term))
            Enter a word or author name to search posts.
        @else
            No posts yet.
        @endif
    </div>
</div>
@endforelse

<div class="d-flex justify-content-center mt-4">
    {{ $posts->links() }}
</div>
<div class="py-2"></div>

@endsection

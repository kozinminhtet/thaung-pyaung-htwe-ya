@extends('layouts.app')

@section('content')

<div class="container py-3">

    @forelse($posts as $post)
    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
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

        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <a href="{{ route('feed.videos', ['category_id' => $post->category_id]) }}"
                    class="text-decoration-none">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        {{ $post->category?->name ?? 'General' }}
                    </span>
                </a>
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
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">No videos yet.</div>
    </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
</div>

@endsection

@section('category-navigation')
@include('partials.category-navigation', [
'categories' => $videoCategories,
'routeName' => 'feed.videos',
'allLabel' => 'All Videos',
'inset' => true,
])
@endsection
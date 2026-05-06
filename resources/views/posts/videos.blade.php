@extends('layouts.app')

@section('content')

<div class="container py-3">
    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-play-circle-fill me-2"></i>ဗွီဒီယိုများ</h5>

    @foreach($posts as $post)
    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">

        {{-- Video Section --}}
        <div class="ratio ratio-16x9 bg-black">
            <video controls class="w-100 h-100" style="object-fit: contain;">
                <source src="{{ asset($post->video_url) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                {{-- Category Link --}}
                <a href="{{ route('posts.index', ['category_id' => $post->category_id]) }}"
                    class="text-decoration-none">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        {{ $post->category?->name ?? 'General' }}
                    </span>
                </a>
                <small class="text-muted" style="font-size: 0.75rem;">
                    <i class="far fa-clock me-1"></i>{{ $post->published_at->diffForHumans() }}
                </small>
            </div>

            {{-- Title --}}
            <h6 class="fw-bold mb-2" style="line-height: 1.4;">
                {{ $post->title }}
            </h6>

            {{-- Content --}}
            <p class="text-secondary mb-2" style="font-size: 0.9rem; line-height: 1.5;">
                {!! nl2br(e(Str::limit($post->content, 120))) !!}
            </p>
            @if(strlen($post->content) > 120)
            <div class="mb-3">
                <a href="{{ route('posts.show', $post->id) }}" class="text-primary text-decoration-none fw-bold"
                    style="font-size: 0.85rem;">
                    ဆက်လက်ဖတ်ရှုရန်...
                </a>
            </div>
            @endif

            <div class="mb-3">
                <small class="text-muted" style="font-size: 0.8rem;">
                    <i class="far fa-eye me-1"></i>{{ number_format($post->views_count) }} views
                </small>
            </div>

            {{-- Interaction Buttons --}}
            <div class="d-flex justify-content-between border-top pt-2">
                <button class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center">
                    <i class="far fa-thumbs-up me-1"></i>{{ number_format($post->likes_count) }}
                </button>
                <button class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center">
                    <i class="far fa-comment me-1"></i>{{ number_format($post->comments_count) }}
                </button>
                <button class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center">
                    <i class="far fa-bookmark me-1"></i>Save
                </button>
                <button class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center">
                    <i class="fas fa-share-alt me-1"></i>Share
                </button>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
</div>

@endsection
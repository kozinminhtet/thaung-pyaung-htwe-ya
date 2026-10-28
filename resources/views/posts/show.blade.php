@extends('layouts.app')

@section('content')

<div class="card mb-2">
    <div class="d-none d-lg-block mb-3 p-3 pb-0">
        <a href="javascript:history.back()" class="desktop-back-btn text-decoration-none text-primary fw-bold">
            <i class="fas fa-arrow-left me-2"></i>
            <span>Back</span>
        </a>
    </div>

    @if($post->video_url)
        @if($post->video_embed_url)
            <div class="ratio ratio-16x9">
                <iframe src="{{ $post->video_embed_url }}" allowfullscreen loading="lazy"></iframe>
            </div>
        @elseif($post->has_video_file)
            <div class="ratio ratio-16x9 bg-black">
                <video controls class="w-100 h-100" style="object-fit: contain;">
                    <source src="{{ $post->video_src }}" type="video/mp4">
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
                <i class="far fa-clock me-1"></i>{{ $post->published_at?->diffForHumans() ?? $post->created_at->diffForHumans() }}
            </small>
        </div>

        <h6 class="fw-bold mb-2" style="line-height: 1.4;">
            {{ $post->display_title }}
        </h6>

        @if($post->content)
            <p class="text-secondary mb-2" style="font-size: 0.95rem; line-height: 1.6;">
                {!! nl2br(e($post->content)) !!}
            </p>
        @endif

        <div class="mb-3">
            <small class="text-muted" style="font-size: 0.8rem;">
                <i class="far fa-eye me-1"></i>{{ number_format($post->views_count) }} views
            </small>
        </div>

        @include('partials.post-interactions', ['post' => $post])
    </div>
</div>

<div class="floating-back-btn d-lg-none" onclick="history.back()">
    <span>Back</span>
</div>

@endsection

@extends('layouts.app')

@section('content')

{{-- Card Start --}}
<div class="card mb-2">
    <div class="d-none d-lg-block mb-3">
        <a href="javascript:history.back()" class="desktop-back-btn text-decoration-none text-primary fw-bold">
            <i class="fas fa-arrow-left me-2"></i>
            <span>နောက်သို့ ပြန်သွားရန်</span>
        </a>
    </div>

    @if($post->video_url)
    <div class="ratio ratio-16x9 bg-black">
        <video controls class="w-100 h-100" style="object-fit: contain;">
            <source src="{{ asset($post->video_url) }}" type="video/mp4">
        </video>
    </div>
    @elseif(!empty($post->image_url))
    <div class="media-wrapper" style="width: 100%; aspect-ratio: 16/9; overflow: hidden; background: #eee;">
        <img src="{{ asset($post->image_url)}}" alt="Post Image"
            style="width: 100%; height: 100%; object-fit: cover; display: block;">
    </div>
    @endif

    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                {{ $post->category?->name ?? 'General' }}
            </span>
            <small class=" text-muted" style="font-size: 0.75rem;">
                <i class="far fa-clock me-1"></i>{{ $post->published_at->diffForHumans() }}
            </small>
        </div>

        {{-- Title --}}
        <h6 class="fw-bold mb-2" style="line-height: 1.4;">
            {{ $post->title ?? 'No Title' }}
        </h6>

        {{-- ❗ DIFFERENT POINT (FULL CONTENT) --}}
        <p class="text-secondary mb-2" style="font-size: 0.95rem; line-height: 1.6;">
            {!! nl2br(e($post->content)) !!}
        </p>

        {{-- Views --}}
        <div class="mb-3">
            <small class="text-muted" style="font-size: 0.8rem;">
                <i class="far fa-eye me-1"></i>{{ number_format($post->views_count) }} views
            </small>
        </div>

        <div class="d-flex justify-content-between border-top pt-2">
            <button class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center">
                <i class="far fa-eye me-1"></i>{{ number_format($post->likes_count) }}
                <i class="far fa-thumbs-up me-1"></i>Like
            </button>
            <button class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center">
                <i class="far fa-eye me-1"></i>{{ number_format($post->comments_count) }}
                <i class="far fa-comment me-1"></i>Comment
            </button>
            <button class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center">
                <i class="far fa-eye me-1"></i>{{ number_format($post->saves_count) }}
                <i class="far fa-bookmark me-1"></i>Save
            </button>
            <button class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center">
                <i class="far fa-eye me-1"></i>{{ number_format($post->shares_count) }}
                <i class="fas fa-share-alt me-1"></i>Share
            </button>
        </div>
    </div>
</div>
{{-- Card End --}}

{{-- Back Button --}}
<div class="floating-back-btn d-lg-none" onclick="history.back()">
    <span>နောက်သို့</span>
</div>

@endsection
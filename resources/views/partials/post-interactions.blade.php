@php
    $viewerLiked = (bool) ($post->viewer_liked ?? false);
    $viewerSaved = (bool) ($post->viewer_saved ?? false);
@endphp

<div class="post-interactions" data-post-interactions>
    <div class="d-flex justify-content-between border-top pt-2">
        @auth
            <button type="button" class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center {{ $viewerLiked ? 'is-active' : '' }}"
                data-action="like" data-url="{{ route($viewerLiked ? 'posts.like.destroy' : 'posts.like', $post) }}"
                data-next-url="{{ route($viewerLiked ? 'posts.like' : 'posts.like.destroy', $post) }}"
                aria-pressed="{{ $viewerLiked ? 'true' : 'false' }}">
                <i class="{{ $viewerLiked ? 'fas' : 'far' }} fa-thumbs-up me-1" aria-hidden="true"></i>
                <span data-count="like">{{ number_format($post->likes_count) }}</span> Like
            </button>
        @else
            <button type="button" class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center" data-requires-login>
                <i class="far fa-thumbs-up me-1" aria-hidden="true"></i><span data-count="like">{{ number_format($post->likes_count) }}</span> Like
            </button>
        @endauth

        <button type="button" class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center"
            data-action="comments" data-url="{{ route('posts.comments.index', $post) }}"
            aria-expanded="false" aria-controls="post-comments-{{ $post->id }}">
            <i class="far fa-comment me-1" aria-hidden="true"></i>
            <span data-count="comment">{{ number_format($post->comments_count) }}</span> Comment
        </button>

        @auth
            <button type="button" class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center {{ $viewerSaved ? 'is-active' : '' }}"
                data-action="save" data-url="{{ route($viewerSaved ? 'posts.save.destroy' : 'posts.save', $post) }}"
                data-next-url="{{ route($viewerSaved ? 'posts.save' : 'posts.save.destroy', $post) }}"
                aria-pressed="{{ $viewerSaved ? 'true' : 'false' }}">
                <i class="{{ $viewerSaved ? 'fas' : 'far' }} fa-bookmark me-1" aria-hidden="true"></i>
                <span data-count="save">{{ number_format($post->saves_count) }}</span> Save
            </button>
        @else
            <button type="button" class="btn btn-link btn-sm text-decoration-none text-secondary flex-grow-1 text-center" data-requires-login>
                <i class="far fa-bookmark me-1" aria-hidden="true"></i><span data-count="save">{{ number_format($post->saves_count) }}</span> Save
            </button>
        @endauth

        <div class="dropdown flex-grow-1 text-center">
            <button type="button" class="btn btn-link btn-sm text-decoration-none text-secondary w-100"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-share-alt me-1" aria-hidden="true"></i>
                <span data-count="share">{{ number_format($post->shares_count) }}</span> Share
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><button type="button" class="dropdown-item" data-share-platform="facebook" data-url="{{ route('feed.show', $post) }}" @auth data-track-url="{{ route('posts.share', $post) }}" @endauth>Facebook</button></li>
                <li><button type="button" class="dropdown-item" data-share-platform="telegram" data-url="{{ route('feed.show', $post) }}" @auth data-track-url="{{ route('posts.share', $post) }}" @endauth>Telegram</button></li>
                <li><button type="button" class="dropdown-item" data-share-platform="copy" data-url="{{ route('feed.show', $post) }}" @auth data-track-url="{{ route('posts.share', $post) }}" @endauth>Copy link</button></li>
            </ul>
        </div>
    </div>

    <section id="post-comments-{{ $post->id }}" class="post-comments mt-2" data-comments-panel data-comments-url="{{ route('posts.comments.index', $post) }}" hidden>
        <button type="button" class="btn btn-link btn-sm px-0" data-comments-more hidden>Load older comments</button>
        <div class="small text-muted py-2" data-comments-list aria-live="polite"></div>
        @auth
            <form class="post-comment-form d-flex gap-2" data-comment-form @guest data-requires-login @endguest action="{{ route('posts.comments.store', $post) }}" method="POST">
                @csrf
                <label class="visually-hidden" for="comment-{{ $post->id }}">Write a comment</label>
                <input id="comment-{{ $post->id }}" class="form-control form-control-sm" type="text" name="content" maxlength="5000" required placeholder="Write a comment...">
                <button class="btn btn-primary btn-sm flex-shrink-0" type="submit" @guest data-requires-login @endguest>Send</button>
            </form>
        @endauth
        <div class="small mt-2" data-interaction-feedback role="status" aria-live="polite"></div>
    </section>
    <div class="small mt-1" data-global-feedback role="status" aria-live="polite"></div>
</div>

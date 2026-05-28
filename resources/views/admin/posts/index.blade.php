@extends('layouts.app')

@section('content')
<div class="admin-posts py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Posts Management</h2>
            <p class="text-muted mb-0">Create, update, publish, and archive posts from one workspace.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('feed.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-house-door"></i>
                <span>Feed</span>
            </a>
            <button class="btn btn-dark d-inline-flex align-items-center gap-2" id="createPostBtn" type="button">
                <i class="bi bi-plus-lg"></i>
                <span>Create Post</span>
            </button>
        </div>
    </div>

    <div class="alert d-none" id="postAlert" role="alert"></div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Post</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Published</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="postsTable">
                        @forelse ($posts as $post)
                        <tr id="postRow{{ $post->id }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="post-thumb bg-light rounded overflow-hidden">
                                        @if ($post->image_url)
                                        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post image">
                                        @else
                                        <i class="bi bi-file-earmark-text text-muted"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-truncate post-content-preview">
                                            {{ \Illuminate\Support\Str::limit($post->content ?: $post->video_url ?: 'Media post', 80) }}
                                        </div>
                                        <small class="text-muted">#{{ $post->id }} -
                                            {{ $post->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $post->category?->name ?? 'Uncategorized' }}</td>
                            <td>
                                <span
                                    class="badge {{ $post->status === 'published' ? 'bg-success' : ($post->status === 'archived' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>{{ $post->user?->name ?? 'Unknown' }}</td>
                            <td>{{ $post->published_at?->format('M d, Y') ?? '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-primary editPostBtn" type="button"
                                        data-id="{{ $post->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger deletePostBtn" type="button"
                                        data-id="{{ $post->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyPostsRow">
                            <td colspan="6" class="text-center py-5 text-muted">No posts found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade admin-post-modal" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0">
            <form id="postForm" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" id="postId" name="post_id">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="postModalTitle">Create Post</h5>
                        <small class="text-muted" id="postModalSubtitle">Add content, media, and publishing
                            status.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="4"
                                placeholder="Write the post content..."></textarea>
                            <div class="invalid-feedback" data-error-for="content"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Uncategorized</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-error-for="category_id"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                            <div class="invalid-feedback" data-error-for="status"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="image" class="form-label">Featured Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                accept="image/jpeg,image/png,image/webp">
                            <div class="form-text" id="currentImageText">JPG, PNG, or WebP up to 5 MB.</div>
                            <div class="invalid-feedback" data-error-for="image"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="video_url" class="form-label">Video URL</label>
                            <input type="url" class="form-control" id="video_url" name="video_url"
                                placeholder="https://youtube.com/...">
                            <div class="invalid-feedback" data-error-for="video_url"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark d-inline-flex align-items-center gap-2" id="savePostBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="savePostSpinner"
                            aria-hidden="true"></span>
                        <span id="savePostText">Save Post</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Delete Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0 text-muted">
                    This post will be permanently deleted. This action cannot be undone.
                </p>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger d-inline-flex align-items-center gap-2"
                    id="confirmDeletePostBtn">
                    <span class="spinner-border spinner-border-sm d-none" id="deletePostSpinner"
                        aria-hidden="true"></span>
                    <span id="deletePostText">Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const postsBaseUrl = "{{ route('admin.posts.index') }}";

window.postRoutes = {
    store: "{{ route('admin.posts.store') }}",
    show: `${postsBaseUrl}/:post`,
    update: `${postsBaseUrl}/:post`,
    destroy: `${postsBaseUrl}/:post`,
    storageBaseUrl: "{{ asset('storage') }}",
};
</script>
<script src="{{ asset('admin/js/posts.js') }}"></script>
@endpush

@push('styles')
<style>
.post-thumb {
    align-items: center;
    display: inline-flex;
    height: 52px;
    justify-content: center;
    width: 72px;
}

.post-thumb img {
    height: 100%;
    object-fit: cover;
    width: 100%;
}

.post-content-preview {
    max-width: 260px;
}

.admin-post-modal .modal-dialog {
    margin-bottom: 1rem;
    margin-top: 1rem;
}

.admin-post-modal .modal-content {
    max-height: calc(100vh - 2rem);
    max-height: calc(100dvh - 2rem);
    overflow: hidden;
}

.admin-post-modal #postForm {
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 2rem);
    max-height: calc(100dvh - 2rem);
    min-height: 0;
}

.admin-post-modal .modal-header,
.admin-post-modal .modal-footer {
    flex-shrink: 0;
}

.admin-post-modal .modal-body {
    flex: 1 1 auto;
    max-height: calc(100vh - 13rem);
    max-height: calc(100dvh - 13rem);
    min-height: 0;
    overflow-y: auto;
}

.admin-post-modal .modal-footer {
    background: #fff;
    box-shadow: 0 -0.25rem 0.75rem rgba(0, 0, 0, 0.04);
    position: relative;
    z-index: 1;
}

@media (max-width: 575.98px) {
    .admin-post-modal .modal-dialog {
        margin: 0.5rem;
    }

    .admin-post-modal .modal-content,
    .admin-post-modal #postForm {
        max-height: calc(100vh - 1rem);
        max-height: calc(100dvh - 1rem);
    }

    .admin-post-modal .modal-body {
        max-height: calc(100vh - 12rem);
        max-height: calc(100dvh - 12rem);
    }
}
</style>
@endpush

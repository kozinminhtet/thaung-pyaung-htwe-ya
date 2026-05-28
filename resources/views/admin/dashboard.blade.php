@extends('layouts.app')

@section('content')
<div class="admin-dashboard py-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Admin Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}. Here is the latest site activity.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-post"></i>
                <span>Manage Posts</span>
            </a>
            <a href="{{ route('feed.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-house-door"></i>
                <span>Feed</span>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 admin-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <small class="text-muted">Total Posts</small>
                            <h3 class="fw-bold mb-0">{{ number_format($postsCount) }}</h3>
                        </div>
                        <span class="admin-stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-file-text"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 admin-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <small class="text-muted">Published</small>
                            <h3 class="fw-bold mb-0">{{ number_format($publishedPostsCount) }}</h3>
                        </div>
                        <span class="admin-stat-icon bg-success-subtle text-success">
                            <i class="bi bi-check2-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 admin-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <small class="text-muted">Drafts</small>
                            <h3 class="fw-bold mb-0">{{ number_format($draftPostsCount) }}</h3>
                        </div>
                        <span class="admin-stat-icon bg-warning-subtle text-warning">
                            <i class="bi bi-pencil"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 admin-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <small class="text-muted">Views</small>
                            <h3 class="fw-bold mb-0">{{ number_format($viewsCount) }}</h3>
                        </div>
                        <span class="admin-stat-icon bg-info-subtle text-info">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Quick Actions</h5>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-dark d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-file-earmark-post me-2"></i>Manage Posts</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('admin.posts.index') }}?create=1" class="btn btn-outline-primary d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-plus-circle me-2"></i>Create Post</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Categories</span>
                        <strong>{{ number_format($categoriesCount) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-muted">Users</span>
                        <strong>{{ number_format($usersCount) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                        <h5 class="fw-bold mb-0">Recent Posts</h5>
                        <a href="{{ route('admin.posts.index') }}" class="text-decoration-none">View All</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Post</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th class="text-end">Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPosts as $post)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-truncate admin-recent-post">
                                                {{ \Illuminate\Support\Str::limit($post->content ?: $post->video_url ?: 'Media post', 70) }}
                                            </div>
                                            <small class="text-muted">by {{ $post->user?->name ?? 'Unknown' }}</small>
                                        </td>
                                        <td>{{ $post->category?->name ?? 'Uncategorized' }}</td>
                                        <td>
                                            <span class="badge {{ $post->status === 'published' ? 'bg-success' : ($post->status === 'archived' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($post->views_count) }}</td>
                                        <td class="text-end text-muted">{{ $post->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No posts yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-stat-icon {
    align-items: center;
    border-radius: 0.5rem;
    display: inline-flex;
    height: 2.5rem;
    justify-content: center;
    width: 2.5rem;
}

.admin-recent-post {
    max-width: 320px;
}
</style>
@endpush

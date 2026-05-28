<div class="col-lg-3 d-none d-lg-block">
    <div class="sidebar-sticky sidebar-scroll">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Popular</h6>

            @forelse($popularPosts ?? [] as $popularPost)
                <a href="{{ route('feed.show', $popularPost->id) }}" class="text-decoration-none text-dark d-block mb-3">
                    <div class="fw-semibold small" style="line-height: 1.4;">
                        {{ $popularPost->display_title }}
                    </div>
                    <div class="text-muted small">
                        {{ $popularPost->category?->name ?? 'General' }} -
                        {{ number_format($popularPost->views_count) }} views
                    </div>
                </a>
            @empty
                <div class="text-muted small">No popular posts yet.</div>
            @endforelse
        </div>
    </div>
</div>

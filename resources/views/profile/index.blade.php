@extends('layouts.app')

@section('content')
<div class="profile-page pb-4">
    <div class="card border-0 shadow-sm mb-3 profile-hero">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="profile-avatar">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <h5 class="fw-bold mb-1 text-truncate">{{ $user->name }}</h5>
                        <p class="text-muted mb-2 small text-truncate">{{ $user->email }}</p>

                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-dark">{{ ucfirst($user->role) }}</span>

                            @if($user->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Banned</span>
                            @endif
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="profile-logout-form">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="card border-0 shadow-sm h-100 profile-stat">
                <div class="card-body text-center p-3">
                    <div class="profile-stat-icon text-primary bg-primary-subtle">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="fw-bold mt-2">{{ number_format($user->posts_count) }}</div>
                    <small class="text-muted">Posts</small>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card border-0 shadow-sm h-100 profile-stat">
                <div class="card-body text-center p-3">
                    <div class="profile-stat-icon text-success bg-success-subtle">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>
                    <div class="fw-bold mt-2">{{ number_format($user->interactions_count) }}</div>
                    <small class="text-muted">Actions</small>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card border-0 shadow-sm h-100 profile-stat">
                <div class="card-body text-center p-3">
                    <div class="profile-stat-icon text-info bg-info-subtle">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div class="fw-bold mt-2">{{ number_format($user->views_count) }}</div>
                    <small class="text-muted">Views</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3 p-md-4">
            <h6 class="fw-bold mb-3">Account Information</h6>

            <div class="profile-info-list">
                <div class="profile-info-row">
                    <span class="text-muted">Name</span>
                    <strong>{{ $user->name }}</strong>
                </div>

                <div class="profile-info-row">
                    <span class="text-muted">Email</span>
                    <strong class="text-break">{{ $user->email }}</strong>
                </div>

                <div class="profile-info-row">
                    <span class="text-muted">Role</span>
                    <strong>{{ ucfirst($user->role) }}</strong>
                </div>

                <div class="profile-info-row">
                    <span class="text-muted">Status</span>
                    <strong>{{ ucfirst($user->status) }}</strong>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 p-md-4">
                <h6 class="fw-bold mb-3">Admin Shortcuts</h6>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-dark d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-speedometer2 me-2"></i>Dashboard</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-primary d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-file-earmark-post me-2"></i>Manage Posts</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.profile-avatar {
    align-items: center;
    background: var(--primary, #2563eb);
    border-radius: 50%;
    color: #fff;
    display: inline-flex;
    flex: 0 0 auto;
    font-size: 1.75rem;
    font-weight: 700;
    height: 72px;
    justify-content: center;
    width: 72px;
}

.profile-logout-form {
    min-width: 110px;
}

.profile-stat-icon {
    align-items: center;
    border-radius: 0.5rem;
    display: inline-flex;
    height: 2.25rem;
    justify-content: center;
    width: 2.25rem;
}

.profile-info-list {
    display: grid;
    gap: 0;
}

.profile-info-row {
    align-items: center;
    border-bottom: 1px solid var(--border, #e5e7eb);
    display: flex;
    gap: 1rem;
    justify-content: space-between;
    padding: 0.75rem 0;
}

.profile-info-row:first-child {
    padding-top: 0;
}

.profile-info-row:last-child {
    border-bottom: 0;
    padding-bottom: 0;
}

@media (max-width: 575.98px) {
    .profile-avatar {
        font-size: 1.5rem;
        height: 60px;
        width: 60px;
    }

    .profile-logout-form {
        width: 100%;
    }

    .profile-stat .card-body {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    .profile-info-row {
        align-items: flex-start;
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>
@endpush

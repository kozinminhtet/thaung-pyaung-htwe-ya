@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><h2 class="fw-bold mb-1">Categories</h2><p class="text-muted mb-0">Manage Video and Article categories.</p></div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-dark">Add category</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table align-middle mb-0">
        <thead class="table-light"><tr><th>Name</th><th>Slug</th><th>Type</th><th>Order</th><th>Posts</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse($categories as $category)
            <tr><td>{{ $category->name }}</td><td>{{ $category->slug }}</td><td>{{ $category->type ? ucfirst($category->type) : 'Unclassified' }}</td><td>{{ $category->sort_order }}</td><td>{{ $category->posts_count }}</td>
                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                    <form class="d-inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Delete</button></form></td></tr>
        @empty<tr><td colspan="6" class="text-center text-muted py-4">No categories found.</td></tr>@endforelse
        </tbody>
    </table></div></div>
    <div class="mt-3">{{ $categories->links() }}</div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 720px">
    <h2 class="fw-bold mb-4">{{ $category->exists ? 'Edit category' : 'Create category' }}</h2>
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="card border-0 shadow-sm">
        @csrf
        @if($category->exists) @method('PUT') @endif
        <div class="card-body">
            <div class="mb-3"><label class="form-label" for="name">Name</label><input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="100">@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="slug">Slug</label><input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required maxlength="100">@error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="icon">Icon</label><input class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" maxlength="50">@error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="type">Type</label><select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required><option value="">Choose type</option><option value="video" @selected(old('type', $category->type) === 'video')>Video</option><option value="article" @selected(old('type', $category->type) === 'article')>Article</option></select>@error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="sort_order">Display order</label><input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}" required>@error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2"><a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a><button class="btn btn-dark" type="submit">Save category</button></div>
    </form>
</div>
@endsection

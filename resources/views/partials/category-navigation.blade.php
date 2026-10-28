@php
$selectedCategory = $categories->firstWhere('id', request()->integer('category_id'));
@endphp

<div class="index-category-nav-wrap bg-white shadow-sm {{ ! empty($inset) ? 'index-category-nav-inset' : '' }}">
    <nav class="index-category-nav-scroll d-flex flex-nowrap gap-1 py-1" aria-label="{{ $allLabel }} categories">
        <a href="{{ route($routeName) }}"
            class="btn btn-sm rounded-pill text-nowrap {{ $selectedCategory ? 'btn-outline-primary' : 'btn-primary' }}"
            @if(! $selectedCategory) aria-current="page" @endif>{{ $allLabel }}</a>

        @foreach($categories as $category)
        <a href="{{ route($routeName, ['category_id' => $category->id]) }}"
            class="btn btn-sm rounded-pill text-nowrap {{ (int) $selectedCategory?->id === (int) $category->id ? 'btn-primary' : 'btn-outline-primary' }}"
            @if((int) $selectedCategory?->id === (int) $category->id) aria-current="page"
            @endif>{{ $category->name }}</a>
        @endforeach
    </nav>
</div>

@push('styles')
<style>
.index-category-nav-wrap {
    position: sticky;
    top: 56px;
    z-index: 999;
    width: 100%;
    max-width: 100%;
    min-width: 0;
}

.index-category-nav-scroll {
    overflow-x: auto;
    width: 100%;
    max-width: 100%;
    min-width: 0;
    scrollbar-width: thin;
    scrollbar-color: #fff #fff;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior-x: contain;
}

.index-category-nav-scroll::-webkit-scrollbar {
    height: 4px;
}

.index-category-nav-scroll::-webkit-scrollbar-track {
    background: #363636;
}

.index-category-nav-scroll::-webkit-scrollbar-thumb {
    background: #363636;
    border-radius: 10px;
}

.index-category-nav-scroll::-webkit-scrollbar-thumb:hover {
    background: #363636;
}

.index-category-nav-inset {
    margin-inline: 12px;
    width: calc(100% - 24px);
}

/* Desktop and default button alignment */
.index-category-nav-scroll .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    min-height: 25px;
    padding: .15rem .4rem;
    font-size: .72rem;
    line-height: 1.2;
    text-align: center;
    white-space: nowrap;
}

@media (min-width: 992px) {
    .index-category-nav-wrap {
        top: 0;
    }
}

/* Mobile */
@media (max-width: 767.98px) {
    .index-category-nav-scroll {
        align-items: center;
        gap: .25rem !important;
        padding-block: .2rem !important;
    }

    .index-category-nav-scroll .btn {
        min-height: 23px;
        padding: .1rem .32rem;
        font-size: .66rem;
        line-height: 1.2;
    }
}
</style>
@endpush
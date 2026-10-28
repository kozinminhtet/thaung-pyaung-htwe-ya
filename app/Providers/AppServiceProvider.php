<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        View::composer('partials.sidebar-right', function ($view) {
            $popularPosts = Post::query()
                ->with('category:id,name')
                ->published()
                ->orderByDesc('views_count')
                ->latest('published_at')
                ->take(5)
                ->get();

            $view->with('popularPosts', $popularPosts);
        });

        View::composer('video.index', function ($view) {
            $view->with('videoCategories', Category::query()
                ->where('type', 'video')
                ->orderBy('sort_order')->orderBy('id')
                ->get(['id', 'name']));
        });

        View::composer('articles.index', function ($view) {
            $view->with('articleCategories', Category::query()
                ->where('type', 'article')
                ->orderBy('sort_order')->orderBy('id')
                ->get(['id', 'name']));
        });
    }
}

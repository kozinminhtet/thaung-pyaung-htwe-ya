<?php

namespace App\Providers;

use App\Models\Post;
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
    }
}

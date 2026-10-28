<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

Route::get('/storage/{path}', function (string $path) {
    $root = realpath(storage_path('app/public'));

    abort_unless($root, 404);

    $file = realpath($root . DIRECTORY_SEPARATOR . $path);

    abort_unless(
        $file && str_starts_with($file, $root . DIRECTORY_SEPARATOR) && is_file($file),
        404
    );

    return response()->file($file);
})->where('path', '.*')->name('storage.local');

/*
|--------------------------------------------------------------------------
| Public Feed Routes
|--------------------------------------------------------------------------
*/

Route::controller(PostController::class)->group(function () {
    Route::get('/', 'index')->name('feed.index');
    Route::get('/search', 'search')->name('feed.search');
    Route::get('/video', 'videoIndex')->name('feed.videos');
    Route::get('/articles', 'articleIndex')->name('feed.articles');
    Route::get('/posts/{id}', 'show')->name('feed.show');
});

Route::get('/posts/{post}/comments', [InteractionController::class, 'comments'])
    ->name('posts.comments.index');

Route::middleware('auth')->group(function () {
    Route::put('/posts/{post}/like', [InteractionController::class, 'like'])
        ->name('posts.like');
    Route::delete('/posts/{post}/like', [InteractionController::class, 'unlike'])
        ->name('posts.like.destroy');
    Route::post('/posts/{post}/comments', [InteractionController::class, 'comment'])
        ->name('posts.comments.store');
    Route::put('/posts/{post}/save', [InteractionController::class, 'save'])
        ->name('posts.save');
    Route::delete('/posts/{post}/save', [InteractionController::class, 'unsave'])
        ->name('posts.save.destroy');
    Route::post('/posts/{post}/share', [InteractionController::class, 'share'])
        ->name('posts.share');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('user.profile');
    Route::get('/saved-posts', [PostController::class, 'savedPosts'])
        ->name('user.saved-posts');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::resource('posts', AdminPostController::class);
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
    });
/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

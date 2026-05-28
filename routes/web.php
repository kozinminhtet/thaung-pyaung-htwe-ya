<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PostController as AdminPostController;

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
    Route::get('/video', 'videoIndex')->name('feed.videos');
    Route::get('/articles', 'articleIndex')->name('feed.articles');
    Route::get('/posts/{id}', 'show')->name('feed.show');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('user.profile');
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
    });
/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $postsCount = Post::count();
        $publishedPostsCount = Post::where('status', 'published')->count();
        $draftPostsCount = Post::where('status', 'draft')->count();
        $categoriesCount = Category::count();
        $usersCount = User::count();
        $viewsCount = Post::sum('views_count');

        $recentPosts = Post::query()
            ->with(['category:id,name', 'user:id,name'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'postsCount',
            'publishedPostsCount',
            'draftPostsCount',
            'categoriesCount',
            'usersCount',
            'viewsCount',
            'recentPosts',
        ));
    }
}

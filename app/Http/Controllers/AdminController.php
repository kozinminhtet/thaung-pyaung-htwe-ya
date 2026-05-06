<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Admin Dashboard
    public function dashboard()
    {
        // For now, simple view
        return view('admin.dashboard');
    }
}

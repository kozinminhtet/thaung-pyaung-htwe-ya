<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $returnTo = $request->query('return_to');
        if (is_string($returnTo)
            && str_starts_with($returnTo, '/')
            && ! str_starts_with($returnTo, '//')
            && ! str_contains($returnTo, '\\')
            && ! preg_match('/[\r\n]/', $returnTo)) {
            $parts = parse_url($returnTo);
            if ($parts !== false && ! isset($parts['host']) && ! isset($parts['scheme'])) {
                $request->session()->put('url.intended', url($returnTo));
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

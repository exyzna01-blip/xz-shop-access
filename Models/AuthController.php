<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $key = Str::lower($request->input('username')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['username' => "Too many login attempts. Try again in {$seconds}s."]);
        }

        $ok = Auth::attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ], true);

        if (!$ok) {
            RateLimiter::hit($key, 60);
            return back()->withErrors(['username' => 'Invalid credentials.']);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        $user = $request->user();
        return redirect()->route($user->role === 'OWNER' ? 'owner.dashboard' : 'admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

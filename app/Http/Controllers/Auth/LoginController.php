<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Display the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle the login request
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $userType = $request->input('user_type'); // New input to identify if it's a user or admin login

        // Log out any existing session if switching between user types
        if ($userType === 'admin') {
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }

            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            }
        } else {
            if (Auth::guard('admin')->check()) {
                Auth::guard('admin')->logout();
            }

            if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('/home');
            }
        }

        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle the logout request
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect based on user type
        return redirect('/login');
    }
}

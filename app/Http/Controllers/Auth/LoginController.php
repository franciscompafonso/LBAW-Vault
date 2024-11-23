<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

    // Check if the email belongs to a non-deleted user
    

    // Log out any existing session if switching between user types
    if ($userType === 'admin') {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        // Attempt to authenticate as an admin
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }
    } else {
        $user = User::where('email', $credentials['email'])->where('isdeleted', false)->first();

        if (!$user) {
            // If the user doesn't exist or is marked as deleted, return an error
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records or the user is deleted.',
            ])->onlyInput('email');
        }
        
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        // Attempt to authenticate as a regular user
        if (Auth::guard('web')->attempt(array_merge($credentials, ['isDeleted' => false]), $request->filled('remember'))) {
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

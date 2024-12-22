<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        if (auth()->check()) {
            return redirect('/home');
        }
        return view('auth.register');
    }

    public function showAdminCreationForm(): View
    {
        if (!auth()->guard('admin')->check()) {
            abort(403, 'Unauthorized action.');
        }

        return view('auth.register'); 
    }


    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:70',
            'username' => 'required|string|min:5|max:25|unique:user_account',
            'email' => 'required|email|max:250|unique:user_account',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if (auth()->guard('admin')->check()) {
        return redirect()->route('admin.user-management')->with('success', 'User account created successfully!');
    }

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('pages.home')
            ->withSuccess('You have successfully registered & logged in!');
    }

}

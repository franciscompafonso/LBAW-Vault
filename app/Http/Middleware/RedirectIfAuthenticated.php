<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if (Auth::guard('web')->check()) {
            return redirect('/home'); // Redirect regular users
        }

        if (Auth::guard('admin')->check()) {
            return redirect('/home'); // Redirect admins
        }

        return $next($request);
    }
}

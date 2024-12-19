<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect() 
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->stateless()->user();
            $user = User::where('google_id', $google_user->getId())->first();
            
            if (!$user) {
                $username = $this->generateUniqueUsername($google_user->getName());

                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    'username' => $username,
                    'password' => bcrypt(Str::random(16)), 
                ]);

                Auth::login($new_user, true);
            } else {
                Auth::login($user, true);
            }

            return redirect()->intended('/home');
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to login with Google.');
        }
    }

    private function generateUniqueUsername($name)
    {
        $username = Str::slug($name);
        $originalUsername = $username;
        $count = 1;

        while (User::where('username', $username)->exists()) {
            $username = "{$originalUsername}{$count}";
            $count++;
        }

        return $username;
    }
}

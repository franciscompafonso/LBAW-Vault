<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\File as HttpFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                if ($google_user->getAvatar()) {
                    $this->uploadProfilePicture($google_user->getAvatar(), $new_user->id);
                }
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

    private function uploadProfilePicture($avatarUrl, $userId)
    {
        $response = Http::get($avatarUrl);
        if ($response->successful()) {
            $tempPath = tempnam(sys_get_temp_dir(), 'avatar');
            file_put_contents($tempPath, $response->getBody()->getContents());
            $file = new HttpFile($tempPath, 'profile_picture.jpg');
            $fileId = FileController::store(new Request(['file' => $file]), 'profile-pictures');
            if ($fileId) {
                $user = User::find($userId);
                $user->file_id = $fileId;
                $user->save();
            }
            unlink($tempPath); 
        }
    }
}

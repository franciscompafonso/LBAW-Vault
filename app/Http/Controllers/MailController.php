<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MailModel; 
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class MailController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if (!$user) {
            Session::flash('error', 'The email you provided couldn\'t be found in our database.');
            return view('auth.resetpassword');
        }
        $token = Str::random(60);
        $user->remember_token = $token;
        $user->save();
        Mail::to($email)->send(new MailModel($token, $user->username));
        Session::flash('success', 'Password reset email sent! Please check ' . $email . '.');
        return view('auth.login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MailModel; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class MailController extends Controller
{
    public function sendResetLink(Request $request)
    {
        // Validate the provided email address
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Check if user with the provided email exists
        $user = User::where('email', $email)->first();
        if (!$user) {
            // If user is not found, show error and return to the reset page
            Session::flash('error', 'The email you provided couldn\'t be found in our database.');
            return view('auth.resetpassword');
        }

        // Generate a reset token
        $token = Password::createToken($user);

        // Store the token in the password_reset_tokens table
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send the reset password link via email
        Mail::to($email)->send(new MailModel($token, $user->username));

        // Flash success message and redirect
        Session::flash('success', 'Password reset email sent! Please check ' . $email . '.');
        return view('auth.login');
    }
}

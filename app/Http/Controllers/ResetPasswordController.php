<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgotpassword');
    }

    public function showResetForm(Request $request, $token = null)
    {
        // Validate if the token exists
        if ($token === null) {
            return view('auth.invalidtoken'); // Render invalid token view
        }

        // Check token expiration
        $passwordReset = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$passwordReset) {
            return view('auth.invalidtoken'); // Render invalid token view
        }
       
        // Get email from the password_reset_tokens table
        $email = $passwordReset->email;

        // Pass the token and email to the resetpassword view
        return view('auth.resetpassword')->with([
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);
        
        $token = $request->token;
        $passwordReset = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$passwordReset) {
            return view('auth.invalidtoken');
        }

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return view('auth.invalidtoken'); // Return invalid token view if no user found
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token from the table
        DB::table('password_reset_tokens')->where('token', $token)->delete();

        Session::flash('success', 'Password reset successfully! You can now log in.');
        return view('auth.login');
    }
}

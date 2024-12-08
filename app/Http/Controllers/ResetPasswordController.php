<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgotpassword');
    }

    public function showResetForm(Request $request, $token = null)
    {
        if ($token === null) {
            return view('auth.invalidtoken');
        }
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            return view('auth.invalidtoken'); 
        }
        return view('auth.resetpassword')->with([
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);
        
        $token = $request->token;
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            return view('auth.invalidtoken');
        }
        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();
        Session::flash('success', 'Password reset successfully! You can now log in.');
        return view('auth.login');
    }
}

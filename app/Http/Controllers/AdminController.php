<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
class AdminController extends Controller
{
    // Display the admin dashboard
    public function index()
    {
        return view('admin.dashboard');
    }

    // Display the user management page
    public function showUserManagement()
    {
        return view('admin.user-management');
    }

    public function listUsers()
    {
        // Retrieve all users
        $users = User::all();

        // Return to a view with the users data
        return view('admin.index', compact('users'));
    }

    // Handle user search by email
    public function searchUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Search for the user by email
        $user = User::where('email', $request->input('email'))->first();

        return view('admin.user-management', [
            'user' => $user,
            'search' => $request->input('email')
        ]);
    }

    // Delete the user by ID
    public function deleteUser($id)
{
    try {
        DB::beginTransaction();

        $user = User::findOrFail($id);

        $deletedUserId = 'deleted-user-' . $user->id;

        // Log user information for debugging
        \Log::info('Deleting user:', ['user' => $user]);

        // Anonymize or disassociate user data in related tables
        DB::table('event')->where('creator_id', $user->id)->update(['creator_id' => null]);
        DB::table('comment')->where('comment_by_id', $user->id)->update(['comment_by_id' => null]);
        DB::table('vote')->where('voted_by_id', $user->id)->update(['voted_by_id' => null]);
        DB::table('poll_response')->where('user_id', $user->id)->update(['user_id' => null]);
        DB::table('answer_review')->where('answered_by_id', $user->id)->update(['answered_by_id' => null]);
        DB::table('review')->where('id_user', $user->id)->update(['id_user' => null]);

        // Log the updates
        \Log::info('User data anonymized or disassociated from related tables.');

        // Soft delete or hard delete the user account
        $user->delete();

        // Log out the user if they are the currently authenticated user
        if (Auth::id() == $user->id) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
        }

        DB::commit();

        return redirect()->route('admin.dashboard')->with('status', 'User has been deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        
        // Log the exception for debugging
        \Log::error('Error deleting user:', ['error' => $e->getMessage()]);

        return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
    }
}


}


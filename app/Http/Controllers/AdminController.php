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
        // Find the user
        $user = User::findOrFail($id);

        // Mark the user as deleted
        $user->isdeleted = true;
        $user->save();

        return redirect()->route('admin.dashboard')->with('status', 'User has been marked as deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
    }
}



}


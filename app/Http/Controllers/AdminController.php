<?php

namespace App\Http\Controllers;

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
        // Find the user by ID and delete
        $user = User::findOrFail($id);
        Event::where('creator_id', $user->id)->delete();
        $user->delete();

        // Redirect back with a success message
        return redirect()->route('admin.user.management')->with('status', 'User account deleted successfully.');
    }
}


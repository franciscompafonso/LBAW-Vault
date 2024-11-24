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
    public function search(Request $request)
{
    // Validate the search input
    $request->validate([
        'email' => 'nullable|email',  // Only validate email if it's provided
    ]);

    // Store the search query
    $search = $request->email;

    // Search logic
    $users = User::where('email', 'like', '%' . $search . '%')->paginate(5);

    // Return the results to the same view
    return view('admin.user-management', compact('users', 'search'));
}

    public function listUsers()
    {
        // Retrieve all users
        $users = User::orderBy('id','asc')->paginate(5);

        // Return to a view with the users data
        return view('admin.user-management', compact('users'));
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

        return redirect()->route('admin.user-management')->with('status', 'User has been marked as deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
    }
}

    public function undeleteUser($id)
    {
        try {
            // Find the user
            $user = User::findOrFail($id);

            // Mark the user as deleted
            $user->isdeleted = false;
            $user->save();

            return redirect()->route('admin.user-management')->with('status', 'User has been unmarked as deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

public function blockUser($userId)
{
    try {
        
        // Execute the SQL statement
        DB::table('user_account')
            ->where('id', $userId)
            ->update(['isblocked' => true]);
        
        return redirect()->route('admin.user-management')->with('status', 'User has been marked as blocked successfully.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error blocking user: ' . $e->getMessage());
    }
}


public function unblockUser($id)
{
    try {
        // Find the user
        $user = User::findOrFail($id);

        // Mark the user as deleted
        $user->isblocked = false;
        $user->save();

        return redirect()->route('admin.users')->with('status', 'User has been unmarked as blocked successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error unblocking user: ' . $e->getMessage());
    }
}



}


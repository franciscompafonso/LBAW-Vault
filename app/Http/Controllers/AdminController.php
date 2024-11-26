<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showCreateAdminForm()
    {
        $admins = Admin::paginate(5); // Fetch users, paginate them if you have many
        return view('admin.create-admin', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Create a new admin
        Admin::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // go back to home
        return redirect()->route('admin.admins.create')->with('message', 'New admin account created successfully!');
    }

    public function search(Request $request)
{
    // Validate the search input
    $request->validate([
        'username' => 'nullable|string|max:255',  // Only validate username if it's provided
    ]);

    // Store the search query
    $search = $request->username;

    // Search logic
    $users = User::where('username', 'like', '%' . $search . '%')->paginate(5);

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

        // Delete any upcoming events created by this user
        // Assuming there's a relationship between Event and User
        // where the Event has a 'user_id' field and a 'date' field
        Event::where('user_id', $id)
            ->where('date', '>', now()) // Check if the event date is in the future
            ->delete();

        return redirect()->route('admin.user-management')->with('status', 'User has been marked as deleted successfully, and upcoming events have been removed.');
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

    public function deleteComment($id)
    {
        // Find the comment.
        $comment = Comment::find($id);

        if (!auth()->guard('admin')->check()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the comment and return it as JSON.
        $comment->delete();
        return back()->with('message', 'successfully deleted the comment!');
    }   

    public function adminEditForm(int $id)
{
    // Make sure the authenticated user is an admin
    if (!auth()->guard('admin')->check()) {
        abort(403, 'Unauthorized action.');
    }

    $user = User::find($id);

    // Optionally, add another authorization check if needed
    // $this->authorize('edit_any_profile', $user);

    return view('user.edit', compact('user'));
}

public function adminEdit(int $id, Request $request)
{
    // Make sure the authenticated user is an admin
    if (!auth()->guard('admin')->check()) {
        abort(403, 'Unauthorized action.');
    }

    $user = User::find($id);

    // Validate the incoming data for admin
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:user_account,username,' . $user->id,
        'email' => 'required|email|max:255|unique:user_account,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',
        'profilepicture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);


    // Update user information
    $user->name = $validatedData['name'];
    $user->username = $validatedData['username'];
    $user->email = $validatedData['email'];

    if ($request->filled('password')) {
        $user->password = bcrypt($validatedData['password']);
    }

    if ($request->hasFile('profilepicture')) {
        if ($user->profilepicture) {
            $oldPath = public_path('images/profile-pictures/' . $user->profilepicture);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $file = $request->file('profilepicture');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/profile-pictures/'), $filename);
        $user->profilepicture = $filename;
    }

    $user->save();
    return redirect()->route('user.profile', ['id' => $user->id]);

}

}
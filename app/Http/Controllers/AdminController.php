<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            $upcomingEventIds = $user->notYetParticipatedEvents()
                ->where('end_date', '>', Carbon::now())
                ->pluck('event_id')
                ->toArray();
            $user->notYetParticipatedEvents()->detach($upcomingEventIds);

            $user->isdeleted = true;
            $user->save();

            Event::where('creator_id', $id)
                ->where('end_date', '>', Carbon::now())
                ->delete();

            return redirect()->route('admin.user-management')
                ->with('status', 'User has been marked as deleted successfully, and upcoming events have been removed.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function blockUser($userId)
    {
        try {
            // Find the user
            $user = User::findOrFail($userId);

            // Get IDs of upcoming events
            $upcomingEventIds = $user->notYetParticipatedEvents()->pluck('event_id')->toArray();

            // Detach the user only from upcoming events
            $user->participatedEvents()->detach($upcomingEventIds);

            // Mark the user as blocked
            $user->isblocked = true;
            $user->save();

            return redirect()->route('admin.user-management')->with('status', 'User has been blocked, and future participations have been removed successfully.');
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
            Storage::put('profile-pictures/' . $filename, file_get_contents($photo));
            $user->profilepicture = $filename;
        }

        $user->save();
        return redirect()->route('user.profile', ['id' => $user->id]);

    }

}
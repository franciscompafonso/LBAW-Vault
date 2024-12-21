<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\File;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Notification;
use App\Models\NotificationSubtypes\AdminAction;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Exception;


class AdminController extends Controller
{
    public function showCreateAdminForm()
    {
        $this->authorize('create', Admin::class);
        $admins = Admin::paginate(5); // Fetch users, paginate them if you have many
        return view('admin.create-admin', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $this->authorize('create', Admin::class);
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
        return redirect()->route('admin.admins.create')->with([
            'success' => true,
            'message' => 'Admin created successfully!'
        ]);
    }

    public function search(Request $request)
    {
        $this->authorize('manageUsers', Admin::class);
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
        $this->authorize('manageUsers', Admin::class);
        // Retrieve all users
        $users = User::orderBy('id','asc')->paginate(5);

        // Return to a view with the users data
        return view('admin.user-management', compact('users'));
    }

    public function deleteUser($id)
    {
        $this->authorize('manageUsers', Admin::class);
        try {
            $user = User::findOrFail($id);

            $upcomingEventIds = $user->notYetParticipatedEvents()
                ->where('end_date', '>', Carbon::now())
                ->pluck('event_id')
                ->toArray();
            $user->notYetParticipatedEvents()->detach($upcomingEventIds);
            
            $uniqueSuffix = uniqid();
            $user->name = "Deleted User $uniqueSuffix";
            $user->email = "deleted-user-$uniqueSuffix@example.com";
            $user->username = "deleted-user-$uniqueSuffix";
            $pictureId = $user->file_id;
            $user->file_id = null;
            $user->isdeleted = true;
            $user->save();

            if ($pictureId) {
                $file = File::find($pictureId);
                if ($file) {
                    $path = $file->path;
                    $file->delete();
                    Storage::disk('profile-pictures')->delete($path);
                }
            }

            Event::where('creator_id', $id)
                ->where('end_date', '>', Carbon::now())
                ->delete();

            return redirect()->route('admin.search.user', ['username' => $user->username])
                ->with([
                    'success' => true,
                    'message' => 'User has been deleted successfully!'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with([
                    'success' => false,
                    'message' => 'Error deleting user: ' . $e->getMessage()
                ]);
        }
    }

    public function blockUser($userId)
    {
        $this->authorize('manageUsers', Admin::class);
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

            // Add notification admin_action emitted to the user
            $notification = Notification::create([
                'emitted_by' => null,
                'emitted_to' => $user->id,
                'date' => now()
            ]);
    
            // Add admin action
            AdminAction::create([
                'id' => $notification->id,
                'admin_id' => auth()->user()->id,
                'action' => 'You were blocked by an admin...',
            ]);

            return redirect()->route('admin.user-management')->with([
                'success' => true,
                'message' => 'User has been blocked, and future participations have been removed successfully.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Error blocking user: ' . $e->getMessage()
            ]);
        }
    }

    public function unblockUser($id)
    {
        $this->authorize('manageUsers', Admin::class);
        try {
            // Find the user
            $user = User::findOrFail($id);

            // Mark the user as deleted
            $user->isblocked = false;
            $user->save();

            return redirect()->route('admin.user-management')->with([
                'success' => true,
                'message' => 'User has been unblocked successfully.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Error unblocking user: ' . $e->getMessage()
            ]);
        }
    }

}
// Error unblocking user: Route [admin.users] not defined.
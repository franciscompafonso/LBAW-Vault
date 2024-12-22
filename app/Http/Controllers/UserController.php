<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(string $username, Request $request)
    {
        $user = User::where('username', $username)->firstOrFail();

        if (!auth()->guard('admin')->check() && !(auth()->check() && auth()->user()->can('viewProfile', $user))) {
            abort(403, 'You do not have permission to view this profile.');
        }

        if ($user->isdeleted) {
            abort(404, 'User not found');
        }

        $myEvents = $user->myEvents()->paginate(4, ['*'], 'myEventsPage');

        $participatedEvents = $user->participatedEvents()->paginate(4, ['*'], 'participatedPage');
        $notYetParticipatedEvents = $user->notYetParticipatedEvents()->paginate(4, ['*'], 'notYetParticipatedPage');
        $ongoingEvents = $user->ongoingEvents()->paginate(4, ['*'], 'ongoingPage');

        if ($request->ajax()) {
            $section = $request->get('section');
            \Log::info("Requested section: {$section}");
            switch ($section) {
                case 'my_events':
                    return view('partials.event_section', [
                        'events' => $myEvents,
                        'sectionId' => 'my_events',
                    ]);
                case 'participated_events':
                    return view('partials.event_section', [
                        'events' => $participatedEvents,
                        'sectionId' => 'participated_events',
                    ]);
                case 'not_yet_participated_events':
                    return view('partials.event_section', [
                        'events' => $notYetParticipatedEvents,
                        'sectionId' => 'not_yet_participated_events',
                    ]);
                case 'ongoing_events':
                    return view('partials.event_section', [
                        'events' => $ongoingEvents,
                        'sectionId' => 'ongoing_events',
                    ]);
                default:
                    return response()->json(['error' => 'Invalid section'], 400);
            }
        }

        return view('user.profile', [
            'user' => $user,
            'myEvents' => $myEvents,
            'participatedEvents' => $participatedEvents,
            'notYetParticipatedEvents' => $notYetParticipatedEvents,
            'ongoingEvents' => $ongoingEvents,
        ]);
    }

    public function editForm(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        if (!auth()->guard('admin')->check() && !(auth()->check() && auth()->user()->can('editProfile', $user))) {
            abort(403, 'You do not have permission to edit this profile.');
        }

        return view('user.edit', compact('user'));
    }

    public function edit(string $username, Request $request)
    {
        $user = User::where('username', $username)->firstOrFail();
        if (!auth()->guard('admin')->check() && !(auth()->check() && auth()->user()->can('editProfile', $user))) {
            abort(403, 'You do not have permission to edit this profile.');
        }

        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:75',
                'username' => 'required|string|min:5|max:35|unique:user_account,username,'.$user->id,
                'email' => 'required|email|max:255|unique:user_account,email,'.$user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'file' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Check if the current password is provided for changing password
            if ($request->filled('current_password') && $request->filled('new_password')) {
                // Validate the current password
                $request->validate([
                    'current_password' => ['required', function ($attribute, $value, $fail) {
                        if (!Hash::check($value, Auth::user()->password)) {
                            return $fail(__('The current password does not match.'));
                        }
                    }],
                    'new_password' => 'required|string|min:8|different:current_password', // Ensure the new password is different from the old one
                    'new_password_confirmation' => 'required|same:new_password', // Confirm new password must match
                ]);

                // Hash and update the new password
                $user->password = Hash::make($request->new_password);  // Save the new password after hashing
            }

            $user->name = $validatedData['name'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];     

            if ($request->hasFile('file')) { 
                $fileId = FileController::store($request, 'profile-pictures');

                if ($fileId) {
                    $pictureId = $user->file_id;
                    $user->file_id = $fileId;
                    $user->save();
                    $this->deletePicture($pictureId);
                }
            }
            else {
                $user->save();
            }

            
            DB::commit();

            return redirect()->route('user.profile', ['username' => $user->username]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    private function deletePicture($pictureId)
    {
        if ($pictureId) {
                $file = File::find($pictureId);
                if ($file) {
                    $path = $file->path;
                    $file->delete();
                    Storage::disk('profile-pictures')->delete($path);
                }
            }
    }

    public function deleteAccount($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $this->authorize('editProfile', $user);

        try {
            $upcomingEventIds = $user->notYetParticipatedEvents()
                ->where('end_date', '>', Carbon::now())
                ->pluck('event_id')
                ->toArray();
            $user->notYetParticipatedEvents()->detach($upcomingEventIds);

            $uniqueSuffix = uniqid();
            $user->name = "Deleted User $uniqueSuffix";
            $user->username = "deleted-$uniqueSuffix";
            $user->email = "deleted-user-$uniqueSuffix@example.com";
            $pictureId = $user->file_id;
            $user->file_id = null;
            $user->isdeleted = true;
            $user->save();

            $this->deletePicture($pictureId);

            Event::where('creator_id', $user->id)
                ->where('end_date', '>', Carbon::now())
                ->delete();

            Auth::logout();

            return redirect('/home')
                ->with('status', 'Your account has been deleted successfully, and upcoming events have been removed.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting account: '.$e->getMessage());
        }
    }
}

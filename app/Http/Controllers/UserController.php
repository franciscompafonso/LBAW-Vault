<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(int $id)
    {
        $user = User::find($id);

        if (! $user | $user->isdeleted) {
            abort(404, 'User not found');
        }
        $myEvents = $user->myEvents()->get();

        $participatedEvents = $user->participatedEvents()->get();
        $notYetParticipatedEvents = $user->notYetParticipatedEvents()->get();
        $ongoingEvents = $user->ongoingEvents()->get();

        return view('user.profile', [
            'user' => $user,
            'myEvents' => $myEvents,
            'participatedEvents' => $participatedEvents,
            'notYetParticipatedEvents' => $notYetParticipatedEvents,
            'ongoingEvents' => $ongoingEvents,
        ]);
    }

    public function editForm(int $id)
    {
        $user = User::find($id);
        $this->authorize('edit_profile', $user, Auth::user());

        return view('user.edit', compact('user'));
    }

    public function edit(int $id, Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::find($id);

            // Check if the authenticated user is the owner or an admin
            if (Auth::user()->cannot('edit_profile', $user) && ! auth()->guard('admin')->check()) {
                abort(403, 'Unauthorized action.');
            }

            // Validate the incoming data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:user_account,username,'.$user->id,
                'email' => 'required|email|max:255|unique:user_account,email,'.$user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'profilepicture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Update the user's details
            $user->name = $validatedData['name'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];

            if ($request->filled('password')) {
                $user->password = bcrypt($validatedData['password']);
            }

            if ($request->hasFile('profilepicture')) {
                $file = $request->file('profilepicture');
                if ($id = FileController::store($request, 'profile-pictures', $file)) {
                    $this->deletePicture($user);
                    $user->picture()->attach($id);
                }
            }

            $user->save();
            DB::commit();

            return redirect()->route('user.profile', ['id' => $user->id]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    private function deletePicture($user) {
        $attachedId = $user->picture->first()->id;
        $attachedPath = $user->picture->first()->path;
        $user->picture()->detach();
        File::find($attachedId)->delete();
        Storage::disk('profile-pictures')->delete($attachedPath);
    }

    public function deleteAccount($id)
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

            $this->deletePicture($user);

            Event::where('creator_id', $id)
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

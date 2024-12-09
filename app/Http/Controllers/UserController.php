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

            if (Auth::user()->cannot('edit_profile', $user) && ! auth()->guard('admin')->check()) {
                abort(403, 'Unauthorized action.');
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:user_account,username,'.$user->id,
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

            return redirect()->route('user.profile', ['id' => $user->id]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    private function deletePicture($pictureId)
    {
        $file = File::find($pictureId);
        $path = $file->path;
        $file->delete();
        Storage::disk('profile-pictures')->delete($path);
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
            $pictureId = $user->file_id;
            $user->file_id = null;
            $user->save();

            $this->deletePicture($pictureId);

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

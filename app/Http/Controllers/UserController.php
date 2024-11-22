<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class UserController extends Controller
{

    public function show(int $id)
    {
        $user = User::find($id);
        $myEvents = $user->myEvents()->get();
        /*
        $participatedEvents = $user->participatedEvents()->get();
        $notYetParticipatedEvents = $user->notYetParticipatedEvents()->get();
        */

        return view('user.profile', [
            'user' => $user,
            'myEvents' => $myEvents,
            /*
            'participatedEvents' => $participatedEvents,
            'notYetParticipatedEvents' => $notYetParticipatedEvents,
            */
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
        $user = User::find($id);

        // Ensure the user has permission to edit their profile
        $this->authorize('edit_profile', $user, Auth::user());

        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user_account,username,' . $user->id, 
            'email' => 'required|email|max:255|unique:user_account,email,' . $user->id,  
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
            if ($user->profilepicture) {
                $oldPath = public_path('images/profile-pictures/' . $user->profilepicture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
    
            $file = $request->file('profilepicture');
            $filename = time() . '_' . $file->getClientOriginalName(); // Ensure a unique filename
            $file->move(public_path('images/profile-pictures/'), $filename);
        
            // Save only the filename in the database
            $user->profilepicture = $filename;
        }

        $user->save();
        return redirect()->route('user.profile', ['id' => $user->id]);
    }


    public function delete(Request $request, $id)
    {
        $user = User::find($id);

        $this->authorize('delete', $user, Auth::user());

        $user->delete();

        return redirect('/');
    }
}

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

        return view('user.profile', [
            'user' => $user
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
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',  
        ]);

        // Update the user's details
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];

        // If a password is provided, hash and update it
        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        // Handle the profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }

            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        // Save the user details
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

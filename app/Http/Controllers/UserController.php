<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    public function edit(int $id, Request $request){
        $user = User::find($id);

        $this->authorize('edit_profile', $user, Auth::user());

        if($request->name) $user->name = $request->input('name');

        if($request->email) $user->email = $request->input('email');

        // if($request->password) $user->password TO DO

        // if ($request->hasFile('profilePicture')) TO DO

        $user->save();
        return redirect()->route('user.profile', ['id' => $id]);
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);

        $this->authorize('delete', $user, Auth::user());

        $user->delete();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
class UserController extends Controller
{

    public function show(int $id)
    {
        $user = User::find($id);

        if (!$user | $user->isdeleted) {abort(404, 'User not found');}
        $myEvents = $user->myEvents()->get();
        
        $participatedEvents = $user->participatedEvents()->get();
        $notYetParticipatedEvents = $user->notYetParticipatedEvents()->get();
        $ongoingEvents = $user->ongoingEvents()->get();

        return view('user.profile', [
            'user' => $user,
            'myEvents' => $myEvents,
            'participatedEvents' => $participatedEvents,
            'notYetParticipatedEvents' => $notYetParticipatedEvents,
            'ongoingEvents' => $ongoingEvents
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

        // Check if the authenticated user is the owner or an admin
        if (Auth::user()->cannot('edit_profile', $user) && !auth()->guard('admin')->check()) {
            abort(403, 'Unauthorized action.');
        }

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
            $filename = time() . '_' . $user->username; // Ensure a unique filename
            $file->move(public_path('images/profile-pictures/'), $filename);
        
            // Save only the filename in the database
            $user->profilepicture = $filename;
        }

        $user->save();
        return redirect()->route('user.profile', ['id' => $user->id]);
    }


    public function deleteAccount($id)
{
    try {
        // Encontrar o usuário
        $user = User::findOrFail($id);

        // Remover participações em eventos futuros
        $upcomingEventIds = $user->notYetParticipatedEvents()
            ->where('start_date', '>', Carbon::now()) // Usa Carbon para verificar datas futuras
            ->pluck('event_id')
            ->toArray();
        $user->notYetParticipatedEvents()->detach($upcomingEventIds);

        // Marcar o usuário como deletado (soft delete)
        $user->isdeleted = true;
        $user->save();

        // Remover eventos futuros criados por este usuário
        Event::where('creator_id', $id)
            ->where('start_date', '>', Carbon::now()) // Verifica eventos com start_date no futuro
            ->delete();

        // Fazer logout do usuário
        Auth::logout();

        // Redirecionar para a página inicial com uma mensagem de status
        return redirect('/home')
            ->with('status', 'Your account has been deleted successfully, and upcoming events have been removed.');
    } catch (\Exception $e) {
        // Redirecionar de volta com uma mensagem de erro em caso de exceção
        return redirect()->back()
            ->with('error', 'Error deleting account: ' . $e->getMessage());
    }
}


   
}

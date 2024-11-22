<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    /**
     * Invite a user to the event.
     */
    public function sendInvite(Request $request, $eventId)
    {
        // Validate the input username
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        // Find the event
        $event = Event::findOrFail($eventId);

        // Find the invitee by username
        $invitee = User::where('username', $request->username)->first();

        if (!$invitee) {
            return back()->with('message', 'The user with the specified username does not exist.');
        }

        // Check if the user is already a participator or has been invited
        $isParticipator = $event->participators()->where('id', $invitee->id)->exists();
        $isAlreadyInvited = Invite::where('invite_to_id', $invitee->id)
                                ->where('invite_by_id', Auth::id())
                                ->where('title', $event->name)
                                ->exists();

        // Handle edge cases
        if ($isParticipator) {
            return back()->with('message', 'User is already a participator in this event.');
        }

        if ($isAlreadyInvited) {
            return back()->with('message', 'User has already been invited to this event.');
        }

        // Prepare invite data
        $inviteData = [
            'invite_by_id' => Auth::id(),
            'invite_to_id' => $invitee->id,
            'title' => $event->name,
            'description' => '', // Empty description, but can be modified as needed
            'date' => now(),
            'accepted' => false,
        ];

        Invite::create($inviteData);
        
        return back()->with('message', 'Invitation sent successfully!');
    }

    
}


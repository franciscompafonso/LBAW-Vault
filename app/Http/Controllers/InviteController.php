<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;
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
            'description' => 'nullable|string|max:255'
        ]);

        // Find the event
        $event = Event::findOrFail($eventId);

        // Check if the user is authorized to invite
        $this->authorize('invite', $event);

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
            'event_id' => $eventId,
            'title' => $event->name,
            'description' => $request->description ?? '',
            'date' => now(),
            'accepted' => false,
        ];

        Invite::create($inviteData);
        
        return back()->with('message', 'Invitation sent successfully!');
    }

    public function acceptInvite(Request $request, $inviteId)
    {
        $invite = Invite::findOrFail($inviteId);

        // Mark the invite as accepted
        $invite->accepted = true;
        $invite->save();

        // Join the event using EventController's logic
        $eventController = app(EventController::class);
        $eventController->joinEvent($invite->event_id);

        // Dismiss the notification
        $notificationController = app(NotificationController::class);
        $notificationController->dismissNotification(new Request(['notification_id' => $request->notification_id]));

        return back()->with('success', 'You have successfully joined the event and accepted the invite!');
    }

    public function declineInvite(Request $request, $inviteId)
    {
        $invite = Invite::findOrFail($inviteId);

        $invite->delete();

        $notificationId = $request->notification_id;
        $notification = Notification::find($notificationId);

        if ($notification) {
            $notification->viewed = true;
            $notification->save();
        }

        return back()->with('success', 'You have successfully declined the invite and dismissed the notification.');
    }
}


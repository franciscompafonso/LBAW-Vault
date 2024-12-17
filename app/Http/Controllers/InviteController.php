<?php

namespace App\Http\Controllers;

use App\Events\InviteSent;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function showUserInvites($id)
    {
        $user = User::findOrFail($id);
        $invites = Invite::where('invite_by_id', $id)->with(['invitee', 'event'])->get();

        return view('user.invites_sent', [
            'user' => $user,
            'invites' => $invites,
        ]);
    }

    public function sendInvite(Request $request, $eventId)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ]);

        $event = Event::findOrFail($eventId);
        $this->authorize('invite', $event);

        $invitee = User::where('username', $request->username)->first();

        if (!$invitee) {
            $message = "The user with the specified username '{$request->username}' does not exist.";
            // Enviando evento Pusher com mensagem
            event(new InviteSent($eventId, $message));

            return back()->with([
                'message' => $message,
                'status' => 'error'
            ]);
        }

        $isParticipator = $event->participators()->where('id', $invitee->id)->exists();
        $isAlreadyInvited = Invite::where('invite_to_id', $invitee->id)
                                ->where('invite_by_id', Auth::id())
                                ->where('title', $event->name)
                                ->exists();

        if ($isParticipator) {
            $message = 'User is already a participator in this event.';
            event(new InviteSent($eventId, $message)); // Envia a mensagem do Pusher
            return back()->with([
                'message' => $message,
                'status' => 'error'
            ]);
        }

        if ($isAlreadyInvited) {
            $message = 'User has already been invited to this event.';
            event(new InviteSent($eventId, $message)); // Envia a mensagem do Pusher
            return back()->with([
                'message' => $message,
                'status' => 'error'
            ]);
        }

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

        // Envia a mensagem do Pusher ao sucesso
        $message = 'Invitation sent successfully!';
        event(new InviteSent($eventId, $message)); 

        return back()->with([
            'message' => $message,
            'status' => 'success'
        ]);
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

    public function deleteInvite($id)
    {
        $invite = Invite::findOrFail($id);

        // Optional: Check if the user has permission to delete the invite
        if (Auth::id() !== $invite->invite_by_id) {
            return back()->with('error', 'You do not have permission to delete this invite.');
        }

        $invite->delete();

        return back()->with('success', 'Invite deleted successfully.');
    }

}


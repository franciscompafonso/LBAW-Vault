<?php

namespace App\Http\Controllers;

use App\Events\AnswerInviteSent;
use App\Events\InviteSent;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function showUserInvites($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('viewInvites', $user);

        // Fetch sent invitations
        $sentInvites = Invite::where('invite_by_id', $id)
                            ->with(['invitee', 'event'])
                            ->get();

        // Fetch received invitations
        $receivedInvites = Invite::where('invite_to_id', $id)
                                ->with(['inviter', 'event'])
                                ->get();

        return view('user.invites', [
            'user' => $user,
            'sentInvites' => $sentInvites,
            'receivedInvites' => $receivedInvites,
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
            return back()->with([
                'message' => "The user with the specified username does not exist.",
                'success' => false
            ]);
        }

        $isParticipator = $event->participators()->where('id', $invitee->id)->exists();
        $isAlreadyInvited = Invite::where('invite_to_id', $invitee->id)
                                ->where('invite_by_id', Auth::id())
                                ->where('title', $event->name)
                                ->exists();

        if ($isParticipator) {
            return back()->with([
                'message' => 'User is already a participator in this event.',
                'success' => false
            ]);
        }

        if ($isAlreadyInvited) {
            return back()->with([
                'message' => 'User has already been invited to this event.',
                'success' => false
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

        event(new InviteSent($invitee->id, $eventId, User::find(Auth::id())->name . " has invited you to " . $event->name));
        return back()->with([
            'message' => 'Invitation sent successfully!',
            'success' => true
        ]);
    }

    public function acceptInvite(Request $request, $inviteId)
    {
        $invite = Invite::findOrFail($inviteId);
        $event = Event::findOrFail($invite->event_id);

        $invite->accepted = true;
        $invite->save();

        $eventController = app(EventController::class);
        $eventController->joinEvent($invite->event_id);

        $notificationController = app(NotificationController::class);
        $notificationController->dismissNotification(new Request(['notification_id' => $request->notification_id]));

        event(new AnswerInviteSent($invite->invite_to_id, $event->id, User::find(Auth::id())->name . " has accepted your invite to " . $event->name));

        return back()->with([
            'message' => 'You have successfully joined the event and accepted the invite!',
            'success' => true
        ]);
    }

    public function deleteInvite($id)
    {
        $invite = Invite::findOrFail($id);
        $event = Event::findOrFail($invite->event_id);

        if (Auth::id() !== $invite->invite_by_id) {
            return back()->with([
                'message' => 'You do not have permission to delete this invite.',
                'success' => false
            ]);
        }

        event(new AnswerInviteSent($invite->invite_to_id, $event->id, User::find(Auth::id())->name . " has rejected your invite to " . $event->name));

        $invite->delete();

        return back()->with([
            'message' => 'Invite deleted successfully.',
            'success' => true
        ]);
    }

}


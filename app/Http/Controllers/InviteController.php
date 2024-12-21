<?php

namespace App\Http\Controllers;

use App\Events\AnswerInviteSent;
use App\Events\InviteSent;
use App\Events\JoinEvent;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\JoinRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function showUserInvites($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $this->authorize('viewInvites', $user);
        $id = $user->id;
        // Fetch sent invitations
        $sentInvites = Invite::where('sender_id', $id)
                            ->with(['receiver', 'event'])
                            ->get();

        // Fetch received invitations
        $receivedInvites = Invite::where('receiver_id', $id)
                                ->with(['sender', 'event'])
                                ->get();

        // Fetch sent requests
        $sentRequests = JoinRequest::where('join_request_by_id', $id)
                                    ->with(['event', 'event.creator'])
                                    ->get();

        // Fetch received requests
        $receivedRequests = JoinRequest::whereHas('event', function ($query) use ($id) {
                            $query->where('creator_id', $id);
                            })
                            ->with(['sender', 'event'])
                            ->get();

        return view('user.invites', [
            'user' => $user,
            'sentInvites' => $sentInvites,
            'receivedInvites' => $receivedInvites,
            'sentRequests' => $sentRequests,
            'receivedRequests' => $receivedRequests,
        ]);
    }

    public function sendInvite(Request $request, $eventId)
    {
        $request->validate([
            'username' => 'required|string|min:5|max:25',
            'description' => 'nullable|string|max:50'
        ]);

        $event = Event::findOrFail($eventId);
        $this->authorize('invite', $event);

        $receiver = User::where('username', $request->username)->first();

        if (!$receiver) {
            return response()->json([
                'message' => "The user with the specified username does not exist.",
                'success' => false
            ]);
        }

        $isParticipator = $event->participators()->where('id', $receiver->id)->exists();
        $isAlreadyInvited = Invite::where('receiver_id', $receiver->id)
                                ->where('sender_id', Auth::id())
                                ->exists();

        if ($isParticipator) {
            return response()->json([
                'message' => 'User is already a participator in this event.',
                'success' => false
            ]);
        }

        if ($isAlreadyInvited) {
            return response()->json([
                'message' => 'User has already been invited to this event.',
                'success' => false
            ]);
        }

        $inviteData = [
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'event_id' => $eventId,
            'description' => $request->description ?? '',
            'date' => now(),
            'accepted' => false,
        ];

        Invite::create($inviteData);

        event(new InviteSent($receiver->id, $eventId, User::find(Auth::id())->name . " has invited you to " . $event->name));
        return response()->json([
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

        event(new AnswerInviteSent($invite->sender_id, $event->id, User::find(Auth::id())->name . " has accepted your invite to " . $event->name));

        return back()->with([
            'message' => 'You have successfully joined the event and accepted the invite!',
            'success' => true
        ]);
    }

    public function declineInvite($id)
    {
        $invite = Invite::findOrFail($id);
        $event = Event::findOrFail($invite->event_id);

        if (Auth::id() !== $invite->receiver_id) {
            return back()->with([
                'message' => 'You do not have permission to reject this invite.',
                'success' => false
            ]);
        }

        event(new AnswerInviteSent($invite->sender_id, $event->id, User::find(Auth::id())->name . " has rejected your invite to " . $event->name));

        $invite->delete();

        return back()->with([
            'message' => 'Invite rejected successfully.',
            'success' => true
        ]);
    }

    public function deleteInvite($id)
    {
        $invite = Invite::findOrFail($id);
        $event = Event::findOrFail($invite->event_id);

        if (Auth::id() !== $invite->sender_id) {
            return back()->with([
                'message' => 'You do not have permission to delete this invite.',
                'success' => false
            ]);
        }

        $invite->delete();

        return back()->with([
            'message' => 'Invite deleted successfully.',
            'success' => true
        ]);
    }

}


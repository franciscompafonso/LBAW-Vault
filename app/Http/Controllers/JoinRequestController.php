<?php

namespace App\Http\Controllers;

use App\Events\AnswerJoinRequest;
use App\Models\JoinRequest;
use App\Models\Event;
use App\Models\Notification;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JoinRequestController extends Controller
{
    

    public function acceptRequest(Request $request, $requestId)
    {
        // Find the join request or fail
        $joinRequest = JoinRequest::findOrFail($requestId);

        // Mark the request as accepted
        $joinRequest->accepted = true;
        $joinRequest->save();

        // Add the user to the event participators
        $event = Event::findOrFail($joinRequest->event_id);
        $event->participators()->attach($joinRequest->join_request_by_id);

        if($request->notification_id){
            // Dismiss the notification
            $notificationController = app(NotificationController::class);
            $notificationController->dismissNotification(new Request(['notification_id' => $request->notification_id]));
        }

        event(new AnswerJoinRequest($joinRequest->join_request_by_id, $event->id, User::find(Auth::id())->name . " accepted your join request to " . $event->name));

        // Return success response
        return back()->with([
            'message' => 'Join request accepted! The user is now a participant of the event.',
            'success' => true
        ]);
    }

    public function declineRequest(Request $request, $requestId)
    {
        // Find the join request by ID
        $joinRequest = JoinRequest::findOrFail($requestId);
        $event = Event::findOrFail($joinRequest->event_id);

        // Delete the join request entry from the database
        $joinRequest->delete();

        // Dismiss the notification
        $notificationId = $request->notification_id;
        Notification::find($notificationId)->delete();

        event(new AnswerJoinRequest($joinRequest->join_request_by_id, $event->id, User::find(Auth::id())->name . " rejected your join request to " . $event->name));

        // Redirect back with a success message
        return back()->with([
            'message' => 'You have successfully declined the request and dismissed the notification.',
            'success' => true
        ]);
    }
}


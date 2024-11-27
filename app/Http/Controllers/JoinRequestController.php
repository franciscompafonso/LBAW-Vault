<?php

namespace App\Http\Controllers;

use App\Models\JoinRequest;
use App\Models\Event;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;

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

        // Dismiss the notification
        $notificationController = app(NotificationController::class);
        $notificationController->dismissNotification(new Request(['notification_id' => $request->notification_id]));

        // Return success response
        return back()->with('success', 'Join request accepted! The user is now a participant of the event.');
    }


    
}


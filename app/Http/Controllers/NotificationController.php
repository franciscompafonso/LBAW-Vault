<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\Invite;
use App\Models\JoinRequest;
use App\Models\Notification;
use App\Models\Poll;
use App\Models\User;
use App\Events\AnswerInviteSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = Auth::user();
            $notifications = Notification::where('emitted_to', $user->id)
                ->where('viewed', false)
                ->orderBy('date', 'desc')
                ->get();

            foreach ($notifications as $notification) {
                $notificationData = $this->getType($notification->id);

                $notification->type = $notificationData['type'];

                foreach ($notificationData as $key => $value) {
                    if ($key !== 'type') {
                        $notification->$key = $value;
                    }
                }
            }

            return response()->json($notifications);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching notifications: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Return the error as JSON response
            return response()->json([
                'error' => 'Failed to fetch notifications.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getType($notificationId)
    {
        $types = [
            'invite_notification' => ['invite_id'],
            'accepted_invite_notification' => ['invite_id'],
            'join_request_notification' => ['join_request_id'],
            'accepted_join_request_notification' => ['join_request_id'],
            'event_update_notification' => ['event_id'],
            'comment_notification' => ['comment_id'],
            'poll_reminder' => ['poll_id'],
            'event_cancelation' => ['event_id'],
            'event_reminder' => ['event_id'],
            'admin_action' => [],
            'unblock_notification' => [],
        ];

        foreach ($types as $type => $params) {
            $modelClass = '\\App\\Models\\NotificationSubtypes\\'.ucfirst(Str::camel($type));

            if (class_exists($modelClass)) {
                $notification = $modelClass::find($notificationId);

                if ($notification) {
                    if ($type == 'comment_notification') {
                        $comment = Comment::find($notification->comment_id);

                        if ($comment) {
                            $comment['event_name'] = Event::find($comment->event_id)->name;
                            $comment['commenter_name'] = User::find($comment->comment_by_id)->name;
                            $comment['commenter_username'] = User::find($comment->comment_by_id)->username;

                            return ['type' => $type, 'data' => $comment];
                        } else {
                            return ['type' => $type, 'data' => 'Comment not found'];
                        }
                    } elseif ($type == 'event_update_notification') {
                        $event = Event::find($notification->event_id);

                        return ['type' => $type, 'data' => $event];
                    } elseif ($type == 'event_cancelation') {
                        $event = Event::find($notification->event_id);

                        return ['type' => $type, 'data' => $event];
                    } elseif ($type == 'accepted_invite_notification') {
                        $invite = Invite::find($notification->invite_id);

                        if ($invite) {
                            $invite['event_name'] = Event::find($invite->event_id)->name;
                            $invite['receiver_name'] = User::find($invite->receiver_id)->name;
                            $invite['receiver_username'] = User::find($invite->receiver_id)->username;

                            return ['type' => $type, 'data' => $invite];
                        } else {
                            return ['type' => $type, 'data' => 'Invite not found'];
                        }
                    } elseif ($type == 'invite_notification') {
                        $invite = Invite::find($notification->invite_id);

                        if ($invite) {
                            $invite['event_name'] = Event::find($invite->event_id)->name;
                            $invite['sender_name'] = User::find($invite->sender_id)->name;
                            $invite['sender_username'] = User::find($invite->sender_id)->username;
                            $invite['receiver_name'] = User::find($invite->receiver_id)->name;

                            return ['type' => $type, 'data' => $invite];
                        } else {
                            return ['type' => $type, 'data' => 'Invite not found'];
                        }
                    } elseif ($type == 'accepted_join_request_notification') {
                        $join_request = JoinRequest::find($notification->join_request_id);

                        if ($join_request) {
                            $join_request['event_name'] = Event::find($join_request->event_id)->name;
                            $join_request['event_creator_id'] = Event::find($join_request->event_id)->creator->id;
                            $join_request['event_creator_name'] = Event::find($join_request->event_id)->creator->name;
                            $join_request['event_creator_username'] = Event::find($join_request->event_id)->creator->username;
                            $join_request['join_request_by_name'] = User::find($join_request->join_request_by_id)->name;

                            return ['type' => $type, 'data' => $join_request];
                        } else {
                            return ['type' => $type, 'data' => 'Join request not found'];
                        }
                    } elseif ($type == 'join_request_notification') {
                        $join_request = JoinRequest::find($notification->join_request_id);

                        if ($join_request) {
                            $join_request['event_name'] = Event::find($join_request->event_id)->name;
                            $join_request['event_creator'] = Event::find($join_request->event_id)->creator->name;
                            $join_request['join_request_by_name'] = User::find($join_request->join_request_by_id)->name;
                            $join_request['join_request_by_username'] = User::find($join_request->join_request_by_id)->username;
                            

                            return ['type' => $type, 'data' => $join_request];
                        } else {
                            return ['type' => $type, 'data' => 'Join request not found'];
                        }
                    } elseif ($type == 'poll_reminder') {
                        $poll = Poll::find($notification->poll_id);

                        if ($poll) {
                            $poll['event_name'] = Event::find($poll->event_id)->name;
                            $poll['user_name'] = User::find($poll->comment_by_id)->name;

                            return ['type' => $type, 'data' => $poll];
                        } else {
                            return ['type' => $type, 'data' => 'Poll not found'];
                        }
                    } else {
                        return ['type' => $type, 'data' => $notification->toArray()];
                    }
                }
            }
        }

        return ['type' => 'unknown'];
    }

    public function dismissNotification($notification_id)
    {
        $notification = Notification::find($notification_id);
        if ($notification) {
            $notification->viewed = true;
            $notification->save();
        }

        return response()->json([
            'message' => 'You have dismissed the notification',
            'success' => true
        ]);
    }

    public function dismissAllNotifications()
    {
        $user = Auth::user();
        Notification::where('emitted_to', $user->id)
            ->update(['viewed' => true]);

        return response()->json([
            'message' => 'You have dismissed all notifications with success',
            'success' => true
        ]);
    }
}

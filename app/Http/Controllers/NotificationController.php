<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Models\Comment;
use App\Models\Invite;
use App\Models\Poll;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
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
    }

    public function getType($notificationId)
    {
        $types = [
            'invite_notification' => ['invite_id'],
            'accepted_invite_notification' => ['event_id'],
            'event_update_notification' => ['event_id'],
            'comment_notification' => ['comment_id'],
            'poll_reminder' => ['poll_id'],
            'event_cancelation' => ['event_id'],
            'event_reminder' => ['event_id'],
            'admin_action' => [],
            'unblock_notification' => []
        ];

        foreach ($types as $type => $params) {
            $modelClass = '\\App\\Models\\NotificationSubtypes\\' . ucfirst(Str::camel($type));
        
            if (class_exists($modelClass)) {
                $notification = $modelClass::find($notificationId);
        
                if ($notification) {
                    if ($type == 'comment_notification') {
                        $comment = Comment::find($notification->comment_id);
                        
                        if ($comment) {
                            $result = $comment->show();
                            return ['type' => $type, 'data' => $result];
                        } else {
                            return ['type' => $type, 'data' => 'Comment not found'];
                        }
                    } elseif ($type == 'invite_notification') {
                        $invite = Invite::find($notification->invite_id);
                        
                        if ($invite) {
                            $result = $invite->show();
                            return ['type' => $type, 'data' => $result];
                        } else {
                            return ['type' => $type, 'data' => 'Invite not found'];
                        }
                    } elseif ($type == 'poll_reminder') {
                        $poll = Poll::find($notification->poll_id);
                        
                        if ($poll) {
                            $result = $poll->show();
                            return ['type' => $type, 'data' => $result];
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

    public function dismissNotification(Request $request)
    {
        $notification = Notification::find($request->notification_id);
        if ($notification) {
            $notification->viewed = true;
            $notification->save();
        }
        return response()->json(['success' => true]);
    }


    public function dismissAllNotifications()
    {
        $user = Auth::user();
        Notification::where('emitted_to', $user->id)
                   ->update(['viewed' => true]);
        return response()->json(['success' => true]);
    }

    public function unreadCount(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $unreadCount = Notification::where('emitted_to', $user->id)
                                    ->where('viewed', false)
                                    ->count();

        return response()->json(['unreadCount' => $unreadCount]);
    }

}

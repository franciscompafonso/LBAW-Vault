<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('emitted_to', $user->id)
                                      ->where('viewed', false)
                                      ->orderBy('date', 'desc')
                                      ->get();
        return response()->json($notifications);
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
}

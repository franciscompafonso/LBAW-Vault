<?php

namespace App\Http\Controllers;

use App\Events\AdminActionSent;
use App\Events\NewComment;
use App\Models\Comment;
use App\Models\Event;
use App\Models\File;
use App\Models\Notification;
use App\Models\NotificationSubtypes\AdminAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{

    /**
     * Delete an comment.
     */
    public function delete($id)
    {
        $comment = Comment::findOrFail($id);

        if (!$comment->reply_to) {
            $replies = Comment::where('reply_to', $id)->get();
            foreach ($replies as $reply) {
                $this->delete($reply->id);
            }
        }
        
        // Check if the current user is an admin or authorized by policy
        if (auth()->guard('admin')->check() || (auth()->check() && auth()->user()->can('delete', $comment))) {
            // Delete associated photos
            foreach ($comment->photos as $photo) {
                $photoId = $photo->id;
                $photoPath = $photo->path;

                File::find($photoId)->delete();
                Storage::disk('others')->delete($photoPath);
            }

            if (auth()->guard('admin')->check()) {
                $notification = Notification::create([
                    'emitted_by' => null,
                    'emitted_to' => $comment->comment_by_id,
                    'date' => now()
                ]);
        
                // Add admin action
                AdminAction::create([
                    'id' => $notification->id,
                    'admin_id' => auth()->user()->id,
                    'action' => 'Your comment, \'' . $comment->text . '\' in the ' . Event::find($comment->event_id)->name . ' event has been deleted by an admin...',
                ]);

                event(new AdminActionSent($comment->comment_by_id, "You're comment was deleted by an admin..."));
            }

            // Delete the comment
            $comment->delete();

            return response()->json([
                'message' => 'Comment deleted successfully!',
                'success' => true
            ]);
        }

        // Unauthorized response
        abort(403, 'You are not authorized to delete this comment.');
    }

    public function show($eventId)
    {
        // Fetch the event and its comments, eager load replies
        $comments = Comment::with('replies.user', 'replies.photos')->where('event_id', $eventId)->get();

        return view('event.show', compact('comments'));
    }

    public function store(Request $request)
    {
        
        $data = $request->validate([
            'content' => 'required|string|max:1000',
            'photos' => 'nullable|array|max:15', // Allow up to 15 photos
            'photos.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $event = Event::find($request->event);

        $comment = $event->comments()->create([
            'comment_by_id' => Auth::id(),
            'text' => $data['content'],
            'is_pinned' => false,
            'score' => 0,
            'edited' => false,
            'date' => now(),
        ]);

        if ($request->photos) {
            foreach ($request->photos as $photo) {
                FileController::store($request, 'others', $photo, $comment->id);
            }
        }

        $participants = $event->participators;

        foreach ($participants as $participant) {
            if($participant->id != Auth::id()) event(new NewComment($participant->id, $event->id, User::find(Auth::id())->name . " has commented on " . Event::find($comment->event_id)->name));
        }

        return response()->json([
            'message' => 'Comment created with success!',
            'success' => true
        ]);;
    }

    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment = Comment::find($commentId);

        // Check if the comment exists
        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found.',
                'success' => false
            ]);;
        }

        // Create a new reply to the comment
        $reply = new Comment();
        $reply->comment_by_id = auth()->id();
        $reply->event_id = $comment->event_id;
        $reply->text = $request->input('text');
        $reply->reply_to = $comment->id;
        $reply->date = now();  // Set the current timestamp
        $reply->save();

        if($comment->comment_by_id != Auth::id()) event(new NewComment($comment->comment_by_id, $comment->event_id, User::find(Auth::id())->name . " has answered to your comment on " . Event::find($comment->event_id)->name));

        return response()->json([
            'message' => 'Reply added with success!',
            'success' => true
        ]);
    }
}
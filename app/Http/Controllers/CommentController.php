<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    /**
     * Delete an comment.
     */
    public function delete($id)
    {
        // Find the comment.
        $comment = Comment::find($id);

        // Check if the current user is authorized to delete this comment.
        $this->authorize('delete', $comment);

        $comment->delete();
        return back()->with('message', 'successfully deleted the comment!');
    }

    public function show($eventId)
    {
        // Fetch the event and its comments, eager load replies
        $comments = Comment::with('replies.user', 'replies.photos')->where('event_id', $eventId)->get();

        return view('event.show', compact('comments'));
    }

    public function store(Event $event)
    {
        // Validate incoming request
        $data = request()->validate([
            'content' => 'required|string|max:1000',
            'photos' => 'nullable|array|max:15', // Allow up to 15 photos
            'photos.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048', // Validate each photo
        ]);

        // Store the comment
        $comment = $event->comments()->create([
            'comment_by_id' => Auth::id(),
            'text' => $data['content'],
            'is_pinned' => false,
            'score' => 0,
            'edited' => false,
            'date' => now(),
        ]);

        if (isset($data['photos'])) {
            PhotoController::storePhotos($comment, $data['photos']);
        }

        return redirect()->back()->with('success', 'Comentário adicionado com sucesso!');
    }

    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment = Comment::find($commentId);

        // Check if the comment exists
        if (!$comment) {
            return redirect()->back()->with('error', 'Comment not found.');
        }

        // Create a new reply to the comment
        $reply = new Comment();
        $reply->comment_by_id = auth()->id();
        $reply->event_id = $comment->event_id;
        $reply->text = $request->input('text');
        $reply->reply_to = $comment->id;
        $reply->date = now();  // Set the current timestamp
        $reply->save();

        return redirect()->back(); // Redirect back after posting the reply
    }
}
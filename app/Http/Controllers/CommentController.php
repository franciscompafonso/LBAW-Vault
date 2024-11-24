<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

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

        // Delete the comment and return it as JSON.
        $comment->delete();
        return back()->with('message', 'successfully deleted the comment!');
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

}
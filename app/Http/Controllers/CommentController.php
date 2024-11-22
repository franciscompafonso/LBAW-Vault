<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentController extends Controller
{

    /**
     * Delete an comment.
     */
    public function deleteComment($id)
    {
        // Find the comment.
        $comment = Comment::find($id);

        // Check if the current user is authorized to delete this comment.
        $this->authorize('delete', $comment);

        // Delete the comment and return it as JSON.
        $comment->delete();
        return back()->with('message', 'successfully deleted the comment!');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentFeedback;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentFeedbackController extends Controller
{
    public function like($commentId)
    {
        return $this->handleFeedback($commentId, 1);
    }

    public function dislike($commentId)
    {
        return $this->handleFeedback($commentId, -1);
    }

    private function handleFeedback($commentId, int $feedbackValue)
    {
        $existingFeedback = CommentFeedback::where('user_id', Auth::id())
            ->where('comment_id', $commentId)
            ->first();

        $comment = Comment::find($commentId);
        
        if ($existingFeedback) {
            CommentFeedback::where('user_id', Auth::id())
                ->where('comment_id', $commentId)
                ->delete();
            if ($existingFeedback->feedback === $feedbackValue) {
                $comment->score -= $feedbackValue;
            } else {
                CommentFeedback::create([
                    'user_id' => Auth::id(),
                    'comment_id' => $commentId,
                    'feedback' => $feedbackValue,
                ]);
                $comment->score += 2*$feedbackValue;
            }
        } else {
            CommentFeedback::create([
                'user_id' => Auth::id(),
                'comment_id' => $commentId,
                'feedback' => $feedbackValue,
            ]);
            $comment->score += $feedbackValue;
        }

        $comment->save();

        return response()->json(['success' => true, 'message' => 'Feedback added with success!'], 200);
    }

}

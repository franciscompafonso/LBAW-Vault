<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentFeedback;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentFeedbackController extends Controller
{
    public function like(Request $request)
    {
        return $this->handleFeedback($request, 1);
    }

    public function dislike(Request $request)
    {
        return $this->handleFeedback($request, -1);
    }

    private function handleFeedback(Request $request, int $feedbackValue)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:comment,id', // Ensure the table name matches your database
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data provided.',
                'errors' => $validator->errors(),
            ], 400); // Return error response for failed validation
        }

        // Check if the user has already provided feedback for this comment
        $existingFeedback = CommentFeedback::where('user_id', Auth::id())
            ->where('comment_id', $request->comment_id)
            ->first();
        
        $comment = Comment::find($request->comment_id);
        
        if ($existingFeedback) {
            if ($existingFeedback->feedback === $feedbackValue) {
                // User is toggling off their feedback
                $existingFeedback->delete();
                $comment->score -= $feedbackValue; // Revert the feedback
            } else {
                // User is switching feedback
                $existingFeedback->update(['feedback' => $feedbackValue]);
                $comment->score += 2 * $feedbackValue; // Adjust score (+2 or -2)
            }
        } else {
            // Insert new feedback
            CommentFeedback::create([
                'user_id' => Auth::id(),
                'comment_id' => $request->comment_id,
                'feedback' => $feedbackValue,
            ]);
            $comment->score += $feedbackValue; // Add the new feedback
        }

        $comment->save();

        return redirect()->back()->with('success', 'Reação adicionada com sucesso!');
    }

}

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
        $validator = Validator::make($request->all(), [
            'comment_id' => 'required|exists:comment,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data provided.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $existingFeedback = CommentFeedback::where('user_id', Auth::id())
            ->where('comment_id', $request->comment_id)
            ->first();
        
        $comment = Comment::find($request->comment_id);
        
        if ($existingFeedback) {
            if ($existingFeedback->feedback === $feedbackValue) {
                $existingFeedback->delete();
                $comment->score -= $feedbackValue;
            } else {
                $existingFeedback->update(['feedback' => $feedbackValue]);
                $comment->score += 2 * $feedbackValue;
            }
        } else {
            CommentFeedback::create([
                'user_id' => Auth::id(),
                'comment_id' => $request->comment_id,
                'feedback' => $feedbackValue,
            ]);
            $comment->score += $feedbackValue;
        }

        $comment->save();

        return redirect()->back()->with('success', 'Reação adicionada com sucesso!');
    }

}

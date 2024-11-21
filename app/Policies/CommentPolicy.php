<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;


class CommentPolicy
{

    /**
     * Determine if a comment can be deleted by a user.
     */
    public function delete(User $user, Comment $comment): bool
    {
      // Only a comment owner can delete it.
      return $user->id === $comment->comment_by_id;
    }
}

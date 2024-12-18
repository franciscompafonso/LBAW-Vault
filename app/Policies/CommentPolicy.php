<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;


class CommentPolicy
{

    public function delete($user, Comment $comment)
    {
        if (auth()->guard('admin')->check()) { return true;}
        return $user->id === $comment->comment_by_id;
    }

    public function reply(User $user, Comment $comment)
    {
        return !$user->isdeleted && !$user->isblocked;
    }

    public function like(User $user, Comment $comment)
    {
        return !$user->isdeleted && !$user->isblocked;
    }
}

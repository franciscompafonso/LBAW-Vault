<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function show(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id || $currentUser->is_admin;
    }

    public function viewProfile($currentUser, User $targetUser)
    {
        $isAdmin = auth()->guard('admin')->check();
        return !$targetUser->isdeleted || $isAdmin;
    }

    public function editProfile($currentUser, User $targetUser)
    {
        $isAdmin = auth()->guard('admin')->check();
        return $currentUser->id === $targetUser->id || $isAdmin;
    }

    public function login(User $user)
    {
        return !$user->isdeleted;
    }

    public function viewInvites($currentUser, User $targetUser)
    {
        return $currentUser->id === $targetUser->id;
    }

}

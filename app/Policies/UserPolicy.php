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

    public function edit_profile(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }
}

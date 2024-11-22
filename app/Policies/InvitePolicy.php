<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class InvitePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user can invite others to an event.
     */
    public function invite(User $user, Event $event): bool
    {
        // Allow if the event is public or the user is the event's creator.
        return $event->visibility === 'public' || $user->id === $event->creator_id;
    }
}

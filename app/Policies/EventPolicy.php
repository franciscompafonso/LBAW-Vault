<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

use Illuminate\Support\Facades\Auth;

class EventPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a given event can be shown to a user.
     */
    public function show(User $user, Event $event): bool
    {
        // Only a event owner can see a event.
        return $event->visibility === "public" || $user->id === $event->creator_id;
    }

    /**
     * Determine if all events can be listed by a user.
     */
    public function index(User $user): bool
    {
        // Any (authenticated) user can list its own events.
        return Auth::check();
    }

    /**
     * Determine if a event can be created by a user.
     */
    public function create(User $user): bool
    {
        // Any user can create a new event.
        return Auth::check();
    }

    /**
     * Determine if a event can be deleted by a user.
     */
    public function delete(User $user, Event $event): bool
    {
      // Only a event owner can delete it.
      return $user->id === $event->user_id;
    }
}

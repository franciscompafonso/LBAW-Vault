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
    public function show(?User $user, Event $event): bool
    {
        if($event->visibility === "public"){
            return true;
        }

        if($event->visibility === "request_only"){
            return true;
        }

        if($user){
            if($user->id === $event->creator_id){
                return true;
            }

            if ($event->participators()->where('participator_id', $user->id)->exists()) {
                return true;
            }

        }
        
        return false;
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
    $isAdmin = auth()->guard('admin')->check();

    return $user->id === $event->creator_id || $isAdmin;
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

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

            if ($event->invites()->where('invite_to_id', $user->id)->where('accepted', false)->exists()) {
                return true;
            }
        }
        return false;
    }

    
    public function index(User $user): bool
    {
        return Auth::check();
    }


    public function create(User $user): bool
    {
        return Auth::check();
    }

    public function delete(User $user, Event $event): bool
{
    $isAdmin = auth()->guard('admin')->check();

    return $user->id === $event->creator_id || $isAdmin;
}

    public function update(User $user, Event $event)
    {
        $isAdmin = auth()->guard('admin')->check();
        return $user->id === $event->creator_id || $isAdmin;
    }

    public function viewSurveyResults(User $user, Event $event)
    {
        $isAdmin = auth()->guard('admin')->check();
        return $user->id === $event->creator_id && $event->end_date < now() || $isAdmin;
    }

    public function createPoll(User $user, Event $event)
    {
        return $user->id === $event->creator_id;
    }

    public function join(User $user, Event $event)
    {
        return $event->visibility === 'public'
            && $event->participators()->count() < $event->capacity
            && !$event->participators->contains($user)
            && !$user->isblocked;
    }

    public function requestJoin(User $user, Event $event)
    {
        return $event->visibility === 'request_only'
            && !$event->participators->contains($user)
            && $event->requests->where('join_request_by_id', $user->id)->where('accepted', false)->isNotEmpty()
            && !$user->isblocked;
    }
    public function leave(User $user, Event $event)
    {
        return $event->participators->contains($user);
    }

    public function kick(User $user, Event $event)
    {
        $isAdmin = auth()->guard('admin')->check();
        return $user->id === $event->creator_id || $isAdmin;
    }

    public function invite(User $user, Event $event): bool
    {
        return $event->visibility === 'public' || $user->id === $event->creator_id;
    }

    public function uploadPhotos(User $user, Event $event)
    {
        return $event->participators->contains($user);
    }

    public function survey(User $user, Event $event)
    {
        return $event->participators->contains($user)
            && $event->end_date < now();
    }

    
}

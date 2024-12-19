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

            if ($event->invites()->where('receiver_id', $user->id)->where('accepted', false)->exists()) {
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
        return Auth::check() && !$user->isblocked;
    }

    public function delete($user, Event $event): bool
    {
        if (auth()->guard('admin')->check()) { return true;}
        return $user->id === $event->creator_id;
    }

    public function update($user, Event $event): bool
    {
        if (auth()->guard('admin')->check()) { return true;}
        return $user->id === $event->creator_id;
    }


    public function viewSurveyResults($user, Event $event)
    {
        if (auth()->guard('admin')->check()) { return true;}
        return $user->id === $event->creator_id;
    }

    public function createPoll(User $user, Event $event)
    {
        return $user->id === $event->creator_id;
    }

    public function join(User $user, Event $event)
    {
        return $event->end_date > now()
            && ((!$event->participators->contains($user) && $event->creator->id === $user->id) || ($event->visibility === 'public'
            && $event->participators()->count() < $event->capacity
            && !$event->participators->contains($user)
            && !$user->isblocked));
    }

    public function requestJoin(User $user, Event $event)
    {
        return $event->end_date > now()
            && $event->visibility === 'request_only'
            && !$event->participators->contains($user)
            && $event->requests->where('join_request_by_id', $user->id)->where('accepted', false)->isEmpty()
            && !$user->isblocked
            && $event->creator->id !== $user->id;
    }

    public function requestSent(User $user, Event $event)
    {
        return !$event->requests->where('join_request_by_id', $user->id)->where('accepted', false)->isEmpty();
    }

    public function leave(User $user, Event $event)
    {
        return $event->participators->contains($user);
    }

    public function kickParticipator(User $user, Event $event, User $participator)
    {
        return $user->id === $event->creator_id && $event->participators->contains($participator);
    }   

    public function invite(User $user, Event $event): bool
    {
        return $event->end_date > now()
            && (($event->participators->contains($user) && $event->visibility === 'public') || $user->id === $event->creator_id);
    }

    public function uploadPhotos(User $user, Event $event)
    {
        return $event->end_date > now()->addDays(7)
        && $event->participators->contains($user);
    }

    public function comment(User $user, Event $event)
    {
        return $event->end_date > now()->addDays(7);
    }

    public function survey(User $user, Event $event)
    {
        return $event->participators->contains($user)
            && $event->end_date < now();
    }
    
    public function addToCalendar(User $user, Event $event)
    {
        return $event->participators->contains($user)
            && $event->end_date < now();
    }
}

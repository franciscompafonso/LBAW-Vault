<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    public function vote(User $user, Poll $poll)
    {
    // Ensure the user hasn't already voted and the poll is still open
    $hasVoted = $poll->responses()->where('user_id', $user->id)->exists();
    return !$hasVoted && now()->between($poll->start_time, $poll->end_time);
    }
}

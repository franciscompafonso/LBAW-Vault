<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Comment;
use App\Models\Event;
use App\Models\PollResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'options' => 'required|string', 
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        $event = Event::findOrFail($eventId);

        $poll = Poll::create([
            'event_id' => $event->id,
            'name' => $request->input('name'),
            'options' => $request->input('options'), 
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Poll created successfully!');
    }

    public function show($pollId)
    {
        $poll = Poll::with('responses')->find($pollId);

        if (!$poll) {
            abort(404, 'Poll not found.');
        }

        $options = json_decode($poll->options, true);
        return view('poll.show', compact('poll', 'options'));
    }

    public function vote(Request $request, $pollId)
    {

        $poll = Poll::findOrFail($pollId);

        $this->authorize('vote', $poll);
        $request->validate([
            'option' => 'required|integer', 
        ]);

        PollResponse::create([
            'poll_id' => $poll->id,
            'user_id' => auth()->id(), 
            'option' => $request->input('option'), 
        ]);

        return back()->with('success', 'Your vote has been recorded.');
    }

}

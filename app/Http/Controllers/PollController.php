<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Comment;
use App\Models\Event;
use App\Models\PollResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PollController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $event = Event::findOrFail($eventId);

        $poll = Poll::create([
            'event_id' => $event->id,
            'name' => $request->input('name'),
            'options' => json_encode($request->input('options')), 
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
        if (!is_array($options)) {
            $options = [];
        }

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

        return back()->with([
            'message' => 'Your vote has been recorded.',
            'success' => true
        ]);
    }

}

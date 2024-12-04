<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Location;
use App\Models\Category;
use App\Models\JoinRequest;
use App\Models\QuestionReview;
use App\Models\AnswerReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class EventController extends Controller
{
    public function create(): Factory|View
    {
        $locations = Location::all();
        $categories = Category::all();
        return view('event.create', compact('locations', 'categories'));
    }

    /**
     * Show the event for a given id.
     */
    public function show(string $id): View
    {
        $event = Event::with(['polls.responses'])->findOrFail($id);

        $this->authorize('show', $event);  

        return view('pages.event', [
            'event' => $event,
            'polls' => $event->polls,
        ]);
    }

    /**
     * Shows all events.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');

        } 

            $user = Auth::user();

            $createdEvents = $user->myEvents()->orderBy('id')->get();
            
            $participatedEvents = $user->participatedEvents()->orderBy('start_date')->get();

            $notYetParticipatedEvents = $user->notYetParticipatedEvents()->orderBy('start_date')->get();

            $ongoingEvents = $user->ongoingEvents()->orderBy('start_date')->get();

            $this->authorize('index', Event::class);


            return view('pages.events', [
                'createdEvents' => $createdEvents,
                'participatedEvents' => $participatedEvents,
                'notYetParticipatedEvents' => $notYetParticipatedEvents,
                'ongoingEvents' => $ongoingEvents,
            ]);
        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'visibility' => 'required|in:public,private',
            'capacity' => 'required|integer|min:1',
            'location_id' => 'required|integer',
            'category_id' => 'required|integer',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $event = Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'visibility' => $validated['visibility'],
            'capacity' => $validated['capacity'],
            'creator_id' => Auth::id(),
            'location_id' => $validated['location_id'],
            'category_id' => $validated['category_id'],
        ]);

        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = time() . '_' . $event->id;
            Storage::put('banner/' . $filename, file_get_contents($file));
            $event->banner = $filename;
        }
        $event->save();

        return redirect()->route('events.show', ['id' => $event->id])->with('success', 'Event created successfully!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $locations = Location::all();
        $categories = Category::all();
        return view('event.edit', compact('event', 'locations', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'visibility' => 'required|in:public,private',
            'capacity' => 'required|integer|min:1',
            'location_id' => 'required|integer',
            'category_id' => 'required|integer',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $event->update($validated);

        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = time() . '_' . $event->id;
            Storage::put('banner/' . $filename, file_get_contents($file));
        }

        $event->banner = $filename;
        $event->save();
        
        return redirect()->route('events.show', $event->id)->with('success', 'Event updated successfully!');
        
    }


    public function showByCategory(Request $request, $id)
    {
        $query = Event::query();

        if ($id !== 'all') {
            $category = Category::findOrFail($id);
            $query->where('category_id', $id);
        } else {
            $category = null;
        }

        $query->where('visibility', '<>', 'private');

        $status = $request->get('status'); // 'ended', 'ongoing', 'future'
        if ($status === 'ended') {
            $query->where('end_date', '<', now());
        } elseif ($status === 'ongoing') {
            $query->where('start_date', '<=', now())->where('end_date', '>=', now());
        } elseif ($status === 'future') {
            $query->where('start_date', '>', now());
        }

        $order = $request->get('order', 'asc');
        $query->orderBy('start_date', $order);

        $events = $query->get();

        return view('pages.events_by_category', compact('category', 'events'));
    }

    /**
     * Delete an event.
     */
    public function deleteEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        $isAdmin = auth()->guard('admin')->check();
        $isEventCreator = auth()->check() && auth()->user()->id === $event->creator_id;

        if (!$isAdmin && !$isEventCreator) {
            abort(403, 'Unauthorized action.');
        }

        $event->delete();

        return redirect()->route('pages.home')->with('message', 'The event has been deleted.');
    }


    public function requestToJoin(Event $event)
    {
        $user = Auth::user();

        if ($event->participators->contains($user) || $event->creator_id === $user->id) {
            return redirect()->back()->with('message', 'You are already part of this event.');
        }

        $existingRequest = JoinRequest::where('join_request_by_id', $user->id)
            ->where('event_id', $event->id)
            ->where('accepted', false)
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('message', 'You have already requested to join this event.');
        }

        JoinRequest::create([
            'join_request_by_id' => $user->id,
            'event_id' => $event->id,
            'accepted' => false,
            'date' => now(),
        ]);

        return redirect()->back()->with('message', 'Your request to join has been sent.');
    }


    /**
     * Join an event.
     */
    public function joinEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        if ($event->participators()->where('id', Auth::id())->exists()) {
            return back()->with('message', 'You are already participating in this event.');
        }

        else if ($event->start_date < now()) {
            return back()->with('message', 'You cannot join an event that has already started.');
        }

        $event->participators()->attach(Auth::id());

        return back()->with('message', 'You have successfully joined the event!');
    }

    /**
     * Leave an event.
     */
    public function leaveEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!$event->participators()->where('id', Auth::id())->exists()) {
            return back()->with('message', 'You are not participating in this event.');
        }

        $event->participators()->detach(Auth::id());

        return back()->with('message', 'You have successfully left the event.');
    }

    /**
     * Kick a participator from the event.
     */
    public function kickParticipator($eventId, $userId)
    {
        $event = Event::findOrFail($eventId);

        if (!$event->participators()->where('id', $userId)->exists()) {
            return back()->with('message', 'The user is not participating in this event.');
        }

        $event->participators()->detach($userId);

        return back()->with('message', 'You have removed the participator.');
    }

    /**
     * Search
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $events = Event::query()
            ->select('event.*', 'location.name as location_name', 'category.name as category_name')
            ->join('location', 'event.location_id', '=', 'location.id')
            ->join('category', 'event.category_id', '=', 'category.id')
            ->whereRaw("ts_vector @@ plainto_tsquery('english', ?)", [$query])
            ->get();

        return response()->json($events);
    }

    public function showSurvey($eventId)
    {
        $event = Event::findOrFail($eventId);
        $questions = QuestionReview::all();
        $hasSubmitted = AnswerReview::where('answered_by_id', auth()->id())
                                    ->where('about_event_id', $eventId)
                                    ->exists();
        return view('partials.survey', compact('event', 'questions', 'hasSubmitted'));
    }

    public function submitSurvey(Request $request, $eventId)
    {
        $request->validate([
            'answers.*' => 'required|integer|between:1,5',
        ]);

        foreach ($request->answers as $questionId => $rating) {
            AnswerReview::create([
                'answered_by_id' => auth()->id(),
                'question_id' => $questionId,
                'about_event_id' => $eventId,
                'rating' => $rating,
                'date' => now(),
            ]);
        }

        return redirect()->route('events.show', $eventId)->with('success', 'Thank you for your feedback!');
    }

    public function showSurveyStats($eventId)
    {
        $event = Event::findOrFail($eventId);

        if ($event->end_date > now()) {
            return redirect()->route('events.show', $eventId)->with('error', 'Survey results are available only after the event ends.');
        }

        $answers = AnswerReview::where('about_event_id', $eventId)
                                ->join('question_review', 'answer_review.question_id', '=', 'question_review.id')
                                ->select('question_review.id as question_id', 'question_review.question_text', 'answer_review.rating')
                                ->get();

        $chartData = [];

        foreach ($answers->groupBy('question_id') as $questionId => $responses) {
            $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            foreach ($responses as $response) {
                $ratings[$response->rating]++;
            }
            $chartData[] = [
                'question_text' => $responses->first()->question_text,
                'ratings' => $ratings
            ];
        }

        return view('partials.survey_stats', compact('event', 'chartData'));
    }

}
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
use Illuminate\Support\Facades\DB;


class EventController extends Controller
{
    // Display form to create a new event
    public function create(): Factory|View
    {
        $locations = Location::all(); // Get all locations from the database
        $categories = Category::all(); // Get all categories from the database
        return view('event.create', compact('locations', 'categories'));
    }

    /**
     * Show the event for a given id.
     */
    public function show(string $id): View
    {
        // Get the event with its polls and poll responses.
        $event = Event::with(['polls.responses'])->findOrFail($id);

        // Check if the current user can see (show) the event.
        $this->authorize('show', $event);  

        // Use the pages.event template to display the event.
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
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } 

            // The user is logged in.
            $user = Auth::user();

            // Get events created by user.
            $createdEvents = $user->myEvents()->orderBy('id')->get();
            
            // Get events user has already participated in.
            $participatedEvents = $user->participatedEvents()->orderBy('start_date')->get();

            //Get events user has not yet participated in.
            $notYetParticipatedEvents = $user->notYetParticipatedEvents()->orderBy('start_date')->get();

            $ongoingEvents = $user->ongoingEvents()->orderBy('start_date')->get();

            // Check if the current user can list the events.
            $this->authorize('index', Event::class);

            // The current user is authorized to list events.

            // Use the pages.events template to display all events.
            return view('pages.events', [
                'createdEvents' => $createdEvents,
                'participatedEvents' => $participatedEvents,
                'notYetParticipatedEvents' => $notYetParticipatedEvents,
                'ongoingEvents' => $ongoingEvents,
            ]);
        
    }

    // Store new event data
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'visibility' => 'required|in:public,private',
            'capacity' => 'required|integer|min:1',
            'location_id' => 'required|integer', // Assuming location_id is an integer (foreign key)
            'category_id' => 'required|integer', // Assuming category_id is an integer (foreign key)
        ]);

        // Create the event
        $event = Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'visibility' => $validated['visibility'],
            'capacity' => $validated['capacity'],
            'creator_id' => Auth::id(), // Logged in user ID
            'location_id' => $validated['location_id'],
            'category_id' => $validated['category_id'],
        ]);

        // Redirect with success message
        return redirect()->route('events.store')->with('success', 'Event created successfully!');
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
            'location_id' => 'required|integer', // Assuming location_id is an integer (foreign key)
            'category_id' => 'required|integer', // Assuming category_id is an integer (foreign key)
        ]);
        $event->update($validated);
        
        return redirect()->route('events.show', $event->id)->with('success', 'Event updated successfully!');
        
    }


    public function showByCategory($id)
    {
        if ($id == 'all') {
            $events = Event::all();
            $category = null; 
        } else {
            $category = Category::findOrFail($id);
            $events = Event::where('category_id', $id)->get();
        }
    

        // Return the view with events and category information
        return view('pages.events_by_category', compact('category', 'events'));
    }

    /**
     * Delete an event.
     */
    public function deleteEvent($eventId)
{
    // Find the event.
    $event = Event::findOrFail($eventId);

    // Determine if the current user is authorized to delete the event.
    $isAdmin = auth()->guard('admin')->check(); // Check if logged in as admin
    $isEventCreator = auth()->check() && auth()->user()->id === $event->creator_id; // Check if the logged-in user is the event creator

    if (!$isAdmin && !$isEventCreator) {
        // Deny access if neither admin nor event creator.
        abort(403, 'Unauthorized action.');
    }

    // Proceed to delete the event.
    $event->delete();

    return redirect()->route('pages.home')->with('message', 'The event has been deleted.');
}


    public function requestToJoin(Event $event)
    {
        $user = Auth::user();

        // Check if the user is already a participator or the creator of the event
        if ($event->participators->contains($user) || $event->creator_id === $user->id) {
            return redirect()->back()->with('message', 'You are already part of this event.');
        }

        // Check if a request already exists for this user and event
        $existingRequest = JoinRequest::where('join_request_by_id', $user->id)
            ->where('event_id', $event->id)
            ->where('accepted', false) // Ensure it's a pending request
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('message', 'You have already requested to join this event.');
        }

        // Create a new request
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

        // Check if the user is already a participator
        if ($event->participators()->where('id', Auth::id())->exists()) {
            return back()->with('message', 'You are already participating in this event.');
        }

        // Attach the user to the participator list
        $event->participators()->attach(Auth::id());

        return back()->with('message', 'You have successfully joined the event!');
    }

    /**
     * Leave an event.
     */
    public function leaveEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Check if the user is a participator
        if (!$event->participators()->where('id', Auth::id())->exists()) {
            return back()->with('message', 'You are not participating in this event.');
        }

        // Detach the user from the participator list
        $event->participators()->detach(Auth::id());

        return back()->with('message', 'You have successfully left the event.');
    }

    /**
     * Kick a participator from the event.
     */
    public function kickParticipator($eventId, $userId)
    {
        $event = Event::findOrFail($eventId);

        // Check if the user is a participator
        if (!$event->participators()->where('id', $userId)->exists()) {
            return back()->with('message', 'The user is not participating in this event.');
        }

        // Detach the user from the participator list
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
            ->whereRaw("ts_vector @@ plainto_tsquery('portuguese', ?)", [$query])
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
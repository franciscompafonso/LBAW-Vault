<?php

namespace App\Http\Controllers;

use App\Models\AnswerReview;
use App\Models\Category;
use App\Models\Event;
use App\Models\File;
use App\Models\JoinRequest;
use App\Models\Location;
use App\Models\QuestionReview;
use App\Models\User;
use App\Events\JoinEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
        if (! Auth::check()) {
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
        // dd($request->all(), Storage::disk("banner"));
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'visibility' => 'required|in:public,private',
            'capacity' => 'required|integer|min:1',
            'location_id' => 'required|integer',
            'category_id' => 'required|integer',
            'file' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $fileId = FileController::store($request, 'banner');
        }

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
            'file_id' => $fileId ?? null
        ]);

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
     
        try {
            DB::beginTransaction();
            $event = Event::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date|after:now',
                'end_date' => 'required|date|after:start_date',
                'visibility' => 'required|in:public,private,request_only',
                'capacity' => 'required|integer|min:1',
                'location_id' => 'required|integer',
                'category_id' => 'required|integer',
                'file' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            $data = $request->except(['_token', '_method', 'file']);

            if ($request->hasFile('file')) {
                $fileId = FileController::store($request, 'banner');
                if ($fileId) {
                    $pictureId = $event->file_id;
                    $data['file_id'] = $fileId;
                    if ($pictureId) $this->deleteBanner($pictureId);
                }
            }

            $event->update($data);

            DB::commit();

            return redirect()->route('events.show', $event->id)->with('success', 'Event updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function searchFiltered(Request $request)
    {
        $query = Event::query();
        $locations = Location::all();
        $categories = Category::all();

        $searchQuery = $request->input('q');

        // If a search query is provided, filter events using full-text search
        if ($searchQuery) {
            $searchResults = Event::query()
                ->select('id')
                ->whereRaw("ts_vector @@ plainto_tsquery('english', ?)", [$searchQuery])
                ->pluck('id');

            $query->whereIn('id', $searchResults);
        }

        // Sorting Algorithms
        $order = $request->get('order', 'date_asc');
        switch ($order) {
            case 'date_asc':
                $query->orderBy('start_date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('start_date', 'desc');
                break;
        }

        // Pagination (results per page)
        $resultsPerPage = $request->get('results', 21);

        // Date Filters
        if ($request->filled('startDate')) {
            $query->where('start_date', '>=', $request->startDate);
        }
        if ($request->filled('endDate')) {
            $query->where('end_date', '<=', $request->endDate);
        }

        // Visibility Filter
        if ($request->filled('visibility')) {
            $query->whereIn('visibility', $request->input('visibility')); // Matches form input name
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->whereIn('category_id', $request->input('category')); // Matches form input name
        }

        // Location Filter
        if ($request->filled('location')) {
            $query->whereIn('location_id', $request->input('location')); // Matches form input name
        }

        if ($request->filled('status_finished') || $request->filled('status_upcoming') || $request->filled('status_ongoing') || $request->filled('status_full_capacity') || $request->filled('status_canceled') || $request->filled('status_joined')) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('status_finished')) {
                    $q->orWhere('end_date', '<', now());
                }
                if ($request->filled('status_upcoming')) {
                    $q->orWhere('start_date', '>', now());
                }
                if ($request->filled('status_ongoing')) {
                    $q->orWhere(function ($subQuery) {
                        $subQuery->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                    });
                }
                if ($request->filled('status_full_capacity')) {
                    $q->orWhereRaw(
                        '(SELECT COUNT(*) FROM participator WHERE participator.event_id = event.id) >= event.capacity'
                    );
                }
                if ($request->filled('status_canceled')) {
                    $q->where('visibility', 'canceled');
                }
                if ($request->filled('status_joined') && auth()->check()) {
                    $userId = auth()->id(); 
                    $q->whereExists(function ($subQuery) use ($userId) {
                        $subQuery->select('participator.participator_id') 
                            ->from('participator')
                            ->whereColumn('participator.event_id', 'event.id') 
                            ->where('participator.participator_id', $userId); 
                    });
                }                
            });
        }
        

        // Count the total filtered events
        $eventsCount = $query->count();

        // Return the filtered results (with pagination)
        $events = $query->paginate($resultsPerPage);

        return view('pages.search', compact(
            'events',
            'locations',
            'categories',
            'eventsCount'
        ));
    }



    private function deleteBanner($bannerId) {
        $file = File::find($bannerId);
        $path = $file->path;
        $file->delete();
        Storage::disk('banner')->delete($path);
    }

    /**
     * Delete an event.
     */
    public function deleteEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        $isAdmin = auth()->guard('admin')->check();
        $isEventCreator = auth()->check() && auth()->user()->id === $event->creator_id;

        if (! $isAdmin && ! $isEventCreator) {
            abort(403, 'Unauthorized action.');
        }

        $pictureId = $event->file_id;
        $event->delete();

        $this->deleteBanner($pictureId);

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
            return response()->json([
                'success' => false,
                'message' => 'You are already participating in this event.',
            ]);
        }

        if ($event->end_date < now()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot join an event that has already ended.',
            ]);
        }

        if ($event->participators()->count() >= $event->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'This event is already at full capacity.',
            ]);
        }

        // Attach user to event
        $event->participators()->attach(Auth::id());

        // Broadcast event (for real-time notification)
        event(new JoinEvent($event->name));

        return response()->json([
            'success' => true,
            'message' => 'You have successfully joined the event!',
        ]);
        
    }


    /**
     * Leave an event.
     */
    public function leaveEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        if (! $event->participators()->where('id', Auth::id())->exists()) {
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

        if (! $event->participators()->where('id', $userId)->exists()) {
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
            ->limit(5)
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
                'ratings' => $ratings,
            ];
        }

        return view('partials.survey_stats', compact('event', 'chartData'));
    }
}

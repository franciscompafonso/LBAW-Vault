<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\District;
use App\Models\Category;


use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $events = Event::query()
            ->select('event.*', 'district.name as district_name', 'category.name as category_name')
            ->join('district', 'event.district_id', '=', 'district.id')
            ->join('category', 'event.category_id', '=', 'category.id')
            ->whereRaw("ts_vector @@ plainto_tsquery('english', ?)", [$query])
            ->limit(5)
            ->get();

        return response()->json($events);
    }


    public function filteredSearch(Request $request)
    {
        $searchQuery = $request->input('q');
        $categoryIds = $request->input('category', []);
        $districtIds = $request->input('district', []);
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Fetch categories and districts for the filters
        $categories = Category::all(); // Assuming you have a Category model
        $districts = District::all(); // Assuming you have a District model

        $query = Event::visible()
            ->search($searchQuery)
            ->category($categoryIds)
            ->district($districtIds)
            ->dateRange($startDate, $endDate);

        // Apply sorting
        $order = $request->get('order', 'date_asc');
        $query->orderBy('start_date', $order === 'date_asc' ? 'asc' : 'desc');

        // Visibility Filter
        if ($request->filled('visibility')) {
            $query->whereIn('visibility', $request->input('visibility'));
        }

        // Status filter refactored logic (can include the refactored code here)
        $this->applyStatusFilters($query, $request);

        // Get results with pagination
        $eventsCount = $query->count();
        $events = $query->paginate($request->get('results', 21));

        if ($request->ajax()) {
            $html = view('partials.events_grid', compact('events'))->render();
            return response()->json(['html' => $html, 'eventsCount' => $eventsCount]);
        }

        return view('pages.search', compact('events', 'eventsCount', 'categories', 'districts', 'searchQuery'));
    }

    /**
     * Refactored method to handle status filters.
     */
    private function applyStatusFilters($query, $request)
    {
        if (
            $request->filled('status_finished') ||
            $request->filled('status_upcoming') ||
            $request->filled('status_ongoing') ||
            $request->filled('status_full_capacity') ||
            $request->filled('status_canceled') ||
            $request->filled('status_joined')
        ) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('status_finished')) {
                    $q->where('end_date', '<', now());
                }
                if ($request->filled('status_upcoming')) {
                    $q->where('start_date', '>', now());
                }
                if ($request->filled('status_ongoing')) {
                    $q->where(function ($subQuery) {
                        $subQuery->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                    });
                }
                if ($request->filled('status_full_capacity')) {
                    $q->whereRaw(
                        '(SELECT COUNT(*) FROM participator WHERE participator.event_id = event.id) >= event.capacity'
                    );
                }
                if ($request->filled('status_canceled')) {
                    $q->where('visibility', '!=', 'canceled');
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
    }

}

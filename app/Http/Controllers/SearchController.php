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
        $query = Event::query();
        $districts = District::all();
        $categories = Category::all();

        $searchQuery = $request->input('q');
        $category = $request->input('category');

        // Exclude private events
        $query->where('visibility', '!=', 'private');

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
            $categoryIds = $request->input('category');
            // Ensure categoryIds is always an array
            if (!is_array($categoryIds)) {
                $categoryIds = [$categoryIds]; // Convert to an array if it's a single value
            }
            $query->whereIn('category_id', $categoryIds);
        }
        

        // District Filter
        if ($request->filled('district')) {
            $query->whereIn('district_id', $request->input('district')); // Matches form input name
        }

        if ($request->filled('status_finished') || $request->filled('status_upcoming') || $request->filled('status_ongoing') || $request->filled('status_full_capacity') || $request->filled('status_canceled') || $request->filled('status_joined')) {
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
                    $q->where('visibility', '!=',  'canceled');
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
            'districts',
            'categories',
            'eventsCount',
            'searchQuery',
            'category',
        ));
    }
}

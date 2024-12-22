<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with(['events' => function ($query) {
            $query->where('end_date', '>', Carbon::now())
                ->where('visibility', '<>', 'private')
                ->limit(4);
        }])
        ->limit(6)
        ->has('events', '>=', 4) // Ensure categories have at least 4 valid events
        ->get();

        $all_events = Event::where('end_date', '>', Carbon::now())    // Only ongoing and future events
            ->where('visibility', '<>', 'private')                      // Exclude private events
            ->orderBy('end_date', 'asc')                                // Sort by event date
            ->limit(4)                                                  // Only fetch 4 events globally
            ->get();

        return view('pages.home', compact('categories', 'all_events'));
    }

    public function home()
    {
        if (Auth::check()) {
            $notifications = Notification::where('emitted_to', Auth::id())->get();
        } else {
            $notifications = collect(); 
        }

        return view('home', ['notifications' => $notifications]);
    }
}
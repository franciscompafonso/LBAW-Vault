<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Display form to create a new event
    public function create()
    {
        $locations = Location::all(); // Get all locations from the database
        $categories = Category::all(); // Get all categories from the database
        return view('event.create', compact('locations', 'categories'));
        
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
        return redirect()->route('cards')->with('success', 'Event created successfully!');
    }
}
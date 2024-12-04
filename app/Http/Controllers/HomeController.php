<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
{
    $categories = Category::with(['events' => function ($query) {
        $query->where('end_date', '>', Carbon::now())->where('visibility', '<>', 'private');
    }])->get();

    return view('pages.home', compact('categories')); // Pass them to the view
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
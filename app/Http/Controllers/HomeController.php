<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('events')->get();
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
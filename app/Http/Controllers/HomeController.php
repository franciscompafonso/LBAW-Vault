<?php
namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('events')->get();
        return view('pages.home', compact('categories')); // Pass them to the view
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured courses
     */
    public function index()
    {
        // Get latest upcoming courses limited to 3
        $courses = Course::with('coach')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->take(3)
            ->get();
        
        // Get coaches for filter dropdown
        $coaches = User::where('role', 'coach')
            ->where('coach_approved', true)
            ->orderBy('name')
            ->get();
        
        // Pass data to welcome view
        return view('welcome', compact('courses', 'coaches'));
    }
}

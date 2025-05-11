<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of courses for the current user
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'coach') {
            // Coaches see their own courses
            $courses = Course::where('coach_id', $user->id)
                ->orderBy('date', 'asc')
                ->paginate(10);
                
            return view('courses.index', compact('courses'));
        } else {
            // Surfeurs see all available courses
            $courses = Course::where('date', '>=', now())
                ->where('available_places', '>', 0)
                ->orderBy('date', 'asc')
                ->paginate(10);
                
            return view('courses.browse', compact('courses'));
        }
    }

    /**
     * Show the form for creating a new course (coaches only)
     */
    public function create()
    {
        // This will be managed by middleware, but adding extra check
        if (Auth::user()->role !== 'coach') {
            return redirect()->route('courses.index')
                ->with('error', 'Only coaches can create courses.');
        }
        
        return view('courses.create');
    }

    /**
     * Store a newly created course (coaches only)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // This will be managed by middleware, but adding extra check
        if ($user->role !== 'coach') {
            return redirect()->route('courses.index')
                ->with('error', 'Only coaches can create courses.');
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:480', // 15 min to 8 hours
            'available_places' => 'required|integer|min:1|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $course = new Course();
        $course->coach_id = $user->id;
        $course->title = $request->title;
        $course->description = $request->description;
        $course->date = $request->date;
        $course->duration = $request->duration;
        $course->available_places = $request->available_places;
        $course->save();
        
        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        
        // If coach, verify it's their course
        if ($user->role === 'coach' && $course->coach_id !== $user->id) {
            return redirect()->route('courses.index')
                ->with('error', 'You can only view your own courses.');
        }
        
        // For coach, also fetch reservations
        if ($user->role === 'coach') {
            $reservations = $course->reservations()->with('surfeur')->get();

            return view('courses.show', compact('course', 'reservations'));
        }
        
        // Find related courses by the same coach (excluding the current one)
        $relatedCourses = Course::where('coach_id', $course->coach_id)
            ->where('id', '!=', $course->id)
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->take(3)
            ->get();
        
        return view('courses.show', compact('course', 'relatedCourses'));
    }

    /**
     * Show the form for editing a course (coaches only, their own courses)
     */
    public function edit(Course $course)
    {
        $user = Auth::user();
        
        if ($user->role !== 'coach' || $course->coach_id !== $user->id) {
            return redirect()->route('courses.index')
                ->with('error', 'You can only edit your own courses.');
        }
        
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course (coaches only, their own courses)
     */
    public function update(Request $request, Course $course)
    {
        $user = Auth::user();
        
        if ($user->role !== 'coach' || $course->coach_id !== $user->id) {
            return redirect()->route('courses.index')
                ->with('error', 'You can only update your own courses.');
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:480',
            'available_places' => 'required|integer|min:1|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Calculate current reservations to ensure we don't reduce places below that
        $reservationCount = $course->reservations()->where('status', 'confirmed')->count();
        
        if ((int)$request->available_places < $reservationCount) {
            return redirect()->back()
                ->withErrors(['available_places' => 'Cannot reduce available places below current reservations.'])
                ->withInput();
        }
        
        $course->title = $request->title;
        $course->description = $request->description;
        $course->date = $request->date;
        $course->duration = $request->duration;
        $course->available_places = $request->available_places;
        $course->save();
        
        return redirect()->route('courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course (coaches only, their own courses)
     */
    public function destroy(Course $course)
    {
        $user = Auth::user();
        
        if ($user->role !== 'coach' || $course->coach_id !== $user->id) {
            return redirect()->route('courses.index')
                ->with('error', 'You can only delete your own courses.');
        }
        
        // Delete the course (this will cascade delete reservations due to foreign key)
        $course->delete();
        
        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
    
    /**
     * Display a listing of all courses for surfeurs to browse
     */
    public function browse(Request $request)
    {
        // Remove user role check to allow all users including guests to browse courses
        
        // Get query parameters
        $date = $request->input('date', 'all');
        $coachId = $request->input('coach_id');
        $availability = $request->input('availability', 'all');
        
        // Start with base query
        $query = Course::with('coach')
            ->where('date', '>=', now());
        
        // Apply date filter
        if ($date === 'today') {
            $query->whereDate('date', today());
        } elseif ($date === 'tomorrow') {
            $query->whereDate('date', today()->addDay());
        } elseif ($date === 'week') {
            $query->whereDate('date', '>=', today())
                  ->whereDate('date', '<=', today()->addDays(7));
        } elseif ($date === 'month') {
            $query->whereDate('date', '>=', today())
                  ->whereDate('date', '<=', today()->addMonth());
        }
        
        // Apply coach filter
        if ($coachId) {
            $query->where('coach_id', $coachId);
        }
        
        // Order by date
        $query->orderBy('date', 'asc');
        
        // Get courses
        $courses = $query->paginate(12);
        
        // Append query parameters to pagination links
        if ($request->has('date') || $request->has('coach_id') || $request->has('availability')) {
            $courses->appends($request->only(['date', 'coach_id', 'availability']));
        }
        
        // Get all approved coaches for the filter
        $coaches = User::where('role', 'coach')
            ->where('coach_approved', true)
            ->orderBy('name')
            ->get();
        
        return view('courses.browse', compact('courses', 'coaches'));
    }
    
    /**
     * Book a course (surfeurs only)
     */
    public function book(Course $course)
    {
        $user = Auth::user();
        
        if ($user->role !== 'surfeur') {
            return redirect()->route('courses.index')
                ->with('error', 'Only surfeurs can book courses.');
        }
        
        // Check if course is in the future
        if ($course->date < now()) {
            return redirect()->route('courses.browse')
                ->with('error', 'Cannot book past courses.');
        }
        
        // Check if there are available places
        if ($course->getRemainingPlacesAttribute() <= 0) {
            return redirect()->route('courses.browse')
                ->with('error', 'No available places for this course.');
        }
        
        // Check if user already has a reservation for this course
        $existingReservation = Reservation::where('course_id', $course->id)
            ->where('surfeur_id', $user->id)
            ->first();
            
        if ($existingReservation) {
            return redirect()->route('surfer.reservations')
                ->with('error', 'You already have a reservation for this course.');
        }
        
        // Create reservation
        $reservation = new Reservation();
        $reservation->course_id = $course->id;
        $reservation->surfeur_id = $user->id;
        $reservation->status = 'confirmed';
        $reservation->save();
        
        return redirect()->route('surfer.reservations')
            ->with('success', 'Course booked successfully.');
    }
    
    /**
     * Display courses by a specific coach
     */
    public function coachCourses(User $coach)
    {
        if ($coach->role !== 'coach') {
            return redirect()->route('courses.browse')
                ->with('error', 'User is not a coach.');
        }
        
        $courses = Course::where('coach_id', $coach->id)
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->paginate(10);
        return view('courses.coach', compact('courses', 'coach'));
    }

    /**
     * Display a coach's public profile and their upcoming courses
     * 
     * @param User $coach
     * @return \Illuminate\View\View
     */
    public function coachProfile(User $coach)
    {
        // Verify this user is actually a coach
        if ($coach->role !== 'coach') {
            return redirect()->route('courses.browse')
                ->with('error', 'The specified user is not a coach.');
        }
        
        // Get upcoming courses by this coach
        $courses = Course::where('coach_id', $coach->id)
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->withCount(['reservations' => function($query) {
                $query->where('status', 'confirmed');
            }])
            ->get();
        
        // Count total courses (including past ones)
        $totalCourses = Course::where('coach_id', $coach->id)->count();
        
        // Count total students (unique surfeurs across all courses)
        $totalStudents = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'confirmed')
            ->distinct('surfeur_id')
            ->count('surfeur_id');
            
        return view('courses.coach', compact('coach', 'courses', 'totalCourses', 'totalStudents'));
    }
} 
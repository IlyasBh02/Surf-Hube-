<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CoachDashboard extends Controller
{
    /**
     * Display the coach dashboard main view with metrics
     */
    public function index()
    {
        $coach = Auth::user();
        
        // Verify user is a coach
        if ($coach->role !== 'coach') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }
        
        // Initialize variables with default values
        $activeCourses = 0;
        $upcomingCourses = 0;
        $totalStudents = 0;
        $newStudentsThisMonth = 0;
        $totalReservations = 0;
        $newReservationsThisWeek = 0;
        $pendingReservationsCount = 0;
        $upcomingCoursesList = [];
        $recentReservations = [];
        
        try {
            // Get upcoming courses with formatted data
            $upcomingCoursesList = Course::where('coach_id', $coach->id)
                ->where('date', '>=', now())
                ->orderBy('date', 'asc')
                ->take(5)
                ->get()
                ->map(function($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'level' => $course->level ?? 'All Levels',
                        'date' => $course->date->format('Y-m-d'),
                        'time' => $course->date->format('h:i A') . ' - ' . 
                                $course->date->addMinutes($course->duration)->format('h:i A'),
                        'booked' => $course->reservations()->where('status', 'confirmed')->count(),
                        'capacity' => $course->available_places,
                    ];
                });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching upcoming courses: ' . $e->getMessage());
            $upcomingCoursesList = [];
        }
            
        try {
            // Get recent reservations
            $recentReservations = Reservation::whereHas('course', function($query) use ($coach) {
                    $query->where('coach_id', $coach->id);
                })
                ->with('course', 'surfeur')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function($reservation) {
                    return [
                        'id' => $reservation->id,
                        'user_name' => $reservation->surfeur->name ?? 'Unknown User',
                        'course_title' => $reservation->course->title ?? 'Unknown Course',
                        'date' => $reservation->course->date->format('Y-m-d'),
                        'status' => $reservation->status,
                        'created_at' => $reservation->created_at->diffForHumans(),
                    ];
                });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching recent reservations: ' . $e->getMessage());
            $recentReservations = [];
        }
            
        try {
            // Dashboard metrics with improved SQL queries
            // Count active courses
            $activeCourses = Course::where('coach_id', $coach->id)
                ->where('date', '>=', now())
                ->count();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error counting active courses: ' . $e->getMessage());
            $activeCourses = 0;
        }
            
        try {
            // Count upcoming courses in the next 7 days
            $upcomingCourses = Course::where('coach_id', $coach->id)
                ->where('date', '>=', now())
                ->where('date', '<=', now()->addDays(7))
                ->count();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error counting upcoming courses: ' . $e->getMessage());
            $upcomingCourses = 0;
        }
            
        try {
            // Count unique students with confirmed reservations
            $totalStudents = Reservation::join('courses', 'reservations.course_id', '=', 'courses.id')
                ->where('courses.coach_id', $coach->id)
                ->where('reservations.status', 'confirmed')
                ->distinct('reservations.surfeur_id')
                ->count('reservations.surfeur_id');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error counting total students: ' . $e->getMessage());
            $totalStudents = 0;
        }
            
        try {
            // Count new students this month - students who made their first reservation this month
            $newStudentsThisMonth = Reservation::join('courses', 'reservations.course_id', '=', 'courses.id')
                ->where('courses.coach_id', $coach->id)
                ->where('reservations.status', 'confirmed')
                ->where('reservations.created_at', '>=', now()->startOfMonth())
                ->distinct('reservations.surfeur_id')
                ->count('reservations.surfeur_id');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error counting new students: ' . $e->getMessage());
            $newStudentsThisMonth = 0;
        }
            
        try {
            // Count all confirmed reservations
            $totalReservations = Reservation::join('courses', 'reservations.course_id', '=', 'courses.id')
                ->where('courses.coach_id', $coach->id)
                ->where('reservations.status', 'confirmed')
                ->count();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error counting total reservations: ' . $e->getMessage());
            $totalReservations = 0;
        }
            
        try {
            // Count new reservations created this week
            $newReservationsThisWeek = Reservation::join('courses', 'reservations.course_id', '=', 'courses.id')
                ->where('courses.coach_id', $coach->id)
                ->where('reservations.status', 'confirmed')
                ->where('reservations.created_at', '>=', now()->startOfWeek())
                ->count();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error counting new reservations: ' . $e->getMessage());
            $newReservationsThisWeek = 0;
        }
            
        try {
            // Get pending reservations count for notification badge
            $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                    $query->where('coach_id', $coach->id);
                })
                ->where('status', 'pending')
                ->count();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error counting pending reservations: ' . $e->getMessage());
            $pendingReservationsCount = 0;
        }
            
        return view('coach.dashboard', compact(
            'upcomingCoursesList',
            'recentReservations',
            'activeCourses',
            'upcomingCourses',
            'totalStudents',
            'newStudentsThisMonth',
            'totalReservations',
            'newReservationsThisWeek',
            'pendingReservationsCount'
        ));
    }
    
    /**
     * Display calendar view of coach's courses
     */
    public function calendar()
    {
        $coach = Auth::user();
        
        // Get all courses for the calendar
        $courses = Course::where('coach_id', $coach->id)
            ->get()
            ->map(function($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'start' => $course->date->format('Y-m-d\TH:i:s'),
                    'end' => $course->date->addMinutes($course->duration)->format('Y-m-d\TH:i:s'),
                    'url' => route('coach.courses.show', $course->id),
                    'backgroundColor' => $course->date < now() ? '#6B7280' : '#3B82F6',
                    'borderColor' => $course->date < now() ? '#4B5563' : '#2563EB',
                ];
            });
            
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
            
        return view('coach.calendar', compact('courses', 'pendingReservationsCount'));
    }
    
    /**
     * Display analytics and statistics for the coach
     */
    public function analytics()
    {
        $coach = Auth::user();
        
        // Get data for the charts - last 6 months
        $today = Carbon::today();
        $sixMonthsAgo = $today->copy()->subMonths(6);
        $months = [];
        $courseData = [];
        $reservationData = [];
        
        // Generate the last 6 months
        for ($i = 0; $i < 6; $i++) {
            $month = $sixMonthsAgo->copy()->addMonths($i);
            $months[] = $month->format('M Y');
            
            // Count courses for this month
            $courseCount = Course::where('coach_id', $coach->id)
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->count();
            $courseData[] = $courseCount;
            
            // Count reservations for this month
            $reservationCount = Reservation::whereHas('course', function($query) use ($coach, $month) {
                    $query->where('coach_id', $coach->id)
                        ->whereYear('date', $month->year)
                        ->whereMonth('date', $month->month);
                })
                ->where('status', 'confirmed')
                ->count();
            $reservationData[] = $reservationCount;
        }
        
        // Get total metrics
        $totalCourses = Course::where('coach_id', $coach->id)->count();
        $totalReservations = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'confirmed')
            ->count();
        $totalEarnings = $totalReservations * 50; // Assuming 50 per reservation
        
        // Get popular courses - Top 5 courses by reservation count
        $popularCourses = Course::where('coach_id', $coach->id)
            ->withCount(['reservations' => function($query) {
                $query->where('status', 'confirmed');
            }])
            ->orderBy('reservations_count', 'desc')
            ->take(5)
            ->get();
            
        // Reservation status distribution
        $reservationStatuses = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
            
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
            
        return view('coach.analytics', compact(
            'months',
            'courseData',
            'reservationData',
            'totalCourses',
            'totalReservations',
            'totalEarnings',
            'popularCourses',
            'reservationStatuses',
            'pendingReservationsCount'
        ));
    }
    
    /**
     * Display and manage coach profile
     */
    public function profile()
    {
        $coach = Auth::user();
        
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
        
        return view('coach.profile', compact('coach', 'pendingReservationsCount'));
    }
    
    /**
     * Update coach profile
     */
    public function updateProfile(Request $request)
    {
        $coach = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'description' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
            'phone' => 'nullable|string|max:20',
            'specialties' => 'nullable|string',
        ]);
        
        // Update user fields
        $updateData = [
            'name' => $validated['name'],
            'bio' => $validated['bio'],
            'description' => $validated['description'] ?? null,
            'years_experience' => $validated['years_experience'] ?? null,
        ];
        
        User::where('id', $coach->id)->update($updateData);
        
        // Handle profile picture if provided
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            
            // Delete previous profile picture if exists
            if ($coach->profile_picture) {
                Storage::disk('public')->delete($coach->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            
            // Update profile picture path in database
            User::where('id', $coach->id)->update([
                'profile_picture' => $path
            ]);
        }
        
        return redirect()->route('coach.profile')
            ->with('success', 'Profile updated successfully');
    }
    
    /**
     * Display all courses created by the coach
     */
    public function courses()
    {
        $coach = Auth::user();
        
        // Get courses for this coach with eager loading of reservations count
        $courses = Course::where('coach_id', $coach->id)
            ->withCount('reservations')
            ->orderBy('date', 'desc')
            ->paginate(10);
            
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
        
        return view('coach.courses.index', compact('courses', 'pendingReservationsCount'));
    }
    
    /**
     * Display the form to create a new course
     */
    public function createCourse()
    {
        $coach = Auth::user();
        
        // Check if the coach is approved
        if (!$coach->coach_approved) {
            return redirect()->route('coach.dashboard')
                ->with('error', 'Your account needs to be approved before you can create courses.');
        }
        
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
        
        return view('coach.courses.create', compact('pendingReservationsCount'));
    }
    
    /**
     * Store a newly created course
     */
    public function storeCourse(Request $request)
    {
        $coach = Auth::user();
        
        // Check if the coach is approved
        if (!$coach->coach_approved) {
            return redirect()->route('coach.dashboard')
                ->with('error', 'Your account needs to be approved before you can create courses.');
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:480',
            'available_places' => 'required|integer|min:1|max:100',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'location' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create a new course
        $course = new Course();
        $course->coach_id = $coach->id;
        $course->title = $request->title;
        $course->description = $request->description;
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            try {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('course_thumbnails', $filename, 'public');
                
                if (!$path) {
                    throw new \Exception('Failed to store the file.');
                }
                
                $course->thumbnail = $path;
                \Illuminate\Support\Facades\Log::info('Thumbnail uploaded successfully: ' . $path);
            } catch (\Exception $e) {
                // Log the error
                \Illuminate\Support\Facades\Log::error('Thumbnail upload failed: ' . $e->getMessage());
                
                // Continue without the thumbnail
                return redirect()->back()
                    ->with('error', 'Thumbnail upload failed: ' . $e->getMessage())
                    ->withInput();
            }
        }
        
        $course->date = $request->date;
        $course->duration = $request->duration;
        $course->available_places = $request->available_places;
        $course->price = $request->price;
        $course->level = $request->level;
        $course->location = $request->location;
        $course->save();
        
        return redirect()->route('coach.courses.index')
            ->with('success', 'Course created successfully!');
    }
    
    /**
     * Display a specific course
     */
    public function showCourse(Course $course)
    {
        $coach = Auth::user();
        
        // Check if the course belongs to the coach
        if ($course->coach_id !== $coach->id) {
            return redirect()->route('coach.courses.index')
                ->with('error', 'You can only view your own courses.');
        }
        
        // Load the course's reservations for display
        $course->load('reservations');
        
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
        
        return view('coach.courses.show', compact('course', 'pendingReservationsCount'));
    }
    
    /**
     * Show the form for editing a course
     */
    public function editCourse(Course $course)
    {
        $coach = Auth::user();
        
        // Check if the course belongs to the coach
        if ($course->coach_id !== $coach->id) {
            return redirect()->route('coach.courses.index')
                ->with('error', 'You can only edit your own courses.');
        }
        
        // Check if the course date is in the past
        if ($course->date < now()) {
            return redirect()->route('coach.courses.show', $course->id)
                ->with('error', 'You cannot edit a course that has already occurred.');
        }
        
        // Load the course's reservations to check current bookings
        $course->load('reservations');
        
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
        
        return view('coach.courses.edit', compact('course', 'pendingReservationsCount'));
    }
    
    /**
     * Update a specific course
     */
    public function updateCourse(Request $request, Course $course)
    {
        $coach = Auth::user();
        
        // Check if the course belongs to the coach
        if ($course->coach_id !== $coach->id) {
            return redirect()->route('coach.courses.index')
                ->with('error', 'You can only update your own courses.');
        }
        
        // Check if the course date is in the past
        if ($course->date < now()) {
            return redirect()->route('coach.courses.show', $course->id)
                ->with('error', 'You cannot edit a course that has already occurred.');
        }
        
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:480',
            'available_places' => 'required|integer|min:1|max:100',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'location' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check if the available places is enough for current reservations
        $confirmedReservationsCount = $course->reservations()->where('status', 'confirmed')->count();
        
        if ((int)$request->available_places < $confirmedReservationsCount) {
            return redirect()->back()
                ->withErrors(['available_places' => 'The number of available places cannot be less than the current number of confirmed reservations (' . $confirmedReservationsCount . ').'])
                ->withInput();
        }
        
        // Update the course
        $course->title = $request->title;
        $course->description = $request->description;
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            try {
                // Delete old thumbnail if exists
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                
                $file = $request->file('thumbnail');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('course_thumbnails', $filename, 'public');
                
                if (!$path) {
                    throw new \Exception('Failed to store the file.');
                }
                
                $course->thumbnail = $path;
                \Illuminate\Support\Facades\Log::info('Thumbnail uploaded successfully: ' . $path);
            } catch (\Exception $e) {
                // Log the error
                \Illuminate\Support\Facades\Log::error('Thumbnail upload failed: ' . $e->getMessage());
                
                return redirect()->back()
                    ->with('error', 'Thumbnail upload failed: ' . $e->getMessage())
                    ->withInput();
            }
        }
        
        $course->date = $request->date;
        $course->duration = $request->duration;
        $course->available_places = $request->available_places;
        $course->price = $request->price;
        $course->level = $request->level;
        $course->location = $request->location;
        $course->save();
        
        return redirect()->route('coach.courses.show', $course->id)
            ->with('success', 'Course updated successfully!');
    }
    
    /**
     * Delete a course
     */
    public function destroyCourse(Course $course)
    {
        $coach = Auth::user();
        
        // Check if the course belongs to the coach
        if ($course->coach_id !== $coach->id) {
            return redirect()->route('coach.courses.index')
                ->with('error', 'You can only delete your own courses.');
        }
        
        // Check if the course date is in the past
        if ($course->date < now()) {
            return redirect()->route('coach.courses.index')
                ->with('error', 'You cannot delete a course that has already occurred.');
        }
        
        // Cancel all reservations for this course
        foreach ($course->reservations as $reservation) {
            $reservation->status = 'cancelled';
            $reservation->save();
        }
        
        // Delete the course
        $course->delete();
        
        return redirect()->route('coach.courses.index')
            ->with('success', 'Course deleted successfully and all reservations have been cancelled.');
    }
    
    /**
     * Display all reservations for the coach's courses with improved debug and data handling
     */
    public function reservations()
    {
        $coach = Auth::user();
        
        // Query parameters
        $status = request('status');
        $courseId = request('course_id');
        $search = request('search');
        
        // Get all courses for this coach - for display in stats and dropdowns
        $courses = Course::where('coach_id', $coach->id)
            ->orderBy('date', 'desc')
            ->get();
        
        // Base query
        $query = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->with(['course', 'surfeur']);
            
        // Apply filters
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($courseId) {
            $query->where('course_id', $courseId); // Simplified direct query
        }
        
        if ($search) {
            $query->whereHas('surfeur', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Get the reservations
        $reservations = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
            
        // Count confirmed reservations per course for display
        $courseStats = [];
        foreach ($courses as $course) {
            $confirmedCount = $course->reservations()->where('status', 'confirmed')->count();
            $courseStats[$course->id] = [
                'title' => $course->title,
                'date' => $course->date->format('d M Y'),
                'available' => $course->available_places,
                'booked' => $confirmedCount,
                'remaining' => $course->available_places - $confirmedCount
            ];
        }
        
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
            
        return view('coach.reservations.index', compact(
            'reservations', 
            'pendingReservationsCount',
            'courses',
            'courseStats'
        ));
    }
    
    /**
     * Display reservations for a specific course
     */
    public function courseReservations(Course $course)
    {
        $coach = Auth::user();
        
        // Verify the course belongs to the coach
        if ($course->coach_id !== $coach->id) {
            return redirect()->route('coach.courses.index')
                ->with('error', 'You can only view reservations for your own courses.');
        }
        
        $reservations = $course->reservations()
            ->with('surfeur')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
            
        return view('coach.course-reservations', compact('course', 'reservations', 'pendingReservationsCount'));
    }
    
    /**
     * Update reservation status (confirm, cancel, mark as completed)
     */
    public function updateReservationStatus(Request $request, Reservation $reservation)
    {
        $coach = Auth::user();
        
        // Verify the reservation is for the coach's course
        if ($reservation->course->coach_id !== $coach->id) {
            return redirect()->route('coach.reservations')
                ->with('error', 'You can only manage reservations for your own courses.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
        ]);
        
        Reservation::where('id', $reservation->id)->update([
            'status' => $validated['status']
        ]);
        
        return back()->with('success', 'Reservation status updated successfully.');
    }
    
    /**
     * Generate a report of coach activities
     */
    public function reports(Request $request)
    {
        $coach = Auth::user();
        
        $period = $request->input('period', 'month');
        
        if ($period === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($period === 'quarter') {
            $startDate = Carbon::now()->startOfQuarter();
            $endDate = Carbon::now()->endOfQuarter();
        } elseif ($period === 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
        } else {
            // Custom date range
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();
        }
        
        // Get courses in the date range
        $courses = Course::where('coach_id', $coach->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
            
        // Get reservations for these courses
        $reservations = Reservation::whereHas('course', function($query) use ($coach, $startDate, $endDate) {
                $query->where('coach_id', $coach->id)
                    ->whereBetween('date', [$startDate, $endDate]);
            })
            ->with('course', 'surfeur')
            ->get();
            
        // Calculate metrics
        $totalCourses = $courses->count();
        $totalReservations = $reservations->count();
        $confirmedReservations = $reservations->where('status', 'confirmed')->count();
        $cancelledReservations = $reservations->where('status', 'cancelled')->count();
        $completedReservations = $reservations->where('status', 'completed')->count();
        
        // Get pending reservations count for notification badge
        $pendingReservationsCount = Reservation::whereHas('course', function($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })
            ->where('status', 'pending')
            ->count();
        
        return view('coach.reports', compact(
            'period',
            'startDate',
            'endDate',
            'courses',
            'totalCourses',
            'totalReservations',
            'confirmedReservations',
            'cancelledReservations',
            'completedReservations',
            'pendingReservationsCount'
        ));
    }
}

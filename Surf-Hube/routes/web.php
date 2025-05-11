<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SurfeurDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Course routes accessible to all authenticated users
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/browse', [CourseController::class, 'browse'])->name('courses.browse');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    
    // Add coach profile route here
    Route::get('/coaches/{coach}', [CourseController::class, 'coachProfile'])->name('courses.coach');
    
    // Reservation routes for all authenticated users
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    
    // Dashboard routes - redirect to role-specific dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard');
        } elseif ($user->role === 'surfeur') {
            return redirect()->route('surfer.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');
    
    // Coach routes
    Route::middleware('role:coach')->group(function () {
        // Coach approval request
        Route::post('/coach/request-approval', [AuthController::class, 'requestCoachApproval'])->name('coach.request-approval');
        
        // Dashboard routes
        Route::get('/coach/dashboard', [\App\Http\Controllers\CoachDashboard::class, 'index'])->name('coach.dashboard');
        
        // Course management routes - using the CoachDashboard controller
        Route::get('/coach/courses', [\App\Http\Controllers\CoachDashboard::class, 'courses'])->name('coach.courses.index');
        Route::get('/coach/courses/create', [\App\Http\Controllers\CoachDashboard::class, 'createCourse'])->name('coach.courses.create');
        Route::post('/coach/courses', [\App\Http\Controllers\CoachDashboard::class, 'storeCourse'])->name('coach.courses.store');
        Route::get('/coach/courses/{course}', [\App\Http\Controllers\CoachDashboard::class, 'showCourse'])->name('coach.courses.show');
        Route::get('/coach/courses/{course}/edit', [\App\Http\Controllers\CoachDashboard::class, 'editCourse'])->name('coach.courses.edit');
        Route::put('/coach/courses/{course}', [\App\Http\Controllers\CoachDashboard::class, 'updateCourse'])->name('coach.courses.update');
        Route::delete('/coach/courses/{course}', [\App\Http\Controllers\CoachDashboard::class, 'destroyCourse'])->name('coach.courses.destroy');
        
        // Reservation management
        Route::get('/coach/reservations', [\App\Http\Controllers\CoachDashboard::class, 'reservations'])->name('coach.reservations');
        Route::get('/coach/courses/{course}/reservations', [\App\Http\Controllers\CoachDashboard::class, 'courseReservations'])->name('coach.course.reservations');
        Route::post('/coach/reservations/{reservation}/status', [\App\Http\Controllers\CoachDashboard::class, 'updateReservationStatus'])->name('coach.reservation.status');
        
        // Profile management
        Route::get('/coach/profile', [\App\Http\Controllers\CoachDashboard::class, 'profile'])->name('coach.profile');
        Route::post('/coach/profile', [\App\Http\Controllers\CoachDashboard::class, 'updateProfile'])->name('coach.profile.update');
        
        // Analytics and reports
        Route::get('/coach/analytics', [\App\Http\Controllers\CoachDashboard::class, 'analytics'])->name('coach.analytics');
        Route::get('/coach/reports', [\App\Http\Controllers\CoachDashboard::class, 'reports'])->name('coach.reports');
        Route::get('/coach/calendar', [\App\Http\Controllers\CoachDashboard::class, 'calendar'])->name('coach.calendar');
    });
    
    // Surfeur routes
    Route::middleware('role:surfeur')->group(function () {
        Route::post('/courses/{course}/book', [CourseController::class, 'book'])->name('courses.book');
        Route::get('/coaches/{coach}/courses', [CourseController::class, 'coachCourses'])->name('coach.public.courses');
        
        // Reservation management for surfeurs
        Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    });
    
    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::post('/admin/approve-coach/{coach}', [AuthController::class, 'approveCoach'])->name('admin.approve-coach');
        Route::post('/admin/reject-coach/{coach}', [AuthController::class, 'rejectCoach'])->name('admin.reject-coach');
        Route::post('/admin/change-user-status/{user}', [AuthController::class, 'changeUserStatus'])->name('admin.change-user-status');
        Route::delete('/admin/delete-user/{user}', [AuthController::class, 'deleteUser'])->name('admin.delete-user');
    });
});

// Dashboard routes
Route::middleware('auth')->group(function () {
    // Admin dashboard routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            // Fetch pending coaches who need approval
            $pendingCoaches = \App\Models\User::where('role', 'coach')
                ->where('coach_approved', false)
                ->get()
                ->map(function($coach) {
                    return [
                        'id' => $coach->id,
                        'name' => $coach->name,
                        'email' => $coach->email,
                        'requested_at' => $coach->created_at->format('Y-m-d'),
                    ];
                });
            
            // Count stats for the dashboard
            $totalUsers = \App\Models\User::count();
            $activeCoaches = \App\Models\User::where('role', 'coach')->where('coach_approved', true)->count();
            $pendingCoachesCount = $pendingCoaches->count();
            
            // Get real data for courses
            $now = now();
            $activeCourses = \App\Models\Course::where('date', '>=', $now)->count();
            
            // Get upcoming courses this week
            $oneWeekFromNow = $now->copy()->addWeek();
            $upcomingCourses = \App\Models\Course::where('date', '>=', $now)
                ->where('date', '<=', $oneWeekFromNow)
                ->count();
            
            // Get total reservations
            $totalReservations = \App\Models\Reservation::count();
            
            // Get new reservations this week
            $startOfWeek = $now->copy()->startOfWeek();
            $newReservationsThisWeek = \App\Models\Reservation::where('created_at', '>=', $startOfWeek)->count();
            
            // Get new users this month
            $startOfMonth = $now->copy()->startOfMonth();
            $newUsersThisMonth = \App\Models\User::where('created_at', '>=', $startOfMonth)->count();
            
            // Additional statistics for system overview
            $totalAdmins = \App\Models\User::where('role', 'admin')->count();
            $totalCoaches = \App\Models\User::where('role', 'coach')->count(); // Both approved and pending
            $totalSurfeurs = \App\Models\User::where('role', 'surfeur')->count();
            
            // Get completed courses (courses with past dates)
            $completedCourses = \App\Models\Course::where('date', '<', $now)->count();
            
            // Last backup date - this would typically come from your backup system
            // For now, we'll use a placeholder that indicates it's a simulated value
            $lastBackup = now()->subDays(1)->format('Y-m-d H:i A');
            
            // Recent activity - fetch real data from various tables
            $recentUsers = \App\Models\User::orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function($user) {
                    return [
                        'type' => 'user_registered',
                        'icon' => $user->role === 'coach' ? 'fa-solid fa-user-tie' : 
                               ($user->role === 'admin' ? 'fa-solid fa-user-shield' : 'fa-solid fa-user'),
                        'message' => 'New user <strong>' . $user->name . '</strong> registered as a ' . $user->role,
                        'time' => $user->created_at->diffForHumans(),
                        'created_at' => $user->created_at
                    ];
                });
                
            $recentCourses = \App\Models\Course::with('coach')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function($course) {
                    return [
                        'type' => 'course_created',
                        'icon' => 'fa-solid fa-calendar-plus',
                        'message' => 'Coach <strong>' . ($course->coach->name ?? 'Unknown') . '</strong> created a new course: <strong>' . $course->title . '</strong>',
                        'time' => $course->created_at->diffForHumans(),
                        'created_at' => $course->created_at
                    ];
                });
                
            $recentReservations = \App\Models\Reservation::with(['course', 'surfeur'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function($reservation) {
                    return [
                        'type' => 'reservation_made',
                        'icon' => 'fa-solid fa-bookmark',
                        'message' => 'User <strong>' . ($reservation->surfeur->name ?? 'Unknown') . '</strong> booked a <strong>' . ($reservation->course->title ?? 'Unknown') . '</strong> course',
                        'time' => $reservation->created_at->diffForHumans(),
                        'created_at' => $reservation->created_at
                    ];
                });
                
            // Combine and sort all activities
            $recentActivities = $recentUsers->concat($recentCourses)
                ->concat($recentReservations)
                ->sortByDesc('created_at')
                ->take(5)
                ->values()
                ->all();
            
            return view('admin.dashboard', compact(
                'pendingCoaches', 
                'pendingCoachesCount',
                'totalUsers',
                'activeCoaches',
                'activeCourses',
                'upcomingCourses',
                'totalReservations',
                'newReservationsThisWeek',
                'newUsersThisMonth',
                'totalAdmins',
                'totalCoaches',
                'totalSurfeurs',
                'completedCourses',
                'lastBackup',
                'recentActivities'
            ));
        })->name('admin.dashboard');
        
        Route::get('/users', function () {
            // Get the count of pending coach approvals for the sidebar badge
            $pendingCoachesCount = \App\Models\User::where('role', 'coach')
                ->where('coach_approved', false)
                ->count();
            
            // Get all users with pagination
            $users = \App\Models\User::orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('admin.users.index', compact('users', 'pendingCoachesCount'));
        })->name('admin.users');
        
        Route::get('/coaches', function () {
            return view('admin.coaches.index');
        })->name('admin.coaches');
        
        Route::get('/courses', function () {
            // Get the count of pending coach approvals for the sidebar badge
            $pendingCoachesCount = \App\Models\User::where('role', 'coach')
                ->where('coach_approved', false)
                ->count();
            
            // Get all coaches for the filter dropdown
            $coaches = \App\Models\User::where('role', 'coach')
                ->where('coach_approved', true)
                ->orderBy('name')
                ->get();
            
            // Get all courses with pagination
            $courses = \App\Models\Course::with(['coach', 'reservations'])
                ->orderBy('date', 'desc')
                ->paginate(10);
            
            // Current date
            $now = now();
            
            // Count upcoming courses within the next 7 days
            $upcomingCourses = \App\Models\Course::where('date', '>=', $now)
                ->where('date', '<=', $now->copy()->addDays(7))
                ->count();
            
            // Count completed courses (past date)
            $completedCourses = \App\Models\Course::where('date', '<', $now)
                ->count();
            
            // Count popular courses (with 5+ reservations) - using a different approach for SQLite compatibility
            $coursesWithReservationCounts = \App\Models\Course::withCount('reservations')->get();
            $popularCoursesCount = $coursesWithReservationCounts->filter(function($course) {
                return $course->reservations_count >= 5;
            })->count();
            
            return view('admin.courses.index', compact(
                'pendingCoachesCount',
                'coaches',
                'courses',
                'upcomingCourses',
                'completedCourses',
                'popularCoursesCount'
            ));
        })->name('admin.courses');
        
        Route::get('/reservations', function () {
            // Get the count of pending coach approvals for the sidebar badge
            $pendingCoachesCount = \App\Models\User::where('role', 'coach')
                ->where('coach_approved', false)
                ->count();
                
            // Get all reservations with relationships to courses, surfeurs, and coaches
            $reservations = \App\Models\Reservation::with([
                'course', 
                'course.coach', 
                'surfeur'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
            // Get counts for stats
            $totalReservations = \App\Models\Reservation::count();
            $confirmedReservations = \App\Models\Reservation::where('status', 'confirmed')->count();
            $cancelledReservations = \App\Models\Reservation::where('status', 'cancelled')->count();
            
            return view('admin.reservations.index', compact(
                'pendingCoachesCount',
                'reservations',
                'totalReservations',
                'confirmedReservations',
                'cancelledReservations'
            ));
        })->name('admin.reservations');
        
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('admin.settings');
        
        Route::get('/courses/{course}/reservations', function ($course) {
            // Get the course with its reservations and surfeurs
            $course = \App\Models\Course::with(['reservations.surfeur', 'coach'])
                ->findOrFail($course);
            
            // Get the count of pending coach approvals for the sidebar badge
            $pendingCoachesCount = \App\Models\User::where('role', 'coach')
                ->where('coach_approved', false)
                ->count();
            
            return view('admin.courses.reservations', compact('course', 'pendingCoachesCount'));
        })->name('admin.courses.reservations');
        
        Route::delete('/courses/{course}', function ($course) {
            $course = \App\Models\Course::findOrFail($course);
            
            // Delete associated reservations first
            $course->reservations()->delete();
            
            // Then delete the course
            $course->delete();
            
            return redirect()->route('admin.courses')
                ->with('success', 'Course and all associated reservations have been deleted.');
        })->name('admin.courses.delete');
        
        Route::post('/reservations/{reservation}/confirm', function ($reservation) {
            $reservation = \App\Models\Reservation::findOrFail($reservation);
            $reservation->status = 'confirmed';
            $reservation->save();
            
            return back()->with('success', 'Reservation confirmed successfully.');
        })->name('admin.reservations.confirm');
        
        Route::post('/reservations/{reservation}/cancel', function ($reservation) {
            $reservation = \App\Models\Reservation::findOrFail($reservation);
            $reservation->status = 'cancelled';
            $reservation->save();
            
            return back()->with('success', 'Reservation cancelled successfully.');
        })->name('admin.reservations.cancel');
    });
    
    // Coach dashboard routes
    Route::middleware('role:coach')->prefix('coach')->group(function () {
        Route::get('/dashboard', function () {
            return view('coach.dashboard');
        })->name('coach.dashboard');
        
        Route::get('/reservations', function () {
            // Logic to get coach reservations
            return view('coach.reservations.index');
        })->name('coach.reservations');
        
        Route::get('/profile', function () {
            return view('coach.profile');
        })->name('coach.profile');
    });
    
    // Surfer dashboard routes
    Route::middleware('role:surfeur')->prefix('surfer')->group(function () {
        Route::get('/dashboard', [SurfeurDashboard::class, 'index'])->name('surfer.dashboard');
        Route::get('/reservations', [SurfeurDashboard::class, 'reservations'])->name('surfer.reservations');
        Route::get('/reservations/{reservation}', [SurfeurDashboard::class, 'showReservation'])->name('surfer.reservations.show');
        Route::patch('/reservations/{reservation}/cancel', [SurfeurDashboard::class, 'cancelReservation'])->name('reservations.cancel');
        Route::get('/profile', [SurfeurDashboard::class, 'profile'])->name('surfer.profile');
        Route::put('/profile', [SurfeurDashboard::class, 'updateProfile'])->name('surfer.profile.update');
    });
});

Route::post('/coaches/{id}/approve', function ($id) {
    $coach = \App\Models\User::findOrFail($id);
    
    if ($coach->role !== 'coach') {
        return redirect()->back()->with('error', 'This user is not a coach.');
    }
    
    $coach->coach_approved = true;
    $coach->status = 'active';
    $coach->save();
    
    return redirect()->back()->with('success', 'Coach approved successfully');
})->name('admin.approve-coach');

Route::post('/coaches/{id}/reject', function ($id) {
    $coach = \App\Models\User::findOrFail($id);
    
    if ($coach->role !== 'coach') {
        return redirect()->back()->with('error', 'This user is not a coach.');
    }
    
    // Either delete the coach or mark them as rejected
    // Option 1: Mark as rejected
    $coach->coach_approved = false;
    $coach->status = 'suspended';
    $coach->save();
    
    // Option 2: Delete the coach (uncomment if you want to delete instead)
    // $coach->delete();
    
    return redirect()->back()->with('success', 'Coach rejected successfully');
})->name('admin.reject-coach');

// Add a test route to check file upload functionality
Route::get('/test-upload', function () {
    return view('test-upload');
});

Route::post('/test-upload', function (Illuminate\Http\Request $request) {
    if ($request->hasFile('test_file') && $request->file('test_file')->isValid()) {
        try {
            $path = $request->file('test_file')->store('test', 'public');
            return "File uploaded successfully: " . $path;
        } catch (\Exception $e) {
            return "Upload failed: " . $e->getMessage();
        }
    }
    return "No file or invalid file";
});

// Add a storage configuration check route
Route::get('/debug-storage', function () {
    $data = [
        'app_url' => config('app.url'),
        'filesystem_disk' => config('filesystems.default'),
        'public_disk_config' => config('filesystems.disks.public'),
        'storage_path' => storage_path('app/public'),
        'public_path' => public_path('storage'),
        'storage_link_exists' => file_exists(public_path('storage')),
        'storage_path_writable' => is_writable(storage_path('app/public')),
        'thumbnails_path' => storage_path('app/public/course_thumbnails'),
        'thumbnails_path_exists' => file_exists(storage_path('app/public/course_thumbnails')),
        'thumbnails_path_writable' => is_writable(storage_path('app/public/course_thumbnails'))
    ];
    
    return response()->json($data);
});

require __DIR__.'/auth.php';

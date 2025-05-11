<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SurfeurDashboard extends Controller
{
    /**
     * Display the dashboard for surfeur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get upcoming reservations
        $upcomingReservations = Reservation::with('course.coach')
            ->where('surfeur_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('course', function ($query) {
                $query->where('date', '>=', Carbon::now());
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Count statistics
        $totalReservations = Reservation::where('surfeur_id', $user->id)->count();
        $confirmedReservations = Reservation::where('surfeur_id', $user->id)
            ->where('status', 'confirmed')
            ->count();
            
        // Get completed courses
        $completedCourses = Reservation::where('surfeur_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('course', function ($query) {
                $query->where('date', '<', Carbon::now());
            })
            ->count();
            
        // Get upcoming courses for recommendations (exclude already booked ones)
        $bookedCourseIds = $user->reservations()->pluck('course_id')->toArray();
        
        $recommendedCourses = Course::with('coach')
            ->where('date', '>=', Carbon::now())
            ->where('coach_approved', true)
            ->whereNotIn('id', $bookedCourseIds)
            ->orderBy('date')
            ->take(3)
            ->get();
            
        return view('surfeur.dashboard', compact(
            'user', 
            'upcomingReservations', 
            'totalReservations', 
            'confirmedReservations', 
            'completedCourses',
            'recommendedCourses'
        ));
    }
    
    /**
     * Display the reservations for the surfeur
     */
    public function reservations(Request $request)
    {
        $user = Auth::user();
        $query = Reservation::with('course.coach')->where('surfeur_id', $user->id);
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply time filter
        if ($request->filled('time')) {
            if ($request->time === 'upcoming') {
                $query->whereHas('course', function ($q) {
                    $q->where('date', '>=', Carbon::now());
                });
            } elseif ($request->time === 'past') {
                $query->whereHas('course', function ($q) {
                    $q->where('date', '<', Carbon::now());
                });
            }
        }
        
        // Get reservations with pagination
        $reservations = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get counts for each status
        $confirmedCount = Reservation::where('surfeur_id', $user->id)->where('status', 'confirmed')->count();
        $pendingCount = Reservation::where('surfeur_id', $user->id)->where('status', 'pending')->count();
        $cancelledCount = Reservation::where('surfeur_id', $user->id)->where('status', 'cancelled')->count();
        
        return view('surfeur.reservations.index', compact(
            'reservations',
            'confirmedCount',
            'pendingCount',
            'cancelledCount'
        ));
    }
    
    /**
     * Display a specific reservation
     */
    public function showReservation(Reservation $reservation)
    {
        // Ensure the reservation belongs to the logged-in user
        if ($reservation->surfeur_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $reservation->load('course.coach');
        
        return view('surfeur.reservations.show', compact('reservation'));
    }
    
    /**
     * Cancel a reservation
     */
    public function cancelReservation(Reservation $reservation)
    {
        // Ensure the reservation belongs to the logged-in user
        if ($reservation->surfeur_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow cancellation of confirmed reservations
        if ($reservation->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed reservations can be cancelled.');
        }
        
        // Update the reservation status
        $reservation->status = 'cancelled';
        $reservation->save();
        
        // Increment available places on the course
        $course = $reservation->course;
        $course->available_places += 1;
        $course->save();
        
        return redirect()->route('surfer.reservations')
            ->with('success', 'Your reservation has been cancelled successfully.');
    }
    
    /**
     * Display the profile for the surfeur
     */
    public function profile()
    {
        $user = Auth::user();
        
        // Get counts for completed and upcoming courses
        $completedCoursesCount = Reservation::where('surfeur_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('course', function ($query) {
                $query->where('date', '<', Carbon::now());
            })
            ->count();
            
        $upcomingCoursesCount = Reservation::where('surfeur_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('course', function ($query) {
                $query->where('date', '>=', Carbon::now());
            })
            ->count();
            
        return view('surfeur.profile', compact(
            'user',
            'completedCoursesCount',
            'upcomingCoursesCount'
        ));
    }
    
    /**
     * Update the surfeur's profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);
        
        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            
            // Delete old profile photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            $user->profile_photo_path = $path;
        }
        
        $user->save();
        
        return redirect()->route('surfer.profile')
            ->with('status', 'profile-updated');
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the user's reservations
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'surfeur') {
            // For surfeurs, redirect to the surfeur dashboard reservations
            return redirect()->route('surfer.reservations');
        } elseif ($user->role === 'coach') {
            // For coaches, show reservations for their courses
            $reservations = Reservation::whereHas('course', function($query) use ($user) {
                    $query->where('coach_id', $user->id);
                })
                ->with('course', 'surfeur')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            return view('reservations.coach', compact('reservations'));
        } else {
            // Admins could see all, but for now redirect
            return redirect()->route('courses.index');
        }
    }

    /**
     * Cancel a reservation (surfeur only)
     */
    public function cancel(Reservation $reservation)
    {
        $user = Auth::user();
        
        // Verify the reservation belongs to the user
        if ($user->role !== 'surfeur' || $reservation->surfeur_id !== $user->id) {
            return redirect()->route('surfer.reservations')
                ->with('error', 'You can only cancel your own reservations.');
        }
        
        // Check if course date is in the future
        if ($reservation->course->date < now()) {
            return redirect()->route('surfer.reservations')
                ->with('error', 'Cannot cancel a reservation for a past course.');
        }
        
        // Update status to cancelled
        $reservation->status = 'cancelled';
        $reservation->save();
        
        return redirect()->route('surfer.reservations')
            ->with('success', 'Reservation cancelled successfully.');
    }

    /**
     * Show a specific reservation
     */
    public function show(Reservation $reservation)
    {
        $user = Auth::user();
        
        // Check permissions
        if ($user->role === 'surfeur' && $reservation->surfeur_id !== $user->id) {
            return redirect()->route('surfer.reservations')
                ->with('error', 'You can only view your own reservations.');
        } elseif ($user->role === 'coach' && $reservation->course->coach_id !== $user->id) {
            return redirect()->route('reservations.index')
                ->with('error', 'You can only view reservations for your courses.');
        }
        
        return view('reservations.show', compact('reservation'));
    }

    /**
     * For coaches to see all reservations for a specific course
     */
    public function courseReservations(Course $course)
    {
        $user = Auth::user();
        
        if ($user->role !== 'coach' || $course->coach_id !== $user->id) {
            return redirect()->route('courses.index')
                ->with('error', 'You can only view reservations for your own courses.');
        }
        
        $reservations = $course->reservations()
            ->with('surfeur')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('reservations.course', compact('reservations', 'course'));
    }
} 
<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cours;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $coachId = Auth::id();
        
        $totalCours = Cours::where('coach_id', $coachId)->count();
        $totalReservations = Reservation::whereHas('cours', function($query) use ($coachId) {
            $query->where('coach_id', $coachId);
        })->count();
        $totalSurfeurs = Reservation::whereHas('cours', function($query) use ($coachId) {
            $query->where('coach_id', $coachId);
        })->distinct('surfer_id')->count();

        $cours = Cours::where('coach_id', $coachId)
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();

        $reservations = Reservation::whereHas('cours', function($query) use ($coachId) {
            $query->where('coach_id', $coachId);
        })->with(['surfer', 'cours'])
            ->latest()
            ->take(5)
            ->get();

        return view('coach.coachDashboard', compact(
            'totalCours',
            'totalReservations',
            'totalSurfeurs',
            'cours',
            'reservations'
        ));
    }
}

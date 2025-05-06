<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Afficher la liste des réservations
     */
    public function index()
    {
        $reservations = Reservation::whereHas('cours', function($query) {
            $query->where('coach_id', Auth::id());
        })
        ->with(['cours', 'surfer'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        return view('coach.reservations.index', compact('reservations'));
    }
    
    /**
     * Mettre à jour le statut d'une réservation
     */
    public function updateStatut(Request $request, Reservation $reservation)
    {
        // Vérifier que le coach est bien le propriétaire du cours
        if ($reservation->cours->coach_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'statut' => 'required|in:En attente,Confirmé,Annulé',
        ]);
        
        $reservation->update($validated);
        
        return redirect()->route('coach.reservations')
            ->with('success', 'Le statut de la réservation a été mis à jour avec succès.');
    }
} 
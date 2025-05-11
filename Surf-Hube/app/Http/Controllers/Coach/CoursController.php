<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursController extends Controller
{
    /**
     * Afficher la liste des cours
     */
    public function index()
    {
        $cours = Cours::where('coach_id', Auth::id())
            ->orderBy('date', 'asc')
            ->paginate(10);
            
        return view('coach.cours.index');
    }
    
    /**
     * Afficher le formulaire de création de cours
     */
    public function create()
    {
        return view('coach.ajouter_cours');
    }
    
    /**
     * Enregistrer un nouveau cours
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'niveau' => 'required|in:debutant,intermediaire,avance',
            'capacite' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ]);
        
        $cours = new Cours($validated);
        $cours->coach_id = Auth::id();
        $cours->save();
        
        return redirect()->route('coach.cours')
            ->with('success', 'Le cours a été créé avec succès.');
    }
    
    /**
     * Afficher le formulaire d'édition d'un cours
     */
    public function edit(Cours $cours)
    {
        // Vérifier que le coach est bien le propriétaire du cours
        if ($cours->coach_id !== Auth::id()) {
            abort(403);
        }
        
        return view('coach.cours.edit', compact('cours'));
    }
    
    /**
     * Mettre à jour un cours
     */
    public function update(Request $request, Cours $cours)
    {
        // Vérifier que le coach est bien le propriétaire du cours
        if ($cours->coach_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'niveau' => 'required|in:debutant,intermediaire,avance',
            'capacite' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ]);
        
        $cours->update($validated);
        
        return redirect()->route('coach.cours')
            ->with('success', 'Le cours a été mis à jour avec succès.');
    }
    
    /**
     * Supprimer un cours
     */
    public function destroy(Cours $cours)
    {
        // Vérifier que le coach est bien le propriétaire du cours
        if ($cours->coach_id !== Auth::id()) {
            abort(403);
        }
        
        $cours->delete();
        
        return redirect()->route('coach.cours')
            ->with('success', 'Le cours a été supprimé avec succès.');
    }
} 
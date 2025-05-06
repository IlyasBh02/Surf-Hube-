<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'sexe' => 'required|in:homme,femme',
            'poids' => 'required|integer|min:1',
            'hauteur' => 'required|integer|min:1',
            'experience' => 'nullable|boolean',
            'role' => 'required|in:surfer,coach',
        ]);

        $user = User::create([
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'sexe' => $request->sexe,
            'poids' => $request->poids,
            'hauteur' => $request->hauteur,
            'experience' => $request->boolean('experience'),
            'role' => $request->role,
            'is_approved' => $request->role === 'coach' ? false : true, // Les coachs ne sont pas approuvés par défaut
        ]);

        Auth::login($user);

        // Redirection selon le rôle
        if ($user->role === 'coach') {
            return redirect()->route('coach.pending');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('courses');
        }
    }
} 
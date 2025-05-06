<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = User::role('coach')->withCount('courses')->get();
        return view('admin.coaches', compact('coaches'));
    }

    public function approve(User $user)
    {
        if ($user->role !== 'coach') {
            return back()->with('error', 'Cet utilisateur n\'est pas un coach.');
        }

        $user->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Le coach a été approuvé avec succès.');
    }

    public function reject(User $user)
    {
        if ($user->role !== 'coach') {
            return back()->with('error', 'Cet utilisateur n\'est pas un coach.');
        }

        $user->update(['is_approved' => false]);
        return redirect()->back()->with('success', 'Le coach a été rejeté avec succès.');
    }

    public function edit(User $user)
    {
        return view('admin.coaches.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_approved' => 'boolean'
        ]);

        $user->update($validated);
        return redirect()->route('admin.coaches.index')->with('success', 'Le coach a été mis à jour avec succès.');
    }
}

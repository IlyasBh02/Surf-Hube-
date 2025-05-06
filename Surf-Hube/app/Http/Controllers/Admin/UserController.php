<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function promote(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cet utilisateur est déjà administrateur.');
        }

        $user->update(['role' => 'admin']);
        return back()->with('success', 'L\'utilisateur a été promu administrateur avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();
        return back()->with('success', 'L\'utilisateur a été supprimé avec succès.');
    }
} 
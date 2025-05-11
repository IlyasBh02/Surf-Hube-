<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role' => 'required|in:coach,surfeur',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->role === 'coach' ? 'pending' : 'active',
            'coach_approved' => false,
        ]);

        Auth::login($user);

        return redirect()->intended($this->redirectBasedOnRole($user));
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Check if user is suspended
            if ($user->status === 'suspended') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                throw ValidationException::withMessages([
                    'email' => ['Your account has been suspended. Please contact the administrator.'],
                ]);
            }
            
            // For coaches, check if approved
            if ($user->role === 'coach' && !$user->coach_approved && $user->status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                throw ValidationException::withMessages([
                    'email' => ['Your coach account is pending approval. Please wait for administrator confirmation.'],
                ]);
            }

            return redirect()->intended($this->redirectBasedOnRole($user));
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return '/admin/dashboard';
        } elseif ($user->isCoach() && $user->coach_approved) {
            return '/coach/dashboard';
        } else {
            return '/surfer/dashboard';
        }
    }

    /**
     * Request coach approval
     */
    public function requestCoachApproval(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'coach') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        // Logic to request approval or re-approval
        $user->status = 'pending';
        $user->save();
        
        return redirect()->back()->with('success', 'Your approval request has been submitted.');
    }

    /**
     * Admin: Approve coach
     */
    public function approveCoach(Request $request, User $coach)
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        if ($coach->role !== 'coach') {
            return redirect()->back()->with('error', 'User is not a coach.');
        }
        
        $coach->coach_approved = true;
        $coach->status = 'active';
        $coach->save();
        
        return redirect()->back()->with('success', 'Coach has been approved.');
    }

    /**
     * Admin: Reject coach
     */
    public function rejectCoach(Request $request, User $coach)
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        if ($coach->role !== 'coach') {
            return redirect()->back()->with('error', 'User is not a coach.');
        }
        
        $coach->coach_approved = false;
        $coach->status = 'suspended';
        $coach->save();
        
        return redirect()->back()->with('success', 'Coach has been rejected.');
    }

    /**
     * Admin: Change user status (activate/suspend)
     */
    public function changeUserStatus(Request $request, User $user)
    {
        $admin = Auth::user();
        
        if (!$admin || !$admin->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $user->status = $request->status;
        $user->save();
        
        $statusText = $request->status === 'active' ? 'activated' : 'suspended';
        return redirect()->back()->with('success', "User has been {$statusText}.");
    }

    /**
     * Admin: Delete user
     */
    public function deleteUser(Request $request, User $user)
    {
        $admin = Auth::user();
        
        if (!$admin || !$admin->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        // Prevent self-deletion
        if ($admin->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->back()->with('success', 'User has been deleted.');
    }
} 
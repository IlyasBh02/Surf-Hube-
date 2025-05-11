<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes are used for authentication functionality only
|
*/

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { 
        return view('auth.login'); 
    })->name('login');
    
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', function () { 
        return view('auth.register'); 
    })->name('register');
    
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
}); 
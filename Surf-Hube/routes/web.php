<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\SettingController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/courses', function () {
    return view('courses');
})->name('courses');

// Auth Routes
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function() {
        return view('adminDashboard');
    })->name('dashboard');

    // Users management
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
    Route::post('/users/{user}/promote', [App\Http\Controllers\Admin\UserController::class, 'promote'])->name('users.promote');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Coaches management
    Route::get('/coaches', [App\Http\Controllers\Admin\CoachController::class, 'index'])->name('coaches.index');
    Route::post('/coaches/{user}/approve', [App\Http\Controllers\Admin\CoachController::class, 'approve'])->name('coaches.approve');
    Route::post('/coaches/{user}/reject', [App\Http\Controllers\Admin\CoachController::class, 'reject'])->name('coaches.reject');
    Route::get('/coaches/{user}/edit', [App\Http\Controllers\Admin\CoachController::class, 'edit'])->name('coaches.edit');
    Route::put('/coaches/{user}', [App\Http\Controllers\Admin\CoachController::class, 'update'])->name('coaches.update');

    // Courses management
    Route::get('/cours', function() {
        return view('admin.cours');
    })->name('cours');

    // Surfers management
    Route::get('/surfers', function() {
        return view('admin.surfers');
    })->name('surfers');

    // Reservations management
    Route::get('/reservations', function() {
        return view('admin.reservations');
    })->name('reservations');

    // Settings
    Route::get('/settings', function() {
        return view('admin.settings');
    })->name('admin.settings');
});

// Coach Routes
Route::middleware(['auth', 'role:coach'])->prefix('coach')->name('coach.')->group(function () {
    // Page d'attente d'approbation
    Route::get('/pending', function() {
        return view('coach.pending');
    })->name('pending');
    
    // Dashboard
    Route::get('/coachDashboard', [App\Http\Controllers\Coach\DashboardController::class, 'index'])->name('coachDashboard');
    
    // Courses management
    Route::get('/cours', [App\Http\Controllers\Coach\CoursController::class, 'index'])->name('cours');
    Route::get('/cours/ajouter', [App\Http\Controllers\Coach\CoursController::class, 'create'])->name('ajouter_cours');
    Route::post('/cours', [App\Http\Controllers\Coach\CoursController::class, 'store'])->name('cours.store');
    Route::get('/cours/{cours}/edit', [App\Http\Controllers\Coach\CoursController::class, 'edit'])->name('edit_cours');
    Route::put('/cours/{cours}', [App\Http\Controllers\Coach\CoursController::class, 'update'])->name('cours.update');
    Route::delete('/cours/{cours}', [App\Http\Controllers\Coach\CoursController::class, 'destroy'])->name('delete_cours');
    
    // Reservations management
    Route::get('/reservations', [App\Http\Controllers\Coach\ReservationController::class, 'index'])->name('reservations');
    Route::put('/reservations/{reservation}/statut', [App\Http\Controllers\Coach\ReservationController::class, 'updateStatut'])->name('reservations.update_statut');
});

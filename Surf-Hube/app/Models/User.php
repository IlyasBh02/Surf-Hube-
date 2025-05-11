<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'coach_approved',
        'bio',
        'description',
        'years_experience',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'coach_approved' => 'boolean',
    ];

    /**
     * Get the courses created by this user (if they are a coach).
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'coach_id');
    }

    /**
     * Get the reservations made by this user (if they are a surfeur).
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'surfeur_id');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a coach.
     */
    public function isCoach()
    {
        return $this->role === 'coach';
    }

    /**
     * Check if the user is a surfeur.
     */
    public function isSurfeur()
    {
        return $this->role === 'surfeur';
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'sexe',
        'poids',
        'hauteur',
        'experience',
        'role',
        'is_approved',
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
    ];

    /**
     * Vérifier si l'utilisateur est un coach
     */
    public function isCoach()
    {
        return $this->role === 'coach';
    }

    /**
     * Vérifier si l'utilisateur est un admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Obtenir les cours donnés par ce coach
     */
    public function cours()
    {
        return $this->hasMany(Cours::class, 'coach_id');
    }

    /**
     * Obtenir les réservations faites par ce surfeur
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'surfer_id');
    }

    /**
     * Vérifier si l'utilisateur est un coach approuvé
     */
    public function isApprovedCoach()
    {
        return $this->role === 'coach' && $this->is_approved;
    }
}

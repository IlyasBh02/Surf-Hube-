<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'cours';
    
    protected $fillable = [
        'titre',
        'description',
        'date',
        'niveau',
        'capacite',
        'prix',
        'coach_id',
    ];
    
    protected $casts = [
        'date' => 'datetime',
        'capacite' => 'integer',
        'prix' => 'decimal:2',
    ];
    
    /**
     * Obtenir le coach qui donne ce cours
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    
    /**
     * Obtenir les réservations pour ce cours
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
    /**
     * Obtenir le nombre de participants actuels
     */
    public function getParticipantsAttribute()
    {
        return $this->reservations()->where('statut', 'Confirmé')->count();
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    
    protected $fillable = [
        'cours_id',
        'surfer_id',
        'date',
        'statut',
        'notes',
    ];
    
    protected $casts = [
        'date' => 'datetime',
    ];
    
    /**
     * Obtenir le cours associé à cette réservation
     */
    public function cours()
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }
    
    /**
     * Obtenir le surfeur qui a fait cette réservation
     */
    public function surfer()
    {
        return $this->belongsTo(User::class, 'surfer_id');
    }
} 
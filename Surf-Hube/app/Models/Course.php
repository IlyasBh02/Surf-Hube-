<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coach_id',
        'title',
        'description',
        'thumbnail',
        'date',
        'duration',
        'available_places',
        'price',
        'level',
        'location',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'duration' => 'integer',
        'available_places' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Get the coach that owns the course.
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    /**
     * Get the reservations for the course.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the surfeurs who have reserved this course.
     */
    public function surfeurs()
    {
        return $this->belongsToMany(User::class, 'reservations', 'course_id', 'surfeur_id');
    }

    /**
     * Get the remaining places for the course.
     */
    public function getRemainingPlacesAttribute()
    {
        $bookedCount = $this->reservations()->where('status', 'confirmed')->count();
        return $this->available_places - $bookedCount;
    }
} 
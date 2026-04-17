<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_time',
        'is_public',
        'reminder_sent', // ✅ Added
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'is_public' => 'boolean', // ✅ Cast to boolean
        'reminder_sent' => 'boolean',
    ];

    // Scope for upcoming events
    public function scopeUpcoming($query)
    {
        return $query->where('event_time', '>', now());
    }

    // Accessor for status
    public function getStatusAttribute()
    {
        return $this->event_time > now() ? 'Upcoming' : 'Passed';
    }

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

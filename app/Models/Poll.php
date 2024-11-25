<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poll extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    // Specify the table name
    protected $table = 'poll';

    protected $fillable = [
        'event_id',
        'name',
        'start_time',
        'end_time',
        'options',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'options' => 'array', // Must confirm this
    ];

    public function show()
    {
        // Check if the current instance has an ID
        if (!$this->id) {
            return ['error' => 'Invite not found'];
        }

        // Return the comment data as an array using the current instance
        return [
            'event_id' => $this->event_id,
            'end_time' => $this->end_time,
            'name' => $this->name
        ];
    }

    /**
     * Get the event where the poll is included.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function responses()
    {
        return $this->hasMany(PollResponse::class, 'poll_id');
    }
    
}

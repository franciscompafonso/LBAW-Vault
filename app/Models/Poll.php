<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poll extends Model
{
    use HasFactory;

    public $timestamps  = false;
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
        'options' => 'array',
    ];

    public function show()
    {
        if (!$this->id) {
            return ['error' => 'Invite not found'];
        }

        return [
            'event_id' => $this->event_id,
            'end_time' => $this->end_time,
            'name' => $this->name
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function responses()
    {
        return $this->hasMany(PollResponse::class, 'poll_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    use HasFactory;

    protected $table = 'join_request';

    public $timestamps = false;

    protected $fillable = [
        'join_request_by_id',
        'event_id',
        'accepted',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
        'accepted' => 'boolean',
    ];

    public function show()
    {
        // Check if the current instance has an ID
        if (!$this->id) {
            return ['error' => 'Request not found'];
        }

        // Fetch the name of the user who made the join request
        $joinRequestByName = User::find($this->join_request_by_id)->name ?? 'Unknown User';

        // Fetch the event associated with this request
        $event = Event::find($this->event_id);

        // Fetch the name of the event creator
        $eventCreatorName = $event ? User::find($event->creator_id)->name ?? 'Unknown Creator' : 'Unknown Creator';
        $eventTitle = $event ? $event->name : '';

        // Return the request data as an array using the current instance
        return [
            'join_request_id' => $this->id,
            'join_request_by_id' => $this->join_request_by_id,
            'event_creator_id' => $event->creator_id ?? null,
            'join_request_by_name' => $joinRequestByName,
            'event_creator_name' => $eventCreatorName,
            'event_id' => $this->event_id,
            'event_title' => $eventTitle,
            'accepted' => $this->accepted,
            'date' => $this->date,
        ];
    }


    public function accept(): bool
    {
        $this->accepted = true;
        return $this->save();
    }

    public function decline(): bool
    {
        $this->accepted = false;
        return $this->save();
    }

    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'join_request_by_id');
    }

    public function receiver()
    {
        $event = Event::find($this->event_id);
        return $event ? User::find($event->creator_id) : null;
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }


}

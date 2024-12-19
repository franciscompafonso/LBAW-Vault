<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $table = 'invite';

    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'event_id',
        'title',
        'accepted',
        'description',
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
            return ['error' => 'Invite not found'];
        }

        // Fetch the username based on sender_id
        $senderName = User::find($this->sender_id)->name ?? 'Unknown User';
        $receiverName = User::find($this->receiver_id)->name ?? 'Someone';

        // Return the comment data as an array using the current instance
        return [
            'invite_id' => $this->id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'sender_name' => $senderName,
            'receiver_name' => $receiverName,
            'event_id' => $this->event_id,
            'title' => $this->title,
            'accepted' => $this->accepted,
            'description' => $this->description,
            'date' => $this->date,
        ];
    }
 
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
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

}

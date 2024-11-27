<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invite extends Model
{
    use HasFactory;

    protected $table = 'invite';

    public $timestamps = false;

    protected $fillable = [
        'invite_by_id',
        'invite_to_id',
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

        // Fetch the username based on invite_by_id
        $inviteByName = User::find($this->invite_by_id)->name ?? 'Unknown User';
        $inviteToName = User::find($this->invite_to_id)->name ?? 'Someone';

        // Return the comment data as an array using the current instance
        return [
            'invite_id' => $this->id,
            'invite_by_id' => $this->invite_by_id,
            'invite_to_id' => $this->invite_to_id,
            'invite_by_name' => $inviteByName,
            'invite_to_name' => $inviteToName,
            'event_id' => $this->event_id,
            'title' => $this->title,
            'accepted' => $this->accepted,
            'description' => $this->description,
            'date' => $this->date,
        ];
    }
 
    public function inviteBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invite_by_id');
    }

    public function inviteTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invite_to_id');
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

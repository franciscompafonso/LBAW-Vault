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
        'title',
        'accepted',
        'description',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
        'accepted' => 'boolean',
    ];

 
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
        return $this->accepted === true;
    }

}

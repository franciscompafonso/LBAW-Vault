<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    // Table associated with the model (optional, if different from the default 'notifications')
    protected $table = 'notifications'; 

    // Disable timestamps if the table doesn't have `created_at` or `updated_at`
    public $timestamps = false;

    // The attributes that are mass assignable
    protected $fillable = [
        'emitted_by',
        'emitted_to',
        'date',
        'viewed',
    ];

    // Define the one-to-many relationship with the 'User' model for 'emitted_by'
    public function emittedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'emitted_by');
    }

    // Define the one-to-many relationship with the 'User' model for 'emitted_to'
    public function emittedTo() : BelongsTo
    {
        return $this->belongsTo(User::class, 'emitted_to');
    }

    // *Attempts* to mark the notification as viewed
    public function markAsViewed()
    {
        $this->viewed = true;
        $this->save();
    }
}

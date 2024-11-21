<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    // Specify the table name
    protected $table = 'comment';

    protected $fillable = [
        'comment_by_id',
        'event_id',
        'text',
        'is_pinned',
        'score',
        'edited',
        'date',
    ];

    /**
     * Get the user who commented.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'comment_by_id');
    }

    /**
     * Get the event where the comment is included.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}

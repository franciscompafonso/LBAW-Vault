<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * Show the comment details by comment ID.
     * 
     * @param int $commentId
     * @return array
     */
    public function show()
    {
        // Check if the current instance has an ID
        if (!$this->id) {
            return ['error' => 'Comment not found'];
        }

        // Return the comment data as an array using the current instance
        return [
            'comment_by_id' => $this->comment_by_id,
            'event_id' => $this->event_id,
            'text' => $this->text,
            'is_pinned' => $this->is_pinned,
            'score' => $this->score,
            'edited' => $this->edited,
            'date' => $this->date,
        ];
    }

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

    /**
     * Get the photos for the comment.
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'comment_id');
    }

    /**
     * Get the feedbacks for the comment.
     */
    public function feedback($user_id)
    {
        // This will return the feedback value, or null if no feedback exists for this user
        return $this->hasMany(CommentFeedback::class, 'comment_id')
                    ->where('user_id', $user_id)
                    ->value('feedback');
    }

    /**
     * Get the replies to the current comment for the same event.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'reply_to')
                    ->where('event_id', $this->event_id);
    }
}

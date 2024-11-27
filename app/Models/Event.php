<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    // Disable Laravel's default timestamps management
    public $timestamps = false;

    // Specify the table name
    protected $table = 'event';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];


    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    
    public function participators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participator', 'event_id', 'participator_id');
    }

    
    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class, 'event_id');
    }

    
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'event_id')
                    ->whereNull('reply_to');
    }

    
    public function reviews(): HasMany
    {
        return $this->hasMany(AnswerReview::class, 'about_event_id');
    }

    
    public function eventUpdateNotifications(): HasMany
    {
        return $this->hasMany(EventUpdateNotification::class, 'event_id');
    }

    
    public function eventReminders(): HasMany
    {
        return $this->hasMany(EventReminder::class, 'event_id');
    }

    
    public function eventCancelations(): HasMany
    {
        return $this->hasMany(EventCancelation::class, 'event_id');
    }

    public function visibility(): ?string
    {
        return $this->visibility;
    }

}
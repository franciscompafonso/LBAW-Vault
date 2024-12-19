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

    const DEFAULT_BANNER = 'default-banner.png';

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

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function participators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participator', 'event_id', 'participator_id');
    }

    public function invites()
    {
        return $this->hasMany(Invite::class, 'event_id');
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
        return $this->hasMany(NotificationSubtypes\EventUpdateNotification::class, 'event_id');
    }

    public function eventReminders(): HasMany
    {
        return $this->hasMany(NotificationSubtypes\EventReminder::class, 'event_id');
    }

    public function eventCancelations(): HasMany
    {
        return $this->hasMany(NotificationSubtypes\EventCancelation::class, 'event_id');
    }

    public function visibility(): ?string
    {
        return $this->visibility;
    }

    public function requests()
    {
        return $this->hasMany(JoinRequest::class, 'event_id');
    }

    public function banner()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function scopeVisible($query) {
        return $query->where('visibility', '!=', 'private');
    }

    public function scopeCategory($query, $categoryIds) {
        if (!empty($categoryIds)) {
            return $query->whereIn('category_id', $categoryIds);
        }
        return $query;
    }
    
    public function scopeDistrict($query, $districtIds) {
        if (!empty($districtIds)) {
            return $query->whereIn('district_id', $districtIds);
        }
        return $query;
    }

    public function scopeDateRange($query, $startDate, $endDate) {
        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }
        return $query;
    }

    public function scopeSearch($query, $searchQuery) {
        if ($searchQuery) {
            $searchResults = $query->select('id')
                ->whereRaw("ts_vector @@ plainto_tsquery('english', ?)", [$searchQuery])
                ->pluck('id');
            return $query->whereIn('id', $searchResults);
        }
        return $query;
    }
}

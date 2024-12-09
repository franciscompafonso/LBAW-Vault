<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
// Added to define Eloquent relationships.
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    protected $table = 'user_account';

    const DEFAULT_PIC = 'default-avatar.png';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function allEvents()
    {
        $createdEvents = $this->myEvents()->get();
        $participatedEvents = $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')->get();
        $notYetParticipatedEvents = $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')->where('event.start_date', '>=', now());

        return $createdEvents->merge($participatedEvents)->unique('id');
    }

    public function myEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

        public function participatedEvents()
    {
        return $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')
                    ->where('event.start_date', '<', now()) // Events that have started
                    ->where('event.end_date', '<=', now()); // Events that have ended
    }


    public function notYetParticipatedEvents()
    {
        return $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')
                    ->where('event.start_date', '>=', now()) // Upcoming events
                    ->where('event.end_date', '>', now()); // Events that haven't ended yet
    }


        public function ongoingEvents()
    {
        return $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')
                    ->where('event.start_date', '<=', now()) // Events that have already started
                    ->where('event.end_date', '>=', now()); // Events that have not yet ended
    }


    public function getDisplayNameAttribute()
    {
        // Check if the user is marked as deleted
        if ($this->isdeleted) {
            // Return the formatted deleted user name
            return 'deleted-user';
        }

        // Otherwise, return the user's real name
        return $this->username;
    }

    public function picture()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}

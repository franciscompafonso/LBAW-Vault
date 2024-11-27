<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PasswordResetToken;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

     // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $table = 'user_account';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profilepicture',
        'isDeleted',
        'isBlocked'
    ];

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

    public function notifications() {
        return $this->hasMany(Notification::class, 'user_id');
    }

    // TO DO: Adaptar funções quando event for feito
    public function allEvents()
    {
        $createdEvents = $this->myEvents()->get();
        $participatedEvents = $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')->get();
        $notYetParticipatedEvents = $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')->where('event.start_date', '>=', now());
        // Combine and remove duplicates
        return $createdEvents->merge($participatedEvents)->unique('id');
    }

    public function myEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    public function participatedEvents()
    {
        return $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')
                    ->where('event.start_date', '<', now()); 
    }

    public function notYetParticipatedEvents()
    {
        return $this->belongsToMany(Event::class, 'participator', 'participator_id', 'event_id')
                    ->where('event.start_date', '>=', now());
    }

    public function getDisplayNameAttribute()
    {
        // Check if the user is marked as deleted
        if ($this->isdeleted) {
            // Return the formatted deleted user name
            return "deleted-user-" . $this->id;
        }

        // Otherwise, return the user's real name
        return $this->username;
    }
}

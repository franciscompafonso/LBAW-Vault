<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class EventReminder extends Model
{
    protected $table = 'event_reminder';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'event_id',
    ];
}

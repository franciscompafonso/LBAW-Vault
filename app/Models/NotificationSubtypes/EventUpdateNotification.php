<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class EventUpdateNotification extends Model
{
    protected $table = 'event_update_notification';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'event_id',
    ];
}

<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class EventCancelation extends Model
{
    protected $table = 'event_cancelation';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'event_id',
    ];
}

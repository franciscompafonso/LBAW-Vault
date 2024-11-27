<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class AcceptedJoinRequestNotification extends Model
{
    protected $table = 'accepted_request_notification';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'request_id',
    ];
}

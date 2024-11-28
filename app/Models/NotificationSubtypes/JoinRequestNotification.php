<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class JoinRequestNotification extends Model
{
    protected $table = 'join_request_notification';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'request_id',
    ];
}

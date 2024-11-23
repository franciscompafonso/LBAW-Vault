<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class AcceptedInviteNotification extends Model
{
    protected $table = 'accepted_invite_notification';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'invite_id',
    ];
}

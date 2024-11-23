<?php
namespace App\Models\NotificationSubtypes;

use Illuminate\Database\Eloquent\Model;

class InviteNotification extends Model
{
    protected $table = 'invite_notification';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'invite_id',
    ];
}

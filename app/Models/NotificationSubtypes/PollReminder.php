<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class PollReminder extends Model
{
    protected $table = 'poll_reminder';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'poll_id',
    ];
}

<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class UnblockNotification extends Model
{
    protected $table = 'unblock_notification';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'admin_id',
    ];
}

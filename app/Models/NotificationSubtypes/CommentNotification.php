<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class CommentNotification extends Model
{
    protected $table = 'comment_notification';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'comment_id',
    ];
}

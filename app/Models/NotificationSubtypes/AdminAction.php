<?php
namespace App\Models\NotificationSubtypes;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    protected $table = 'admin_action';
    public $timestamps = false;

    protected $guarded;
}


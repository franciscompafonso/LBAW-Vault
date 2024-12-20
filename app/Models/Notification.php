<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notification'; 
    public $timestamps = false;

    protected $guarded = [];

    public function emittedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'emitted_by');
    }

    public function emittedTo() : BelongsTo
    {
        return $this->belongsTo(User::class, 'emitted_to');
    }
}

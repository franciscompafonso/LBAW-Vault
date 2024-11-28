<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    protected $table = 'notification'; 
    public $timestamps = false;

    protected $fillable = [
        'emitted_by',
        'emitted_to',
        'date',
        'viewed',
    ];

    protected $casts = [
        'date' => 'datetime',
        'viewed' => 'boolean',
    ];

    public function emittedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'emitted_by');
    }

    public function emittedTo() : BelongsTo
    {
        return $this->belongsTo(User::class, 'emitted_to');
    }

    public function markAsViewed()
    {
        $this->viewed = true;
        $this->save();
    }
}

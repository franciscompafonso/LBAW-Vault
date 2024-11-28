<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollResponse extends Model
{
    public $timestamps = false;
    protected $primaryKey = ['poll_id', 'user_id'];
    public $incrementing = false;
    protected $table = 'poll_response';

    protected $fillable = ['poll_id', 'user_id', 'option'];

    // Define relationships
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
}

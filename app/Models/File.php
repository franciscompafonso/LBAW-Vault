<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'file';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}

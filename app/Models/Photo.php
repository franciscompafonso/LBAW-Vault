<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photo';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'comment_id',
        'path',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}

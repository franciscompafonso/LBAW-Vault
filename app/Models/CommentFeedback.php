<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentFeedback extends Model
{
    protected $table = 'comment_feedback';

    // Specify that there is no auto-incrementing primary key
    public $incrementing = false;

    // Define the composite primary key
    protected $primaryKey = ['user_id', 'comment_id'];

    protected $fillable = [
        'user_id',
        'comment_id',
        'feedback',
    ];

    public $timestamps = false;
}

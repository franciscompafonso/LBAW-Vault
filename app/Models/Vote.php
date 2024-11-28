<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'vote';

    public $timestamps = false;

    protected $fillable = [
        'voted_by_id',
        'comment_id',
        'type',
    ];

    public function votedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voted_by_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public static function countUpvotes(int $commentId): int
    {
        return self::where('comment_id', $commentId)->where('type', 1)->count();
    }

    public static function countDownvotes(int $commentId): int
    {
        return self::where('comment_id', $commentId)->where('type', -1)->count();
    }

}

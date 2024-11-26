<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionReview extends Model
{
    use HasFactory;
    protected $table = 'question_review';
    protected $fillable = ['question_text'];
}
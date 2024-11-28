<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerReview extends Model
{
    use HasFactory;

    protected $table = 'answer_review'; 
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'answered_by_id',  
        'question_id',     
        'about_event_id', 
        'rating',          
        'date',            
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'answered_by_id');
    }

    public function question()
    {
        return $this->belongsTo(QuestionReview::class, 'question_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'about_event_id');
    }
}
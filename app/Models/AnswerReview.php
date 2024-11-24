<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerReview extends Model
{
    use HasFactory;

    // Define the table name (matches the provided table name)
    protected $table = 'answer_review'; 

    // Fillable fields for mass assignment
    protected $fillable = [
        'answered_by_id',  // the user who answered
        'question_id',     // the question being reviewed
        'about_event_id',  // the event being reviewed
        'rating',          // rating given in the review
        'date',            // date of the review
    ];

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'answered_by_id');
    }

    // Relationship to the QuestionReview model
    public function question()
    {
        return $this->belongsTo(QuestionReview::class, 'question_id');
    }

    // Relationship to the Event model
    public function event()
    {
        return $this->belongsTo(Event::class, 'about_event_id');
    }
}
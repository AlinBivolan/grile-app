<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSessionQuestion extends Model
{
    protected $fillable = [
        'quiz_session_id',
        'question_id',
        'display_order',
        'answer_order',
        'selected_answer_ids',
        'is_answered',
        'is_correct',
        'answered_at',
    ];

    protected $casts = [
        'answer_order' => 'array',
        'selected_answer_ids' => 'array',
        'is_answered' => 'boolean',
        'is_correct' => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function quizSession()
    {
        return $this->belongsTo(QuizSession::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionStatistic extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'times_seen',
        'times_correct',
        'times_wrong',
        'current_streak',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    protected $fillable = [
    'user_id',
    'category_id',
    'mode',
    'score',
    'total_questions',
    'started_at',
    'completed_at',
];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sessionQuestions()
    {
        return $this->hasMany(QuizSessionQuestion::class);
    }
}
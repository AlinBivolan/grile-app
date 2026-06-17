<?php

namespace App\Http\Controllers;

use App\Models\QuestionStatistic;
use App\Models\QuizSession;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = QuestionStatistic::where('user_id', $user->id)->get();

        $totalSeen = $stats->sum('times_seen');
        $totalCorrect = $stats->sum('times_correct');
        $totalWrong = $stats->sum('times_wrong');

        $accuracy = $totalSeen > 0
            ? round(($totalCorrect / $totalSeen) * 100)
            : 0;

        $totalScore = QuizSession::where('user_id', $user->id)->sum('score');

        $recentSessions = QuizSession::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalSeen',
            'totalCorrect',
            'totalWrong',
            'accuracy',
            'totalScore',
            'recentSessions'
        ));
    }
}
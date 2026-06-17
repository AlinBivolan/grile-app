<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->leftJoin('quiz_sessions', 'users.id', '=', 'quiz_sessions.user_id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COALESCE(SUM(quiz_sessions.score), 0) as total_score'),
                DB::raw('COUNT(quiz_sessions.id) as tests_count')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_score')
            ->limit(50)
            ->get();

        return view('leaderboard.index', compact('users'));
    }
}
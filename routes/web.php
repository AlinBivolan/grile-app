<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OnlineUserController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/questions/{question}/image', [QuizController::class, 'questionImage'])
        ->name('questions.image');

    Route::post(
        '/questions/{question}/difficult',
        [QuizController::class, 'toggleDifficult']
    )->name('questions.difficult');

    Route::get('/quiz', [QuizController::class, 'index'])
        ->name('quiz.index');

    Route::post('/quiz/start', [QuizController::class, 'start'])
        ->name('quiz.start');

    Route::get('/quiz/{session}', [QuizController::class, 'show'])
        ->name('quiz.show');

    Route::post(
        '/quiz/{session}/question/{sessionQuestion}/answer',
        [QuizController::class, 'answer']
    )->name('quiz.answer');

    Route::post('/quiz/{session}/abandon', [QuizController::class, 'abandon'])
        ->name('quiz.abandon');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])
        ->name('leaderboard.index');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('questions', QuestionController::class);

        Route::get('/online-users', [OnlineUserController::class, 'index'])
            ->name('online-users.index');
    });

require __DIR__.'/auth.php';

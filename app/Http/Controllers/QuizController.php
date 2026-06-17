<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DifficultQuestion;
use App\Models\Question;
use App\Models\QuestionStatistic;
use App\Models\QuizSession;
use App\Models\QuizSessionQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('questions')->get();

        $difficultCount = DifficultQuestion::where('user_id', Auth::id())
            ->count();

        return view('quiz.index', compact('categories', 'difficultCount'));
    }

    public function start(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'mode' => [
                'required',
                'in:training,exam,hardcore,category,wrong,unseen,difficult',
            ],
        ]);

        $mode = $data['mode'];

        if ($mode === 'category') {
            $questions = Question::with('answers')
                ->where('is_active', true)
                ->where('category_id', $data['category_id'])
                ->inRandomOrder()
                ->get();
        } elseif ($mode === 'training') {
            $questions = Question::with('answers')
                ->where('is_active', true)
                ->inRandomOrder()
                ->get();
        } elseif ($mode === 'exam') {
            $questions = Question::with('answers')
                ->where('is_active', true)
                ->inRandomOrder()
                ->limit(30)
                ->get();
        } elseif ($mode === 'wrong') {
            $wrongQuestionIds = QuestionStatistic::where('user_id', Auth::id())
                ->where('times_wrong', '>', 0)
                ->pluck('question_id');

            $questions = Question::with('answers')
                ->where('is_active', true)
                ->whereIn('id', $wrongQuestionIds)
                ->inRandomOrder()
                ->get();
        } elseif ($mode === 'unseen') {
            $seenQuestionIds = QuestionStatistic::where('user_id', Auth::id())
                ->pluck('question_id');

            $questions = Question::with('answers')
                ->where('is_active', true)
                ->whereNotIn('id', $seenQuestionIds)
                ->inRandomOrder()
                ->get();
        } elseif ($mode === 'difficult') {
            $difficultQuestionIds = DifficultQuestion::where(
                'user_id',
                Auth::id()
            )->pluck('question_id');

            $questions = Question::with('answers')
                ->where('is_active', true)
                ->whereIn('id', $difficultQuestionIds)
                ->inRandomOrder()
                ->get();
        } else {
            $weakQuestionIds = QuestionStatistic::where('user_id', Auth::id())
                ->where('times_wrong', '>', 0)
                ->orderByDesc('times_wrong')
                ->orderBy('current_streak')
                ->limit(20)
                ->pluck('question_id');

            $weakQuestions = Question::with('answers')
                ->where('is_active', true)
                ->whereIn('id', $weakQuestionIds)
                ->inRandomOrder()
                ->limit(20)
                ->get();

            $remaining = 30 - $weakQuestions->count();

            $randomQuestions = Question::with('answers')
                ->where('is_active', true)
                ->whereNotIn('id', $weakQuestions->pluck('id'))
                ->inRandomOrder()
                ->limit($remaining)
                ->get();

            $questions = $weakQuestions
                ->merge($randomQuestions)
                ->shuffle();
        }

        if ($questions->isEmpty()) {
            return back()->withErrors([
                'quiz' => 'Nu există întrebări disponibile pentru această selecție.',
            ]);
        }

        $session = QuizSession::create([
            'user_id' => Auth::id(),
            'category_id' => $data['category_id'] ?? null,
            'mode' => $mode,
            'score' => 0,
            'total_questions' => $questions->count(),
            'started_at' => now(),
        ]);

        foreach ($questions as $index => $question) {
            QuizSessionQuestion::create([
                'quiz_session_id' => $session->id,
                'question_id' => $question->id,
                'display_order' => $index + 1,
                'answer_order' => $question->answers
                    ->shuffle()
                    ->pluck('id')
                    ->values()
                    ->toArray(),
                'is_answered' => false,
            ]);
        }

        return redirect()->route('quiz.show', $session);
    }

    public function show(QuizSession $session)
    {
        abort_unless($session->user_id === Auth::id(), 403);

        $sessionQuestion = $session->sessionQuestions()
            ->where('is_answered', false)
            ->orderBy('display_order')
            ->with('question.answers')
            ->first();

        if (!$sessionQuestion) {
            $session->update([
                'completed_at' => $session->completed_at ?? now(),
            ]);

            return view('quiz.result', compact('session'));
        }

        $question = $sessionQuestion->question;

        $answers = $question->answers
            ->sortBy(
                fn ($answer) =>
                array_search($answer->id, $sessionQuestion->answer_order)
            )
            ->values();

        $isDifficult = DifficultQuestion::where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->exists();

        return view('quiz.show', compact(
            'session',
            'sessionQuestion',
            'question',
            'answers',
            'isDifficult'
        ));
    }

    public function answer(
        Request $request,
        QuizSession $session,
        QuizSessionQuestion $sessionQuestion
    ) {
        abort_unless($session->user_id === Auth::id(), 403);
        abort_unless(
            $sessionQuestion->quiz_session_id === $session->id,
            403
        );

        if ($sessionQuestion->is_answered) {
            return redirect()->route('quiz.show', $session);
        }

        $question = $sessionQuestion->question()
            ->with('answers')
            ->firstOrFail();

        $data = $request->validate([
            'answer_ids' => ['required', 'array', 'min:1'],
            'answer_ids.*' => [
                'integer',
                'exists:answers,id',
            ],
        ]);

        $selectedIds = array_map('intval', $data['answer_ids']);

        $validAnswerIds = $question->answers
            ->pluck('id')
            ->toArray();

        foreach ($selectedIds as $selectedId) {
            abort_unless(in_array($selectedId, $validAnswerIds), 422);
        }

        $correctIds = $question->answers()
            ->where('is_correct', true)
            ->pluck('id')
            ->sort()
            ->values()
            ->toArray();

        sort($selectedIds);

        $isCorrect = $selectedIds === $correctIds;

        $statistic = QuestionStatistic::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'question_id' => $question->id,
            ],
            [
                'times_seen' => 0,
                'times_correct' => 0,
                'times_wrong' => 0,
                'current_streak' => 0,
            ]
        );

        $statistic->increment('times_seen');

        if ($isCorrect) {
            $session->increment('score', 10);
            $statistic->increment('times_correct');
            $statistic->increment('current_streak');
        } else {
            $statistic->increment('times_wrong');

            $statistic->update([
                'current_streak' => 0,
            ]);
        }

        $statistic->update([
            'last_seen_at' => now(),
        ]);

        $sessionQuestion->update([
            'is_answered' => true,
        ]);

        if ($session->mode === 'exam') {
            return redirect()->route('quiz.show', $session);
        }

        $selectedAnswers = $question->answers()
            ->whereIn('id', $selectedIds)
            ->get();

        $correctAnswers = $question->answers()
            ->whereIn('id', $correctIds)
            ->get();

        $isDifficult = DifficultQuestion::where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->exists();

        return view('quiz.feedback', compact(
            'session',
            'sessionQuestion',
            'question',
            'selectedAnswers',
            'correctAnswers',
            'isCorrect',
            'isDifficult'
        ));
    }

    public function toggleDifficult(Question $question)
    {
        $existing = DifficultQuestion::where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with(
                'success',
                'Întrebarea a fost eliminată din lista de întrebări dificile.'
            );
        }

        DifficultQuestion::create([
            'user_id' => Auth::id(),
            'question_id' => $question->id,
        ]);

        return back()->with(
            'success',
            'Întrebarea a fost marcată ca dificilă.'
        );
    }

    public function abandon(QuizSession $session)
    {
        abort_unless($session->user_id === Auth::id(), 403);

        $session->update([
            'completed_at' => now(),
        ]);

        $session->sessionQuestions()
            ->where('is_answered', false)
            ->update([
                'is_answered' => true,
            ]);

        return redirect()
            ->route('quiz.index')
            ->withErrors([
                'quiz' => 'Ai renunțat la test.',
            ]);
    }

    <?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DifficultQuestion;
use App\Models\Question;
use App\Models\QuestionStatistic;
use App\Models\QuizSession;
use App\Models\QuizSessionQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('questions')->get();

        $difficultCount = DifficultQuestion::where('user_id', Auth::id())
            ->count();

        return view('quiz.index', compact('categories', 'difficultCount'));
    }

    public function start(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'mode' => [
                'required',
                'in:training,exam,hardcore,category,wrong,unseen,difficult',
            ],
        ]);

        $mode = $data['mode'];

        if ($mode === 'category') {
            $questions = Question::with('answers')
                ->where('is_active', true)
                ->where('category_id', $data['category_id'])
                ->inRandomOrder()
                ->get();
        } elseif ($mode === 'training') {
            $questions = Question::with('answers')
                ->where('is_active', true)
                ->inRandomOrder()
                ->get();
        } elseif ($mode === 'exam') {
            $questions = Question::with('answers')
                ->where('is_active', true)
                ->inRandomOrder()
                ->limit(30)
                ->get();
        } elseif ($mode === 'wrong') {
            $wrongQuestionIds = QuestionStatistic::where('user_id', Auth::id())
                ->where('times_wrong', '>', 0)
                ->pluck('question_id');

            $questions = Question::with('answers')
                ->where('is_active', true)
                ->whereIn('id', $wrongQuestionIds)
                ->inRandomOrder()
                ->get();
        } elseif ($mode === 'unseen') {
            $seenQuestionIds = QuestionStatistic::where('user_id', Auth::id())
                ->pluck('question_id');

            $questions = Question::with('answers')
                ->where('is_active', true)
                ->whereNotIn('id', $seenQuestionIds)
                ->inRandomOrder()
                ->get();
        } elseif ($mode === 'difficult') {
            $difficultQuestionIds = DifficultQuestion::where(
                'user_id',
                Auth::id()
            )->pluck('question_id');

            $questions = Question::with('answers')
                ->where('is_active', true)
                ->whereIn('id', $difficultQuestionIds)
                ->inRandomOrder()
                ->get();
        } else {
            $weakQuestionIds = QuestionStatistic::where('user_id', Auth::id())
                ->where('times_wrong', '>', 0)
                ->orderByDesc('times_wrong')
                ->orderBy('current_streak')
                ->limit(20)
                ->pluck('question_id');

            $weakQuestions = Question::with('answers')
                ->where('is_active', true)
                ->whereIn('id', $weakQuestionIds)
                ->inRandomOrder()
                ->limit(20)
                ->get();

            $remaining = 30 - $weakQuestions->count();

            $randomQuestions = Question::with('answers')
                ->where('is_active', true)
                ->whereNotIn('id', $weakQuestions->pluck('id'))
                ->inRandomOrder()
                ->limit($remaining)
                ->get();

            $questions = $weakQuestions
                ->merge($randomQuestions)
                ->shuffle();
        }

        if ($questions->isEmpty()) {
            return back()->withErrors([
                'quiz' => 'Nu există întrebări disponibile pentru această selecție.',
            ]);
        }

        $session = QuizSession::create([
            'user_id' => Auth::id(),
            'category_id' => $data['category_id'] ?? null,
            'mode' => $mode,
            'score' => 0,
            'total_questions' => $questions->count(),
            'started_at' => now(),
        ]);

        foreach ($questions as $index => $question) {
            QuizSessionQuestion::create([
                'quiz_session_id' => $session->id,
                'question_id' => $question->id,
                'display_order' => $index + 1,
                'answer_order' => $question->answers
                    ->shuffle()
                    ->pluck('id')
                    ->values()
                    ->toArray(),
                'is_answered' => false,
            ]);
        }

        return redirect()->route('quiz.show', $session);
    }

    public function show(QuizSession $session)
    {
        abort_unless($session->user_id === Auth::id(), 403);

        $sessionQuestion = $session->sessionQuestions()
            ->where('is_answered', false)
            ->orderBy('display_order')
            ->with('question.answers')
            ->first();

        if (!$sessionQuestion) {
            $session->update([
                'completed_at' => $session->completed_at ?? now(),
            ]);

            return view('quiz.result', compact('session'));
        }

        $question = $sessionQuestion->question;

        $answers = $question->answers
            ->sortBy(
                fn ($answer) =>
                array_search($answer->id, $sessionQuestion->answer_order)
            )
            ->values();

        $isDifficult = DifficultQuestion::where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->exists();

        return view('quiz.show', compact(
            'session',
            'sessionQuestion',
            'question',
            'answers',
            'isDifficult'
        ));
    }

    public function answer(
        Request $request,
        QuizSession $session,
        QuizSessionQuestion $sessionQuestion
    ) {
        abort_unless($session->user_id === Auth::id(), 403);
        abort_unless(
            $sessionQuestion->quiz_session_id === $session->id,
            403
        );

        if ($sessionQuestion->is_answered) {
            return redirect()->route('quiz.show', $session);
        }

        $question = $sessionQuestion->question()
            ->with('answers')
            ->firstOrFail();

        $data = $request->validate([
            'answer_ids' => ['required', 'array', 'min:1'],
            'answer_ids.*' => [
                'integer',
                'exists:answers,id',
            ],
        ]);

        $selectedIds = array_map('intval', $data['answer_ids']);

        $validAnswerIds = $question->answers
            ->pluck('id')
            ->toArray();

        foreach ($selectedIds as $selectedId) {
            abort_unless(in_array($selectedId, $validAnswerIds), 422);
        }

        $correctIds = $question->answers()
            ->where('is_correct', true)
            ->pluck('id')
            ->sort()
            ->values()
            ->toArray();

        sort($selectedIds);

        $isCorrect = $selectedIds === $correctIds;

        $statistic = QuestionStatistic::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'question_id' => $question->id,
            ],
            [
                'times_seen' => 0,
                'times_correct' => 0,
                'times_wrong' => 0,
                'current_streak' => 0,
            ]
        );

        $statistic->increment('times_seen');

        if ($isCorrect) {
            $session->increment('score', 10);
            $statistic->increment('times_correct');
            $statistic->increment('current_streak');
        } else {
            $statistic->increment('times_wrong');

            $statistic->update([
                'current_streak' => 0,
            ]);
        }

        $statistic->update([
            'last_seen_at' => now(),
        ]);

        $sessionQuestion->update([
            'is_answered' => true,
        ]);

        if ($session->mode === 'exam') {
            return redirect()->route('quiz.show', $session);
        }

        $selectedAnswers = $question->answers()
            ->whereIn('id', $selectedIds)
            ->get();

        $correctAnswers = $question->answers()
            ->whereIn('id', $correctIds)
            ->get();

        $isDifficult = DifficultQuestion::where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->exists();

        return view('quiz.feedback', compact(
            'session',
            'sessionQuestion',
            'question',
            'selectedAnswers',
            'correctAnswers',
            'isCorrect',
            'isDifficult'
        ));
    }

    public function toggleDifficult(Question $question)
    {
        $existing = DifficultQuestion::where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with(
                'success',
                'Întrebarea a fost eliminată din lista de întrebări dificile.'
            );
        }

        DifficultQuestion::create([
            'user_id' => Auth::id(),
            'question_id' => $question->id,
        ]);

        return back()->with(
            'success',
            'Întrebarea a fost marcată ca dificilă.'
        );
    }

    public function abandon(QuizSession $session)
    {
        abort_unless($session->user_id === Auth::id(), 403);

        $session->update([
            'completed_at' => now(),
        ]);

        $session->sessionQuestions()
            ->where('is_answered', false)
            ->update([
                'is_answered' => true,
            ]);

        return redirect()
            ->route('quiz.index')
            ->withErrors([
                'quiz' => 'Ai renunțat la test.',
            ]);
    }
}
}
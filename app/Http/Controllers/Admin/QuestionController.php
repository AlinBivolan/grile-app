<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('category', 'answers')
            ->latest()
            ->get();

        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.questions.create', compact('categories'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'category_id' => ['required', 'exists:categories,id'],
        'image' => ['required', 'image', 'max:4096'],
        'explanation' => ['nullable', 'string'],
        'answer_type' => ['nullable'],
        'difficulty' => ['required', 'integer', 'min:1', 'max:5'],

        'answers' => ['required', 'array'],
        'answers.*.answer_text' => ['nullable', 'string'],
        'answers.*.is_correct' => ['nullable'],
        'answers.*.feedback' => ['nullable', 'string'],
    ]);

    $answers = collect($data['answers'])
        ->filter(fn ($answer) => !empty(trim($answer['answer_text'] ?? '')))
        ->values();

    if ($answers->count() < 3) {
        return back()
            ->withErrors(['answers' => 'Trebuie să introduci cel puțin 3 răspunsuri.'])
            ->withInput();
    }

    if ($answers->count() > 6) {
        return back()
            ->withErrors(['answers' => 'Poți introduce maximum 6 răspunsuri.'])
            ->withInput();
    }

    $correctCount = $answers
        ->filter(fn ($answer) => isset($answer['is_correct']))
        ->count();

    if ($correctCount < 1) {
        return back()
            ->withErrors(['answers' => 'Trebuie să marchezi cel puțin un răspuns corect.'])
            ->withInput();
    }

    $imagePath = $request->file('image')->store('questions', 'public');

    $question = Question::create([
        'category_id' => $data['category_id'],
        'image_path' => $imagePath,
        'explanation' => $data['explanation'] ?? null,
        'answer_type' => $data['multiple'],
        'difficulty' => $data['difficulty'],
        'is_active' => true,
    ]);

    foreach ($answers as $answerData) {
        Answer::create([
            'question_id' => $question->id,
            'answer_text' => $answerData['answer_text'],
            'is_correct' => isset($answerData['is_correct']),
            'feedback' => $answerData['feedback'] ?? null,
        ]);
    }

    return redirect()
        ->route('admin.questions.index')
        ->with('success', 'Întrebarea a fost creată.');
}

    public function edit(Question $question)
    {
        $question->load('answers');
        $categories = Category::orderBy('name')->get();

        return view('admin.questions.edit', compact('question', 'categories'));
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:4096'],
            'explanation' => ['nullable', 'string'],
            'answer_type' => ['nullable'],
            'difficulty' => ['required', 'integer', 'min:1', 'max:5'],
            'is_active' => ['nullable'],

            'answers' => ['required', 'array', 'min:2'],
            'answers.*.answer_text' => ['required', 'string'],
            'answers.*.is_correct' => ['nullable'],
            'answers.*.feedback' => ['nullable', 'string'],
        ]);

        $correctCount = collect($data['answers'])
            ->filter(fn ($answer) => isset($answer['is_correct']))
            ->count();

        if ($correctCount < 1) {
            return back()
                ->withErrors(['answers' => 'Trebuie să marchezi cel puțin un răspuns corect.'])
                ->withInput();
        }

        if ($data['answer_type'] === 'single' && $correctCount !== 1) {
            return back()
                ->withErrors(['answers' => 'Pentru întrebările single trebuie să existe exact un răspuns corect.'])
                ->withInput();
        }

        $updateData = [
            'category_id' => $data['category_id'],
            'explanation' => $data['explanation'] ?? null,
            'answer_type' => $data['multiple'],
            'difficulty' => $data['difficulty'],
            'is_active' => isset($data['is_active']),
        ];

        if ($request->hasFile('image')) {
            $updateData['image_path'] = $request->file('image')->store('questions', 'public');
        }

        $question->update($updateData);

        $question->answers()->delete();

        foreach ($data['answers'] as $answerData) {
            Answer::create([
                'question_id' => $question->id,
                'answer_text' => $answerData['answer_text'],
                'is_correct' => isset($answerData['is_correct']),
                'feedback' => $answerData['feedback'] ?? null,
            ]);
        }

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Întrebarea a fost modificată.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Întrebarea a fost ștearsă.');
    }
}
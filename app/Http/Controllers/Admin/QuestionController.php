<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('category', 'answers')->latest()->get();

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
            'difficulty' => ['required', 'integer', 'min:1', 'max:5'],
            'answers' => ['required', 'array'],
            'answers.*.answer_text' => ['nullable', 'string'],
            'answers.*.is_correct' => ['nullable'],
            'answers.*.feedback' => ['nullable', 'string'],
        ]);

        $answers = collect($data['answers'])
            ->filter(fn ($answer) => filled($answer['answer_text'] ?? null))
            ->values();

        if ($answers->count() < 3 || $answers->count() > 6) {
            return back()->withErrors([
                'answers' => 'Trebuie să introduci între 3 și 6 răspunsuri.',
            ])->withInput();
        }

        if ($answers->filter(fn ($answer) => isset($answer['is_correct']))->count() < 1) {
            return back()->withErrors([
                'answers' => 'Trebuie să marchezi cel puțin un răspuns corect.',
            ])->withInput();
        }

        $imagePath = $request->file('image')->store('questions', 'private');

        DB::transaction(function () use ($data, $answers, $imagePath): void {
            $question = Question::create([
                'category_id' => $data['category_id'],
                'image_path' => $imagePath,
                'explanation' => $data['explanation'] ?? null,
                'answer_type' => 'multiple',
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
        });

        return redirect()->route('admin.questions.index')
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
            'difficulty' => ['required', 'integer', 'min:1', 'max:5'],
            'is_active' => ['nullable', 'boolean'],
            'answers' => ['required', 'array'],
            'answers.*.answer_text' => ['nullable', 'string'],
            'answers.*.is_correct' => ['nullable'],
            'answers.*.feedback' => ['nullable', 'string'],
        ]);

        $answers = collect($data['answers'])
            ->filter(fn ($answer) => filled($answer['answer_text'] ?? null))
            ->values();

        if ($answers->count() < 3 || $answers->count() > 6) {
            return back()->withErrors([
                'answers' => 'Trebuie să introduci între 3 și 6 răspunsuri.',
            ])->withInput();
        }

        if ($answers->filter(fn ($answer) => isset($answer['is_correct']))->count() < 1) {
            return back()->withErrors([
                'answers' => 'Trebuie să marchezi cel puțin un răspuns corect.',
            ])->withInput();
        }

        $oldImagePath = $question->image_path;
        $newImagePath = null;
        $isActive = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('questions', 'private');
        }

        DB::transaction(function () use (
            $question,
            $data,
            $answers,
            $newImagePath,
            $isActive
        ): void {
            $updateData = [
                'category_id' => $data['category_id'],
                'explanation' => $data['explanation'] ?? null,
                'answer_type' => 'multiple',
                'difficulty' => $data['difficulty'],
                'is_active' => $isActive,
            ];

            if ($newImagePath) {
                $updateData['image_path'] = $newImagePath;
            }

            $question->update($updateData);
            $question->answers()->delete();

            foreach ($answers as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => isset($answerData['is_correct']),
                    'feedback' => $answerData['feedback'] ?? null,
                ]);
            }
        });

        if ($newImagePath && $oldImagePath) {
            Storage::disk('private')->delete($oldImagePath);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Întrebarea a fost modificată.');
    }

    public function destroy(Question $question)
    {
        $imagePath = $question->image_path;
        $question->delete();

        if ($imagePath) {
            Storage::disk('private')->delete($imagePath);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Întrebarea a fost ștearsă.');
    }
}

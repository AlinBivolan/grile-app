<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $questions = Question::with(['category', 'answers'])
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where(
                    'category_id',
                    $request->integer('category_id')
                )
            )
            ->when(
                $request->filled('status'),
                function ($query) use ($request) {
                    if ($request->string('status')->toString() === 'active') {
                        $query->where('is_active', true);
                    }

                    if ($request->string('status')->toString() === 'inactive') {
                        $query->where('is_active', false);
                    }
                }
            )
            ->latest()
            ->get();

        return view('admin.questions.index', compact('questions', 'categories'));
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
            'answers.*.is_correct' => ['nullable', 'boolean'],
            'answers.*.feedback' => ['nullable', 'string'],
        ]);

        $answers = $this->cleanAnswers($data['answers']);
        $this->validateAnswerCollection($answers);

        $imagePath = $request->file('image')->store('questions', 'private');

        try {
            DB::transaction(function () use ($data, $answers, $imagePath): void {
                $question = new Question();
                $question->forceFill([
                    'category_id' => $data['category_id'],
                    'image_path' => $imagePath,
                    'explanation' => $data['explanation'] ?? null,
                    'answer_type' => 'multiple',
                    'difficulty' => $data['difficulty'],
                    'is_active' => true,
                ]);
                $question->saveOrFail();

                foreach ($answers as $answerData) {
                    $answer = new Answer();
                    $answer->forceFill([
                        'question_id' => $question->id,
                        'answer_text' => $answerData['answer_text'],
                        'is_correct' => $answerData['is_correct'],
                        'feedback' => $answerData['feedback'],
                    ]);
                    $answer->saveOrFail();
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('private')->delete($imagePath);
            throw $exception;
        }

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Întrebarea a fost creată.');
    }

    public function edit(Question $question)
    {
        $question->load([
            'answers' => fn ($query) => $query->orderBy('id'),
        ]);

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
            'answers.*.id' => [
                'nullable',
                'integer',
                Rule::exists('answers', 'id')->where(
                    fn ($query) => $query->where('question_id', $question->id)
                ),
            ],
            'answers.*.answer_text' => ['nullable', 'string'],
            'answers.*.is_correct' => ['nullable', 'boolean'],
            'answers.*.feedback' => ['nullable', 'string'],
        ]);

        $answers = $this->cleanAnswers($data['answers']);
        $this->validateAnswerCollection($answers);

        $oldImagePath = $question->image_path;
        $newImagePath = null;

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('questions', 'private');
        }

        try {
            DB::transaction(function () use ($question, $data, $answers, $newImagePath, $request): void {
                $question->forceFill([
                    'category_id' => $data['category_id'],
                    'explanation' => $data['explanation'] ?? null,
                    'answer_type' => 'multiple',
                    'difficulty' => $data['difficulty'],
                    'is_active' => $request->boolean('is_active'),
                ]);

                if ($newImagePath !== null) {
                    $question->image_path = $newImagePath;
                }

                $question->saveOrFail();

                $keptAnswerIds = [];

                foreach ($answers as $answerData) {
                    if ($answerData['id']) {
                        $answer = $question->answers()->whereKey($answerData['id'])->firstOrFail();
                    } else {
                        $answer = new Answer();
                        $answer->question_id = $question->id;
                    }

                    $answer->forceFill([
                        'answer_text' => $answerData['answer_text'],
                        'is_correct' => $answerData['is_correct'],
                        'feedback' => $answerData['feedback'],
                    ]);
                    $answer->saveOrFail();
                    $keptAnswerIds[] = $answer->id;
                }

                $question->answers()->whereNotIn('id', $keptAnswerIds)->delete();
            });
        } catch (\Throwable $exception) {
            if ($newImagePath !== null) {
                Storage::disk('private')->delete($newImagePath);
            }
            throw $exception;
        }

        if ($newImagePath !== null && $oldImagePath && $oldImagePath !== $newImagePath) {
            Storage::disk('private')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.questions.edit', $question)
            ->with('success', 'Modificările au fost salvate.');
    }

    public function destroy(Question $question)
    {
        $imagePath = $question->image_path;

        DB::transaction(function () use ($question): void {
            $question->answers()->delete();
            $question->delete();
        });

        if ($imagePath) {
            Storage::disk('private')->delete($imagePath);
        }

        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Întrebarea a fost ștearsă.');
    }

    private function cleanAnswers(array $answers)
    {
        return collect($answers)
            ->filter(fn ($answer) => filled($answer['answer_text'] ?? null))
            ->map(function ($answer) {
                return [
                    'id' => isset($answer['id']) ? (int) $answer['id'] : null,
                    'answer_text' => trim($answer['answer_text']),
                    'is_correct' => isset($answer['is_correct']),
                    'feedback' => filled($answer['feedback'] ?? null)
                        ? trim($answer['feedback'])
                        : null,
                ];
            })
            ->values();
    }

    private function validateAnswerCollection($answers): void
    {
        $errors = [];

        if ($answers->count() < 3 || $answers->count() > 9) {
            $errors['answers'] = 'Trebuie să introduci între 3 și 9 răspunsuri.';
        }

        if ($answers->where('is_correct', true)->isEmpty()) {
            $errors['correct_answer'] = 'Trebuie să marchezi cel puțin un răspuns corect.';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }
}

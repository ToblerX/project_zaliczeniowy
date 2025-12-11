<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizBuilderController extends Controller
{
    public function create()
    {
        $this->authorizeAdmin(auth()->user());

        return view('quizzes.builder');
    }

    public function index()
    {
        $this->authorizeAdmin(auth()->user());

        $quizzes = Quiz::withCount('questions')->orderBy('created_at', 'desc')->get();

        return view('quizzes.builder_index', [
            'quizzes' => $quizzes,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request->user());

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.text' => ['required', 'string'],
            'questions.*.correct_answer' => ['required', 'string'],
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        foreach ($validated['questions'] as $questionData) {
            Question::create([
                'quiz_id' => $quiz->id,
                'text' => $questionData['text'],
                'correct_answer' => $questionData['correct_answer'],
            ]);
        }

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('status', 'Quiz created successfully.');
    }

    public function edit(Quiz $quiz)
    {
        $this->authorizeAdmin(auth()->user());

        $quiz->load('questions');

        return view('quizzes.builder', [
            'quiz' => $quiz,
        ]);
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorizeAdmin($request->user());

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.text' => ['required', 'string'],
            'questions.*.correct_answer' => ['required', 'string'],
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        // For simplicity replace all existing questions with submitted ones
        $quiz->questions()->delete();
        foreach ($validated['questions'] as $questionData) {
            $quiz->questions()->create([
                'text' => $questionData['text'],
                'correct_answer' => $questionData['correct_answer'],
            ]);
        }

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('status', 'Quiz updated successfully.');
    }

    protected function authorizeAdmin($user): void
    {
        if (! $user || ! $user->is_admin) {
            abort(403);
        }
    }
}

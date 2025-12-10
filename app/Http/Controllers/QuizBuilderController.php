<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizBuilderController extends Controller
{
    public function index()
    {
        return view('quizzes.builder');
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

    protected function authorizeAdmin($user): void
    {
        if (! $user || ! $user->is_admin) {
            abort(403);
        }
    }
}

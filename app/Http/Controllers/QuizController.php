<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::all();

        return view('quizzes.index', compact('quizzes'));
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions');

        return view('quizzes.show', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $quiz->load('questions');

        $validated = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'string'],
        ]);

        $answersInput = $validated['answers'];

        $totalQuestions = $quiz->questions->count();
        $correctCount = 0;
        $perQuestionResults = [];

        foreach ($quiz->questions as $question) {
            if (! isset($answersInput[$question->id])) {
                continue;
            }

            $given = $answersInput[$question->id];

            // Store the answer
            $answer = new Answer();
            $answer->question_id = $question->id;
            $answer->answer = $given;

            if (auth()->check()) {
                $answer->user_id = auth()->id();
            }

            $answer->save();

            // Check correctness when a correct_answer is defined
            if (! is_null($question->correct_answer)) {
                $isCorrect = trim(mb_strtolower($given)) === trim(mb_strtolower($question->correct_answer));

                $perQuestionResults[$question->id] = $isCorrect;

                if ($isCorrect) {
                    $correctCount++;
                }
            } else {
                $perQuestionResults[$question->id] = null;
            }
        }
        // Store best result per user
        if (auth()->check()) {
            $user = auth()->user();

            $result = QuizResult::firstOrNew([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
            ]);

            $result->total_questions = $totalQuestions;

            if (is_null($result->best_correct) || $correctCount > $result->best_correct) {
                $result->best_correct = $correctCount;
            }

            $result->save();
        }

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with([
                'result_total' => $totalQuestions,
                'result_correct' => $correctCount,
                'result_answers' => $answersInput,
                'result_per_question' => $perQuestionResults,
            ]);
    }
}

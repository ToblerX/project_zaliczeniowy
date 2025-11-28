<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function show(Quiz $quiz, Question $question)
    {
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        return view('questions.show', compact('quiz', 'question'));
    }

    public function answer(Request $request, Quiz $quiz, Question $question)
    {
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $validated = $request->validate([
            'answer' => ['required', 'string'],
        ]);

        $answer = new Answer();
        $answer->question_id = $question->id;
        $answer->answer = $validated['answer'];

        if (auth()->check()) {
            $answer->user_id = auth()->id();
        }

        $answer->save();

        $isCorrect = null;

        if (! is_null($question->correct_answer)) {
            $isCorrect = trim(mb_strtolower($validated['answer'])) === trim(mb_strtolower($question->correct_answer));
        }

        return redirect()
            ->route('questions.show', [$quiz, $question])
            ->with([
                'status' => $isCorrect === true
                    ? 'Correct! Well done.'
                    : ($isCorrect === false
                        ? 'Incorrect answer. Please try again.'
                        : 'Answer submitted.'),
                'is_correct' => $isCorrect,
            ]);
    }
}

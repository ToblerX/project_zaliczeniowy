<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // First sample quiz
        $quiz1 = Quiz::create([
            'title' => 'Basic PHP Quiz',
            'description' => 'Test your basic PHP knowledge.',
        ]);

        $questions1 = [
            [
                'text' => 'What does PHP stand for?',
                'correct_answer' => 'Hypertext Preprocessor',
            ],
            [
                'text' => 'Which symbol is used to start a variable in PHP?',
                'correct_answer' => '$',
            ],
            [
                'text' => 'Which function is used to output text in PHP?',
                'correct_answer' => 'echo',
            ],
        ];

        foreach ($questions1 as $data) {
            Question::create([
                'quiz_id' => $quiz1->id,
                'text' => $data['text'],
                'correct_answer' => $data['correct_answer'],
            ]);
        }

        // Second sample quiz
        $quiz2 = Quiz::create([
            'title' => 'Laravel Basics Quiz',
            'description' => 'Check what you know about Laravel.',
        ]);

        $questions2 = [
            [
                'text' => 'Which artisan command creates a new controller?',
                'correct_answer' => 'php artisan make:controller',
            ],
            [
                'text' => 'Where are Laravel routes for web requests defined?',
                'correct_answer' => 'routes/web.php',
            ],
            [
                'text' => 'Which file stores the main database configuration?',
                'correct_answer' => '.env',
            ],
        ];

        foreach ($questions2 as $data) {
            Question::create([
                'quiz_id' => $quiz2->id,
                'text' => $data['text'],
                'correct_answer' => $data['correct_answer'],
            ]);
        }
    }
}

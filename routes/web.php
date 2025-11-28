<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;

// Public home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth scaffolding (login, register, etc.)
require __DIR__.'/auth.php';

// Dashboard from Breeze, extended with quiz results
Route::get('/dashboard', function () {
    $user = auth()->user();

    $quizzes = \App\Models\Quiz::with(['results' => function ($query) use ($user) {
        $query->where('user_id', $user->id);
    }])->get();

    return view('dashboard', [
        'quizzes' => $quizzes,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Quiz taking (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');

    // Submit all answers for a quiz (must be logged in so best score can be stored)
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])
        ->name('quizzes.submit');
});

// Breeze-style profile management (used by navigation.blade links)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student profile with best quiz results (separate page)
Route::get('/student-profile', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->name('profile.show');

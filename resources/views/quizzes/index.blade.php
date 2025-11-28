<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quizzes</title>
</head>
<body>
    <h1>Quiz list</h1>

    <ul>
        @forelse ($quizzes as $quiz)
            <li>
                <a href="{{ route('quizzes.show', $quiz) }}">{{ $quiz->title }}</a>
            </li>
        @empty
            <li>No quizzes available.</li>
        @endforelse
    </ul>

    <p><a href="{{ route('home') }}">Back to home</a></p>
</body>
</html>

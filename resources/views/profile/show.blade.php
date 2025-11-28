<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student profile</title>
</head>
<body>
    <h1>Student profile</h1>

    <p>Logged in as: <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>

    <h2>Your quiz results</h2>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Best score</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($quizzes as $quiz)
                @php
                    $result = $quiz->results->first();
                @endphp
                <tr>
                    <td>{{ $quiz->title }}</td>
                    <td>
                        @if ($result)
                            {{ $result->best_correct }} / {{ $result->total_questions }}
                        @else
                            Not attempted
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No quizzes available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p><a href="{{ route('quizzes.index') }}">Back to quizzes</a></p>
</body>
</html>

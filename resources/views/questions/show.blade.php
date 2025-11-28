<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Question</title>
</head>
<body>
    <h1>{{ $quiz->title }} - Question</h1>

    <p>{{ $question->text }}</p>

    {{-- Correct / incorrect / generic status --}}
    @if (session('status'))
        @php $isCorrect = session('is_correct'); @endphp
        <p style="color: {{ $isCorrect === false ? 'red' : 'green' }};">
            {{ session('status') }}
        </p>
    @endif

    {{-- Validation errors (e.g. empty answer) --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('questions.answer', [$quiz, $question]) }}">
        @csrf
        <label for="answer">Your answer:</label>
        <input
            type="text"
            id="answer"
            name="answer"
            value="{{ old('answer') }}"
            required
        >
        @error('answer')
            <div style="color: red;">{{ $message }}</div>
        @enderror

        <button type="submit">Submit</button>
    </form>

    <p><a href="{{ route('dashboard') }}">Back to dashboard</a></p>
</body>
</html>
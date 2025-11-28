<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    @if ($quiz->description)
                        <p class="text-gray-700">{{ $quiz->description }}</p>
                    @endif

                    @if (session('result_total'))
                        <div class="rounded-md bg-blue-50 border border-blue-200 p-4 text-sm text-blue-800">
                            {{ __('You answered :correct out of :total questions correctly.', [
                                'correct' => session('result_correct'),
                                'total' => session('result_total'),
                            ]) }}
                        </div>
                    @endif

                    <div>
                        <h3 class="font-semibold text-lg mb-3">{{ __('Questions') }}</h3>

                        @if ($errors->any())
                            <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($quiz->questions->isEmpty())
                            <p class="text-gray-500">{{ __('No questions for this quiz yet.') }}</p>
                        @else
                            @php
                                $submittedAnswers = session('result_answers', []);
                                $perQuestion = session('result_per_question', []);
                            @endphp

                            <form method="POST" action="{{ route('quizzes.submit', $quiz) }}" class="space-y-4">
                                @csrf

                                <ol class="space-y-3 list-decimal list-inside">
                                    @foreach ($quiz->questions as $question)
                                        @php
                                            $isCorrect = $perQuestion[$question->id] ?? null;
                                            $baseClasses = 'rounded-md border p-4';
                                            $stateClasses = $isCorrect === true
                                                ? 'border-green-300 bg-green-50'
                                                : ($isCorrect === false
                                                    ? 'border-red-300 bg-red-50'
                                                    : 'border-gray-200 bg-white');

                                            $value = old('answers.' . $question->id,
                                                $submittedAnswers[$question->id] ?? '');
                                        @endphp

                                        <li>
                                            <div class="{{ $baseClasses }} {{ $stateClasses }} space-y-2">
                                                <p class="font-medium">{{ $question->text }}</p>

                                                <input
                                                    type="text"
                                                    name="answers[{{ $question->id }}]"
                                                    value="{{ $value }}"
                                                    required
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                >
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>

                                <div class="flex items-center justify-between pt-4">
                                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                        &larr; {{ __('Back to dashboard') }}
                                    </a>

                                    <x-primary-button>
                                        {{ __('Submit all answers') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

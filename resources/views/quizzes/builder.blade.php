<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quiz builder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <p class="text-sm text-gray-600">
                        {{ __('Create a new quiz by providing its name, optional description, and one or more questions with correct answers.') }}
                    </p>

                    @if (session('status'))
                        <div class="rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-md bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('quiz-builder.store') }}" id="quiz-builder-form" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Quiz name') }}</label>
                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="{{ old('title') }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            >
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description (optional)') }}</label>
                            <textarea
                                name="description"
                                id="description"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            >{{ old('description') }}</textarea>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-lg">{{ __('Questions') }}</h3>
                            </div>

                            <div id="questions-container" class="space-y-4 mb-4">
                                @php
                                    $oldQuestions = old('questions', [
                                        ['text' => '', 'correct_answer' => ''],
                                    ]);
                                @endphp

                                @foreach ($oldQuestions as $index => $q)
                                    <div class="rounded-md border border-gray-200 p-4 space-y-3 question-item">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">{{ __('Question text') }}</label>
                                            <input
                                                type="text"
                                                name="questions[{{ $index }}][text]"
                                                value="{{ $q['text'] ?? '' }}"
                                                required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                            >
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">{{ __('Correct answer') }}</label>
                                            <input
                                                type="text"
                                                name="questions[{{ $index }}][correct_answer]"
                                                value="{{ $q['correct_answer'] ?? '' }}"
                                                required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                            >
                                        </div>

                                        <button
                                            type="button"
                                            class="text-xs text-red-600 hover:text-red-800 remove-question"
                                        >
                                            {{ __('Remove this question') }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-2">
                                <button
                                    type="button"
                                    id="add-question"
                                    class="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                >
                                    {{ __('Add question') }}
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                &larr; {{ __('Back to dashboard') }}
                            </a>

                            <x-primary-button>
                                {{ __('Save quiz') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('questions-container');
            const addButton = document.getElementById('add-question');

            let index = container.querySelectorAll('.question-item').length;

            function addQuestion(text = '', correctAnswer = '') {
                const wrapper = document.createElement('div');
                wrapper.className = 'rounded-md border border-gray-200 p-4 space-y-3 mt-4 question-item';
                wrapper.innerHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Question text') }}</label>
                        <input
                            type="text"
                            name="questions[${index}][text]"
                            value="${text}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Correct answer') }}</label>
                        <input
                            type="text"
                            name="questions[${index}][correct_answer]"
                            value="${correctAnswer}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                    </div>

                    <button
                        type="button"
                        class="text-xs text-red-600 hover:text-red-800 remove-question"
                    >
                        {{ __('Remove this question') }}
                    </button>
                `;

                container.appendChild(wrapper);
                index++;
            }

            addButton?.addEventListener('click', () => {
                addQuestion();
            });

            container.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-question')) {
                    const item = event.target.closest('.question-item');
                    if (item) {
                        item.remove();
                    }
                }
            });
        });
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage quizzes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">
                                {{ __('Create, view and edit quizzes. Only admins can access this page.') }}
                            </p>
                        </div>
                        <a
                            href="{{ route('quiz-builder.create') }}"
                            class="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            {{ __('Create new quiz') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700">{{ __('Title') }}</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700">{{ __('Questions') }}</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700">{{ __('Created at') }}</th>
                                    <th class="px-4 py-2 text-right font-semibold text-gray-700">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($quizzes as $quiz)
                                    <tr>
                                        <td class="px-4 py-2">
                                            <div class="font-medium text-gray-900">{{ $quiz->title }}</div>
                                            @if($quiz->description)
                                                <div class="text-xs text-gray-500 line-clamp-1">{{ $quiz->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-gray-700">
                                            {{ $quiz->questions_count }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-500">
                                            {{ $quiz->created_at->format('Y-m-d') }}
                                        </td>
                                        <td class="px-4 py-2 text-right space-x-2">
                                            <a
                                                href="{{ route('quizzes.show', $quiz) }}"
                                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                                            >
                                                {{ __('View') }}
                                            </a>
                                            <a
                                                href="{{ route('quiz-builder.edit', $quiz) }}"
                                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                                            >
                                                {{ __('Edit') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                            {{ __('No quizzes found. Create your first quiz.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

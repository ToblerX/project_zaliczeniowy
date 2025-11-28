<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <p>{{ __("You're logged in!") }}</p>

                    <div>
                        <h3 class="font-semibold text-lg mb-2">{{ __('Your quiz results') }}</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="border-b font-medium">
                                    <tr>
                                        <th class="px-4 py-2">{{ __('Quiz') }}</th>
                                        <th class="px-4 py-2">{{ __('Best score') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($quizzes as $quiz)
                                        @php
                                            $result = $quiz->results->first();
                                        @endphp
                                        <tr class="border-b">
                                            <td class="px-4 py-2">
                                                <a href="{{ route('quizzes.show', $quiz) }}" class="text-blue-600 hover:underline">
                                                    {{ $quiz->title }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-2">
                                                @if ($result)
                                                    {{ $result->best_correct }} / {{ $result->total_questions }}
                                                @else
                                                    {{ __('Not attempted') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-2 text-gray-500">
                                                {{ __('No quizzes available.') }}
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
    </div>
</x-app-layout>

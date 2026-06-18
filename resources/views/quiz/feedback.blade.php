<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rezultatul răspunsului
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($isCorrect)
                    <div class="mb-6 p-6 bg-green-100 border-2 border-green-500 rounded-lg">
                        <div class="text-2xl font-bold text-green-800">
                            ✓ Răspuns corect
                        </div>
                        <div class="mt-1 text-green-700">
                            Ai primit 10 puncte.
                        </div>
                    </div>
                @else
                    <div class="mb-6 p-6 bg-red-100 border-2 border-red-500 rounded-lg">
                        <div class="text-2xl font-bold text-red-800">
                            ✗ Răspuns greșit
                        </div>
                        <div class="mt-1 text-red-700">
                            Mai jos vezi răspunsul tău și răspunsul corect.
                        </div>
                    </div>
                @endif

                <div class="mb-6">
                    <img
                        src="{{ route('questions.image', $question) }}"
                        alt="Imagine întrebare"
                        class="w-full border rounded">
                </div>

                <div class="mb-6">
                    <h3 class="font-bold text-lg mb-3">Răspunsul tău</h3>

                    @foreach ($selectedAnswers as $answer)
                        <div class="border-2 rounded p-4 mb-3
                            {{ $answer->is_correct
                                ? 'border-green-500 bg-green-50'
                                : 'border-red-500 bg-red-50' }}">

                            <div class="font-semibold">
                                {{ $answer->answer_text }}
                            </div>

                            <div class="mt-2 font-bold
                                {{ $answer->is_correct ? 'text-green-700' : 'text-red-700' }}">
                                {{ $answer->is_correct
                                    ? '✓ Această variantă este corectă.'
                                    : '✗ Această variantă este greșită.' }}
                            </div>

                            @if ($answer->feedback)
                                <div class="mt-3 text-sm text-gray-700">
                                    {{ $answer->feedback }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @unless ($isCorrect)
                    <div class="mb-6">
                        <h3 class="font-bold text-lg mb-3">Răspunsul corect</h3>

                        @foreach ($correctAnswers as $answer)
                            <div class="border-2 border-green-500 bg-green-50 rounded p-4 mb-3">
                                <div class="font-semibold text-green-900">
                                    ✓ {{ $answer->answer_text }}
                                </div>

                                @if ($answer->feedback)
                                    <div class="mt-3 text-sm text-green-800">
                                        {{ $answer->feedback }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endunless

                @if ($question->explanation)
                    <div class="mb-6 p-5 bg-blue-50 border border-blue-300 rounded">
                        <h3 class="font-bold text-lg mb-2">Explicație</h3>
                        <p class="whitespace-pre-line">{{ $question->explanation }}</p>
                    </div>
                @endif

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('quiz.show', $session) }}"
                       class="px-5 py-3 bg-blue-600 text-white rounded font-bold">
                        Următoarea întrebare
                    </a>

                    <form method="POST"
                          action="{{ route('questions.difficult', $question) }}">
                        @csrf

                        <button class="px-5 py-3 rounded font-bold
                            {{ $isDifficult
                                ? 'bg-gray-600 text-white'
                                : 'bg-purple-600 text-white' }}">
                            {{ $isDifficult
                                ? 'Elimină din dificile'
                                : '⭐ Marchează ca dificilă' }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

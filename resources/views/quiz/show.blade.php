<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Întrebarea {{ $sessionQuestion->display_order }} / {{ $session->total_questions }}
        </h2>
    </x-slot>

    @php
        $progress = round(($sessionQuestion->display_order / $session->total_questions) * 100);

        $modeLabels = [
            'training' => 'Mod antrenament',
            'exam' => 'Mod examen',
            'hardcore' => 'Mod hardcore',
            'category' => 'Categorie',
            'wrong' => 'Întrebări greșite',
            'unseen' => 'Întrebări nevăzute',
            'difficult' => 'Întrebări dificile',
        ];
    @endphp

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                <div class="mb-5">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>{{ $modeLabels[$session->mode] ?? 'Quiz' }}</span>
                        <span>{{ $progress }}%</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded h-3 overflow-hidden">
                        <div
                            class="bg-blue-600 h-3 rounded transition-all duration-300"
                            style="width: {{ $progress }}%">
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <img
                        src="{{ asset('storage/' . $question->image_path) }}"
                        alt="Întrebarea {{ $sessionQuestion->display_order }}"
                        class="w-full border rounded">
                </div>

                <div class="mb-4 p-3 bg-blue-50 text-blue-800 rounded text-sm">
                    Poți selecta una sau mai multe variante de răspuns.
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('quiz.answer', [$session, $sessionQuestion]) }}"
                    id="answer-form">
                    @csrf

                    <div class="space-y-3">
                        @foreach ($answers as $index => $answer)
                            <label
                                class="answer-option block border rounded p-4 cursor-pointer transition">
                                <div class="flex gap-3 items-start">
                                    <input
                                        type="checkbox"
                                        name="answer_ids[]"
                                        value="{{ $answer->id }}"
                                        class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                    <div class="answer-text">
                                        <span class="font-bold">
                                            {{ chr(65 + $index) }}.
                                        </span>

                                        <span>
                                            {{ $answer->answer_text }}
                                        </span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button
                            type="submit"
                            id="submit-answer"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded disabled:opacity-50">
                            {{ $session->mode === 'exam' ? 'Trimite răspunsul' : 'Verifică răspunsul' }}
                        </button>
                    </div>
                </form>

                <form
                    method="POST"
                    action="{{ route('quiz.abandon', $session) }}"
                    class="mt-3"
                    onsubmit="return confirm('Sigur vrei să renunți la test?')">
                    @csrf

                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                        Renunță la test
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('answer-form');
            const submitButton = document.getElementById('submit-answer');

            if (!form || !submitButton) {
                return;
            }

            form.addEventListener('submit', function () {
                submitButton.disabled = true;
                submitButton.textContent = 'Se trimite...';
            });
        });
    </script>
</x-app-layout>
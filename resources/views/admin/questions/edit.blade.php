<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Editează întrebarea #{{ $question->id }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Modifică imaginea, setările și variantele de răspuns.
                </p>
            </div>

            <a
                href="{{ route('admin.questions.index') }}"
                class="inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700"
            >
                Înapoi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-5 sm:p-7">

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-300 bg-red-100 p-4 text-red-800">
                        <div class="font-bold mb-2">
                            Nu s-au putut salva modificările:
                        </div>

                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('admin.questions.update', $question) }}"
                    enctype="multipart/form-data"
                    id="edit-question-form"
                >
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block font-medium mb-2">
                                Categorie
                            </label>

                            <select
                                name="category_id"
                                class="w-full rounded-lg border-gray-300"
                                required
                            >
                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id', $question->category_id) == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium mb-2">
                                Dificultate
                            </label>

                            <input
                                type="number"
                                name="difficulty"
                                value="{{ old('difficulty', $question->difficulty) }}"
                                min="1"
                                max="5"
                                class="w-full rounded-lg border-gray-300"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center gap-3 rounded-lg border p-4 cursor-pointer">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                @checked(old('is_active', $question->is_active))
                            >

                            <div>
                                <div class="font-semibold">
                                    Întrebare activă
                                </div>

                                <div class="text-sm text-gray-500">
                                    Întrebările inactive nu apar în quiz-uri.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-2">
                            Imagine curentă
                        </label>

                        <a
                            href="{{ route('questions.image', $question) }}"
                            target="_blank"
                            class="block"
                        >
                            <img
                                src="{{ route('questions.image', $question) }}"
                                alt="Imaginea întrebării"
                                class="w-full max-h-[520px] object-contain rounded-xl border bg-white p-2"
                            >
                        </a>

                        <label class="block font-medium mt-4 mb-2">
                            Înlocuiește imaginea, opțional
                        </label>

                        <input
                            type="file"
                            name="image"
                            accept="image/*"
                            class="block w-full rounded-lg border border-gray-300 p-2"
                        >

                        <p class="mt-2 text-sm text-gray-500">
                            Dacă nu alegi altă imagine, cea actuală rămâne neschimbată.
                        </p>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-2">
                            Explicație generală, opțională
                        </label>

                        <textarea
                            name="explanation"
                            rows="4"
                            class="auto-diacritics auto-diacritics w-full rounded-lg border-gray-300"
                            placeholder="Explicația afișată după răspuns..."
                        >{{ old('explanation', $question->explanation) }}</textarea>
                    </div>

                    <div class="border-t pt-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h3 class="font-bold text-xl">
                                    Variante de răspuns
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Completează între 3 și 9 variante și marchează cel puțin una ca fiind corectă.
                                </p>
                            </div>

                            <div class="text-sm rounded-lg bg-blue-50 border border-blue-200 px-3 py-2">
                                Exemple: <code>\(N^2\)</code>,
                                <code>\(2^N\)</code>,
                                <code>\(a_i\)</code>,
                                <code>\(T_{max}\)</code>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @for ($i = 0; $i < 9; $i++)
                                @php
                                    $answer = $question->answers->get($i);
                                    $answerText = old("answers.$i.answer_text", $answer?->answer_text);
                                    $answerFeedback = old("answers.$i.feedback", $answer?->feedback);
                                    $isCorrect = old("answers.$i.is_correct", $answer?->is_correct);
                                @endphp

                                <div class="answer-card rounded-xl border p-4 sm:p-5">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                                        <div class="font-bold">
                                            Varianta {{ chr(65 + $i) }}
                                        </div>

                                        <label class="inline-flex items-center gap-2 rounded-lg border px-3 py-2 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="answers[{{ $i }}][is_correct]"
                                                value="1"
                                                class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                                @checked($isCorrect)
                                            >

                                            <span class="font-medium">
                                                Răspuns corect
                                            </span>
                                        </label>
                                    </div>

                                    <textarea
                                        name="answers[{{ $i }}][answer_text]"
                                        rows="2"
                                        class="auto-diacritics auto-diacritics w-full rounded-lg border-gray-300"
                                        placeholder="Scrie varianta de răspuns..."
                                    >{{ $answerText }}</textarea>

                                    <details class="mt-3">
                                        <summary class="cursor-pointer select-none text-sm font-semibold text-blue-600">
                                            Feedback opțional
                                            @if ($answerFeedback)
                                                <span class="ml-1 text-green-600">• completat</span>
                                            @endif
                                        </summary>

                                        <div class="mt-3">
                                            <textarea
                                                name="answers[{{ $i }}][feedback]"
                                                rows="2"
                                                class="auto-diacritics auto-diacritics w-full rounded-lg border-gray-300"
                                                placeholder="Explică de ce această variantă este corectă sau greșită..."
                                            >{{ $answerFeedback }}</textarea>
                                        </div>
                                    </details>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="sticky bottom-0 mt-8 -mx-5 sm:-mx-7 -mb-5 sm:-mb-7 rounded-b-xl border-t bg-white/95 p-4 sm:px-7 backdrop-blur">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <a
                                href="{{ route('admin.questions.index') }}"
                                class="rounded-lg bg-gray-600 px-5 py-3 text-center font-semibold text-white hover:bg-gray-700"
                            >
                                Renunță
                            </a>

                            <button
                                type="submit"
                                id="save-question-button"
                                class="rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
                            >
                                Salvează modificările
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('edit-question-form');
            const saveButton = document.getElementById('save-question-button');

            const fixRomanianText = function (value) {
                return value
                    .replace(/,s/g, 'ș')
                    .replace(/,S/g, 'Ș')
                    .replace(/,t/g, 'ț')
                    .replace(/,T/g, 'Ț')
                    .replace(/ş/g, 'ș')
                    .replace(/Ş/g, 'Ș')
                    .replace(/ţ/g, 'ț')
                    .replace(/Ţ/g, 'Ț');
            };

            document.querySelectorAll('.auto-diacritics').forEach(function (field) {
                field.addEventListener('paste', function () {
                    window.setTimeout(function () {
                        field.value = fixRomanianText(field.value);
                    }, 0);
                });
            });

            if (form && saveButton) {
                form.addEventListener('submit', function () {
                    saveButton.disabled = true;
                    saveButton.textContent = 'Se salvează...';
                });
            }
        });
    </script>
</x-app-layout>

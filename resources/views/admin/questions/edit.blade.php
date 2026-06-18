<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editează întrebare
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc pl-5">
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
                >
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Categorie
                        </label>

                        <select
                            name="category_id"
                            class="w-full border-gray-300 rounded"
                        >
                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        old('category_id', $question->category_id)
                                        == $category->id
                                    )
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Imagine curentă
                        </label>

                        <img
                            src="{{ route('questions.image', $question) }}"
                            alt="Imagine curentă"
                            class="w-full max-w-2xl border rounded mb-4"
                        >

                        <label class="block font-medium mb-1">
                            Schimbă imaginea
                        </label>

                        <input
                            type="file"
                            name="image"
                            accept="image/*"
                            class="w-full"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Dificultate
                        </label>

                        <input
                            type="number"
                            name="difficulty"
                            value="{{ old('difficulty', $question->difficulty) }}"
                            min="1"
                            max="5"
                            class="w-full border-gray-300 rounded"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', $question->is_active))
                            >

                            Întrebare activă
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Explicație generală, opțională
                        </label>

                        <textarea
                            name="explanation"
                            rows="4"
                            class="auto-diacritics w-full border-gray-300 rounded"
                        >{{ old('explanation', $question->explanation) }}</textarea>
                    </div>

                    <h3 class="font-bold text-lg mt-6 mb-2">
                        Răspunsuri
                    </h3>

                    <p class="text-sm text-gray-500 mb-4">
                        Completează între 3 și 9 variante. Variantele nefolosite
                        pot rămâne goale.
                    </p>

                    <div class="mb-5 p-4 bg-blue-50 border border-blue-300 rounded">
                        <div class="font-bold mb-2">
                            Formule, puteri și indici
                        </div>

                        <p class="text-sm mb-2">
                            Pune formulele între
                            <code>\(</code>
                            și
                            <code>\)</code>.
                        </p>

                        <div class="text-sm space-y-1">
                            <div>Putere: <code>\(N^2\)</code></div>
                            <div>Putere cu literă: <code>\(2^N\)</code></div>
                            <div>Indice: <code>\(a_i\)</code></div>
                            <div>Indice lung: <code>\(T_{max}\)</code></div>
                            <div>Formulă: <code>\(N \log N\)</code></div>
                        </div>
                    </div>

                    @for ($i = 0; $i < 9; $i++)
                        @php
                            $answer = $question->answers->get($i);
                            $feedbackValue = old(
                                "answers.$i.feedback",
                                $answer?->feedback
                            );
                        @endphp

                        <div class="mb-4 border rounded p-4">
                            <div class="flex items-center justify-between mb-2">
                                <label class="font-medium">
                                    Răspuns {{ $i + 1 }}
                                </label>

                                @if ($answer)
                                    <span class="text-xs text-gray-500">
                                        Răspuns existent
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500">
                                        Opțional
                                    </span>
                                @endif
                            </div>

                            <textarea
                                name="answers[{{ $i }}][answer_text]"
                                rows="2"
                                class="auto-diacritics w-full border-gray-300 rounded"
                                placeholder="Exemplu: \(N^2\) sau Complexitatea este \(N \log N\)"
                            >{{ old("answers.$i.answer_text", $answer?->answer_text) }}</textarea>

                            <label class="flex items-center gap-2 mt-3">
                                <input
                                    type="checkbox"
                                    name="answers[{{ $i }}][is_correct]"
                                    value="1"
                                    @checked(
                                        old(
                                            "answers.$i.is_correct",
                                            $answer?->is_correct
                                        )
                                    )
                                >

                                <span class="font-medium">
                                    Varianta este corectă
                                </span>
                            </label>

                            <details
                                class="mt-3"
                                @if ($feedbackValue) open @endif
                            >
                                <summary class="cursor-pointer text-sm text-blue-600 font-medium">
                                    + Feedback opțional
                                </summary>

                                <div class="mt-3">
                                    <textarea
                                        name="answers[{{ $i }}][feedback]"
                                        rows="2"
                                        class="auto-diacritics w-full border-gray-300 rounded"
                                        placeholder="Explicație pentru această variantă..."
                                    >{{ $feedbackValue }}</textarea>
                                </div>
                            </details>
                        </div>
                    @endfor

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded"
                        >
                            Salvează modificările
                        </button>

                        <a
                            href="{{ route('admin.questions.index') }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded"
                        >
                            Înapoi
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                    setTimeout(function () {
                        field.value = fixRomanianText(field.value);
                    }, 0);
                });
            });
        });
    </script>
</x-app-layout>
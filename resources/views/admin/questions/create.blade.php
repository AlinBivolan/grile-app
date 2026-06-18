<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Adaugă întrebare
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('admin.questions.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="answer_type" value="multiple">

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Categorie</label>
                        <select name="category_id" class="w-full border-gray-300 rounded">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Imagine întrebare</label>
                        <input type="file" name="image" class="w-full">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Dificultate</label>
                        <input type="number" name="difficulty" value="1" min="1" max="5"
                               class="w-full border-gray-300 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Explicație generală, opțională</label>
                        <textarea name="explanation" rows="4"
                                  class="w-full border-gray-300 rounded">{{ old('explanation') }}</textarea>
                    </div>

                    <h3 class="font-bold mt-6 mb-2">Răspunsuri</h3>

<p class="text-sm text-gray-500 mb-4">
    Completează între 3 și 9 variante. Cele nefolosite pot rămâne goale.
</p>

@for ($i = 0; $i < 9; $i++)
    <div class="mb-4 border rounded p-4">
        <label class="block font-medium mb-1">
            Răspuns {{ $i + 1 }}
        </label>

        <textarea
            name="answers[{{ $i }}][answer_text]"
            rows="2"
            class="w-full border-gray-300 rounded"
            placeholder="Scrie varianta de răspuns...">{{ old("answers.$i.answer_text") }}</textarea>

        <label class="flex items-center gap-2 mt-3">
            <input
                type="checkbox"
                name="answers[{{ $i }}][is_correct]"
                value="1"
                @checked(old("answers.$i.is_correct"))>

            <span class="font-medium">
                Varianta este corectă
            </span>
        </label>

        <details class="mt-3">
            <summary class="cursor-pointer text-sm text-blue-600 font-medium">
                + Adaugă feedback opțional
            </summary>

            <div class="mt-3">
                <textarea
                    name="answers[{{ $i }}][feedback]"
                    rows="2"
                    class="w-full border-gray-300 rounded"
                    placeholder="Explicație pentru această variantă...">{{ old("answers.$i.feedback") }}</textarea>
            </div>
        </details>
    </div>
@endfor

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Salvează întrebarea
                    </button>

                    <a href="{{ route('admin.questions.index') }}" class="ml-2 text-gray-600">
                        Înapoi
                    </a>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function normalizeRomanianText(value) {
                return value
                    .replace(/˘\s*a/g, 'ă')
                    .replace(/˘\s*A/g, 'Ă')
                    .replace(/a\s*˘/g, 'ă')
                    .replace(/A\s*˘/g, 'Ă')
                    .replace(/ˆ\s*a/g, 'â')
                    .replace(/ˆ\s*A/g, 'Â')
                    .replace(/a\s*ˆ/g, 'â')
                    .replace(/A\s*ˆ/g, 'Â')
                    .replace(/ˆ\s*[iı]/g, 'î')
                    .replace(/ˆ\s*I/g, 'Î')
                    .replace(/[iı]\s*ˆ/g, 'î')
                    .replace(/I\s*ˆ/g, 'Î')
                    .replace(/[¸,]\s*s/g, 'ș')
                    .replace(/[¸,]\s*S/g, 'Ș')
                    .replace(/s\s*¸/g, 'ș')
                    .replace(/S\s*¸/g, 'Ș')
                    .replace(/ş/g, 'ș')
                    .replace(/Ş/g, 'Ș')
                    .replace(/[¸,]\s*t/g, 'ț')
                    .replace(/[¸,]\s*T/g, 'Ț')
                    .replace(/t\s*¸/g, 'ț')
                    .replace(/T\s*¸/g, 'Ț')
                    .replace(/ţ/g, 'ț')
                    .replace(/Ţ/g, 'Ț')
                    .replace(/ı/g, 'i');
            }

            document.querySelectorAll('.auto-diacritics').forEach(function (field) {
                field.addEventListener('paste', function (event) {
                    event.preventDefault();

                    const clipboardText =
                        event.clipboardData?.getData('text/plain') ?? '';

                    const correctedText = normalizeRomanianText(clipboardText);
                    const start = field.selectionStart ?? field.value.length;
                    const end = field.selectionEnd ?? field.value.length;

                    field.setRangeText(correctedText, start, end, 'end');
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                });

                field.addEventListener('blur', function () {
                    field.value = normalizeRomanianText(field.value);
                });
            });
        });
    </script>

</x-app-layout>
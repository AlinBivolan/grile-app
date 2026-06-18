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

                <form method="POST"
                      action="{{ route('admin.questions.update', $question) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Categorie</label>
                        <select name="category_id" class="w-full border-gray-300 rounded">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id', $question->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Imagine curentă</label>

                        <img
                            src="{{ route('questions.image', $question) }}"
                            alt="Imagine curentă"
                            class="w-96 border rounded mb-3">

                        <label class="block font-medium mb-1">Schimbă imaginea</label>
                        <input type="file" name="image" accept="image/*" class="w-full">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Dificultate</label>
                        <input type="number"
                               name="difficulty"
                               value="{{ old('difficulty', $question->difficulty) }}"
                               min="1"
                               max="5"
                               class="w-full border-gray-300 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   @checked(old('is_active', $question->is_active))>
                            Întrebare activă
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Explicație generală, opțională
                        </label>

                        <textarea name="explanation"
                                  rows="4"
                                  class="w-full border-gray-300 rounded">{{ old('explanation', $question->explanation) }}</textarea>
                    </div>

                    <h3 class="font-bold mt-6 mb-3">Răspunsuri</h3>

                    @foreach ($question->answers as $i => $answer)
                        <div class="mb-4 border rounded p-4">
                            <label class="block font-medium mb-1">
                                Răspuns {{ $i + 1 }}
                            </label>

                            <textarea name="answers[{{ $i }}][answer_text]"
                                      rows="2"
                                      class="w-full border-gray-300 rounded">{{ old("answers.$i.answer_text", $answer->answer_text) }}</textarea>

                            <label class="flex items-center gap-2 mt-2">
                                <input type="checkbox"
                                       name="answers[{{ $i }}][is_correct]"
                                       value="1"
                                       @checked(old("answers.$i.is_correct", $answer->is_correct))>
                                Corect
                            </label>

                            <label class="block font-medium mt-3 mb-1">
                                Feedback pentru acest răspuns, opțional
                            </label>

                            <textarea name="answers[{{ $i }}][feedback]"
                                      rows="2"
                                      class="w-full border-gray-300 rounded">{{ old("answers.$i.feedback", $answer->feedback) }}</textarea>
                        </div>
                    @endforeach

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded">
                        Salvează modificările
                    </button>

                    <a href="{{ route('admin.questions.index') }}"
                       class="ml-2 text-gray-600">
                        Înapoi
                    </a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

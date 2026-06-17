<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Întrebări
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex gap-2">
                <a href="{{ route('admin.questions.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    Adaugă întrebare
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded">
                    Categorii
                </a>
            </div>

            <div class="bg-white shadow rounded p-4">
                @forelse ($questions as $question)
                    <div class="border-b py-4 flex gap-4">
                        <img src="{{ asset('storage/' . $question->image_path) }}"
                             class="w-48 border rounded">

                        <div class="flex-1">
                            <div class="font-bold">
                                {{ $question->category->name }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Tip: {{ $question->answer_type }} |
                                Dificultate: {{ $question->difficulty }} |
                                Activă: {{ $question->is_active ? 'Da' : 'Nu' }}
                            </div>

                            <div class="mt-2 text-sm">
                                Răspunsuri: {{ $question->answers->count() }}
                            </div>

                            <div class="mt-3 flex gap-2">
                                <a href="{{ route('admin.questions.edit', $question) }}"
                                   class="px-3 py-1 bg-yellow-500 text-white rounded">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.questions.destroy', $question) }}"
                                      onsubmit="return confirm('Sigur ștergi întrebarea?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="px-3 py-1 bg-red-600 text-white rounded">
                                        Șterge
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Nu există întrebări încă.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
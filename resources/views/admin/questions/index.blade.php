<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Întrebări
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-5 flex flex-wrap gap-2">
                <a
                    href="{{ route('admin.questions.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded"
                >
                    Adaugă întrebare
                </a>

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded"
                >
                    Categorii
                </a>
            </div>

            <div class="bg-white shadow rounded p-5 mb-6">
                <form
                    method="GET"
                    action="{{ route('admin.questions.index') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4"
                >
                    <div>
                        <label class="block font-medium mb-1">
                            Categorie
                        </label>

                        <select
                            name="category_id"
                            class="w-full border-gray-300 rounded"
                        >
                            <option value="">
                                Toate categoriile
                            </option>

                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        request('category_id')
                                        == $category->id
                                    )
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full border-gray-300 rounded"
                        >
                            <option value="">
                                Toate
                            </option>

                            <option
                                value="active"
                                @selected(request('status') === 'active')
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                @selected(request('status') === 'inactive')
                            >
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded"
                        >
                            Filtrează
                        </button>

                        <a
                            href="{{ route('admin.questions.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded"
                        >
                            Resetează
                        </a>
                    </div>
                </form>
            </div>

            <div class="mb-4 text-sm text-gray-500">
                {{ $questions->count() }} întrebări găsite
            </div>

            <div class="space-y-6">
                @forelse ($questions as $question)
                    <div class="bg-white shadow rounded p-5">
                        <div class="flex flex-col lg:flex-row gap-6">

                            <div class="lg:w-1/2">
                                <div class="flex flex-wrap gap-2 mb-3">
<span class="px-2 py-1 bg-gray-200 text-gray-900 rounded text-sm">
    ID: {{ $question->id }}
</span>

<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
    {{ $question->category->name }}
</span>

<span class="px-2 py-1 bg-gray-200 text-gray-900 rounded text-sm">
    Dificultate: {{ $question->difficulty }}
</span>

                                    <span class="px-2 py-1 rounded text-sm
                                        {{ $question->is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800' }}">
                                        {{ $question->is_active
                                            ? 'Activă'
                                            : 'Inactivă' }}
                                    </span>
                                </div>

                                <img
                                    src="{{ route('questions.image', $question) }}"
                                    alt="Imagine întrebare"
                                    class="w-full border rounded"
                                >
                            </div>

                            <div class="lg:w-1/2">
                                <h3 class="font-bold text-lg mb-3">
                                    Răspunsuri
                                </h3>

                                <div class="space-y-3">
                                    @foreach ($question->answers as $index => $answer)
                                        <div class="p-3 border-2 rounded
                                            {{ $answer->is_correct
                                                ? 'border-green-500 bg-green-50'
                                                : 'border-red-300 bg-red-50' }}">
                                            <div class="flex gap-2">
                                                <span class="font-bold">
                                                    {{ chr(65 + $index) }}.
                                                </span>

                                                <span>
                                                    {{ $answer->answer_text }}
                                                </span>
                                            </div>

                                            <div class="mt-2 text-sm font-bold
                                                {{ $answer->is_correct
                                                    ? 'text-green-700'
                                                    : 'text-red-700' }}">
                                                {{ $answer->is_correct
                                                    ? '✓ Corect'
                                                    : '✗ Greșit' }}
                                            </div>

                                            @if ($answer->feedback)
                                                <details class="mt-2">
                                                    <summary class="cursor-pointer text-sm text-blue-600">
                                                        Vezi feedback
                                                    </summary>

                                                    <div class="mt-2 text-sm">
                                                        {{ $answer->feedback }}
                                                    </div>
                                                </details>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if ($question->explanation)
                                    <details class="mt-4">
                                        <summary class="cursor-pointer font-medium text-blue-600">
                                            Vezi explicația generală
                                        </summary>

                                        <div class="mt-2 p-3 bg-blue-50 border rounded">
                                            {{ $question->explanation }}
                                        </div>
                                    </details>
                                @endif

                                <div class="mt-5 flex flex-wrap gap-2">
                                    <a
                                        href="{{ route('admin.questions.edit', $question) }}"
                                        class="px-4 py-2 bg-yellow-500 text-white rounded"
                                    >
                                        Editează
                                    </a>

                                    <a
                                        href="{{ route('questions.image', $question) }}"
                                        target="_blank"
                                        class="px-4 py-2 bg-blue-600 text-white rounded"
                                    >
                                        Deschide imaginea
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.questions.destroy', $question) }}"
                                        onsubmit="return confirm('Sigur ștergi întrebarea?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded"
                                        >
                                            Șterge
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow rounded p-6">
                        Nu există întrebări pentru filtrele selectate.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
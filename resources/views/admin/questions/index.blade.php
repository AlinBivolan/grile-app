<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Alege modul de lucru
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6 mb-6">
                <h3 class="text-xl font-bold mb-4">Toate categoriile</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $modes = [
                            ['training', 'Antrenament', 'Toate întrebările, în ordine aleatorie.', 'bg-blue-600'],
                            ['exam', 'Examen', '30 de întrebări aleatorii, fără feedback imediat.', 'bg-indigo-600'],
                            ['hardcore', 'Hardcore', 'Prioritizează întrebările la care ai greșit.', 'bg-red-600'],
                            ['wrong', 'Întrebări greșite', 'Repetă întrebările la care ai greșit.', 'bg-orange-600'],
                            ['unseen', 'Întrebări nevăzute', 'Întrebări pe care nu le-ai rezolvat încă.', 'bg-green-600'],
                        ];
                    @endphp

                    @foreach ($modes as [$value, $title, $description, $buttonClass])
                        <form method="POST"
                              action="{{ route('quiz.start') }}"
                              class="border rounded p-4">
                            @csrf
                            <input type="hidden" name="mode" value="{{ $value }}">

                            <div class="font-bold mb-2">{{ $title }}</div>

                            <p class="text-sm text-gray-600 mb-4">
                                {{ $description }}
                            </p>

                            <button class="px-4 py-2 {{ $buttonClass }} text-white rounded">
                                Începe
                            </button>
                        </form>
                    @endforeach

                    <form method="POST"
                          action="{{ route('quiz.start') }}"
                          class="border rounded p-4">
                        @csrf
                        <input type="hidden" name="mode" value="difficult">

                        <div class="font-bold mb-2">⭐ Întrebări dificile</div>

                        <p class="text-sm text-gray-600 mb-2">
                            Întrebările marcate de tine pentru repetare.
                        </p>

                        <p class="text-sm font-bold mb-4">
                            {{ $difficultCount }} întrebări
                        </p>

                        <button class="px-4 py-2 bg-purple-600 text-white rounded">
                            Începe
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <h3 class="text-xl font-bold mb-4">Categorii</h3>

                @forelse ($categories as $category)
                    <form method="POST"
                          action="{{ route('quiz.start') }}"
                          class="border rounded p-4 mb-4">
                        @csrf

                        <input type="hidden"
                               name="category_id"
                               value="{{ $category->id }}">

                        <input type="hidden"
                               name="mode"
                               value="category">

                        <div class="font-bold">{{ $category->name }}</div>

                        <div class="text-sm text-gray-500 mb-3">
                            {{ $category->questions_count }} întrebări
                        </div>

                        <button class="px-4 py-2 bg-blue-600 text-white rounded">
                            Fă toate întrebările
                        </button>
                    </form>
                @empty
                    <p class="text-gray-500">Nu există categorii încă.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>

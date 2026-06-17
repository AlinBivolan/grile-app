<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rezultat test
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6 text-center">

                <h3 class="text-2xl font-bold mb-4">
                    Test finalizat
                </h3>

                <div class="text-xl mb-2">
                    Scor: <strong>{{ $session->score }}</strong> puncte
                </div>

                <div class="text-gray-600 mb-6">
                    Întrebări: {{ $session->total_questions }}
                </div>

                <a href="{{ route('quiz.index') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    Începe alt test
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
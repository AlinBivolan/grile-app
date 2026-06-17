<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow rounded p-5">
                    <div class="text-sm text-gray-500">Întrebări rezolvate</div>
                    <div class="text-3xl font-bold">{{ $totalSeen }}</div>
                </div>

                <div class="bg-white shadow rounded p-5">
                    <div class="text-sm text-gray-500">Corecte</div>
                    <div class="text-3xl font-bold text-green-600">{{ $totalCorrect }}</div>
                </div>

                <div class="bg-white shadow rounded p-5">
                    <div class="text-sm text-gray-500">Greșite</div>
                    <div class="text-3xl font-bold text-red-600">{{ $totalWrong }}</div>
                </div>

                <div class="bg-white shadow rounded p-5">
                    <div class="text-sm text-gray-500">Acuratețe</div>
                    <div class="text-3xl font-bold">{{ $accuracy }}%</div>
                </div>
            </div>

            <div class="bg-white shadow rounded p-6 mb-6">
                <h3 class="text-lg font-bold mb-2">Punctaj total</h3>
                <div class="text-4xl font-bold">{{ $totalScore }} puncte</div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-bold mb-4">Ultimele teste</h3>

                @forelse ($recentSessions as $session)
                    <div class="border-b py-3 flex justify-between">
                        <div>
                            Test #{{ $session->id }}
                            <div class="text-sm text-gray-500">
                                {{ $session->created_at->format('d.m.Y H:i') }}
                            </div>
                        </div>

                        <div class="font-bold">
                            {{ $session->score }} puncte /
                            {{ $session->total_questions }} întrebări
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">
                        Nu ai teste efectuate încă.
                    </p>
                @endforelse
            </div>

            <div class="mt-6">
                <a href="{{ route('quiz.index') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    Începe un test nou
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Leaderboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                @foreach ($users as $index => $user)
                    <div class="flex justify-between border-b py-3">
                        <div>
                            <span class="font-bold">#{{ $index + 1 }}</span>
                            {{ $user->name }}
                        </div>

                        <div>
                            <strong>{{ $user->total_score }}</strong> puncte
                            <span class="text-sm text-gray-500">
                                / {{ $user->tests_count }} teste
                            </span>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
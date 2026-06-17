<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Utilizatori online
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                <div class="mb-5">
                    <div class="text-3xl font-bold">
                        {{ $onlineUsers->count() }}
                    </div>

                    <div class="text-gray-500">
                        utilizatori activi în ultimele 5 minute
                    </div>
                </div>

                @forelse ($onlineUsers as $user)
                    <div class="flex items-center justify-between border-b py-4">
                        <div>
                            <div class="font-bold">
                                {{ $user->name }}
                            </div>

                            <div class="text-sm text-gray-500">
                                {{ $user->email }}
                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                Rol: {{ $user->role }}
                            </div>
                        </div>

                        <div class="text-sm text-green-600 font-bold">
                            ● Online
                        </div>
                    </div>
                @empty
                    <div class="text-gray-500">
                        Nu există utilizatori online momentan.
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Categorii
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('admin.categories.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    Adaugă categorie
                </a>
            </div>

            <div class="bg-white shadow rounded p-4">
                @forelse ($categories as $category)
                    <div class="flex justify-between items-center border-b py-3">
                        <div>
                            <div class="font-bold">{{ $category->name }}</div>
                            <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                            <div class="text-sm">{{ $category->description }}</div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="px-3 py-1 bg-yellow-500 text-white rounded">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('Sigur ștergi categoria?')">
                                @csrf
                                @method('DELETE')

                                <button class="px-3 py-1 bg-red-600 text-white rounded">
                                    Șterge
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>Nu există categorii încă.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Adaugă categorie
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Nume categorie</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded">

                        @error('name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Descriere</label>
                        <textarea name="description"
                                  class="w-full border-gray-300 rounded"
                                  rows="4">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                        Salvează
                    </button>

                    <a href="{{ route('admin.categories.index') }}"
                       class="ml-2 text-gray-600">
                        Înapoi
                    </a>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
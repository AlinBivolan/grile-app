<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Alege modul de lucru
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Selectează modul potrivit pentru felul în care vrei să înveți.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-300 bg-red-100 p-4 text-red-800">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-300 bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <section class="mb-10">
                <div class="mb-5 flex items-end justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            Moduri de învățare
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Folosește toate întrebările din platformă.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="group rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="training">

                        <div class="mb-5 flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-2xl text-white">
                                🎯
                            </div>

                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-800">
                                Recomandat
                            </span>
                        </div>

                        <h4 class="text-lg font-bold text-gray-900">
                            Antrenament
                        </h4>

                        <p class="mt-2 min-h-[48px] text-sm text-gray-600">
                            Parcurge toate întrebările în ordine aleatorie și primește feedback imediat.
                        </p>

                        <button
                            type="submit"
                            class="mt-6 w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700"
                        >
                            Începe antrenamentul
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="group rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="exam">

                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-2xl text-white">
                            📝
                        </div>

                        <h4 class="text-lg font-bold text-gray-900">
                            Examen
                        </h4>

                        <p class="mt-2 min-h-[48px] text-sm text-gray-600">
                            Primești 30 de întrebări aleatorii, fără feedback până la final.
                        </p>

                        <button
                            type="submit"
                            class="mt-6 w-full rounded-xl bg-indigo-600 px-4 py-3 font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Simulează examenul
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="group rounded-2xl border border-red-200 bg-gradient-to-br from-red-50 to-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="hardcore">

                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-red-600 text-2xl text-white">
                            🔥
                        </div>

                        <h4 class="text-lg font-bold text-gray-900">
                            Hardcore
                        </h4>

                        <p class="mt-2 min-h-[48px] text-sm text-gray-600">
                            Primești mai ales întrebările la care ai avut cele mai multe dificultăți.
                        </p>

                        <button
                            type="submit"
                            class="mt-6 w-full rounded-xl bg-red-600 px-4 py-3 font-semibold text-white transition hover:bg-red-700"
                        >
                            Începe modul hardcore
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="group rounded-2xl border border-orange-200 bg-gradient-to-br from-orange-50 to-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="wrong">

                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-orange-600 text-2xl text-white">
                            ↻
                        </div>

                        <h4 class="text-lg font-bold text-gray-900">
                            Întrebări greșite
                        </h4>

                        <p class="mt-2 min-h-[48px] text-sm text-gray-600">
                            Repetă doar întrebările la care ai răspuns greșit anterior.
                        </p>

                        <button
                            type="submit"
                            class="mt-6 w-full rounded-xl bg-orange-600 px-4 py-3 font-semibold text-white transition hover:bg-orange-700"
                        >
                            Repetă greșelile
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="group rounded-2xl border border-green-200 bg-gradient-to-br from-green-50 to-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="unseen">

                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-green-600 text-2xl text-white">
                            ✨
                        </div>

                        <h4 class="text-lg font-bold text-gray-900">
                            Întrebări nevăzute
                        </h4>

                        <p class="mt-2 min-h-[48px] text-sm text-gray-600">
                            Rezolvă întrebările pe care nu le-ai întâlnit până acum.
                        </p>

                        <button
                            type="submit"
                            class="mt-6 w-full rounded-xl bg-green-600 px-4 py-3 font-semibold text-white transition hover:bg-green-700"
                        >
                            Descoperă întrebări noi
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="group rounded-2xl border border-purple-200 bg-gradient-to-br from-purple-50 to-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="difficult">

                        <div class="mb-5 flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-600 text-2xl text-white">
                                ⭐
                            </div>

                            <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-800">
                                {{ $difficultCount }} salvate
                            </span>
                        </div>

                        <h4 class="text-lg font-bold text-gray-900">
                            Întrebări dificile
                        </h4>

                        <p class="mt-2 min-h-[48px] text-sm text-gray-600">
                            Repetă întrebările pe care le-ai marcat manual pentru recapitulare.
                        </p>

                        <button
                            type="submit"
                            class="mt-6 w-full rounded-xl bg-purple-600 px-4 py-3 font-semibold text-white transition hover:bg-purple-700"
                        >
                            Repetă întrebările dificile
                        </button>
                    </form>

                </div>
            </section>

            <section>
                <div class="mb-5">
                    <h3 class="text-xl font-bold text-gray-900">
                        Categorii
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Rezolvă toate întrebările dintr-o anumită categorie.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @forelse ($categories as $category)
                        <form
                            method="POST"
                            action="{{ route('quiz.start') }}"
                            class="rounded-2xl border bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="category_id"
                                value="{{ $category->id }}"
                            >

                            <input
                                type="hidden"
                                name="mode"
                                value="category"
                            >

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h4 class="font-bold text-gray-900">
                                        {{ $category->name }}
                                    </h4>

                                    <div class="mt-1 text-sm text-gray-500">
                                        {{ $category->questions_count }} întrebări disponibile
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="shrink-0 rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700"
                                >
                                    Începe categoria
                                </button>
                            </div>
                        </form>
                    @empty
                        <div class="rounded-2xl border bg-white p-6 text-gray-500 md:col-span-2">
                            Nu există categorii încă.
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</x-app-layout>

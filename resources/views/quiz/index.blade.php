<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold">
                Alege modul de lucru
            </h2>

            <p class="mt-1 text-sm opacity-70">
                Selectează modul potrivit pentru felul în care vrei să înveți.
            </p>
        </div>
    </x-slot>

    <style>
        .quiz-page {
            --quiz-bg: #f8fafc;
            --quiz-card: #ffffff;
            --quiz-card-soft: #f8fafc;
            --quiz-text: #0f172a;
            --quiz-muted: #64748b;
            --quiz-border: #e2e8f0;
            --quiz-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        }

        html.dark .quiz-page {
            --quiz-bg: #020617;
            --quiz-card: #0f172a;
            --quiz-card-soft: #111827;
            --quiz-text: #f8fafc;
            --quiz-muted: #cbd5e1;
            --quiz-border: #334155;
            --quiz-shadow: 0 14px 32px rgba(0, 0, 0, 0.35);
        }

        .quiz-page {
            background: var(--quiz-bg);
            color: var(--quiz-text);
        }

        .quiz-title,
        .quiz-card-title,
        .quiz-category-title {
            color: var(--quiz-text);
        }

        .quiz-muted {
            color: var(--quiz-muted);
        }

        .quiz-card,
        .quiz-category-card,
        .quiz-empty {
            background: var(--quiz-card);
            border: 1px solid var(--quiz-border);
            box-shadow: var(--quiz-shadow);
        }

        .quiz-card,
        .quiz-category-card {
            transition:
                transform 180ms ease,
                box-shadow 180ms ease,
                border-color 180ms ease;
        }

        .quiz-card:hover,
        .quiz-category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.14);
        }

        html.dark .quiz-card:hover,
        html.dark .quiz-category-card:hover {
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.5);
        }

        .quiz-card-blue {
            background:
                linear-gradient(
                    145deg,
                    rgba(37, 99, 235, 0.13),
                    transparent 55%
                ),
                var(--quiz-card);
        }

        .quiz-card-indigo {
            background:
                linear-gradient(
                    145deg,
                    rgba(79, 70, 229, 0.13),
                    transparent 55%
                ),
                var(--quiz-card);
        }

        .quiz-card-red {
            background:
                linear-gradient(
                    145deg,
                    rgba(220, 38, 38, 0.13),
                    transparent 55%
                ),
                var(--quiz-card);
        }

        .quiz-card-orange {
            background:
                linear-gradient(
                    145deg,
                    rgba(234, 88, 12, 0.13),
                    transparent 55%
                ),
                var(--quiz-card);
        }

        .quiz-card-green {
            background:
                linear-gradient(
                    145deg,
                    rgba(22, 163, 74, 0.13),
                    transparent 55%
                ),
                var(--quiz-card);
        }

        .quiz-card-purple {
            background:
                linear-gradient(
                    145deg,
                    rgba(147, 51, 234, 0.13),
                    transparent 55%
                ),
                var(--quiz-card);
        }

        .quiz-count-badge {
            color: #e9d5ff;
            background: #581c87;
            border: 1px solid #7e22ce;
        }

        .quiz-recommended-badge {
            color: #dbeafe;
            background: #1e3a8a;
            border: 1px solid #2563eb;
        }

        .quiz-category-card:hover {
            border-color: #3b82f6;
        }

        .quiz-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: 0.9rem;
            color: #ffffff;
            font-size: 1.5rem;
            line-height: 1;
        }

        .quiz-button {
            display: inline-flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            border-radius: 0.8rem;
            padding: 0.8rem 1rem;
            color: #ffffff;
            font-weight: 700;
            transition:
                transform 150ms ease,
                filter 150ms ease;
        }

        .quiz-button:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }
    </style>

    <div class="quiz-page min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-500 bg-red-950/80 p-4 text-red-100">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-500 bg-green-950/80 p-4 text-green-100">
                    {{ session('success') }}
                </div>
            @endif

            <section class="mb-10">
                <div class="mb-5">
                    <h3 class="quiz-title text-xl font-bold">
                        Moduri de învățare
                    </h3>

                    <p class="quiz-muted mt-1 text-sm">
                        Folosește toate întrebările din platformă.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="quiz-card quiz-card-blue rounded-2xl p-6"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="training">

                        <div class="mb-5 flex items-start justify-between gap-3">
                            <div class="quiz-icon bg-blue-600">🎯</div>

                            <span class="quiz-recommended-badge rounded-full px-3 py-1 text-xs font-bold">
                                Recomandat
                            </span>
                        </div>

                        <h4 class="quiz-card-title text-lg font-bold">
                            Antrenament
                        </h4>

                        <p class="quiz-muted mt-2 min-h-[48px] text-sm">
                            Parcurge toate întrebările în ordine aleatorie și primește feedback imediat.
                        </p>

                        <button type="submit" class="quiz-button mt-6 bg-blue-600">
                            Începe antrenamentul
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="quiz-card quiz-card-indigo rounded-2xl p-6"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="exam">

                        <div class="quiz-icon mb-5 bg-indigo-600">📝</div>

                        <h4 class="quiz-card-title text-lg font-bold">
                            Examen
                        </h4>

                        <p class="quiz-muted mt-2 min-h-[48px] text-sm">
                            Primești 30 de întrebări aleatorii, fără feedback până la final.
                        </p>

                        <button type="submit" class="quiz-button mt-6 bg-indigo-600">
                            Simulează examenul
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="quiz-card quiz-card-red rounded-2xl p-6"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="hardcore">

                        <div class="quiz-icon mb-5 bg-red-600">🔥</div>

                        <h4 class="quiz-card-title text-lg font-bold">
                            Hardcore
                        </h4>

                        <p class="quiz-muted mt-2 min-h-[48px] text-sm">
                            Primești mai ales întrebările la care ai avut cele mai multe dificultăți.
                        </p>

                        <button type="submit" class="quiz-button mt-6 bg-red-600">
                            Începe modul hardcore
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="quiz-card quiz-card-orange rounded-2xl p-6"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="wrong">

                        <div class="quiz-icon mb-5 bg-orange-600">↻</div>

                        <h4 class="quiz-card-title text-lg font-bold">
                            Întrebări greșite
                        </h4>

                        <p class="quiz-muted mt-2 min-h-[48px] text-sm">
                            Repetă doar întrebările la care ai răspuns greșit anterior.
                        </p>

                        <button type="submit" class="quiz-button mt-6 bg-orange-600">
                            Repetă greșelile
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="quiz-card quiz-card-green rounded-2xl p-6"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="unseen">

                        <div class="quiz-icon mb-5 bg-green-600">✨</div>

                        <h4 class="quiz-card-title text-lg font-bold">
                            Întrebări nevăzute
                        </h4>

                        <p class="quiz-muted mt-2 min-h-[48px] text-sm">
                            Rezolvă întrebările pe care nu le-ai întâlnit până acum.
                        </p>

                        <button type="submit" class="quiz-button mt-6 bg-green-600">
                            Descoperă întrebări noi
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('quiz.start') }}"
                        class="quiz-card quiz-card-purple rounded-2xl p-6"
                    >
                        @csrf
                        <input type="hidden" name="mode" value="difficult">

                        <div class="mb-5 flex items-start justify-between gap-3">
                            <div class="quiz-icon bg-purple-600">⭐</div>

                            <span class="quiz-count-badge rounded-full px-3 py-1 text-xs font-bold">
                                {{ $difficultCount }} salvate
                            </span>
                        </div>

                        <h4 class="quiz-card-title text-lg font-bold">
                            Întrebări dificile
                        </h4>

                        <p class="quiz-muted mt-2 min-h-[48px] text-sm">
                            Repetă întrebările pe care le-ai marcat manual pentru recapitulare.
                        </p>

                        <button type="submit" class="quiz-button mt-6 bg-purple-600">
                            Repetă întrebările dificile
                        </button>
                    </form>

                </div>
            </section>

            <section>
                <div class="mb-5">
                    <h3 class="quiz-title text-xl font-bold">
                        Categorii
                    </h3>

                    <p class="quiz-muted mt-1 text-sm">
                        Rezolvă toate întrebările dintr-o anumită categorie.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @forelse ($categories as $category)
                        <form
                            method="POST"
                            action="{{ route('quiz.start') }}"
                            class="quiz-category-card rounded-2xl p-5"
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
                                    <h4 class="quiz-category-title font-bold">
                                        {{ $category->name }}
                                    </h4>

                                    <div class="quiz-muted mt-1 text-sm">
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
                        <div class="quiz-empty rounded-2xl p-6">
                            Nu există categorii încă.
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</x-app-layout>

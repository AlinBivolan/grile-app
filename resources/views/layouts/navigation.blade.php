<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo
                            class="block h-9 w-auto fill-current text-gray-800"
                        />
                    </a>
                </div>

                <div class="ml-3 sm:ml-4">
                    @if (auth()->user()?->role === 'admin')
                        <a
                            href="{{ route('admin.online-users.index') }}"
                            class="inline-flex items-center gap-2 px-2 sm:px-3 py-2 rounded-lg border text-xs sm:text-sm font-bold hover:bg-gray-50"
                        >
                            <span class="text-green-500">●</span>
                            {{ $onlineUsersCount }} online
                        </a>
                    @else
                        <div class="inline-flex items-center gap-2 px-2 sm:px-3 py-2 text-xs sm:text-sm font-bold">
                            <span class="text-green-500">●</span>
                            {{ $onlineUsersCount }} online
                        </div>
                    @endif
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                    >
                        Dashboard
                    </x-nav-link>

                    <x-nav-link
                        :href="route('quiz.index')"
                        :active="request()->routeIs('quiz.*')"
                    >
                        Quiz
                    </x-nav-link>

                    <x-nav-link
                        :href="route('leaderboard.index')"
                        :active="request()->routeIs('leaderboard.*')"
                    >
                        Leaderboard
                    </x-nav-link>

                    @if (auth()->user()?->role === 'admin')
                        <x-nav-link
                            :href="route('admin.categories.index')"
                            :active="request()->routeIs('admin.categories.*')"
                        >
                            Categorii
                        </x-nav-link>

                        <x-nav-link
                            :href="route('admin.questions.index')"
                            :active="request()->routeIs('admin.questions.*')"
                        >
                            Întrebări
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <button
                    type="button"
                    onclick="toggleTheme()"
                    class="mr-4 inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-medium hover:bg-gray-50"
                >
                    <span class="theme-icon">🌙</span>
                    <span>Temă</span>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition"
                        >
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg
                                    class="fill-current h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                Deconectare
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center gap-2 sm:hidden">
                <button
                    type="button"
                    onclick="toggleTheme()"
                    aria-label="Schimbă tema"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg border text-lg hover:bg-gray-100"
                >
                    <span class="theme-icon">🌙</span>
                </button>

                <button
                    @click="open = ! open"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-500"
                    aria-label="Deschide meniul"
                >
                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <path
                            :class="{'hidden': open, 'inline-flex': ! open}"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                        <path
                            :class="{'hidden': ! open, 'inline-flex': open}"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div
        :class="{'block': open, 'hidden': ! open}"
        class="hidden sm:hidden"
    >
        <div class="pt-2 pb-3 space-y-1">
            <div class="px-4 py-2">
                @if (auth()->user()?->role === 'admin')
                    <a
                        href="{{ route('admin.online-users.index') }}"
                        class="font-bold"
                    >
                        <span class="text-green-500">●</span>
                        {{ $onlineUsersCount }} online
                    </a>
                @else
                    <span class="font-bold">
                        <span class="text-green-500">●</span>
                        {{ $onlineUsersCount }} online
                    </span>
                @endif
            </div>

            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')"
            >
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('quiz.index')"
                :active="request()->routeIs('quiz.*')"
            >
                Quiz
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('leaderboard.index')"
                :active="request()->routeIs('leaderboard.*')"
            >
                Leaderboard
            </x-responsive-nav-link>

            @if (auth()->user()?->role === 'admin')
                <x-responsive-nav-link
                    :href="route('admin.categories.index')"
                    :active="request()->routeIs('admin.categories.*')"
                >
                    Categorii
                </x-responsive-nav-link>

                <x-responsive-nav-link
                    :href="route('admin.questions.index')"
                    :active="request()->routeIs('admin.questions.*')"
                >
                    Întrebări
                </x-responsive-nav-link>
            @endif

            <button
                type="button"
                onclick="toggleTheme()"
                class="flex w-full items-center gap-3 px-4 py-3 text-left text-base font-medium"
            >
                <span class="theme-icon">🌙</span>
                Schimbă tema
            </button>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ Auth::user()->name }}
                </div>

                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        Deconectare
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');

            localStorage.setItem('theme', isDark ? 'dark' : 'light');

            document.querySelectorAll('.theme-icon').forEach(function (icon) {
                icon.textContent = isDark ? '☀️' : '🌙';
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const isDark = document.documentElement.classList.contains('dark');

            document.querySelectorAll('.theme-icon').forEach(function (icon) {
                icon.textContent = isDark ? '☀️' : '🌙';
            });
        });
    </script>
</nav>

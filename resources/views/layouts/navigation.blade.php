<nav x-data="{ open: false }" class="sticky top-0 z-50 w-full">
    <!-- Top bar -->
    <div class="border-b border-slate-200/70 bg-white/80 backdrop-blur-xl">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">

                <!-- LEFT -->
                <div class="flex items-center gap-8">
                    <!-- Brand -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 font-bold text-white">
                            RS
                        </div>
                        <div class="leading-tight">
                            <p class="text-sm font-semibold text-slate-900">RSUD Kardinah</p>
                            <span class="text-xs text-slate-500">Sistem SKM</span>
                        </div>
                    </a>

                    <!-- Links (Desktop) -->
                    <div class="hidden sm:flex items-center gap-6">
                        <a
                            href="{{ route('dashboard') }}"
                            class="px-1 py-2 text-sm font-medium transition border-b-2
                                   {{ request()->routeIs('dashboard')
                                        ? 'border-blue-600 text-slate-900'
                                        : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' }}"
                        >
                            Dashboard
                        </a>
                    </div>
                </div>

                <!-- RIGHT (Desktop) -->
                <div class="hidden sm:flex items-center gap-3">
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button
                                type="button"
                                class="inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2
                                       text-sm font-medium text-slate-700 shadow-sm transition
                                       hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-blue-600/20"
                            >
                                <!-- Avatar -->
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-xs font-semibold text-white">
                                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                </div>

                                <!-- Name + FULL Email -->
                                <div class="hidden md:block text-left leading-tight max-w-[260px]">
                                    <div class="text-sm font-semibold text-slate-900 truncate">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-slate-500 break-all">
                                        {{ Auth::user()->email }}
                                    </div>
                                </div>

                                <!-- Chevron -->
                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Optional: header inside dropdown (minimal) -->
                            <div class="px-4 py-3">
                                <div class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500 break-all">{{ Auth::user()->email }}</div>
                            </div>

                            <div class="my-1 border-t border-slate-200"></div>

                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <div class="my-1 border-t border-slate-200"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- HAMBURGER (Mobile) -->
                <div class="flex items-center sm:hidden">
                    <button
                        @click="open = ! open"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2
                               text-slate-700 shadow-sm transition
                               hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-blue-600/20"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- MOBILE PANEL -->
    <div x-show="open" x-cloak class="sm:hidden">
        <div class="border-b border-slate-200/70 bg-white/80 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 pb-4 sm:px-6 lg:px-8">
                <div class="mt-3 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                    <div class="px-4 py-4">
                        <div class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500 break-all">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="border-t border-slate-200"></div>

                    <div class="p-2">
                        <a
                            href="{{ route('dashboard') }}"
                            class="block px-4 py-3 text-sm font-medium border-l-2 transition
                                   {{ request()->routeIs('dashboard')
                                        ? 'border-blue-600 text-slate-900 bg-slate-50'
                                        : 'border-transparent text-slate-700 hover:bg-slate-50' }}"
                        >
                            Dashboard
                        </a>

                        <a
                            href="{{ route('profile.edit') }}"
                            class="mt-1 block px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                        >
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button
                                type="submit"
                                class="w-full px-4 py-3 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            >
                                Log Out
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</nav>

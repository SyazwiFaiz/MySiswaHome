
<nav class="sticky top-0 z-50 border-b border-gray-200/80 bg-white/95 shadow-sm backdrop-blur-xl">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-8">

        <div class="flex min-h-[76px] items-center justify-between gap-3">

            {{-- Brand --}}
            <a href="{{ route('dashboard') }}"
               class="group flex shrink-0 items-center gap-3 rounded-2xl py-2 pr-2 transition">

                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-700 shadow-lg shadow-indigo-200 transition duration-300 group-hover:scale-105">
                    <svg class="h-6 w-6 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M3 11.5 12 4l9 7.5M5.5 10v9a1 1 0 001 1H10v-6h4v6h3.5a1 1 0 001-1v-9"/>
                    </svg>
                </div>

                <div>
                    <div class="text-lg font-extrabold tracking-tight text-gray-900">
                        MySiswa<span class="text-indigo-600">Home</span>
                    </div>
                    <div class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-gray-400">
                        Student Rental Platform
                    </div>
                </div>
            </a>

            {{-- Desktop Search --}}
            <div class="hidden min-w-0 flex-1 md:block md:max-w-md lg:max-w-xl xl:mx-8 xl:max-w-2xl">
                <form action="{{ route('student.dashboard') }}" method="GET">
                    <div class="group relative">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400 transition group-focus-within:text-indigo-600"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>
                        </svg>

                        <input
                            type="text"
                            name="location"
                            value="{{ request('location') }}"
                            placeholder="Cari rumah, lokasi atau kawasan..."
                            aria-label="Cari rumah mengikut lokasi"
                            class="h-12 w-full rounded-2xl border border-gray-200 bg-gray-50/80 pl-12 pr-24 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 hover:border-gray-300 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                        >

                        <button
                            type="submit"
                            class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- Desktop Navigation --}}
            <div class="hidden items-center gap-1 lg:flex">

                {{-- Cari Rumah --}}
                <a href="{{ route('student.dashboard') }}"
                   @if(request()->routeIs('student.dashboard')) aria-current="page" @endif
                   class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition
                   {{ request()->routeIs('student.dashboard')
                        ? 'bg-indigo-50 text-indigo-700'
                        : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}">

                    <svg class="h-5 w-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>
                    </svg>

                    Cari Rumah
                </a>

                {{-- Rumah Kegemaran --}}
                <a href="{{ route('favourite') }}"
                   @if(request()->routeIs('favourite')) aria-current="page" @endif
                   class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition
                   {{ request()->routeIs('favourite')
                        ? 'bg-rose-50 text-rose-600'
                        : 'text-gray-600 hover:bg-rose-50 hover:text-rose-600' }}">

                    <svg class="h-5 w-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/>
                    </svg>

                    Kegemaran
                </a>
            </div>

            {{-- User Dropdown --}}
            <div class="flex shrink-0 items-center gap-2">

                <div class="hidden h-9 w-px bg-gray-200 lg:block"></div>

                <x-dropdown align="right" width="60">
                    <x-slot name="trigger">
                        <button
                            type="button"
                            aria-label="Buka menu pengguna"
                            class="group flex items-center gap-2 rounded-2xl border border-transparent py-1.5 pl-1.5 pr-2 transition hover:border-gray-200 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 sm:gap-3 sm:pl-2">

                            {{-- Avatar --}}
                            @if(Auth::user()->avatar)
                                <img
                                    src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                    alt="{{ Auth::user()->name }}"
                                    class="h-10 w-10 rounded-xl object-cover ring-1 ring-gray-200"
                                >
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-700 text-base font-bold text-white shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="hidden max-w-[130px] text-left sm:block">
                                <div class="truncate text-sm font-bold text-gray-800">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="mt-0.5 text-xs capitalize text-gray-500">
                                    {{ Auth::user()->role }}
                                </div>
                            </div>

                            <svg class="hidden h-4 w-4 text-gray-400 transition group-hover:text-gray-700 sm:block"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="m19 9-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        {{-- User Information --}}
                        <div class="border-b border-gray-100 bg-gray-50/80 px-4 py-4">
                            <p class="truncate text-sm font-bold text-gray-900">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="mt-1 truncate text-xs text-gray-500">
                                {{ Auth::user()->email }}
                            </p>
                            <span class="mt-3 inline-flex rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold capitalize text-indigo-700">
                                {{ Auth::user()->role }}
                            </span>
                        </div>

                        {{-- Profile --}}
                        <x-dropdown-link :href="route('profile.edit')">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-gray-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.8"
                                          d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
                                </svg>
                                <span>Profil Saya</span>
                            </div>
                        </x-dropdown-link>

                        {{-- Favourite --}}
                        <x-dropdown-link :href="route('favourite')">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-gray-400"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.8"
                                          d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/>
                                </svg>
                                <span>Rumah Kegemaran</span>
                            </div>
                        </x-dropdown-link>

                        {{-- Logout --}}
                        <div class="my-1 border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">

                                <div class="flex items-center gap-3 text-red-600">
                                    <svg class="h-5 w-5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1.8"
                                              d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/>
                                    </svg>
                                    <span>Log Keluar</span>
                                </div>
                            </x-dropdown-link>
                        </form>

                    </x-slot>
                </x-dropdown>
            </div>
        </div>

        {{-- Mobile Search --}}
        <div class="border-t border-gray-100 py-3 md:hidden">
            <form action="{{ route('student.dashboard') }}" method="GET">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>
                    </svg>

                    <input
                        type="text"
                        name="location"
                        value="{{ request('location') }}"
                        placeholder="Cari rumah atau lokasi..."
                        aria-label="Cari rumah mengikut lokasi"
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 pl-11 pr-20 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                    >

                    <button
                        type="submit"
                        class="absolute right-1 top-1/2 -translate-y-1/2 rounded-lg bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white transition hover:bg-indigo-700">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        {{-- Mobile Navigation --}}
        <div class="grid grid-cols-2 gap-2 pb-3 lg:hidden">

            <a href="{{ route('student.dashboard') }}"
               @if(request()->routeIs('student.dashboard')) aria-current="page" @endif
               class="flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold transition
               {{ request()->routeIs('student.dashboard')
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'bg-gray-50 text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                <svg class="h-4 w-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>
                </svg>

                Cari Rumah
            </a>

            <a href="{{ route('favourite') }}"
               @if(request()->routeIs('favourite')) aria-current="page" @endif
               class="flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold transition
               {{ request()->routeIs('favourite')
                    ? 'bg-rose-500 text-white shadow-sm'
                    : 'bg-gray-50 text-gray-600 hover:bg-rose-50 hover:text-rose-600' }}">

                <svg class="h-4 w-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/>
                </svg>

                Kegemaran
            </a>
        </div>

    </div>
</nav>

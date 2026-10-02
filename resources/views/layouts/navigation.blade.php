<nav class="sticky top-0 z-50 border-b border-indigo-500/30 bg-indigo-600 shadow-sm">
    <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">

    <div class="flex h-16 items-center justify-between gap-4">

        {{-- Brand --}}
        <a
            href="{{ route('dashboard') }}"
            class="flex shrink-0 items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-white/10"
        >
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm">
                <svg
                    class="h-6 w-6 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"
                    />
                </svg>
            </div>

            <div class="hidden sm:block">
                <div class="text-base font-extrabold leading-tight text-white">
                    MySiswaHome
                </div>
                <div class="text-[10px] font-medium uppercase tracking-wider text-indigo-100">
                    Student Rental
                </div>
            </div>
        </a>


        {{-- Search Bar --}}
        <div class="hidden flex-1 md:block md:max-w-xl lg:max-w-2xl">

            <form
                action="{{ route('student.dashboard') }}"
                method="GET"
            >
                <div class="relative">

                    {{-- Search Icon --}}
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                            />
                        </svg>
                    </div>

                    <input
                        type="text"
                        name="location"
                        value="{{ request('location') }}"
                        placeholder="Cari rumah, lokasi atau kawasan..."
                        class="h-11 w-full rounded-xl border-0 bg-white pl-11 pr-20 text-sm text-gray-700 shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    >

                    <button
                        type="submit"
                        class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-lg bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    >
                        Cari
                    </button>

                </div>
            </form>

        </div>


        {{-- Right Navigation --}}
        <div class="flex items-center gap-2">

            {{-- Find Homes --}}
            <a
                href="{{ route('student.dashboard') }}"
                class="hidden items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10 lg:flex"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                    />
                </svg>

                Cari Rumah
            </a>


            {{-- Favourite --}}
            <a
                href="{{ route('favourite') }}"
                class="hidden h-10 w-10 items-center justify-center rounded-xl text-white transition hover:bg-white/10 sm:flex"
                title="Rumah Kegemaran"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z"
                    />
                </svg>
            </a>


            {{-- Divider --}}
            <div class="hidden h-8 w-px bg-white/20 sm:block"></div>


            {{-- User Dropdown --}}
            <x-dropdown align="right" width="56">

                <x-slot name="trigger">

                    <button
                        class="flex items-center gap-2 rounded-xl px-2 py-1.5 text-white transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/30"
                    >

                        {{-- Avatar --}}
                        @if(Auth::user()->avatar)
                            <img
                                src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                alt="{{ Auth::user()->name }}"
                                class="h-9 w-9 rounded-full object-cover ring-2 ring-white/40"
                            >
                        @else
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-sm font-bold text-indigo-600 ring-2 ring-white/20"
                            >
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif


                        {{-- User Name --}}
                        <div class="hidden text-left sm:block">
                            <div class="max-w-[130px] truncate text-sm font-semibold text-white">
                                {{ Auth::user()->name }}
                            </div>

                            <div class="text-[11px] text-indigo-100">
                                {{ ucfirst(Auth::user()->role) }}
                            </div>
                        </div>


                        {{-- Arrow --}}
                        <svg
                            class="hidden h-4 w-4 text-indigo-100 sm:block"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>

                </x-slot>


                {{-- Dropdown --}}
                <x-slot name="content">

                    {{-- Profile Header --}}
                    <div class="border-b border-gray-100 px-4 py-3">
                        <p class="text-sm font-semibold text-gray-800">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="mt-0.5 truncate text-xs text-gray-500">
                            {{ Auth::user()->email }}
                        </p>
                    </div>


                    {{-- Profile --}}
                    <x-dropdown-link :href="route('profile.edit')">
                        <div class="flex items-center gap-3">
                            <svg
                                class="h-5 w-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>

                            <span>Profile</span>
                        </div>
                    </x-dropdown-link>


                    {{-- Favourite --}}
                    <x-dropdown-link :href="route('favourite')">
                        <div class="flex items-center gap-3">
                            <svg
                                class="h-5 w-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z"
                                />
                            </svg>

                            <span>Rumah Kegemaran</span>
                        </div>
                    </x-dropdown-link>


                    {{-- Logout --}}
                    <div class="border-t border-gray-100"></div>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf

                        <x-dropdown-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                        >
                            <div class="flex items-center gap-3 text-red-600">
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>

                                <span>Log Keluar</span>
                            </div>
                        </x-dropdown-link>

                    </form>

                </x-slot>

            </x-dropdown>

        </div>

    </div>
</div>


{{-- Mobile Search --}}
<div class="border-t border-indigo-500/30 px-4 pb-3 pt-2 md:hidden">

    <form
        action="{{ route('student.dashboard') }}"
        method="GET"
    >
        <div class="relative">

            <svg
                class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                />
            </svg>

            <input
                type="text"
                name="location"
                value="{{ request('location') }}"
                placeholder="Cari rumah atau lokasi..."
                class="h-10 w-full rounded-xl border-0 bg-white pl-10 pr-16 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
            >

            <button
                type="submit"
                class="absolute right-1 top-1/2 -translate-y-1/2 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white"
            >
                Cari
            </button>

        </div>
    </form>

</div>

</nav>

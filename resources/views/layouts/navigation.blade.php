@php
    $user = Auth::user();
    $isLandlord = $user && $user->role === 'landlord';

    $landlordLinks = [
        ['Dashboard', 'landlord.create', ['landlord.create', 'landlord.dashboard', 'landlord.x.overview']],
        ['Create House', 'landlord.houses.create', ['landlord.houses.create']],
        ['Manage House', 'landlord.manage', ['landlord.manage', 'landlord.edit', 'landlord.update', 'landlord.delete']],
        ['Permintaan', 'landlord.x.inquiries', ['landlord.x.inquiries']],
        ['Ulasan', 'landlord.x.reviews', ['landlord.x.reviews']],
        ['Sewaan', 'landlord.x.tenancies.index', ['landlord.x.tenancies.*']],
    ];
@endphp

<nav class="fixed top-0 inset-x-0 z-50 border-b border-gray-200/80 bg-white/95 shadow-sm backdrop-blur-xl">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-[76px] items-center justify-between gap-3">

            <a
                href="{{ $isLandlord ? route('landlord.create') : route('dashboard') }}"
                class="group flex shrink-0 items-center gap-3 rounded-2xl py-2 pr-2"
            >
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-700 shadow-lg shadow-indigo-200 transition group-hover:scale-105">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M3 11.5 12 4l9 7.5M5.5 10v9a1 1 0 001 1H10v-6h4v6h3.5a1 1 0 001-1v-9"/>
                    </svg>
                </div>

                <div>
                    <div class="text-lg font-extrabold tracking-tight text-gray-900">
                        MySiswa<span class="text-indigo-600">Home</span>
                    </div>
                    <div class="mt-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-gray-400">
                        {{ $isLandlord ? 'Landlord Portal' : 'Student Rental Platform' }}
                    </div>
                </div>
            </a>

            @if (!$isLandlord && \Illuminate\Support\Facades\Route::has('student.dashboard'))
                <div class="hidden min-w-0 flex-1 md:block md:max-w-md lg:max-w-xl xl:mx-8 xl:max-w-2xl">
                    <form action="{{ route('student.dashboard') }}" method="GET">
                        <div class="relative">
                            <input
                                type="text"
                                name="location"
                                value="{{ request('location') }}"
                                placeholder="Cari rumah, lokasi atau kawasan..."
                                aria-label="Cari rumah mengikut lokasi"
                                class="h-12 w-full rounded-2xl border border-gray-200 bg-gray-50 pl-4 pr-20 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                            >
                            <button
                                type="submit"
                                class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                            >
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="hidden items-center gap-2 lg:flex">
                @if (!$isLandlord)
                    @if (\Illuminate\Support\Facades\Route::has('student.dashboard'))
                        <a
                            href="{{ route('student.dashboard') }}"
                            class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs('student.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}"
                        >
                            Cari Rumah
                        </a>
                    @endif

                    @if (\Illuminate\Support\Facades\Route::has('favourite'))
                        <a
                            href="{{ route('favourite') }}"
                            class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs('favourite') ? 'bg-rose-50 text-rose-600' : 'text-gray-600 hover:bg-rose-50 hover:text-rose-600' }}"
                        >
                            Kegemaran
                        </a>
                    @endif
                @endif
            </div>

            @auth
                <div class="flex shrink-0 items-center gap-2">
                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <button
                                type="button"
                                class="flex items-center gap-3 rounded-2xl border border-transparent px-2 py-1.5 transition hover:border-gray-200 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-indigo-500/10"
                            >
                                @if ($user->avatar)
                                    <img
                                        src="{{ asset('storage/' . $user->avatar) }}"
                                        alt="{{ $user->name }}"
                                        class="h-10 w-10 rounded-xl object-cover ring-1 ring-gray-200"
                                    >
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-700 font-bold text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div class="hidden max-w-[130px] text-left sm:block">
                                    <div class="truncate text-sm font-bold text-gray-800">{{ $user->name }}</div>
                                    <div class="mt-0.5 text-xs capitalize text-gray-500">{{ $user->role }}</div>
                                </div>

                                <svg class="hidden h-4 w-4 text-gray-400 sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="border-b border-gray-100 bg-gray-50/80 px-4 py-4">
                                <p class="truncate text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                <p class="mt-1 truncate text-xs text-gray-500">{{ $user->email }}</p>
                                <span class="mt-3 inline-flex rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold capitalize text-indigo-700">
                                    {{ $user->role }}
                                </span>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                Profil Saya
                            </x-dropdown-link>

                            @if (!$isLandlord && \Illuminate\Support\Facades\Route::has('favourite'))
                                <x-dropdown-link :href="route('favourite')">
                                    Rumah Kegemaran
                                </x-dropdown-link>
                            @endif

                            <div class="my-1 border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    <span class="text-red-600">Log Keluar</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth
        </div>

        <div class="border-t border-gray-100 py-3 lg:hidden">
            @if ($isLandlord)
                <div class="flex flex-wrap gap-2">
                    @foreach ($landlordLinks as [$label, $routeName, $patterns])
                        @if (\Illuminate\Support\Facades\Route::has($routeName))
                            @php
                                $active = collect($patterns)->contains(
                                    fn ($pattern) => request()->routeIs($pattern)
                                );
                            @endphp

                            <a
                                href="{{ route($routeName) }}"
                                class="rounded-xl px-3 py-2 text-sm font-semibold transition {{ $active ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}"
                            >
                                {{ $label }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-2 gap-2">
                    @if (\Illuminate\Support\Facades\Route::has('student.dashboard'))
                        <a
                            href="{{ route('student.dashboard') }}"
                            class="rounded-xl px-3 py-2.5 text-center text-sm font-semibold {{ request()->routeIs('student.dashboard') ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-600' }}"
                        >
                            Cari Rumah
                        </a>
                    @endif

                    @if (\Illuminate\Support\Facades\Route::has('favourite'))
                        <a
                            href="{{ route('favourite') }}"
                            class="rounded-xl px-3 py-2.5 text-center text-sm font-semibold {{ request()->routeIs('favourite') ? 'bg-rose-500 text-white' : 'bg-gray-50 text-gray-600' }}"
                        >
                            Kegemaran
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</nav>


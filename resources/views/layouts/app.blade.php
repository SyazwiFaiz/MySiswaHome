
@php
    $user = Auth::user();

    $menu = [
        [
            'label' => 'Cari Rumah',
            'short' => 'Cari',
            'route' => 'student.dashboard',
            'active' => request()->routeIs('student.dashboard'),
            'icon' => 'M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z',
        ],
        [
            'label' => 'Carian Peta',
            'short' => 'Peta',
            'route' => 'MapSearch',
            'active' => request()->routeIs('MapSearch'),
            'icon' => 'M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z',
        ],
        [
            'label' => 'Favourite',
            'short' => 'Favourite',
            'route' => 'favourite',
            'active' => request()->routeIs('favourite'),
            'icon' => 'M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364 4.318 12.682a4.5 4.5 0 010-6.364z',
        ],
        [
            'label' => 'Profil',
            'short' => 'Profil',
            'route' => 'profile.edit',
            'active' => request()->routeIs('profile.*'),
            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MySiswaHome') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    <div class="min-h-screen">

        {{-- Navigation Atas --}}
        @include('layouts.navigation')

        <div class="flex">

            {{-- SIDEBAR DESKTOP: KEKAL STATIK --}}
            <aside
                class="hidden w-64 shrink-0 flex-col border-r border-gray-200 bg-white lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:flex lg:h-screen">

                {{-- Logo Sidebar --}}
                <div class="flex h-20 shrink-0 items-center gap-3 border-b border-gray-100 px-6">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-700 shadow-lg shadow-indigo-200">
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
                        <p class="text-base font-extrabold tracking-tight text-gray-900">
                            MySiswa<span class="text-indigo-600">Home</span>
                        </p>
                        <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-widest text-gray-400">
                            Student Rental
                        </p>
                    </div>
                </div>

                {{-- Menu Sidebar --}}
                <nav aria-label="Navigasi sidebar"
                     class="flex-1 space-y-1.5 overflow-y-auto px-4 py-6">

                    <p class="mb-4 px-3 text-xs font-bold uppercase tracking-widest text-gray-400">
                        Menu Utama
                    </p>

                    @foreach (array_slice($menu, 0, 3) as $item)
                        <a href="{{ route($item['route']) }}"
                           @if ($item['active']) aria-current="page" @endif
                           class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition duration-200
                           {{ $item['active']
                                ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-200'
                                : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">

                            <svg class="h-5 w-5 shrink-0"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="{{ $item['icon'] }}"/>
                            </svg>

                            <span>{{ $item['label'] }}</span>

                            @if ($item['active'])
                                <span class="ml-auto h-2 w-2 rounded-full bg-white"></span>
                            @endif
                        </a>
                    @endforeach

                    <div class="pt-7">
                        <p class="mb-4 px-3 text-xs font-bold uppercase tracking-widest text-gray-400">
                            Akaun
                        </p>
                    </div>

                    @php
                        $profile = $menu[3];
                    @endphp

                    <a href="{{ route($profile['route']) }}"
                       @if ($profile['active']) aria-current="page" @endif
                       class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition duration-200
                       {{ $profile['active']
                            ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-200'
                            : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">

                        <svg class="h-5 w-5 shrink-0"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="{{ $profile['icon'] }}"/>
                        </svg>

                        <span>{{ $profile['label'] }}</span>

                        @if ($profile['active'])
                            <span class="ml-auto h-2 w-2 rounded-full bg-white"></span>
                        @endif
                    </a>
                </nav>

                {{-- Kad Pengguna Di Bawah Sidebar --}}
                @if ($user)
                    <div class="shrink-0 border-t border-gray-100 p-4">
                        <div class="flex items-center gap-3 rounded-2xl border border-indigo-100 bg-indigo-50 p-3">

                            @if ($user->avatar)
                                <img
                                    src="{{ asset('storage/' . $user->avatar) }}"
                                    alt="{{ $user->name }}"
                                    class="h-10 w-10 shrink-0 rounded-xl object-cover"
                                >
                            @else
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-sm font-bold text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold text-indigo-900">
                                    {{ $user->name }}
                                </p>
                                <p class="truncate text-xs text-indigo-500">
                                    {{ $user->email }}
                                </p>
                            </div>

                            <svg class="h-4 w-4 shrink-0 text-indigo-400"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="m9 18 6-6-6-6"/>
                            </svg>
                        </div>
                    </div>
                @endif
            </aside>

            {{-- KANDUNGAN UTAMA --}}
            <main class="min-w-0 flex-1 pb-24 lg:ml-64 lg:pb-0">

                {{-- Tajuk Halaman --}}
                @isset($header)
                    <header class="border-b border-gray-200 bg-white">
                        <div class="px-5 py-5 sm:px-8 lg:px-10">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                {{-- Kandungan Halaman --}}
                <div class="px-5 py-6 sm:px-8 sm:py-8 lg:px-10">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>

    {{-- NAVIGASI TELEFON --}}
    <nav aria-label="Navigasi utama telefon"
         class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white/95 backdrop-blur-xl lg:hidden"
         style="padding-bottom: env(safe-area-inset-bottom, 0px);">

        <div class="mx-auto grid max-w-md grid-cols-4">

            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}"
                   @if ($item['active']) aria-current="page" @endif
                   class="flex flex-col items-center gap-1 px-2 py-2.5 text-[11px] font-semibold transition
                   {{ $item['active'] ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">

                    <span class="flex h-8 w-12 items-center justify-center rounded-full transition
                        {{ $item['active'] ? 'bg-indigo-100' : '' }}">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="{{ $item['icon'] }}"/>
                        </svg>
                    </span>

                    {{ $item['short'] }}
                </a>
            @endforeach

        </div>
    </nav>

    @stack('scripts')

</body>
</html>

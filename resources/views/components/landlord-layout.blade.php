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

@php
    $user = auth()->user();
    $has = fn ($name) => \Illuminate\Support\Facades\Route::has($name);

    // Admin yang melawat halaman /landlord/* turut nampak menu landlord
    $isLandlord = $user && ($user->role === 'landlord' || ($user->role === 'admin' && request()->is('landlord/*')));

    $icons = [
        'dashboard' => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z',
        'add' => 'M3 10.5L12 3l9 7.5V21H5a2 2 0 01-2-2V10.5zM12 11v6m-3-3h6',
        'inbox' => 'M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z',
        'star' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z',
        'doc' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'search' => 'M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z',
        'map' => 'M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z',
        'heart' => 'M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364 4.318 12.682a4.5 4.5 0 010-6.364z',
        'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    ];

    if ($isLandlord) {
        $pending = 0;
        try {
            $pending = \App\Models\Inquiry::whereHas('house', fn ($q) => $q->where('landlord_id', $user->id))->where('status', 'pending')->count();
        } catch (\Throwable $e) {
            // jadual belum wujud (migrate belum dijalankan)
        }

        $heading = 'Menu Landlord';
        $tagline = 'Urus rumah, permintaan pelajar dan sewaan anda di satu tempat.';
        $menu = [
            ['label' => 'Dashboard', 'short' => 'Utama', 'route' => 'landlord.x.overview', 'active' => request()->routeIs('landlord.x.overview', 'landlord.x.houses.edit'), 'icon' => $icons['dashboard'], 'badge' => 0],
            ['label' => 'Tambah Rumah', 'short' => 'Tambah', 'route' => 'landlord.x.houses.create', 'active' => request()->routeIs('landlord.x.houses.create'), 'icon' => $icons['add'], 'badge' => 0],
            ['label' => 'Permintaan', 'short' => 'Minat', 'route' => 'landlord.x.inquiries', 'active' => request()->routeIs('landlord.x.inquiries'), 'icon' => $icons['inbox'], 'badge' => $pending],
            ['label' => 'Ulasan', 'short' => 'Ulasan', 'route' => 'landlord.x.reviews', 'active' => request()->routeIs('landlord.x.reviews'), 'icon' => $icons['star'], 'badge' => 0],
            ['label' => 'Sewaan', 'short' => 'Sewaan', 'route' => 'landlord.x.tenancies.index', 'active' => request()->routeIs('landlord.x.tenancies.*'), 'icon' => $icons['doc'], 'badge' => 0],
        ];
    } else {
        $heading = 'Menu Utama';
        $tagline = 'Cari rumah sewa yang sesuai untuk anda.';
        $mapRoute = $has('MapSearch') ? 'MapSearch' : 'permohonan';
        $menu = [
            ['label' => 'Cari Rumah', 'short' => 'Cari', 'route' => 'student.dashboard', 'active' => request()->routeIs('student.dashboard'), 'icon' => $icons['search'], 'badge' => 0],
            ['label' => 'Carian Peta', 'short' => 'Peta', 'route' => $mapRoute, 'active' => request()->routeIs($mapRoute), 'icon' => $icons['map'], 'badge' => 0],
            ['label' => 'Favourite', 'short' => 'Favourite', 'route' => 'favourite', 'active' => request()->routeIs('favourite'), 'icon' => $icons['heart'], 'badge' => 0],
            ['label' => 'Permintaan Saya', 'short' => 'Minta', 'route' => 'student.x.inquiries', 'active' => request()->routeIs('student.x.inquiries'), 'icon' => $icons['inbox'], 'badge' => 0],
        ];
    }

    // Sembunyikan menu yang route-nya belum wujud
    $menu = array_values(array_filter($menu, fn ($m) => $has($m['route'])));

    $profile = ['label' => 'Profil', 'short' => 'Profil', 'route' => 'profile.edit', 'active' => request()->routeIs('profile.*'), 'icon' => $icons['user'], 'badge' => 0];
    $hasProfile = $has('profile.edit');
    $mobileMenu = $hasProfile ? array_merge($menu, [$profile]) : $menu;
@endphp

<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    <div class="min-h-screen pt-[76px]">

        <div id="topNavWrap" style="display: contents;">
            @include('layouts.navigation')
        </div>

        <div class="flex min-h-[calc(100vh-64px)]">

            {{-- SIDEBAR DESKTOP --}}
            <aside id="landlordSidebar" class="fixed bottom-0 left-0 top-[76px] z-30 hidden w-64 flex-col border-r border-gray-200 bg-white lg:flex">

                <nav aria-label="Navigasi sidebar" class="flex-1 space-y-1.5 overflow-y-auto px-4 py-6">

                    <p class="mb-4 px-3 text-xs font-bold uppercase tracking-widest text-gray-400">{{ $heading }}</p>

                    @foreach ($menu as $item)
                        <a href="{{ route($item['route']) }}"
                           @if ($item['active']) aria-current="page" @endif
                           class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition duration-200
                           {{ $item['active']
                                ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-200'
                                : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">

                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>

                            <span>{{ $item['label'] }}</span>

                            @if ($item['badge'])
                                <span class="ml-auto rounded-full px-2 py-0.5 text-[11px] font-bold leading-none {{ $item['active'] ? 'bg-white text-indigo-600' : 'bg-rose-500 text-white' }}">{{ $item['badge'] }}</span>
                            @elseif ($item['active'])
                                <span class="ml-auto h-2 w-2 rounded-full bg-white"></span>
                            @endif
                        </a>
                    @endforeach

                    @if ($hasProfile)
                        <div class="pt-7">
                            <p class="mb-4 px-3 text-xs font-bold uppercase tracking-widest text-gray-400">Akaun</p>
                        </div>

                        <a href="{{ route($profile['route']) }}"
                           @if ($profile['active']) aria-current="page" @endif
                           class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition duration-200
                           {{ $profile['active']
                                ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-200'
                                : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">

                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $profile['icon'] }}" />
                            </svg>

                            <span>{{ $profile['label'] }}</span>

                            @if ($profile['active'])
                                <span class="ml-auto h-2 w-2 rounded-full bg-white"></span>
                            @endif
                        </a>
                    @endif
                </nav>

                {{-- Kad pengguna --}}
                @if ($user)
                    <div class="shrink-0 border-t border-gray-100 p-4">
                        <div class="flex items-center gap-3 rounded-2xl border border-indigo-100 bg-indigo-50 p-3">
                            @if ($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-10 w-10 shrink-0 rounded-xl object-cover">
                            @else
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-sm font-bold text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold text-indigo-900">{{ $user->name }}</p>
                                <p class="truncate text-xs text-indigo-500">{{ $user->email }}</p>
                            </div>

                            <svg class="h-4 w-4 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                            </svg>
                        </div>
                        <p class="mt-3 px-1 text-[11px] leading-4 text-gray-400">{{ $tagline }}</p>
                    </div>
                @endif
            </aside>

            {{-- KANDUNGAN UTAMA --}}
            <main id="landlordMain" class="min-w-0 flex-1 pb-24 lg:ml-64 lg:pb-0">

                @isset($header)
                    <header class="border-b border-gray-200 bg-white">
                        <div class="px-4 py-4 sm:px-6 lg:px-8 lg:py-5">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <div class="px-4 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>

    {{-- NAVIGASI TELEFON --}}
    <nav aria-label="Navigasi utama telefon"
         class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white/95 backdrop-blur-xl lg:hidden"
         style="padding-bottom: env(safe-area-inset-bottom, 0px);">

        <div class="mx-auto grid max-w-md" style="grid-template-columns: repeat({{ max(count($mobileMenu), 1) }}, minmax(0, 1fr));">
            @foreach ($mobileMenu as $item)
                <a href="{{ route($item['route']) }}"
                   @if ($item['active']) aria-current="page" @endif
                   class="flex flex-col items-center gap-1 px-1 py-2.5 text-[11px] font-semibold transition
                   {{ $item['active'] ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">

                    <span class="relative flex h-8 w-12 items-center justify-center rounded-full transition {{ $item['active'] ? 'bg-indigo-100' : '' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                        </svg>
                        @if ($item['badge'])
                            <span class="absolute right-1 top-0 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold leading-none text-white">{{ $item['badge'] }}</span>
                        @endif
                    </span>

                    {{ $item['short'] }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Rapatkan kandungan dan sidebar tepat di bawah navbar atas, tanpa bergantung pada ketinggian tetap --}}
    <script>
    (function () {
        var wrap = document.getElementById('topNavWrap');
        var main = document.getElementById('landlordMain');
        var side = document.getElementById('landlordSidebar');
        if (!wrap || !main) return;

        function navBottom() {
            var bottom = 0;
            wrap.querySelectorAll('*').forEach(function (el) {
                var pos = getComputedStyle(el).position;
                if (pos !== 'fixed' && pos !== 'sticky') return;
                var r = el.getBoundingClientRect();
                if (r.height > 0 && r.top <= 1) bottom = Math.max(bottom, r.bottom);
            });
            if (!bottom && wrap.firstElementChild) bottom = wrap.firstElementChild.getBoundingClientRect().bottom;
            return bottom;
        }

        function align() {
            if (window.scrollY > 0) return;
            var target = Math.round(navBottom());
            if (!target) return;
            main.style.marginTop = '0px';
            var top = Math.round(main.getBoundingClientRect().top);
            main.style.marginTop = (target - top) + 'px';
            if (side) side.style.top = target + 'px';
        }

        align();
        window.addEventListener('load', align);
        window.addEventListener('resize', align);
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(align);
    })();
    </script>

    @stack('scripts')

</body>
</html>

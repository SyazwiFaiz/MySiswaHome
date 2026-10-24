<!DOCTYPE html>
<html lang="ms" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Papan pemuka') – Mysiswa Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Pembangunan: Tailwind CDN. Untuk produksi, tukar kepada Vite + tailwindcss (lihat README). --}}
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    paper: '#F9FAFB', ink: '#1F2937', mute: '#6B7280', line: '#E5E7EB',
                },
                fontFamily: {
                    display: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                },
            } },
        };
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .btn { @apply inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500 focus-visible:ring-offset-2 disabled:opacity-50; }
            .btn-primary { @apply bg-indigo-600 text-white shadow-sm hover:bg-indigo-700; }
            .btn-ghost { @apply border border-indigo-200 bg-white text-indigo-600 hover:bg-indigo-50; }
            .btn-danger { @apply bg-rose-700 text-white hover:bg-rose-800; }
            .btn-sm { @apply px-3 py-2 text-xs; }
            .input { @apply block w-full rounded-xl border border-line bg-white px-3 py-2 text-sm text-ink placeholder:text-mute/70 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600/20; }
            .label { @apply mb-1 block text-sm font-medium text-ink; }
            .panel { @apply rounded-2xl border border-gray-100 bg-white shadow-sm; }
            .th { @apply px-4 py-2.5 text-left text-xs font-semibold text-mute; }
            .td { @apply px-4 py-3 text-sm; }
            .link { @apply font-semibold text-indigo-600 underline-offset-2 hover:underline; }
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important} @media (prefers-reduced-motion:reduce){*{transition:none!important;animation:none!important}}</style>
    @stack('head')
</head>

@php
    $nav = [
        ['Papan pemuka', 'admin.dashboard', 'admin.dashboard', 'M3.75 3.75h6.5v6.5h-6.5zM13.75 3.75h6.5v6.5h-6.5zM3.75 13.75h6.5v6.5h-6.5zM13.75 13.75h6.5v6.5h-6.5z'],
        ['Rumah', 'admin.houses.index', 'admin.houses.*', 'M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M10 21v-6h4v6'],
        ['Pengguna', 'admin.users.index', 'admin.users.*', 'M16 19v-1.5a3.5 3.5 0 00-3.5-3.5h-5A3.5 3.5 0 004 17.5V19M10 11a3.5 3.5 0 100-7 3.5 3.5 0 000 7zM20 19v-1.5a3.5 3.5 0 00-2.5-3.35M15.5 4.2a3.5 3.5 0 010 6.6'],
        ['Kegemaran', 'admin.favourites.index', 'admin.favourites.*', 'M12 20s-7-4.4-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.6-7 10-7 10z'],
        ['Log aktiviti', 'admin.activity.index', 'admin.activity.*', 'M12 7v5l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

<body class="h-full bg-paper font-sans text-ink antialiased" x-data="{ nav: false }" @keydown.escape.window="nav = false">

    {{-- Overlay mobile --}}
    <div x-show="nav" x-cloak x-transition.opacity class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden" @click="nav = false"></div>

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-gray-100 bg-white text-gray-600 transition-transform lg:translate-x-0"
           :class="{ '!translate-x-0': nav }" aria-label="Menu utama">
        <div class="flex h-16 items-center justify-between px-5">
            <a href="{{ route('admin.dashboard') }}" class="font-display text-xl font-extrabold tracking-tight text-indigo-600">
                Mysiswa <span class="ml-1 rounded-full bg-indigo-100 px-2 py-0.5 align-middle font-sans text-[11px] font-semibold text-indigo-700">Admin</span>
            </a>
            <button class="rounded-lg p-1 text-gray-500 hover:bg-gray-100 lg:hidden" @click="nav = false" aria-label="Tutup menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            @foreach ($nav as [$label, $route, $pattern, $icon])
                @php $active = request()->routeIs($pattern); @endphp
                <a href="{{ route($route) }}"
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500
                          {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}"
                   @if ($active) aria-current="page" @endif>
                    @if ($active)<span class="absolute inset-y-2 left-0 w-1 rounded-r bg-indigo-600"></span>@endif
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="border-t border-gray-100 p-3">
            <div class="flex items-center gap-3 rounded-lg px-2 py-2">
                <x-avatar :user="auth()->user()" class="h-9 w-9" />
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button class="w-full rounded-xl px-3 py-2 text-left text-sm text-gray-600 hover:bg-gray-50 hover:text-indigo-700">Log keluar</button>
            </form>
        </div>
    </aside>

    {{-- Kandungan --}}
    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-gray-100 bg-white/90 px-4 backdrop-blur sm:px-6">
            <button class="-ml-1 rounded-lg p-2 text-ink hover:bg-white lg:hidden" @click="nav = true" aria-label="Buka menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
            <h1 class="truncate font-display text-xl font-bold tracking-tight">@yield('heading', 'Papan pemuka')</h1>
            <div class="ml-auto flex items-center gap-2">
                @yield('actions')
                <a href="{{ url('/') }}" target="_blank" class="btn btn-ghost btn-sm hidden sm:inline-flex">Lihat laman</a>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6">
            @if (session('success') || session('error') || $errors->has('ids') || $errors->has('action'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition.opacity role="status"
                     class="mb-5 flex items-start justify-between gap-3 rounded-lg border px-4 py-3 text-sm {{ session('error') || $errors->has('ids') || $errors->has('action') ? 'border-rose-200 bg-rose-50 text-rose-900' : 'border-indigo-200 bg-indigo-50 text-indigo-900' }}">
                    <span>{{ session('success') ?? session('error') ?? $errors->first('ids') ?? $errors->first('action') }}</span>
                    <button @click="show = false" class="text-current/60 hover:text-current" aria-label="Tutup">✕</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Modal pengesahan padam (dipakai semua halaman) --}}
    <div x-data="{ open: false, action: '', label: '', note: '' }" x-cloak
         @confirm-delete.window="open = true; action = $event.detail.action; label = $event.detail.label; note = $event.detail.note || ''"
         @keydown.escape.window="open = false">
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-end justify-center bg-gray-900/50 p-4 sm:items-center" @click.self="open = false">
            <form method="POST" :action="action" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl" role="dialog" aria-modal="true" aria-labelledby="del-title">
                @csrf @method('DELETE')
                <h2 id="del-title" class="font-display text-lg font-bold">Padam item ini?</h2>
                <p class="mt-2 text-sm text-mute"><strong class="text-ink" x-text="label"></strong></p>
                <p class="mt-2 text-sm text-mute" x-show="note" x-text="note"></p>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" class="btn btn-ghost" @click="open = false">Batal</button>
                    <button class="btn btn-danger">Ya, padam</button>
                </div>
            </form>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

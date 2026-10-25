<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MySiswaHome') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-800">

    @php
        $user = auth()->user();
        $isLandlord = $user && $user->role === 'landlord';
    @endphp

    <div class="min-h-screen pt-[76px]">

        @include('layouts.navigation')

        <div class="flex min-h-[calc(100vh-64px)]">

            <aside class="fixed left-0 top-[76px] bottom-0 hidden h-auto w-64 flex-col overflow-y-hidden border-r border-gray-200 bg-white lg:flex">

                <nav class="flex-1 space-y-1 px-3 py-3">

                    <p class="mb-4 px-3 text-xs font-bold uppercase tracking-widest text-gray-400">
                        {{ $isLandlord ? 'Menu Landlord' : 'Menu Utama' }}
                    </p>

                    @if ($isLandlord)

                        <a href="{{ route('landlord.x.overview') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                            {{ request()->routeIs('landlord.x.overview', 'landlord.create')
                                ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"/></svg>

                            <span class="font-semibold">Dashboard</span>
                        </a>

                        @if (\Illuminate\Support\Facades\Route::has('landlord.houses.create'))
                            <a href="{{ route('landlord.houses.create') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                                {{ request()->routeIs('landlord.houses.create')
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10.5 12 3l9 7.5V21H5a2 2 0 0 1-2-2V10.5Z" stroke-width="2" stroke-linejoin="round"/><path d="M12 11v6m-3-3h6" stroke-width="2" stroke-linecap="round"/></svg>

                                <span class="font-semibold">Create House</span>
                            </a>
                        @endif

                        <a href="{{ route('landlord.manage') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                            {{ request()->routeIs('landlord.manage', 'landlord.edit', 'landlord.update', 'landlord.delete')
                                ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="14" rx="2" stroke-width="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2" stroke-width="2" stroke-linejoin="round"/></svg>

                            <span class="font-semibold">Manage House</span>
                        </a>

                        @if (\Illuminate\Support\Facades\Route::has('landlord.x.inquiries'))
                            <a href="{{ route('landlord.x.inquiries') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                                {{ request()->routeIs('landlord.x.inquiries')
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 11.5a8.4 8.4 0 0 1-8.4 8.4 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.8a8.4 8.4 0 0 1-.9-3.7A8.5 8.5 0 0 1 12.5 3h.5a8.5 8.5 0 0 1 8 8v.5Z" stroke-width="2" stroke-linejoin="round"/><path d="M8 12h8m-8-3h5" stroke-width="2" stroke-linecap="round"/></svg>

                                <span class="font-semibold">Permintaan</span>
                            </a>
                        @endif

                        @if (\Illuminate\Support\Facades\Route::has('landlord.x.reviews'))
                            <a href="{{ route('landlord.x.reviews') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                                {{ request()->routeIs('landlord.x.reviews')
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z" stroke-width="2" stroke-linejoin="round"/></svg>

                                <span class="font-semibold">Ulasan</span>
                            </a>
                        @endif

                        @if (\Illuminate\Support\Facades\Route::has('landlord.x.tenancies.index'))
                            <a href="{{ route('landlord.x.tenancies.index') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                                {{ request()->routeIs('landlord.x.tenancies.*')
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke-width="2" stroke-linejoin="round"/><path d="M14 3v5h5M9 13h6m-6 4h6" stroke-width="2" stroke-linecap="round"/></svg>

                                <span class="font-semibold">Sewaan</span>
                            </a>
                        @endif

                    @else

                        @if (\Illuminate\Support\Facades\Route::has('student.dashboard'))
                            <a href="{{ route('student.dashboard') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                                {{ request()->routeIs('student.dashboard')
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="7" stroke-width="2" />
                                    <path stroke-linecap="round" stroke-width="2" d="m16 16 4 4" />
                                </svg>

                                <span class="font-semibold">Cari Rumah</span>
                            </a>
                        @endif

                        @if (\Illuminate\Support\Facades\Route::has('permohonan'))
                            <a href="{{ route('permohonan') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                                {{ request()->routeIs('permohonan')
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 21s7-5.5 7-12a7 7 0 10-14 0c0 6.5 7 12 7 12z" />
                                    <circle cx="12" cy="9" r="2" stroke-width="2" />
                                </svg>

                                <span class="font-semibold">Carian Peta</span>
                            </a>
                        @endif

                        @if (\Illuminate\Support\Facades\Route::has('favourite'))
                            <a href="{{ route('favourite') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                                {{ request()->routeIs('favourite')
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 00-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 00-.1-7.8z" />
                                </svg>

                                <span class="font-semibold">Favourite</span>
                            </a>
                        @endif

                    @endif

                    <div class="pt-8">
                        <p class="mb-4 px-3 text-xs font-bold uppercase tracking-widest text-gray-400">
                            Akaun
                        </p>

                        @if (\Illuminate\Support\Facades\Route::has('profile.edit'))
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 rounded-xl px-3 py-2 transition duration-200
                                {{ request()->routeIs('profile.*')
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke-width="2"/><path d="M4 21a8 8 0 0 1 16 0" stroke-width="2" stroke-linecap="round"/></svg>

                                <span class="font-semibold">Profil</span>
                            </a>
                        @endif
                    </div>

                </nav>

                <div class="border-t border-gray-100 p-3">
                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                        <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-white text-xl shadow-sm">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <p class="text-sm font-bold text-indigo-800">MySiswaHome</p>

                        <p class="mt-1 text-xs leading-5 text-indigo-600">
                            {{ $isLandlord
                                ? 'Urus dan kemas kini rumah sewa anda dengan mudah.'
                                : 'Cari rumah sewa yang sesuai untuk anda.' }}
                        </p>
                    </div>
                </div>

            </aside>

            <main class="min-w-0 flex-1 lg:ml-64">

                @isset($header)
                    <header class="border-b border-gray-200 bg-white">
                        <div class="px-4 py-5 sm:px-6 lg:px-10 lg:py-6">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <div class="px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
                    {{ $slot }}
                </div>

            </main>

        </div>
    </div>

</body>
</html>









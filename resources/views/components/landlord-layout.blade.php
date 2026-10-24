<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MySiswaHome') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap"
        rel="stylesheet">

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <div class="min-h-screen">

        {{-- Navigation --}}
        @include('layouts.navigation')

        <div class="flex min-h-[calc(100vh-64px)]">

            {{-- Sidebar --}}
            <aside class="hidden w-64 shrink-0 flex-col border-r border-gray-200 bg-white lg:flex">

                {{-- Sidebar Logo --}}
             
                {{-- Sidebar Menu --}}
                <nav class="flex-1 space-y-2 px-4 py-6">

                    <p class="mb-4 px-3 text-xs font-bold uppercase tracking-widest text-gray-400">
                        Menu Utama
                    </p>

                    {{-- Manage House --}}
                    <a href="{{ route('landlord.manage') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                        {{ request()->routeIs('landlord.manage')
                            ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                            : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1V10.5z" />
                        </svg>

                        <span class="font-semibold">
                            Manage House
                        </span>
                    </a>

                    {{-- Create Listing --}}
                    <a href="{{ route('landlord.create') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                        {{ request()->routeIs('landlord.create', 'landlord.houses.create')
                            ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                            : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>

                        <span class="font-semibold">
                            Create Listing
                        </span>
                    </a>

                    {{-- More --}}
                    {{-- Gunakan route yang memang wujud --}}
                    <a href="{{ route('landlord.manage') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                        text-gray-600 hover:bg-indigo-50 hover:text-indigo-700">

                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>

                        <span class="font-semibold">
                            More
                        </span>
                    </a>

                    {{-- Account Section --}}
                    <div class="pt-8">
                        <p class="mb-4 px-3 text-xs font-bold uppercase tracking-widest text-gray-400">
                            Akaun
                        </p>

                        {{-- Profile --}}
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 transition duration-200
                            {{ request()->routeIs('profile.*')
                                ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                                : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">

                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>

                            <span class="font-semibold">
                                Profil
                            </span>
                        </a>
                    </div>

                </nav>

                {{-- Sidebar Bottom --}}
                <div class="border-t border-gray-100 p-4">

                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4">

                        <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-white text-xl shadow-sm">
                            🏠
                        </div>

                        <p class="text-sm font-bold text-indigo-800">
                            MySiswaHome
                        </p>

                        <p class="mt-1 text-xs leading-5 text-indigo-600">
                            Urus dan kemas kini rumah sewa anda dengan mudah.
                        </p>

                    </div>

                </div>

            </aside>

            {{-- Main Content --}}
            <main class="min-w-0 flex-1">

                {{-- Page Heading --}}
                @isset($header)
                    <header class="border-b border-gray-200 bg-white">

                        <div class="px-4 py-5 sm:px-6 lg:px-10 lg:py-6">
                            {{ $header }}
                        </div>

                    </header>
                @endisset

                {{-- Page Content --}}
                <div class="px-4 py-6 sm:px-6 lg:px-10 lg:py-8">

                    {{ $slot }}

                </div>

            </main>

        </div>

    </div>

</body>

</html>

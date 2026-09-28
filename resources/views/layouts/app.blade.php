<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MySiswaHome') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased bg-gray-50 text-gray-800">

    <div class="min-h-screen">

        <!-- Navigation -->
        @include('layouts.navigation')


        <div class="flex">

            <!-- Sidebar -->
            <aside class="hidden lg:flex lg:flex-col w-64 min-h-[calc(100vh-64px)] bg-white border-r border-gray-200">

                <!-- Sidebar Logo -->
                <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-100">

                    <img src="{{ asset('img/logo.png') }}" alt="MySiswaHome" class="h-12 w-auto object-contain">

                    <div>

                        <h1 class="text-lg font-bold text-gray-800">
                            MySiswaHome
                        </h1>

                        <p class="text-xs text-gray-400">
                            Student Rental
                        </p>

                    </div>

                </div>


                {{-- Sidebar Menu --}}
                <nav class="flex-1 px-4 py-6 space-y-2">

                    <p class="px-3 mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Menu
                    </p>


                    {{-- Cari Rumah --}}
                    <a href="{{ route('student.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
    {{ request()->routeIs('student.dashboard')
        ? 'bg-indigo-600 text-white shadow-sm'
        : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                        </svg>

                        <span class="font-medium">
                            Cari Rumah
                        </span>
                    </a>


                    {{-- Favourite --}}
                    <a href="{{ route('favourite') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
        {{ request()->routeIs('favourite')
            ? 'bg-indigo-600 text-white shadow-sm'
            : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364 4.318 12.682a4.5 4.5 0 010-6.364z" />
                        </svg>

                        <span class="font-medium">
                            Favourite
                        </span>
                    </a>


                    {{-- Permohonan --}}
                    <a href="{{ route('permohonan') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
        {{ request()->routeIs('permohonan')
            ? 'bg-indigo-600 text-white shadow-sm'
            : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>

                        <span class="font-medium">
                            Permohonan
                        </span>
                    </a>


                    {{-- Akaun --}}
                    <div class="pt-6">
                        <p class="px-3 mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                            Akaun
                        </p>
                    </div>


                    {{-- Profil --}}
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
        {{ request()->routeIs('profile.*')
            ? 'bg-indigo-600 text-white shadow-sm'
            : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>

                        <span class="font-medium">
                            Profil
                        </span>
                    </a>

                </nav>


                <!-- Sidebar Bottom -->
                <div class="p-4 border-t border-gray-100">

                    <div class="p-4 rounded-xl bg-indigo-50">

                        <p class="text-sm font-semibold text-indigo-700">
                            🏠 MySiswaHome
                        </p>

                        <p class="mt-1 text-xs text-indigo-500">
                            Cari rumah sewa yang sesuai dengan keperluan anda.
                        </p>

                    </div>

                </div>

            </aside>


            <!-- Main Content -->
            <main class="flex-1 min-w-0">

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-gray-200">

                        <div class="px-6 py-6 sm:px-8 lg:px-10">

                            {{ $header }}

                        </div>

                    </header>
                @endisset


                <!-- Page Content -->
                <div class="px-6 py-8 sm:px-8 lg:px-10">

                    {{ $slot }}

                </div>

            </main>

        </div>

    </div>

</body>

</html>

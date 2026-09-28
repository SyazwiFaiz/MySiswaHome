<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'MySiswaHome') }}</title>

{{-- Fonts --}}
<link rel="preconnect" href="https://fonts.bunny.net">

<link
    href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
    rel="stylesheet"
/>

{{-- Scripts --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="min-h-screen bg-slate-50 font-sans antialiased text-slate-900">

<div class="min-h-screen w-full">

    {{-- Main Authentication Container --}}
    <div class="flex min-h-screen w-full flex-col lg:flex-row">

        {{-- =========================================
             LEFT SIDE - BRANDING
        ========================================== --}}
        <div class="relative hidden min-h-screen overflow-hidden bg-indigo-700 lg:flex lg:w-1/2">

            {{-- Decorative Background --}}
            <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-indigo-500/40"></div>

            <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-indigo-800/50"></div>

            <div class="relative z-10 flex min-h-screen w-full flex-col justify-between p-10 xl:p-14">

                {{-- Logo --}}
                <div>
                    <a
                        href="{{ url('/') }}"
                        class="inline-flex items-center gap-3"
                    >

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-lg">
                            <img
                                src="{{ asset('img/logo.png') }}"
                                alt="MySiswaHome"
                                class="h-9 w-auto object-contain"
                            >
                        </div>

                        <div>
                            <p class="text-xl font-bold tracking-tight text-white">
                                MySiswaHome
                            </p>

                            <p class="text-xs text-indigo-200">
                                Your student home
                            </p>
                        </div>

                    </a>
                </div>


                {{-- Introduction --}}
                <div class="max-w-xl">

                    <span class="mb-6 inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium text-indigo-100 ring-1 ring-inset ring-white/20">
                         Student Accommodation Platform
                    </span>

                    <h1 class="text-4xl font-bold leading-tight tracking-tight text-white xl:text-6xl">
                        Your journey to a
                        <span class="block text-indigo-200">
                            better student home.
                        </span>
                    </h1>

                    <p class="mt-6 max-w-lg text-base leading-7 text-indigo-100 xl:text-lg">
                        MySiswaHome helps students discover and manage
                        accommodation in a simple and convenient way.
                    </p>


                    {{-- Features --}}
                    <div class="mt-10 space-y-5">

                        {{-- Feature 1 --}}
                        <div class="flex items-center gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-lg">
                                🏠
                            </div>

                            <div>
                                <p class="font-semibold text-white">
                                    Find your ideal home
                                </p>

                                <p class="text-sm text-indigo-200">
                                    Explore accommodation suitable for students.
                                </p>
                            </div>

                        </div>


                        {{-- Feature 2 --}}
                        <div class="flex items-center gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-lg">
                                🔐
                            </div>

                            <div>
                                <p class="font-semibold text-white">
                                    Safe & secure
                                </p>

                                <p class="text-sm text-indigo-200">
                                    Keep your account and information protected.
                                </p>
                            </div>

                        </div>


                        {{-- Feature 3 --}}
                        <div class="flex items-center gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-lg">
                                ✨
                            </div>

                            <div>
                                <p class="font-semibold text-white">
                                    Simple experience
                                </p>

                                <p class="text-sm text-indigo-200">
                                    Manage your student accommodation with ease.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <p class="text-sm text-indigo-200">
                    © {{ date('Y') }} MySiswaHome. All rights reserved.
                </p>

            </div>

        </div>


        {{-- =========================================
             RIGHT SIDE - PAGE CONTENT
        ========================================== --}}
        <div class="flex min-h-screen w-full items-center justify-center bg-white px-5 py-10 sm:px-8 lg:w-1/2 lg:px-12 xl:px-20">

            <div class="w-full max-w-md">

                {{-- Mobile Logo --}}
                <div class="mb-8 flex justify-center lg:hidden">

                    <a
                        href="{{ url('/') }}"
                        class="flex items-center gap-3"
                    >

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-md ring-1 ring-slate-200">
                            <img
                                src="{{ asset('img/logo.png') }}"
                                alt="MySiswaHome"
                                class="h-9 w-auto object-contain"
                            >
                        </div>

                        <div>
                            <p class="text-xl font-bold tracking-tight text-slate-900">
                                MySiswaHome
                            </p>

                            <p class="text-xs text-slate-500">
                                Your student home
                            </p>
                        </div>

                    </a>

                </div>


                {{-- Page Content --}}
                <div class="w-full">
                    {{ $slot }}
                </div>

            </div>

        </div>

    </div>

</div>
</body>

</html>

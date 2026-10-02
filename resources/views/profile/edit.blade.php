<x-app-layout>

<x-slot name="header">
    <div>
        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">
            Profile
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Urus maklumat akaun, profil dan keselamatan anda.
        </p>
    </div>
</x-slot>


<div class="min-h-screen bg-gray-50 py-6 sm:py-8">

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">


        {{-- Profile Banner --}}
        <div class="mb-8 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">

            {{-- Banner --}}
            <div class="relative h-48 overflow-hidden bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 sm:h-56 lg:h-64">

                {{-- Decorative Background --}}
                <div class="absolute -right-16 -top-28 h-72 w-72 rounded-full bg-white/10"></div>

                <div class="absolute -bottom-32 left-1/4 h-72 w-72 rounded-full bg-white/5"></div>

                <div class="absolute right-1/4 top-10 h-32 w-32 rounded-full bg-white/5"></div>

                {{-- Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/20 to-purple-900/20"></div>


                {{-- Banner Text --}}
                <div class="absolute inset-x-0 bottom-0 px-6 pb-8 sm:px-10 sm:pb-10 lg:px-12">

                    <div class="ml-0 sm:ml-36 lg:ml-40">

                        <p class="mb-1 text-sm font-medium text-indigo-100">
                            Selamat datang kembali !
                        </p>

                        <h1 class="truncate text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                            {{ auth()->user()->name }}
                        </h1>

                        <p class="mt-1 truncate text-sm text-indigo-100 sm:text-base">
                            {{ auth()->user()->email }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Profile Information Bar --}}
            <div class="relative px-6 pb-6 sm:px-10 lg:px-12">

                <div class="-mt-14 flex flex-col gap-5 sm:-mt-16 sm:flex-row sm:items-end">


                    {{-- Avatar --}}
                    <form
                        action="{{ route('profile.avatar') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="avatarForm"
                        class="relative z-10 shrink-0"
                    >
                        @csrf

                        <label
                            for="avatarUpload"
                            class="group relative block cursor-pointer"
                        >

                            <div class="relative flex h-32 w-32 items-center justify-center overflow-hidden rounded-3xl border-4 border-white bg-indigo-100 shadow-xl sm:h-36 sm:w-36">

                                @if (auth()->user()->avatar)

                                    <img
                                        src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        alt="{{ auth()->user()->name }}"
                                        class="h-full w-full object-cover"
                                    >

                                @else

                                    <span class="text-5xl font-extrabold text-indigo-600">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>

                                @endif


                                {{-- Hover --}}
                                <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/55 opacity-0 transition duration-200 group-hover:opacity-100">

                                    <svg
                                        class="mb-1 h-7 w-7 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 7h2l2-3h10l2 3h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="13"
                                            r="3"
                                        />
                                    </svg>

                                    <span class="text-xs font-bold text-white">
                                        Tukar Foto
                                    </span>

                                </div>

                            </div>


                            {{-- Camera Button --}}
                            <div class="absolute -bottom-1 -right-1 flex h-10 w-10 items-center justify-center rounded-full border-4 border-white bg-indigo-600 text-white shadow-lg">

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 7h2l2-3h10l2 3h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z"
                                    />

                                    <circle
                                        cx="12"
                                        cy="13"
                                        r="3"
                                    />
                                </svg>

                            </div>


                            <input
                                type="file"
                                name="avatar"
                                id="avatarUpload"
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                class="hidden"
                                onchange="document.getElementById('avatarForm').submit()"
                            >

                        </label>

                    </form>


                    {{-- User Details --}}
                    <div class="flex min-w-0 flex-1 flex-col gap-3 pb-1 sm:flex-row sm:items-center sm:justify-between">

                        {{-- Role --}}
                        <div class="flex flex-wrap items-center gap-2">

                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-3.5 py-1.5 text-xs font-bold text-indigo-700">

                                <svg
                                    class="mr-1.5 h-3.5 w-3.5"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M10 2a6 6 0 00-6 6c0 4.5 6 10 6 10s6-5.5 6-10a6 6 0 00-6-6zm0 8.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"
                                    />
                                </svg>

                                {{ ucfirst(auth()->user()->role) }}

                            </span>


                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-700">

                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                Akaun Aktif

                            </span>

                        </div>


                        {{-- Photo Hint --}}
                        <p class="text-xs text-gray-400">
                            JPG, PNG atau WEBP
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- Settings --}}
        <div class="grid gap-6 lg:grid-cols-2">


            {{-- Personal Information --}}
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-6 py-5">

                    <div class="flex items-center gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50">

                            <svg
                                class="h-5 w-5 text-indigo-600"
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

                        </div>

                        <div>

                            <h3 class="text-lg font-extrabold text-gray-900">
                                Maklumat Peribadi
                            </h3>

                            <p class="mt-0.5 text-sm text-gray-500">
                                Kemas kini nama dan alamat email anda.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6">

                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                </div>

            </section>


            {{-- Password --}}
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-6 py-5">

                    <div class="flex items-center gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-50">

                            <svg
                                class="h-5 w-5 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 10-8 0v3h8z"
                                />
                            </svg>

                        </div>

                        <div>

                            <h3 class="text-lg font-extrabold text-gray-900">
                                Kata Laluan & Keselamatan
                            </h3>

                            <p class="mt-0.5 text-sm text-gray-500">
                                Pastikan akaun anda dilindungi dengan baik.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6">

                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>

                </div>

            </section>


            {{-- Delete Account --}}
            <section class="overflow-hidden rounded-2xl border border-red-100 bg-white shadow-sm lg:col-span-2">

                <div class="border-b border-red-100 bg-red-50/60 px-6 py-5">

                    <div class="flex items-center gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100">

                            <svg
                                class="h-5 w-5 text-red-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                                />
                            </svg>

                        </div>

                        <div>

                            <h3 class="text-lg font-extrabold text-red-700">
                                Padam Akaun
                            </h3>

                            <p class="mt-0.5 text-sm text-gray-500">
                                Padam akaun dan semua data berkaitan secara kekal.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6">

                    <div class="max-w-3xl">

                        <div class="mb-5 rounded-xl border border-red-100 bg-red-50 px-4 py-3">

                            <div class="flex gap-3">

                                <svg
                                    class="mt-0.5 h-5 w-5 shrink-0 text-red-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                                    />
                                </svg>

                                <p class="text-sm leading-5 text-red-700">
                                    Tindakan ini tidak boleh dibuat asal. Semua maklumat akaun anda akan dipadam secara kekal.
                                </p>

                            </div>

                        </div>

                        @include('profile.partials.delete-user-form')

                    </div>

                </div>

            </section>

        </div>


        {{-- Footer --}}
        <footer class="py-10 text-center">

            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50">

                <svg
                    class="h-5 w-5 text-indigo-600"
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

            <p class="text-sm font-bold text-gray-500">
                MySiswaHome
            </p>

            <p class="mt-1 text-xs text-gray-400">
                Find a better place to call home.
            </p>

        </footer>

    </div>

</div>

</x-app-layout>

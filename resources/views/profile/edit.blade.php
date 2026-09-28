<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Profile
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Urus maklumat akaun dan keselamatan anda.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10">

        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            {{-- Profile Header --}}
            <div class="mb-8 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">

                {{-- Cover --}}
                <div class="h-28 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600">
                </div>
                {{-- Profile Content --}}
                <div class="px-6 pb-7 sm:px-8">

                    <div class="-mt-16 flex flex-col gap-5 sm:flex-row sm:items-start">

                        {{-- Avatar --}}
                        <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data"
                            id="avatarForm" class="shrink-0">
                            @csrf

                            <label for="avatarUpload" class="group relative block cursor-pointer">

                                <div
                                    class="-mt-2  flex h-22 w-28 items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-indigo-100 shadow-lg">

                                    @if (auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                            alt="Profile Picture" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-3xl font-bold text-indigo-600">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </span>
                                    @endif

                                    {{-- Hover --}}
                                    <div
                                        class="absolute inset-1 flex items-center justify-center rounded-xl bg-black/50 opacity-0 transition duration-200 group-hover:opacity-100">

                                        <span class="text-xs font-semibold text-white">
                                            Change
                                        </span>

                                    </div>

                                </div>

                                {{-- Upload --}}
                                <input type="file" name="avatar" id="avatarUpload"
                                    accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden"
                                    onchange="document.getElementById('avatarForm').submit()">

                            </label>

                        </form>


                        {{-- User Information --}}
                        <div
                            class="min-w-0 flex-1 rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm sm:px-6">

                            {{-- Name --}}
                            <div class="min-w-0">

                                <h1 class="truncate text-2xl font-bold leading-tight text-gray-900">
                                    {{ auth()->user()->name }}
                                </h1>

                                {{-- Email --}}
                                <p class="mt-1 truncate text-sm text-gray-500">
                                    {{ auth()->user()->email }}
                                </p>

                            </div>


                            {{-- Account Status --}}
                            <div class="mt-4 flex flex-wrap items-center gap-2">

                                {{-- Student --}}
                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600">
                                    Student
                                </span>

                                {{-- Active --}}
                                <span
                                    class="inline-flex items-center rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-600">

                                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                    Active Account

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Profile Information --}}
            <div class="space-y-6">

                {{-- Personal Information --}}
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-6 py-5 sm:px-8">

                        <div class="flex items-center gap-4">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50">

                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>

                            </div>

                            <div>

                                <h3 class="text-lg font-bold text-gray-900">
                                    Personal Information
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Update your name and email address.
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="p-6 sm:p-8">

                        <div class="max-w-2xl">

                            @include('profile.partials.update-profile-information-form')

                        </div>

                    </div>

                </div>


                {{-- Password --}}
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-6 py-5 sm:px-8">

                        <div class="flex items-center gap-4">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50">

                                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 10-8 0v3h8z" />
                                </svg>

                            </div>

                            <div>

                                <h3 class="text-lg font-bold text-gray-900">
                                    Password & Security
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Keep your account secure with a strong password.
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="p-6 sm:p-8">

                        <div class="max-w-2xl">

                            @include('profile.partials.update-password-form')

                        </div>

                    </div>

                </div>


                {{-- Delete Account --}}
                <div class="overflow-hidden rounded-2xl border border-red-100 bg-white shadow-sm">

                    <div class="border-b border-red-100 bg-red-50/50 px-6 py-5 sm:px-8">

                        <div class="flex items-center gap-4">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-100">

                                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z" />
                                </svg>

                            </div>

                            <div>

                                <h3 class="text-lg font-bold text-red-700">
                                    Delete Account
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Permanently delete your account and all associated data.
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="p-6 sm:p-8">

                        <div class="max-w-2xl">

                            @include('profile.partials.delete-user-form')

                        </div>

                    </div>

                </div>

            </div>


            {{-- Footer --}}
            <div class="py-8 text-center">

                <p class="text-xs text-gray-400">
                    MySiswaHome
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Find a better place to call home.
                </p>

            </div>

        </div>

    </div>

</x-app-layout>

<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Header --}}
        <div class="mb-8">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-indigo-600">
                Welcome to MySiswaHome
            </p>

            <h2 class="text-3xl font-bold tracking-tight text-slate-900">
                Create your account
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Sign up for your MySiswaHome account to continue.
            </p>
        </div>

        {{-- Name --}}
        <div>
            <x-input-label
                for="name"
                :value="__('Name')"
                class="mb-2"
            />

            <x-text-input
                id="name"
                class="block w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Enter your full name"
            />

            <x-input-error
                :messages="$errors->get('name')"
                class="mt-2"
            />
        </div>

        {{-- Email Address --}}
        <div class="mt-4">
            <x-input-label
                for="email"
                :value="__('Email')"
                class="mb-2"
            />

            <x-text-input
                id="email"
                class="block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="you@example.com"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        {{-- Account Type --}}
        <div class="mt-5">
            <x-input-label
                for="role"
                :value="__('Register as')"
                class="mb-3 block text-sm font-medium text-slate-700"
            />

            <div class="grid grid-cols-2 gap-3">

                {{-- Student --}}
                <label class="cursor-pointer">
                    <input
                        type="radio"
                        name="role"
                        value="student"
                        class="peer sr-only"
                        {{ old('role', 'student') === 'student' ? 'checked' : '' }}
                    >

                    <div
                        class="rounded-xl border border-slate-300 bg-slate-50 p-4 text-center
                               transition duration-200
                               hover:border-indigo-300 hover:bg-indigo-50/50
                               peer-checked:border-indigo-600
                               peer-checked:bg-indigo-50
                               peer-checked:ring-2
                               peer-checked:ring-indigo-500/20"
                    >
                        <div class="mb-2 text-2xl">
                            
                        </div>

                        <p class="font-semibold text-slate-900">
                            Student
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Find your student home
                        </p>
                    </div>
                </label>

                {{-- Landlord --}}
                <label class="cursor-pointer">
                    <input
                        type="radio"
                        name="role"
                        value="landlord"
                        class="peer sr-only"
                        {{ old('role') === 'landlord' ? 'checked' : '' }}
                    >

                    <div
                        class="rounded-xl border border-slate-300 bg-slate-50 p-4 text-center
                               transition duration-200
                               hover:border-indigo-300 hover:bg-indigo-50/50
                               peer-checked:border-indigo-600
                               peer-checked:bg-indigo-50
                               peer-checked:ring-2
                               peer-checked:ring-indigo-500/20"
                    >
                        <div class="mb-2 text-2xl">
                            
                        </div>

                        <p class="font-semibold text-slate-900">
                            Landlord
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            List and manage properties
                        </p>
                    </div>
                </label>

            </div>

            <x-input-error
                :messages="$errors->get('role')"
                class="mt-2"
            />
        </div>

        {{-- Password --}}
        <div class="mt-5">
            <x-input-label
                for="password"
                :value="__('Password')"
                class="mb-2"
            />

            <x-text-input
                id="password"
                class="block w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Create a password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>

        {{-- Confirm Password --}}
        <div class="mt-4">
            <x-input-label
                for="password_confirmation"
                :value="__('Confirm Password')"
                class="mb-2"
            />

            <x-text-input
                id="password_confirmation"
                class="block w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm your password"
            />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />
        </div>

        {{-- Buttons --}}
        <div class="mt-6 flex items-center justify-between">

            <a
                class="rounded-md text-sm font-medium text-gray-600 underline
                       hover:text-gray-900
                       focus:outline-none focus:ring-2 focus:ring-indigo-500
                       focus:ring-offset-2"
                href="{{ route('login') }}"
            >
                {{ __('Already registered?') }}
            </a>

            <x-primary-button
                class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold
                       text-white shadow-sm transition duration-200
                       hover:bg-indigo-700 hover:shadow-md
                       focus:outline-none focus:ring-2
                       focus:ring-indigo-500 focus:ring-offset-2
                       active:bg-indigo-800"
            >
                {{ __('Create Account') }}
            </x-primary-button>

        </div>

    </form>

</x-guest-layout>
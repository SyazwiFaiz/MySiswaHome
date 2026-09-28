<nav class="bg-indigo-600 shadow-sm">

    <div class="max-w-full mx-auto px-6 lg:px-8">

        <div class="relative flex items-center justify-between h-16">

            {{-- Home --}}
            <a
                href="{{ route('dashboard') }}"
                class="flex items-center justify-center
                       w-10 h-10
                       rounded-xl
                       text-white
                       hover:bg-indigo-500
                       transition"
                title="Dashboard"
            >
                <svg
                    class="w-6 h-6"
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
            </a>


            {{-- Search Bar --}}
            <div
                class="absolute left-1/2 -translate-x-1/2
                       hidden md:block
                       w-[460px] lg:w-[560px]"
            >

                <form action="#" method="GET">

                    <div class="relative">

                        {{-- Search Icon --}}
                        <div
                            class="absolute inset-y-0 left-0
                                   flex items-center pl-4
                                   pointer-events-none"
                        >

                            <svg
                                class="w-5 h-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                                />
                            </svg>

                        </div>


                        {{-- Input --}}
                        <input
                            type="text"
                            name="search"
                            placeholder="Cari rumah sewa, lokasi atau kawasan..."
                            class="w-full h-11
                                   pl-11 pr-20
                                   bg-white
                                   rounded-xl
                                   border-0
                                   text-sm text-gray-700
                                   placeholder-gray-400
                                   shadow-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-indigo-300"
                        >


                        {{-- Search Button --}}
                        <button
                            type="submit"
                            class="absolute right-1.5 top-1/2
                                   -translate-y-1/2
                                   px-4 py-1.5
                                   bg-indigo-600
                                   hover:bg-indigo-700
                                   text-white
                                   text-sm font-medium
                                   rounded-lg
                                   transition"
                        >
                            Cari
                        </button>

                    </div>

                </form>

            </div>


            {{-- User --}}
            <div class="flex items-center ml-auto">

                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">

                        <button
                            class="flex items-center gap-2
                                   rounded-lg px-2 py-2
                                   text-white
                                   hover:bg-indigo-500
                                   focus:outline-none
                                   transition"
                        >

                            {{-- Avatar --}}
                            <div
                                class="flex h-9 w-9
                                       items-center justify-center
                                       rounded-full
                                       bg-white
                                       text-indigo-600
                                       text-sm
                                       font-bold"
                            >
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>


                            {{-- Name --}}
                            <span class="hidden sm:block text-sm font-medium">
                                {{ Auth::user()->name }}
                            </span>


                            {{-- Arrow --}}
                            <svg
                                class="h-4 w-4 text-indigo-100"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>

                        </button>

                    </x-slot>


                    {{-- Dropdown --}}
                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>


                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >

                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                Log Out
                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

            </div>

        </div>

    </div>

</nav>

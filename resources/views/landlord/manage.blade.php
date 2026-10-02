<x-landlord-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Manage House
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Urus dan pantau semua rumah yang anda telah post.
            </p>
        </div>
    </x-slot>

    <div class="w-full px-6 py-8">

        {{-- Top Section --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-8">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    My Houses
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Manage your property listings and keep your information up to date.
                </p>
            </div>

            <a href="{{ route('landlord.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3
                      bg-indigo-600 text-white text-sm font-semibold rounded-xl
                      hover:bg-indigo-700 transition shadow-sm">

                <span class="text-lg">+</span>
                Add New House

            </a>

        </div>


        {{-- Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">

            {{-- Total --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Total Houses
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-800">
                            {{ $houses->count() }}
                        </p>
                    </div>

                    <div class="w-12 h-12 flex items-center justify-center
                                bg-indigo-50 text-indigo-600 rounded-xl text-xl">
                        🏠
                    </div>

                </div>

            </div>


            {{-- Available --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Available
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-600">
                            {{ $houses->where('status', 'available')->count() }}
                        </p>
                    </div>

                    <div class="w-12 h-12 flex items-center justify-center
                                bg-green-50 text-green-600 rounded-xl text-xl">
                        ✓
                    </div>

                </div>

            </div>


            {{-- Unavailable --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Unavailable
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-600">
                            {{ $houses->where('status', '!=', 'available')->count() }}
                        </p>
                    </div>

                    <div class="w-12 h-12 flex items-center justify-center
                                bg-gray-100 text-gray-600 rounded-xl text-xl">
                        —
                    </div>

                </div>

            </div>

        </div>


        {{-- Success Message --}}
        @if(session('success'))

            <div class="mb-6 flex items-center gap-3 p-4
                        bg-green-50 border border-green-200
                        text-green-700 rounded-xl">

                <span class="text-lg">✓</span>

                <p class="text-sm font-medium">
                    {{ session('success') }}
                </p>

            </div>

        @endif


        {{-- House List --}}
        @if($houses->count() > 0)

            <div class="space-y-5">

                @foreach($houses as $house)

                    <div class="bg-white border border-gray-100
                                rounded-2xl shadow-sm overflow-hidden
                                hover:shadow-md transition">

                        <div class="flex flex-col lg:flex-row">

                            {{-- Image --}}
                            <div class="w-full lg:w-72 h-56 lg:h-auto
                                        bg-gray-100 flex-shrink-0">

                                @if($house->image)

                                    @php
                                        $images = json_decode($house->image, true);
                                    @endphp

                                    @if(is_array($images) && count($images) > 0)

                                        <img
                                            src="{{ asset('storage/' . $images[0]) }}"
                                            alt="{{ $house->title }}"
                                            class="w-full h-full object-cover"
                                        >

                                    @else

                                        <img
                                            src="{{ asset('storage/' . $house->image) }}"
                                            alt="{{ $house->title }}"
                                            class="w-full h-full object-cover"
                                        >

                                    @endif

                                @else

                                    <div class="w-full h-full flex items-center justify-center">

                                        <div class="text-center text-gray-400">

                                            <div class="text-4xl mb-2">
                                                🏠
                                            </div>

                                            <p class="text-sm">
                                                No Image
                                            </p>

                                        </div>

                                    </div>

                                @endif

                            </div>


                            {{-- House Information --}}
                            <div class="flex-1 p-6">

                                <div class="flex flex-col sm:flex-row
                                            sm:items-start sm:justify-between gap-4">

                                    <div>

                                        <div class="flex items-center gap-3 flex-wrap">

                                            <h3 class="text-xl font-bold text-gray-800">
                                                {{ $house->title }}
                                            </h3>

                                            @if($house->status === 'available')

                                                <span class="inline-flex items-center gap-1
                                                             px-3 py-1 text-xs font-semibold
                                                             rounded-full bg-green-100
                                                             text-green-700">

                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                    Available

                                                </span>

                                            @else

                                                <span class="inline-flex items-center gap-1
                                                             px-3 py-1 text-xs font-semibold
                                                             rounded-full bg-gray-100
                                                             text-gray-600">

                                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                                    {{ ucfirst($house->status) }}

                                                </span>

                                            @endif

                                        </div>


                                        <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">

                                            <span>📍</span>

                                            <span>
                                                {{ $house->address }}
                                            </span>

                                        </div>

                                    </div>


                                    {{-- Price --}}
                                    <div class="sm:text-right">

                                        <p class="text-2xl font-bold text-indigo-600">
                                            RM {{ number_format($house->monthly_rent, 2) }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            per month
                                        </p>

                                    </div>

                                </div>


                                {{-- Property Details --}}
                                <div class="grid grid-cols-2 sm:grid-cols-4
                                            gap-4 mt-6 py-4
                                            border-y border-gray-100">

                                    <div>

                                        <p class="text-xs text-gray-400">
                                            Property Type
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-700">
                                            {{ ucfirst($house->property_type) }}
                                        </p>

                                    </div>


                                    <div>

                                        <p class="text-xs text-gray-400">
                                            Bedrooms
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-700">
                                            {{ $house->bedrooms }}
                                        </p>

                                    </div>


                                    <div>

                                        <p class="text-xs text-gray-400">
                                            Bathrooms
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-700">
                                            {{ $house->bathrooms }}
                                        </p>

                                    </div>


                                    <div>

                                        <p class="text-xs text-gray-400">
                                            Furnished
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-700">
                                            {{ ucfirst($house->furnished) }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Actions --}}
                                <div class="flex flex-col sm:flex-row
                                            sm:items-center sm:justify-between
                                            gap-3 mt-5">

                                    <p class="text-xs text-gray-400">
                                        Posted {{ $house->created_at->diffForHumans() }}
                                    </p>


                                    <div class="flex gap-2">

                                        {{-- Edit --}}
                                        <a href="{{ route('landlord.edit', $house->id) }}"
                                           class="inline-flex items-center justify-center
                                                  gap-2 px-4 py-2.5
                                                  bg-indigo-50 text-indigo-700
                                                  text-sm font-semibold rounded-lg
                                                  hover:bg-indigo-100 transition">

                                            ✏️
                                            Edit

                                        </a>


                                        {{-- Delete --}}
                                        <form action="{{ route('landlord.delete', $house->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this house listing?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center justify-center
                                                           gap-2 px-4 py-2.5
                                                           bg-red-50 text-red-600
                                                           text-sm font-semibold rounded-lg
                                                           hover:bg-red-100 transition">

                                                🗑️
                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            {{-- Empty State --}}
            <div class="bg-white border border-gray-100
                        rounded-2xl shadow-sm p-12 text-center">

                <div class="w-20 h-20 mx-auto flex items-center
                            justify-center bg-indigo-50
                            rounded-full text-4xl">

                    🏠

                </div>


                <h3 class="mt-6 text-xl font-bold text-gray-800">
                    No Houses Yet
                </h3>


                <p class="mt-2 max-w-md mx-auto text-sm text-gray-500">
                    You haven't posted any houses yet.
                    Start by adding your first property listing.
                </p>


                <a href="{{ route('landlord.create') }}"
                   class="inline-flex items-center gap-2 mt-6
                          px-5 py-3 bg-indigo-600 text-white
                          text-sm font-semibold rounded-xl
                          hover:bg-indigo-700 transition">

                    <span class="text-lg">+</span>
                    Add Your First House

                </a>

            </div>

        @endif

    </div>

</x-landlord-layout>

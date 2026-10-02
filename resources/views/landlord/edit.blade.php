<x-landlord-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Edit House
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Update your house information.
            </p>
        </div>
    </x-slot>


    <div class="w-full px-6 py-8">

        <div class="max-w-5xl mx-auto">

            {{-- Back --}}
            <div class="mb-6">

                <a href="{{ route('landlord.manage') }}"
                    class="inline-flex items-center gap-2 text-sm
                          text-gray-500 hover:text-indigo-600 transition">

                    ← Back to My Houses

                </a>

            </div>


            {{-- Form --}}
            <div class="bg-white border border-gray-100
                        rounded-2xl shadow-sm overflow-hidden">

                <div class="p-6 sm:p-8">

                    <div class="mb-8">

                        <h1 class="text-2xl font-bold text-gray-800">
                            House Information
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Make sure your house information is accurate and up to date.
                        </p>

                    </div>


                    <form action="{{ route('landlord.update', $house->id) }}" method="POST">

                        @csrf
                        @method('PUT')


                        {{-- Title --}}
                        <div class="mb-6">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                House Title
                            </label>

                            <input type="text" name="title" value="{{ old('title', $house->title) }}" required
                                class="w-full rounded-xl border-gray-300
                                       focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Example: Spacious Room Near UNISEL">

                            @error('title')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-6">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>

                            <textarea name="description" rows="5"
                                class="w-full rounded-xl border-gray-300
                                       focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Describe your house...">{{ old('description', $house->description) }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Address + Area --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Address
                                </label>

                                <input type="text" name="address" value="{{ old('address', $house->address) }}"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Area
                                </label>

                                <input type="text" name="area" value="{{ old('area', $house->area) }}"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                            </div>

                        </div>


                        {{-- Phone + Rent --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Contact Number
                                </label>

                                <input type="text" name="phone" value="{{ old('phone', $house->phone) }}"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                            </div>


                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Monthly Rent (RM)
                                </label>

                                <input type="number" name="monthly_rent"
                                    value="{{ old('monthly_rent', $house->monthly_rent) }}" min="0"
                                    step="0.01" required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                @error('monthly_rent')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>


                        {{-- Bedrooms + Bathrooms --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Bedrooms
                                </label>

                                <input type="number" name="bedrooms" value="{{ old('bedrooms', $house->bedrooms) }}"
                                    min="0" required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                            </div>


                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Bathrooms
                                </label>

                                <input type="number" name="bathrooms"
                                    value="{{ old('bathrooms', $house->bathrooms) }}" min="0" required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                            </div>

                        </div>


                        {{-- Property Type + Furnished --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Property Type
                                </label>

                                <select name="property_type"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                    <option value="bilik"
                                        {{ old('property_type', $house->property_type) == 'bilik' ? 'selected' : '' }}>
                                        Bilik
                                    </option>

                                    <option value="rumah"
                                        {{ old('property_type', $house->property_type) == 'rumah' ? 'selected' : '' }}>
                                        Rumah
                                    </option>

                                    <option value="apartment"
                                        {{ old('property_type', $house->property_type) == 'apartment' ? 'selected' : '' }}>
                                        Apartment
                                    </option>

                                    <option value="studio"
                                        {{ old('property_type', $house->property_type) == 'studio' ? 'selected' : '' }}>
                                        Studio
                                    </option>

                                </select>

                            </div>


                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Furnished
                                </label>

                                <select name="furnished"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                    <option value="yes"
                                        {{ old('furnished', $house->furnished) == 'yes' ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                    <option value="no"
                                        {{ old('furnished', $house->furnished) == 'no' ? 'selected' : '' }}>
                                        No
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Status --}}
                        {{-- Status --}}
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Listing Status
                            </label>

                            <select name="status"
                                class="w-full rounded-xl border-gray-300
               focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="available"
                                    {{ old('status', $house->status) == 'available' ? 'selected' : '' }}>
                                    Available
                                </option>

                                <option value="rented"
                                    {{ old('status', $house->status) == 'rented' ? 'selected' : '' }}>
                                    Rented
                                </option>
                            </select>

                            @error('status')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        {{-- Buttons --}}
                        <div
                            class="flex flex-col-reverse sm:flex-row
                                    sm:justify-end gap-3 pt-6
                                    border-t border-gray-100">

                            <a href="{{ route('landlord.manage') }}"
                                class="inline-flex items-center justify-center
                                      px-5 py-3 rounded-xl
                                      bg-gray-100 text-gray-700
                                      text-sm font-semibold
                                      hover:bg-gray-200 transition">

                                Cancel

                            </a>


                            <button type="submit"
                                class="inline-flex items-center justify-center
                                       px-5 py-3 rounded-xl
                                       bg-indigo-600 text-white
                                       text-sm font-semibold
                                       hover:bg-indigo-700 transition">

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-landlord-layout>

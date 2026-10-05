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
                <a
                    href="{{ route('landlord.manage') }}"
                    class="inline-flex items-center gap-2 text-sm
                           text-gray-500 hover:text-indigo-600 transition">

                    ← Back to My Houses

                </a>
            </div>

            {{-- Form Container --}}
            <div class="bg-white border border-gray-100
                        rounded-2xl shadow-sm overflow-hidden">

                <div class="p-6 sm:p-8">

                    {{-- Header --}}
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-gray-800">
                            House Information
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Make sure your house information is accurate and up to date.
                        </p>
                    </div>

                    <form
                        action="{{ route('landlord.update', $house->id) }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="mb-6">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                House Title
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title', $house->title) }}"
                                required
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

                            <textarea
                                name="description"
                                rows="5"
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

                                <input
                                    type="text"
                                    name="address"
                                    value="{{ old('address', $house->address) }}"
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

                                <input
                                    type="text"
                                    name="area"
                                    value="{{ old('area', $house->area) }}"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                @error('area')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                        {{-- Phone + Rent --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Contact Number
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $house->phone) }}"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Monthly Rent (RM)
                                </label>

                                <input
                                    type="number"
                                    name="monthly_rent"
                                    value="{{ old('monthly_rent', $house->monthly_rent) }}"
                                    min="0"
                                    step="0.01"
                                    required
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

                                <input
                                    type="number"
                                    name="bedrooms"
                                    value="{{ old('bedrooms', $house->bedrooms) }}"
                                    min="0"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                @error('bedrooms')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Bathrooms
                                </label>

                                <input
                                    type="number"
                                    name="bathrooms"
                                    value="{{ old('bathrooms', $house->bathrooms) }}"
                                    min="0"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                @error('bathrooms')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                        {{-- Property Type + Furnished --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Property Type
                                </label>

                                <select
                                    name="property_type"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                    <option
                                        value="bilik"
                                        {{ old('property_type', $house->property_type) == 'bilik' ? 'selected' : '' }}>
                                        Bilik
                                    </option>

                                    <option
                                        value="rumah"
                                        {{ old('property_type', $house->property_type) == 'rumah' ? 'selected' : '' }}>
                                        Rumah
                                    </option>

                                    <option
                                        value="apartment"
                                        {{ old('property_type', $house->property_type) == 'apartment' ? 'selected' : '' }}>
                                        Apartment
                                    </option>

                                    <option
                                        value="studio"
                                        {{ old('property_type', $house->property_type) == 'studio' ? 'selected' : '' }}>
                                        Studio
                                    </option>

                                </select>

                                @error('property_type')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Furnished
                                </label>

                                <select
                                    name="furnished"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-indigo-500 focus:ring-indigo-500">

                                    <option
                                        value="yes"
                                        {{ old('furnished', $house->furnished) == 'yes' ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                    <option
                                        value="no"
                                        {{ old('furnished', $house->furnished) == 'no' ? 'selected' : '' }}>
                                        No
                                    </option>

                                </select>

                                @error('furnished')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                        {{-- House Images --}}
                        <div class="mb-8">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                House Images
                            </label>

                            <p class="text-sm text-gray-500 mb-4">
                                View your current images or upload new images.
                                Maximum 5 images, 5MB each.
                            </p>

                            {{-- Current Images --}}
                            @if ($house->image)

                                @php
                                    $currentImages = json_decode($house->image, true);

                                    if (!is_array($currentImages)) {
                                        $currentImages = [$house->image];
                                    }
                                @endphp

                                @if (count($currentImages) > 0)

                                    <div class="mb-6">

                                        <p class="text-sm font-medium text-gray-700 mb-3">
                                            Current Images
                                        </p>

                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">

                                            @foreach ($currentImages as $image)

                                                <div class="relative group">

                                                    <img
                                                        src="{{ asset('storage/' . $image) }}"
                                                        alt="House Image"
                                                        class="w-full h-32 object-cover rounded-xl border border-gray-200">

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                @endif

                            @endif

                            {{-- Upload New Images --}}
                            <label
                                for="image"
                                class="flex flex-col items-center justify-center
                                       w-full h-40
                                       border-2 border-dashed border-gray-300
                                       rounded-xl
                                       cursor-pointer
                                       bg-gray-50
                                       hover:bg-indigo-50
                                       hover:border-indigo-400
                                       transition">

                                <svg
                                    class="w-8 h-8 text-gray-400 mb-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4" />

                                </svg>

                                <span class="text-sm font-medium text-gray-600">
                                    Click to upload new images
                                </span>

                                <span class="text-xs text-gray-400 mt-1">
                                    JPG, JPEG, PNG, WEBP
                                </span>

                                <input
                                    id="image"
                                    type="file"
                                    name="image[]"
                                    multiple
                                    accept="image/jpeg,image/png,image/webp"
                                    class="hidden">

                            </label>

                            @error('image')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                            @error('image.*')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Status --}}
                        <div class="mb-8">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Listing Status
                            </label>

                            <select
                                name="status"
                                required
                                class="w-full rounded-xl border-gray-300
                                       focus:border-indigo-500 focus:ring-indigo-500">

                                <option
                                    value="available"
                                    {{ old('status', $house->status) == 'available' ? 'selected' : '' }}>
                                    Available
                                </option>

                                <option
                                    value="rented"
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

                            <a
                                href="{{ route('landlord.manage') }}"
                                class="inline-flex items-center justify-center
                                       px-5 py-3 rounded-xl
                                       bg-gray-100 text-gray-700
                                       text-sm font-semibold
                                       hover:bg-gray-200 transition">

                                Cancel

                            </a>

                            <button
                                type="submit"
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
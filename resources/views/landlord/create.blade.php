<x-landlord-layout>

<x-slot name="header">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-800">
                Create Listing
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Sewakan rumah anda dengan mudah.
            </p>
        </div>

        <a href="{{ route('landlord.manage') }}"
           class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
            ← Back to Manage House
        </a>
    </div>
</x-slot>

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Page Introduction --}}
    <div class="mb-8">
        <div class="rounded-2xl bg-gradient-to-r from-indigo-50 to-purple-50 p-6 ring-1 ring-indigo-100">
            <div class="max-w-3xl">
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">
                    New Listing
                </span>

                <h1 class="mt-3 text-2xl font-bold text-gray-900 sm:text-3xl">
                    Add Your House
                </h1>

                <p class="mt-2 text-sm leading-6 text-gray-600 sm:text-base">
                    Fill in the details below to create a rental listing.
                    Make sure the information provided is accurate and complete.
                </p>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">
            <div class="flex gap-3">
                <div class="text-lg">⚠️</div>

                <div>
                    <h3 class="font-semibold text-red-800">
                        Please check the following errors:
                    </h3>

                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('landlord.houses.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">

        @csrf

        {{-- House Information --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-lg">
                        🏠
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            House Information
                        </h2>

                        <p class="text-sm text-gray-500">
                            Basic information about your property.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-5 p-6">

                {{-- House Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700">
                        House Title
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Example: Cozy House Near UNISEL"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Describe the house, nearby facilities, transportation, neighbourhood and other useful information..."
                        class="mt-2 block w-full resize-none rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700">
                        Full Address
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="address"
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        placeholder="Enter the full property address"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                    @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Area --}}
                <div>
                    <label for="area" class="block text-sm font-semibold text-gray-700">
                        Area
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="area"
                        type="text"
                        name="area"
                        value="{{ old('area') }}"
                        placeholder="Example: Semenyih"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                    @error('area')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Landlord Contact --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-lg">
                        👤
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            Landlord Contact
                        </h2>

                        <p class="text-sm text-gray-500">
                            Contact information displayed to students.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 p-6 md:grid-cols-2">

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700">
                        Full Name
                    </label>

                    <div class="relative mt-2">
                        <input
                            type="text"
                            value="{{ Auth::user()->name }}"
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600"
                            readonly
                        >

                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                            Account
                        </span>
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700">
                        Email Address
                    </label>

                    <div class="relative mt-2">
                        <input
                            type="email"
                            value="{{ Auth::user()->email }}"
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600"
                            readonly
                        >

                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                            Account
                        </span>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="md:col-span-2">
                    <label for="phone" class="block text-sm font-semibold text-gray-700">
                        Phone Number
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="phone"
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Example: 012-3456789"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                    <p class="mt-2 text-xs text-gray-500">
                        Students will use this number to contact you about the property.
                    </p>

                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Rental Details --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-lg">
                        💰
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            Rental Details
                        </h2>

                        <p class="text-sm text-gray-500">
                            Set the rental price and property details.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 p-6 sm:grid-cols-2 lg:grid-cols-3">

                {{-- Monthly Rent --}}
                <div>
                    <label for="monthly_rent" class="block text-sm font-semibold text-gray-700">
                        Monthly Rent
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="relative mt-2">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-500">
                            RM
                        </span>

                        <input
                            id="monthly_rent"
                            type="number"
                            name="monthly_rent"
                            value="{{ old('monthly_rent') }}"
                            placeholder="500"
                            min="0"
                            step="0.01"
                            class="block w-full rounded-xl border-gray-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                    </div>

                    @error('monthly_rent')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bedrooms --}}
                <div>
                    <label for="bedrooms" class="block text-sm font-semibold text-gray-700">
                        Bedrooms
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="bedrooms"
                        type="number"
                        name="bedrooms"
                        value="{{ old('bedrooms', 1) }}"
                        min="1"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                    @error('bedrooms')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bathrooms --}}
                <div>
                    <label for="bathrooms" class="block text-sm font-semibold text-gray-700">
                        Bathrooms
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="bathrooms"
                        type="number"
                        name="bathrooms"
                        value="{{ old('bathrooms', 1) }}"
                        min="1"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                    @error('bathrooms')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Property Type --}}
                <div>
                    <label for="property_type" class="block text-sm font-semibold text-gray-700">
                        Property Type
                    </label>

                    <select
                        id="property_type"
                        name="property_type"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Select type</option>

                        <option value="Apartment" {{ old('property_type') == 'Apartment' ? 'selected' : '' }}>
                            Apartment
                        </option>

                        <option value="Condominium" {{ old('property_type') == 'Condominium' ? 'selected' : '' }}>
                            Condominium
                        </option>

                        <option value="Terrace House" {{ old('property_type') == 'Terrace House' ? 'selected' : '' }}>
                            Terrace House
                        </option>

                        <option value="Semi-D" {{ old('property_type') == 'Semi-D' ? 'selected' : '' }}>
                            Semi-D
                        </option>

                        <option value="Room" {{ old('property_type') == 'Room' ? 'selected' : '' }}>
                            Room
                        </option>
                    </select>

                    @error('property_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Furnishing --}}
                <div>
                    <label for="furnished" class="block text-sm font-semibold text-gray-700">
                        Furnishing
                    </label>

                    <select
                        id="furnished"
                        name="furnished"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Select furnishing</option>

                        <option value="Fully Furnished" {{ old('furnished') == 'Fully Furnished' ? 'selected' : '' }}>
                            Fully Furnished
                        </option>

                        <option value="Partially Furnished" {{ old('furnished') == 'Partially Furnished' ? 'selected' : '' }}>
                            Partially Furnished
                        </option>

                        <option value="Unfurnished" {{ old('furnished') == 'Unfurnished' ? 'selected' : '' }}>
                            Unfurnished
                        </option>
                    </select>

                    @error('furnished')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- House Images --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

            <div class="border-b border-gray-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-lg">
                        📷
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            House Images
                        </h2>

                        <p class="text-sm text-gray-500">
                            Add clear photos to attract potential tenants.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6">

                <label
                    for="image"
                    class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center transition hover:border-indigo-400 hover:bg-indigo-50"
                >
                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-indigo-100 text-2xl">
                        📸
                    </div>

                    <p class="text-sm font-semibold text-gray-700">
                        Click to upload house images
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        You can select multiple images
                    </p>

                    <span class="mt-4 rounded-lg bg-white px-4 py-2 text-xs font-semibold text-indigo-600 shadow-sm ring-1 ring-gray-200">
                        Choose Images
                    </span>

                    <input
                        id="image"
                        type="file"
                        name="image[]"
                        multiple
                        accept="image/jpeg,image/png,image/webp"
                        class="hidden"
                    >
                </label>

                <div class="mt-4 flex flex-wrap gap-2 text-xs text-gray-500">
                    <span class="rounded-full bg-gray-100 px-3 py-1">
                        JPG
                    </span>

                    <span class="rounded-full bg-gray-100 px-3 py-1">
                        JPEG
                    </span>

                    <span class="rounded-full bg-gray-100 px-3 py-1">
                        PNG
                    </span>

                    <span class="rounded-full bg-gray-100 px-3 py-1">
                        WEBP
                    </span>

                    <span class="rounded-full bg-gray-100 px-3 py-1">
                        Max 5MB / image
                    </span>
                </div>

                @error('image')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('image.*')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>
        </div>

        {{-- Listing Status Info --}}
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
            <div class="flex gap-3">
                <div class="text-lg">
                    🟢
                </div>

                <div>
                    <h3 class="font-semibold text-emerald-800">
                        New listing status
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-emerald-700">
                        Your new house listing will be available to students after it is successfully created.
                    </p>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('landlord.manage') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-7 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                <span>＋</span>
                Add House
            </button>

        </div>

    </form>

</div>

</x-landlord-layout>

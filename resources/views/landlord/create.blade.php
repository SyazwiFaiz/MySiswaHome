<x-app-layout>

    <div class="max-w-4xl mx-auto px-6 py-8">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                Add Your House
            </h1>

            <p class="mt-1 text-gray-500">
                Enter the details of your rental property.
            </p>
        </div>

        <form action="{{ route('landlord.houses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- House Information -->
            <div class="rounded-2xl bg-white p-6 shadow-sm">

                <h2 class="mb-5 text-lg font-semibold text-gray-800">
                    House Information
                </h2>

                <div class="space-y-5">

                    <!-- House Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            House Title
                        </label>

                        <input type="text" name="title" value="{{ old('title') }}"
                            placeholder="Example: Cozy House Near UNISEL" class="mt-2 w-full rounded-xl border-gray-300"
                            required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Description
                        </label>

                        <textarea name="description" rows="4" placeholder="Describe your house..."
                            class="mt-2 w-full rounded-xl border-gray-300">{{ old('description') }}</textarea>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Address
                        </label>

                        <input type="text" name="address" value="{{ old('address') }}"
                            placeholder="Full house address" class="mt-2 w-full rounded-xl border-gray-300" required>
                    </div>

                    <!-- Area -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Area
                        </label>

                        <input type="text" name="area" value="{{ old('area') }}" placeholder="Example: Semenyih"
                            class="mt-2 w-full rounded-xl border-gray-300" required>
                    </div>

                </div>
            </div>


            <!-- Landlord Contact Information -->
            <div class="rounded-2xl bg-white p-6 shadow-sm">

                <h2 class="mb-2 text-lg font-semibold text-gray-800">
                    Landlord Contact Information
                </h2>

                <p class="mb-5 text-sm text-gray-500">
                    This information will be shown to students who view your house listing.
                </p>

                <div class="space-y-5">

                    <!-- Landlord Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Full Name
                        </label>

                        <input type="text" value="{{ Auth::user()->name }}"
                            class="mt-2 w-full rounded-xl border-gray-300 bg-gray-50 text-gray-600" readonly>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>

                        <input type="email" value="{{ Auth::user()->email }}"
                            class="mt-2 w-full rounded-xl border-gray-300 bg-gray-50 text-gray-600" readonly>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Phone Number
                        </label>

                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                            placeholder="Example: 012-3456789" class="mt-2 w-full rounded-xl border-gray-300" required>

                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>



            <!-- Rental Details -->
            <div class="rounded-2xl bg-white p-6 shadow-sm">

                <h2 class="mb-5 text-lg font-semibold text-gray-800">
                    Rental Details
                </h2>

                <div class="grid gap-5 sm:grid-cols-2">

                    <!-- Monthly Rent -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Monthly Rent (RM)
                        </label>

                        <input type="number" name="monthly_rent" value="{{ old('monthly_rent') }}" placeholder="500"
                            min="0" class="mt-2 w-full rounded-xl border-gray-300" required>
                    </div>

                    <!-- Bedrooms -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Bedrooms
                        </label>

                        <input type="number" name="bedrooms" value="{{ old('bedrooms', 1) }}" min="1"
                            class="mt-2 w-full rounded-xl border-gray-300" required>
                    </div>

                    <!-- Bathrooms -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Bathrooms
                        </label>

                        <input type="number" name="bathrooms" value="{{ old('bathrooms', 1) }}" min="1"
                            class="mt-2 w-full rounded-xl border-gray-300" required>
                    </div>

                    <!-- Property Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Property Type
                        </label>

                        <select name="property_type" class="mt-2 w-full rounded-xl border-gray-300">
                            <option value="">Select type</option>

                            <option value="Apartment" {{ old('property_type') == 'Apartment' ? 'selected' : '' }}>
                                Apartment
                            </option>

                            <option value="Condominium" {{ old('property_type') == 'Condominium' ? 'selected' : '' }}>
                                Condominium
                            </option>

                            <option value="Terrace House"
                                {{ old('property_type') == 'Terrace House' ? 'selected' : '' }}>
                                Terrace House
                            </option>

                            <option value="Semi-D" {{ old('property_type') == 'Semi-D' ? 'selected' : '' }}>
                                Semi-D
                            </option>

                            <option value="Room" {{ old('property_type') == 'Room' ? 'selected' : '' }}>
                                Room
                            </option>
                        </select>
                    </div>

                    <!-- Furnishing -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Furnishing
                        </label>

                        <select name="furnished" class="mt-2 w-full rounded-xl border-gray-300">
                            <option value="">Select furnishing</option>

                            <option value="Fully Furnished"
                                {{ old('furnished') == 'Fully Furnished' ? 'selected' : '' }}>
                                Fully Furnished
                            </option>

                            <option value="Partially Furnished"
                                {{ old('furnished') == 'Partially Furnished' ? 'selected' : '' }}>
                                Partially Furnished
                            </option>

                            <option value="Unfurnished" {{ old('furnished') == 'Unfurnished' ? 'selected' : '' }}>
                                Unfurnished
                            </option>
                        </select>
                    </div>

                </div>
            </div>


            <!-- House Images -->
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-semibold text-gray-800">
                    House Images
                </h2>

                <input type="file" name="image[]" multiple accept="image/*"
                    class="block w-full text-sm text-gray-500">

                <p class="mt-2 text-xs text-gray-500">
                    You can select multiple images. JPG, JPEG, PNG or WEBP. Maximum 5MB per image.
                </p>
            </div>


            <!-- Submit -->
            <div class="flex justify-end">

                <button type="submit"
                    class="rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white transition hover:bg-indigo-700">
                    Add House
                </button>

            </div>

        </form>

    </div>

</x-app-layout>

<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Dashboard
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Cari rumah sewa yang sesuai untuk kehidupan student anda.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50">

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Welcome -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    Hai, {{ Auth::user()->name }}
                </h1>

                <p class="mt-2 text-gray-500">
                    Jom cari rumah sewa yang sesuai dengan bajet dan lokasi anda.
                </p>
            </div>


            <!-- Search Box -->
            <div class="mb-10 overflow-hidden rounded-2xl bg-indigo-600 shadow-lg">

                <div class="p-6 sm:p-8">

                    <h2 class="text-xl font-bold text-white">
                        Cari Rumah Sewa
                    </h2>

                    <p class="mt-1 text-sm text-indigo-100">
                        Cari rumah berdasarkan lokasi, harga dan jenis kediaman.
                    </p>

                    <form action="{{ route('student.dashboard') }}" method="GET" class="mt-6">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                            <!-- Location -->
                            <div class="md:col-span-1">

                                <label class="mb-2 block text-sm font-medium text-white">
                                    Lokasi
                                </label>

                                <input type="text" name="location" value="{{ request('location') }}"
                                    placeholder="Contoh: Semenyih"
                                    class="w-full rounded-xl border-0 px-4 py-3 text-sm text-gray-800 shadow-sm focus:ring-2 focus:ring-white">

                            </div>


                            <!-- Price -->
                            <div>

                                <label class="mb-2 block text-sm font-medium text-white">
                                    Bajet Maksimum
                                </label>

                                <select name="price"
                                    class="w-full rounded-xl border-0 px-4 py-3 text-sm text-gray-800 shadow-sm focus:ring-2 focus:ring-white">

                                    <option value="">
                                        Pilih bajet
                                    </option>

                                    <option value="300" {{ request('price') == '300' ? 'selected' : '' }}>
                                        RM300
                                    </option>

                                    <option value="400" {{ request('price') == '400' ? 'selected' : '' }}>
                                        RM400
                                    </option>

                                    <option value="500" {{ request('price') == '500' ? 'selected' : '' }}>
                                        RM500
                                    </option>

                                    <option value="600" {{ request('price') == '600' ? 'selected' : '' }}>
                                        RM600
                                    </option>

                                    <option value="1000" {{ request('price') == '1000' ? 'selected' : '' }}>
                                        RM1000
                                    </option>

                                </select>

                            </div>


                            <!-- Property Type -->
                            <div>

                                <label class="mb-2 block text-sm font-medium text-white">
                                    Jenis
                                </label>

                                <select name="type"
                                    class="w-full rounded-xl border-0 px-4 py-3 text-sm text-gray-800 shadow-sm focus:ring-2 focus:ring-white">

                                    <option value="">
                                        Semua jenis
                                    </option>

                                    <option value="bilik" {{ request('type') == 'bilik' ? 'selected' : '' }}>
                                        Bilik
                                    </option>

                                    <option value="rumah" {{ request('type') == 'rumah' ? 'selected' : '' }}>
                                        Rumah
                                    </option>

                                    <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>
                                        Apartment
                                    </option>

                                    <option value="studio" {{ request('type') == 'studio' ? 'selected' : '' }}>
                                        Studio
                                    </option>

                                </select>

                            </div>


                            <!-- Search Button -->
                            <div class="flex items-end">

                                <button type="submit"
                                    class="w-full rounded-xl bg-white px-5 py-3 font-semibold text-indigo-600 shadow-sm transition hover:bg-indigo-50">
                                    Search
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            <!-- Recommended Houses Header -->
            <div class="mb-6 flex items-center justify-between">

                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        @if (request()->hasAny(['location', 'price', 'type']))
                            Hasil Carian Rumah
                        @else
                            Rumah Disyorkan
                        @endif
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        @if (request()->hasAny(['location', 'price', 'type']))
                            Rumah yang sepadan dengan carian anda.
                        @else
                            Pilihan rumah sewa yang mungkin sesuai untuk anda.
                        @endif
                    </p>
                </div>

                @if (request()->hasAny(['location', 'price', 'type']))
                    <a href="{{ route('student.dashboard') }}"
                        class="rounded-lg border border-indigo-200 bg-white px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50">
                        Reset Carian
                    </a>
                @endif

            </div>


            <!-- HOUSE LISTING -->
            <div id="houseListing" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                @forelse($houses as $house)

                    @php
                        $houseImages = json_decode($house->image, true) ?? [];

                        // Fallback untuk data lama yang masih single image
                        if (empty($houseImages) && !empty($house->image)) {
                            $houseImages = [$house->image];
                        }
                    @endphp

                    <!-- House Card -->
                    <div
                        class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                        <!-- Image -->
                        <div class="relative h-48 bg-gray-200">

                            @if (!empty($houseImages))
                                <img src="{{ asset('storage/' . $houseImages[0]) }}" alt="{{ $house->title }}"
                                    class="h-full w-full object-cover">

                                @if (count($houseImages) > 1)
                                    <div
                                        class="absolute bottom-3 right-3 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white">
                                        📷 {{ count($houseImages) }} gambar
                                    </div>
                                @endif
                            @else
                                <div class="flex h-full items-center justify-center text-5xl">
                                    🏠
                                </div>
                            @endif

                            <!-- Status -->
                            <div class="absolute left-3 top-3">
                                @if ($house->status === 'available')
                                    <span
                                        class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Available
                                    </span>
                                @else
                                    <span
                                        class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        {{ ucfirst($house->status) }}
                                    </span>
                                @endif
                            </div>

                        </div>

                        <!-- Content -->
                        <div class="p-5">

                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $house->title }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                📍 {{ $house->area }}
                            </p>

                            <div class="mt-4 flex items-center justify-between">

                                <div>
                                    <span class="text-xl font-bold text-indigo-600">
                                        RM{{ number_format($house->monthly_rent) }}
                                    </span>

                                    <span class="text-sm text-gray-400">
                                        / bulan
                                    </span>
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ $house->bedrooms }} Bilik
                                </div>

                            </div>

                            <button type="button"
                                onclick='showHouseDetail(
                        {{ $house->id }},
                        @json($houseImages)
                    )'
                                class="mt-5 w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">

                                Lihat Butiran

                            </button>

                        </div>

                    </div>

                @empty

                    <!-- Empty State -->
                    <div
                        class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center">

                        <div class="text-5xl">
                            🏠
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-gray-700">
                            Belum ada rumah tersedia
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Buat masa ini belum ada landlord yang menambah rumah sewa.
                        </p>

                    </div>

                @endforelse

            </div>



            <!-- FULL HOUSE DETAIL -->

            <div id="houseDetail" class="hidden" data-house-id="">
                <!-- Back Button -->
                <div class="mb-6">

                    <button type="button" onclick="backToHouseList()"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">

                        ← Kembali ke senarai rumah

                    </button>

                </div>


                <!-- DETAIL CARD -->

                <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-lg">


                    <!-- LARGE HOUSE IMAGE -->
                    <div class="relative h-72 overflow-hidden bg-gray-200 sm:h-96 lg:h-[460px]">

                        <!-- Main Image -->
                        <img id="detailHouseImage" src="" alt="House Image"
                            class="hidden h-full w-full object-cover">

                        <!-- No Image -->
                        <div id="detailNoImage" class="flex h-full items-center justify-center text-gray-400">
                            <div class="text-center">

                                <div class="text-6xl">
                                    🏠
                                </div>

                                <p class="mt-3 text-sm">
                                    Tiada gambar
                                </p>

                            </div>
                        </div>

                        <!-- Previous -->
                        <button id="prevImageButton" type="button" onclick="previousHouseImage()"
                            class="absolute left-4 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-xl font-bold text-gray-700 shadow-md transition hover:bg-white">
                            ←
                        </button>

                        <!-- Next -->
                        <button id="nextImageButton" type="button" onclick="nextHouseImage()"
                            class="absolute right-4 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-xl font-bold text-gray-700 shadow-md transition hover:bg-white">
                            →
                        </button>

                        <!-- Image Counter -->
                        <div id="imageCounter"
                            class="absolute bottom-4 left-1/2 hidden -translate-x-1/2 rounded-full bg-black/60 px-4 py-1.5 text-sm font-semibold text-white">
                            1 / 1
                        </div>

                        <!-- Favourite -->
                        <button id="favouriteButton" type="button" onclick="toggleFavourite()"
                            class="absolute right-5 top-5 flex h-11 w-11 items-center justify-center rounded-full bg-white text-2xl text-gray-700 shadow-md transition hover:scale-110 hover:bg-red-50">
                            <span id="favouriteIcon">♡</span>
                        </button>

                    </div>



                    <!-- DETAIL CONTENT -->

                    <div class="p-6 sm:p-8 lg:p-10">


                        <!-- TITLE + PRICE -->

                        <div
                            class="flex flex-col gap-5 border-b border-gray-100 pb-7 sm:flex-row sm:items-start sm:justify-between">

                            <!-- Title -->
                            <div>

                                <h1 id="detailHouseTitle" class="text-3xl font-bold text-gray-800 sm:text-4xl">
                                </h1>

                                <p id="detailHouseArea" class="mt-3 text-base text-gray-500">
                                </p>

                            </div>


                            <!-- Price -->
                            <div class="sm:text-right">

                                <div id="detailHousePrice" class="text-3xl font-bold text-indigo-600">
                                </div>

                                <span class="text-sm text-gray-400">
                                    / bulan
                                </span>

                            </div>

                        </div>



                        <!-- HOUSE QUICK DETAILS -->

                        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">


                            <!-- Bedrooms -->
                            <div class="rounded-2xl bg-gray-50 p-5">

                                <p class="text-sm text-gray-500">
                                    Bilik Tidur
                                </p>

                                <p id="detailBedrooms" class="mt-2 text-lg font-bold text-gray-800">
                                </p>

                            </div>


                            <!-- Bathrooms -->
                            <div class="rounded-2xl bg-gray-50 p-5">

                                <p class="text-sm text-gray-500">
                                    Bilik Air
                                </p>

                                <p id="detailBathrooms" class="mt-2 text-lg font-bold text-gray-800">
                                </p>

                            </div>


                            <!-- Property Type -->
                            <div class="rounded-2xl bg-gray-50 p-5">

                                <p class="text-sm text-gray-500">
                                    Jenis Rumah
                                </p>

                                <p id="detailPropertyType" class="mt-2 text-lg font-bold capitalize text-gray-800">
                                </p>

                            </div>


                            <!-- Furnished -->
                            <div class="rounded-2xl bg-gray-50 p-5">

                                <p class="text-sm text-gray-500">
                                    Furnished
                                </p>

                                <p id="detailFurnished" class="mt-2 text-lg font-bold text-gray-800">
                                </p>

                            </div>

                        </div>



                        <!-- MAIN DETAIL AREA -->

                        <div class="mt-10 grid grid-cols-1 gap-10 lg:grid-cols-3">


                            <!-- LEFT CONTENT -->

                            <div class="lg:col-span-2">


                                <!-- Address -->
                                <div>

                                    <h2 class="text-xl font-bold text-gray-800">
                                        📍 Alamat
                                    </h2>

                                    <p id="detailAddress" class="mt-3 text-sm leading-7 text-gray-600">
                                    </p>

                                </div>


                                <!-- Description -->
                                <div class="mt-8">

                                    <h2 class="text-xl font-bold text-gray-800">
                                        📝 Penerangan Rumah
                                    </h2>

                                    <p id="detailDescription"
                                        class="mt-3 whitespace-pre-line text-sm leading-7 text-gray-600">
                                    </p>

                                </div>


                                <!-- Status -->
                                <div class="mt-8">

                                    <h2 class="text-xl font-bold text-gray-800">
                                        Status Rumah
                                    </h2>

                                    <span id="detailStatus"
                                        class="mt-3 inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                                    </span>

                                </div>

                            </div>



                            <!-- LANDLORD INFORMATION -->

                            <div>

                                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6">

                                    <h2 class="text-lg font-bold text-indigo-800">
                                        👤 Maklumat Pemilik
                                    </h2>


                                    <div class="mt-6 space-y-5">


                                        <!-- Name -->
                                        <div>

                                            <p class="text-xs text-gray-500">
                                                Nama
                                            </p>

                                            <p id="detailLandlordName" class="mt-1 font-semibold text-gray-800">
                                            </p>

                                        </div>


                                        <!-- Phone -->
                                        <div>

                                            <p class="text-xs text-gray-500">
                                                Telefon
                                            </p>

                                            <p id="detailLandlordPhone" class="mt-1 font-semibold text-gray-800">
                                            </p>

                                        </div>


                                        <!-- Email -->
                                        <div>

                                            <p class="text-xs text-gray-500">
                                                Email
                                            </p>

                                            <p id="detailLandlordEmail"
                                                class="mt-1 break-all font-semibold text-gray-800">
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- BOTTOM BACK BUTTON -->

                        <div class="mt-10 border-t border-gray-100 pt-6">

                            <button type="button" onclick="backToHouseList()"
                                class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">

                                ← Kembali ke Senarai Rumah

                            </button>

                        </div>

                    </div>

                </div>

            </div>



            <!-- JAVASCRIPT -->

            <script>
                // GET HOUSES FROM LARAVEL
               

                const houses = @json($houses->items());


                // HOUSE IMAGE SLIDER VARIABLES

                let currentHouseImages = [];
                let currentImageIndex = 0;


                // SHOW HOUSE DETAIL

                function showHouseDetail(houseId, images = []) {

                    // Simpan ID rumah yang sedang dibuka
                    document.getElementById('houseDetail').dataset.houseId = houseId;


                    // =====================================================
                    // CHECK FAVOURITE STATUS
                    // =====================================================

                    checkFavourite(houseId);


                    // =====================================================
                    // CARI RUMAH
                    // =====================================================

                    const house = houses.find(
                        h => Number(h.id) === Number(houseId)
                    );


                    // Jika rumah tidak dijumpai
                    if (!house) {

                        console.error('House not found:', houseId);

                        return;
                    }


                    // =====================================================
                    // HOUSE INFORMATION
                    // =====================================================

                    // TITLE
                    document.getElementById('detailHouseTitle').textContent =
                        house.title ?? 'Tiada nama';


                    // AREA
                    document.getElementById('detailHouseArea').textContent =
                        '📍 ' + (house.area ?? 'Lokasi tidak dinyatakan');


                    // PRICE
                    document.getElementById('detailHousePrice').textContent =
                        'RM' + Number(house.monthly_rent ?? 0).toLocaleString();


                    // BEDROOMS
                    document.getElementById('detailBedrooms').textContent =
                        (house.bedrooms ?? 0) + ' Bilik';


                    // BATHROOMS
                    document.getElementById('detailBathrooms').textContent =
                        (house.bathrooms ?? 0) + ' Bilik Air';


                    // PROPERTY TYPE
                    document.getElementById('detailPropertyType').textContent =
                        house.property_type ?? 'Tidak dinyatakan';


                    // FURNISHED
                    document.getElementById('detailFurnished').textContent =
                        house.furnished ?? 'Tidak dinyatakan';


                    // ADDRESS
                    document.getElementById('detailAddress').textContent =
                        house.address ?? 'Alamat tidak dinyatakan';


                    // DESCRIPTION
                    document.getElementById('detailDescription').textContent =
                        house.description ?? 'Tiada penerangan diberikan';


                    // LANDLORD NAME
                    document.getElementById('detailLandlordName').textContent =
                        house.landlord?.name ?? 'Tidak diketahui';


                    // LANDLORD PHONE
                    document.getElementById('detailLandlordPhone').textContent =
                        house.phone ?? 'Tidak diberikan';


                    // LANDLORD EMAIL
                    document.getElementById('detailLandlordEmail').textContent =
                        house.landlord?.email ?? 'Tidak diberikan';


                    // =====================================================
                    // STATUS
                    // =====================================================

                    const status = document.getElementById('detailStatus');


                    if (house.status === 'available') {

                        status.textContent = 'Available';

                        status.className =
                            'mt-3 inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700';

                    } else {

                        status.textContent =
                            house.status ?? 'Tidak dinyatakan';

                        status.className =
                            'mt-3 inline-flex rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600';
                    }


                    // =====================================================
                    // HOUSE IMAGES
                    // =====================================================

                    currentHouseImages =
                        Array.isArray(images) ? images : [];

                    currentImageIndex = 0;

                    updateHouseImage();


                    // =====================================================
                    // HIDE HOUSE LIST
                    // =====================================================

                    document
                        .getElementById('houseListing')
                        .classList.add('hidden');


                    // =====================================================
                    // SHOW HOUSE DETAIL
                    // =====================================================

                    document
                        .getElementById('houseDetail')
                        .classList.remove('hidden');


                    // =====================================================
                    // SCROLL TO TOP
                    // =====================================================

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }


                // CHECK FAVOURITE STATUS

                async function checkFavourite(houseId) {

                    try {

                        const response = await fetch(
                            `/favourite/${houseId}/check`, {
                                method: 'GET',

                                headers: {
                                    'Accept': 'application/json'
                                }
                            }
                        );


                        const data = await response.json();


                        // =================================================
                        // GET BUTTON
                        // =================================================

                        const icon =
                            document.getElementById('favouriteIcon');

                        const button =
                            document.getElementById('favouriteButton');


                        // =================================================
                        // IF FAVOURITE
                        // =================================================

                        if (data.favourite) {

                            icon.textContent = '♥';

                            button.classList.remove(
                                'text-gray-700',
                                'hover:bg-red-50'
                            );

                            button.classList.add(
                                'text-red-500',
                                'bg-red-50'
                            );

                        }


                        // =================================================
                        // NOT FAVOURITE
                        // =================================================
                        else {

                            icon.textContent = '♡';

                            button.classList.remove(
                                'text-red-500',
                                'bg-red-50'
                            );

                            button.classList.add(
                                'text-gray-700',
                                'hover:bg-red-50'
                            );
                        }

                    } catch (error) {

                        console.error(
                            'Gagal check favourite:',
                            error
                        );
                    }
                }


                // UPDATE HOUSE IMAGE

                function updateHouseImage() {

                    const image =
                        document.getElementById('detailHouseImage');

                    const noImage =
                        document.getElementById('detailNoImage');

                    const prevButton =
                        document.getElementById('prevImageButton');

                    const nextButton =
                        document.getElementById('nextImageButton');

                    const counter =
                        document.getElementById('imageCounter');


                    // =====================================================
                    // NO IMAGE
                    // =====================================================

                    if (
                        !currentHouseImages ||
                        currentHouseImages.length === 0
                    ) {

                        image.src = '';

                        image.classList.add('hidden');

                        noImage.classList.remove('hidden');

                        prevButton.classList.add('hidden');
                        prevButton.classList.remove('flex');

                        nextButton.classList.add('hidden');
                        nextButton.classList.remove('flex');

                        counter.classList.add('hidden');

                        return;
                    }


                    // =====================================================
                    // SHOW CURRENT IMAGE
                    // =====================================================

                    image.src =
                        '/storage/' +
                        currentHouseImages[currentImageIndex];

                    image.classList.remove('hidden');

                    noImage.classList.add('hidden');


                    // =====================================================
                    // IMAGE COUNTER
                    // =====================================================

                    counter.textContent =
                        (currentImageIndex + 1) +
                        ' / ' +
                        currentHouseImages.length;

                    counter.classList.remove('hidden');


                    // =====================================================
                    // SLIDER BUTTONS
                    // =====================================================

                    if (currentHouseImages.length > 1) {

                        prevButton.classList.remove('hidden');
                        prevButton.classList.add('flex');

                        nextButton.classList.remove('hidden');
                        nextButton.classList.add('flex');

                    } else {

                        prevButton.classList.add('hidden');
                        prevButton.classList.remove('flex');

                        nextButton.classList.add('hidden');
                        nextButton.classList.remove('flex');
                    }
                }


                // PREVIOUS IMAGE

                function previousHouseImage() {

                    if (currentHouseImages.length <= 1) {
                        return;
                    }


                    currentImageIndex--;


                    if (currentImageIndex < 0) {

                        currentImageIndex =
                            currentHouseImages.length - 1;
                    }


                    updateHouseImage();
                }


                // NEXT IMAGE

                function nextHouseImage() {

                    if (currentHouseImages.length <= 1) {
                        return;
                    }


                    currentImageIndex++;


                    if (
                        currentImageIndex >=
                        currentHouseImages.length
                    ) {

                        currentImageIndex = 0;
                    }


                    updateHouseImage();
                }


                // TOGGLE FAVOURITE

                async function toggleFavourite() {

                    const houseDetail =
                        document.getElementById('houseDetail');


                    const houseId =
                        houseDetail.dataset.houseId;


                    // Jika tiada ID rumah
                    if (!houseId) {

                        console.error(
                            'House ID tidak dijumpai.'
                        );

                        return;
                    }


                    try {

                        const response = await fetch(
                            `/favourite/${houseId}/toggle`, {
                                method: 'POST',

                                headers: {

                                    'Content-Type': 'application/json',

                                    'Accept': 'application/json',

                                    'X-CSRF-TOKEN': document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .getAttribute('content')
                                }
                            }
                        );


                        const data =
                            await response.json();


                        // =================================================
                        // ERROR
                        // =================================================

                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Favourite gagal.'
                            );
                        }


                        // =================================================
                        // GET BUTTON
                        // =================================================

                        const icon =
                            document.getElementById(
                                'favouriteIcon'
                            );


                        const button =
                            document.getElementById(
                                'favouriteButton'
                            );


                        // =================================================
                        // ADD FAVOURITE
                        // =================================================

                        if (data.favourite) {

                            icon.textContent = '♥';


                            button.classList.remove(
                                'text-gray-700',
                                'hover:bg-red-50'
                            );


                            button.classList.add(
                                'text-red-500',
                                'bg-red-50'
                            );

                        }


                        // =================================================
                        // REMOVE FAVOURITE
                        // =================================================
                        else {

                            icon.textContent = '♡';


                            button.classList.remove(
                                'text-red-500',
                                'bg-red-50'
                            );


                            button.classList.add(
                                'text-gray-700',
                                'hover:bg-red-50'
                            );
                        }

                    } catch (error) {

                        console.error(
                            'Favourite Error:',
                            error
                        );


                        alert(
                            'Tidak dapat mengemaskini Favourite.'
                        );
                    }
                }


                // BACK TO HOUSE LIST

                function backToHouseList() {

                    // =====================================================
                    // HIDE DETAIL
                    // =====================================================

                    document
                        .getElementById('houseDetail')
                        .classList.add('hidden');


                    // =====================================================
                    // SHOW HOUSE LIST
                    // =====================================================

                    document
                        .getElementById('houseListing')
                        .classList.remove('hidden');


                    // =====================================================
                    // RESET HOUSE ID
                    // =====================================================

                    document
                        .getElementById('houseDetail')
                        .dataset.houseId = '';


                    // =====================================================
                    // RESET FAVOURITE BUTTON
                    // =====================================================

                    document
                        .getElementById('favouriteIcon')
                        .textContent = '♡';


                    const favouriteButton =
                        document.getElementById(
                            'favouriteButton'
                        );


                    favouriteButton.classList.remove(
                        'text-red-500',
                        'bg-red-50'
                    );


                    favouriteButton.classList.add(
                        'text-gray-700',
                        'hover:bg-red-50'
                    );


                    // =====================================================
                    // RESET SLIDER
                    // =====================================================

                    currentHouseImages = [];

                    currentImageIndex = 0;


                    // =====================================================
                    // SCROLL TO TOP
                    // =====================================================

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            </script>






            <!-- Pagination -->
            @if ($houses->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $houses->links() }}
                </div>
            @endif

            <!-- Bottom Info -->
            <div class="mt-10 rounded-2xl border border-indigo-100 bg-indigo-50 p-6">

                <div class="flex gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-xl">
                        💡
                    </div>

                    <div>

                        <h3 class="font-bold text-indigo-800">
                            Tips mencari rumah sewa
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-indigo-600">
                            Pastikan anda menyemak lokasi, harga sewa,
                            kemudahan dan syarat rumah sebelum membuat
                            permohonan.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>

<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Favourite
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Lihat Rumah Favourite Anda Disini !
            </p>
        </div>
    </x-slot>


    <div class="min-h-screen bg-gray-50">

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- HEADER --}}

            <div class="mb-8">

                <h1 class="text-3xl font-bold text-gray-800">
                    Rumah Favourite
                </h1>

                <p class="mt-2 text-gray-500">
                    Senarai rumah yang anda simpan sebagai favourite.
                </p>

            </div>


            {{-- HOUSE LISTING --}}

            <div
                id="houseListing"
                class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
            >

                @forelse($houses as $house)

                    @php

                        $houseImages = json_decode($house->image, true) ?? [];

                        if (empty($houseImages) && !empty($house->image)) {
                            $houseImages = [$house->image];
                        }

                    @endphp


                    {{-- HOUSE CARD --}}

                    <div
                        class="house-card overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                        data-house-id="{{ $house->id }}"
                    >

                        {{-- IMAGE --}}

                        <div class="relative h-48 bg-gray-200">

                            @if (!empty($houseImages))

                                <img
                                    src="{{ asset('storage/' . $houseImages[0]) }}"
                                    alt="{{ $house->title }}"
                                    class="h-full w-full object-cover"
                                >

                                @if (count($houseImages) > 1)

                                    <div
                                        class="absolute bottom-3 right-3 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white"
                                    >
                                        📷 {{ count($houseImages) }} gambar
                                    </div>

                                @endif

                            @else

                                <div
                                    class="flex h-full items-center justify-center text-5xl"
                                >
                                    🏠
                                </div>

                            @endif


                            {{-- STATUS --}}

                            <div class="absolute left-3 top-3">

                                @if ($house->status === 'available')

                                    <span
                                        class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                                    >
                                        Available
                                    </span>

                                @else

                                    <span
                                        class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600"
                                    >
                                        {{ ucfirst($house->status) }}
                                    </span>

                                @endif

                            </div>


                           
                            {{-- UNFAVOURITE BUTTON --}}
                           

                            <button
                                type="button"
                                onclick="removeFavouriteFromCard(event, {{ $house->id }})"
                                class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-white text-xl text-red-500 shadow-md transition hover:scale-110 hover:bg-red-50"
                                title="Buang daripada Favourite"
                            >
                                ♥
                            </button>

                        </div>


                       
                        {{-- CONTENT --}}
                       

                        <div class="p-5">

                            {{-- TITLE --}}

                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $house->title }}
                            </h3>


                            {{-- AREA --}}

                            <p class="mt-1 text-sm text-gray-500">
                                📍 {{ $house->area }}
                            </p>


                            {{-- PRICE + BEDROOM --}}

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


                           
                            {{-- DETAIL BUTTON --}}
                           

                            <button
                                type="button"
                                onclick='showHouseDetail(
                                    {{ $house->id }},
                                    @json($houseImages)
                                )'
                                class="mt-5 w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >
                                Lihat Butiran
                            </button>

                        </div>

                    </div>


                @empty

                   
                    {{-- EMPTY STATE --}}
                   

                    <div
                        id="emptyFavourite"
                        class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center"
                    >

                        <div class="text-5xl">
                            ♡
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-gray-700">
                            Belum ada rumah favourite
                        </h3>

                        <p class="mt-2 text-sm text-gray-500">
                            Rumah yang anda favourite akan dipaparkan di sini.
                        </p>

                        <a
                            href="{{ route('student.dashboard') }}"
                            class="mt-6 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Cari Rumah
                        </a>

                    </div>

                @endforelse

            </div>


            {{-- ========================================================= --}}
            {{-- FULL HOUSE DETAIL --}}
            {{-- ========================================================= --}}

            <div
                id="houseDetail"
                class="hidden"
                data-house-id=""
            >

                {{-- BACK BUTTON --}}

                <div class="mb-6">

                    <button
                        type="button"
                        onclick="backToHouseList()"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                    >
                        ← Kembali ke Favourite
                    </button>

                </div>


               
                {{-- DETAIL CARD --}}
               

                <div
                    class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-lg"
                >


                   
                    {{-- LARGE HOUSE IMAGE --}}
                   

                    <div
                        class="relative h-72 overflow-hidden bg-gray-200 sm:h-96 lg:h-[460px]"
                    >

                        {{-- MAIN IMAGE --}}

                        <img
                            id="detailHouseImage"
                            src=""
                            alt="House Image"
                            class="hidden h-full w-full object-cover"
                        >


                        {{-- NO IMAGE --}}

                        <div
                            id="detailNoImage"
                            class="flex h-full items-center justify-center text-gray-400"
                        >

                            <div class="text-center">

                                <div class="text-6xl">
                                    🏠
                                </div>

                                <p class="mt-3 text-sm">
                                    Tiada gambar
                                </p>

                            </div>

                        </div>


                        {{-- PREVIOUS --}}

                        <button
                            id="prevImageButton"
                            type="button"
                            onclick="previousHouseImage()"
                            class="absolute left-4 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-xl font-bold text-gray-700 shadow-md transition hover:bg-white"
                        >
                            ←
                        </button>


                        {{-- NEXT --}}

                        <button
                            id="nextImageButton"
                            type="button"
                            onclick="nextHouseImage()"
                            class="absolute right-4 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-xl font-bold text-gray-700 shadow-md transition hover:bg-white"
                        >
                            →
                        </button>


                        {{-- IMAGE COUNTER --}}

                        <div
                            id="imageCounter"
                            class="absolute bottom-4 left-1/2 hidden -translate-x-1/2 rounded-full bg-black/60 px-4 py-1.5 text-sm font-semibold text-white"
                        >
                            1 / 1
                        </div>


                       
                        {{-- FAVOURITE BUTTON --}}
                       

                        <button
                            id="favouriteButton"
                            type="button"
                            onclick="toggleFavourite()"
                            class="absolute right-5 top-5 flex h-11 w-11 items-center justify-center rounded-full bg-white text-2xl text-red-500 shadow-md transition hover:scale-110 hover:bg-red-50"
                        >
                            <span id="favouriteIcon">
                                ♥
                            </span>
                        </button>

                    </div>


                   
                    {{-- DETAIL CONTENT --}}
                   

                    <div class="p-6 sm:p-8 lg:p-10">


                       
                        {{-- TITLE + PRICE --}}
                       

                        <div
                            class="flex flex-col gap-5 border-b border-gray-100 pb-7 sm:flex-row sm:items-start sm:justify-between"
                        >

                            <div>

                                <h1
                                    id="detailHouseTitle"
                                    class="text-3xl font-bold text-gray-800 sm:text-4xl"
                                >
                                </h1>

                                <p
                                    id="detailHouseArea"
                                    class="mt-3 text-base text-gray-500"
                                >
                                </p>

                            </div>


                            <div class="sm:text-right">

                                <div
                                    id="detailHousePrice"
                                    class="text-3xl font-bold text-indigo-600"
                                >
                                </div>

                                <span class="text-sm text-gray-400">
                                    / bulan
                                </span>

                            </div>

                        </div>


                       
                        {{-- QUICK DETAILS --}}
                       

                        <div
                            class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4"
                        >

                            {{-- BEDROOM --}}

                            <div class="rounded-2xl bg-gray-50 p-5">

                                <p class="text-sm text-gray-500">
                                    Bilik Tidur
                                </p>

                                <p
                                    id="detailBedrooms"
                                    class="mt-2 text-lg font-bold text-gray-800"
                                >
                                </p>

                            </div>


                            {{-- BATHROOM --}}

                            <div class="rounded-2xl bg-gray-50 p-5">

                                <p class="text-sm text-gray-500">
                                    Bilik Air
                                </p>

                                <p
                                    id="detailBathrooms"
                                    class="mt-2 text-lg font-bold text-gray-800"
                                >
                                </p>

                            </div>


                            {{-- PROPERTY TYPE --}}

                            <div class="rounded-2xl bg-gray-50 p-5">

                                <p class="text-sm text-gray-500">
                                    Jenis Rumah
                                </p>

                                <p
                                    id="detailPropertyType"
                                    class="mt-2 text-lg font-bold capitalize text-gray-800"
                                >
                                </p>

                            </div>


                            {{-- FURNISHED --}}

                            <div class="rounded-2xl bg-gray-50 p-5">

                                <p class="text-sm text-gray-500">
                                    Furnished
                                </p>

                                <p
                                    id="detailFurnished"
                                    class="mt-2 text-lg font-bold text-gray-800"
                                >
                                </p>

                            </div>

                        </div>


                       
                        {{-- MAIN DETAIL AREA --}}
                       

                        <div
                            class="mt-10 grid grid-cols-1 gap-10 lg:grid-cols-3"
                        >

                           
                            {{-- LEFT CONTENT --}}
                           

                            <div class="lg:col-span-2">


                                {{-- ADDRESS --}}

                                <div>

                                    <h2 class="text-xl font-bold text-gray-800">
                                        📍 Alamat
                                    </h2>

                                    <p
                                        id="detailAddress"
                                        class="mt-3 text-sm leading-7 text-gray-600"
                                    >
                                    </p>

                                </div>


                                {{-- DESCRIPTION --}}

                                <div class="mt-8">

                                    <h2 class="text-xl font-bold text-gray-800">
                                        📝 Penerangan Rumah
                                    </h2>

                                    <p
                                        id="detailDescription"
                                        class="mt-3 whitespace-pre-line text-sm leading-7 text-gray-600"
                                    >
                                    </p>

                                </div>


                                {{-- STATUS --}}

                                <div class="mt-8">

                                    <h2 class="text-xl font-bold text-gray-800">
                                        Status Rumah
                                    </h2>

                                    <span
                                        id="detailStatus"
                                        class="mt-3 inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700"
                                    >
                                    </span>

                                </div>

                            </div>


                           
                            {{-- LANDLORD --}}
                           

                            <div>

                                <div
                                    class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6"
                                >

                                    <h2 class="text-lg font-bold text-indigo-800">
                                        👤 Maklumat Pemilik
                                    </h2>


                                    <div class="mt-6 space-y-5">


                                        {{-- NAME --}}

                                        <div>

                                            <p class="text-xs text-gray-500">
                                                Nama
                                            </p>

                                            <p
                                                id="detailLandlordName"
                                                class="mt-1 font-semibold text-gray-800"
                                            >
                                            </p>

                                        </div>


                                        {{-- PHONE --}}

                                        <div>

                                            <p class="text-xs text-gray-500">
                                                Telefon
                                            </p>

                                            <p
                                                id="detailLandlordPhone"
                                                class="mt-1 font-semibold text-gray-800"
                                            >
                                            </p>

                                        </div>


                                        {{-- EMAIL --}}

                                        <div>

                                            <p class="text-xs text-gray-500">
                                                Email
                                            </p>

                                            <p
                                                id="detailLandlordEmail"
                                                class="mt-1 break-all font-semibold text-gray-800"
                                            >
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                       
                        {{-- BOTTOM BACK BUTTON --}}
                       

                        <div class="mt-10 border-t border-gray-100 pt-6">

                            <button
                                type="button"
                                onclick="backToHouseList()"
                                class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >
                                ← Kembali ke Favourite
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =============================================================== --}}
    {{-- JAVASCRIPT --}}
    {{-- =============================================================== --}}

    <script>

        // =========================================================
        // GET FAVOURITE HOUSES FROM LARAVEL
        // =========================================================

        const houses = @json($houses->values());


        // =========================================================
        // IMAGE SLIDER VARIABLES
        // =========================================================

        let currentHouseImages = [];
        let currentImageIndex = 0;


        // =========================================================
        // SHOW HOUSE DETAIL
        // =========================================================

        function showHouseDetail(houseId, images = []) {

            const houseDetail =
                document.getElementById('houseDetail');


            // Simpan house ID

            houseDetail.dataset.houseId = houseId;


            // Cari rumah

            const house = houses.find(
                h => Number(h.id) === Number(houseId)
            );


            if (!house) {

                console.error(
                    'House not found:',
                    houseId
                );

                return;
            }


            // =====================================================
            // HOUSE INFORMATION
            // =====================================================

            document.getElementById(
                'detailHouseTitle'
            ).textContent =
                house.title ?? 'Tiada nama';


            document.getElementById(
                'detailHouseArea'
            ).textContent =
                '📍 ' +
                (house.area ?? 'Lokasi tidak dinyatakan');


            document.getElementById(
                'detailHousePrice'
            ).textContent =
                'RM' +
                Number(
                    house.monthly_rent ?? 0
                ).toLocaleString();


            document.getElementById(
                'detailBedrooms'
            ).textContent =
                (house.bedrooms ?? 0) +
                ' Bilik';


            document.getElementById(
                'detailBathrooms'
            ).textContent =
                (house.bathrooms ?? 0) +
                ' Bilik Air';


            document.getElementById(
                'detailPropertyType'
            ).textContent =
                house.property_type ??
                'Tidak dinyatakan';


            document.getElementById(
                'detailFurnished'
            ).textContent =
                house.furnished ??
                'Tidak dinyatakan';


            document.getElementById(
                'detailAddress'
            ).textContent =
                house.address ??
                'Alamat tidak dinyatakan';


            document.getElementById(
                'detailDescription'
            ).textContent =
                house.description ??
                'Tiada penerangan diberikan';


            // =====================================================
            // LANDLORD INFORMATION
            // =====================================================

            document.getElementById(
                'detailLandlordName'
            ).textContent =
                house.landlord?.name ??
                'Tidak diketahui';


            document.getElementById(
                'detailLandlordPhone'
            ).textContent =
                house.phone ??
                'Tidak diberikan';


            document.getElementById(
                'detailLandlordEmail'
            ).textContent =
                house.landlord?.email ??
                'Tidak diberikan';


            // =====================================================
            // STATUS
            // =====================================================

            const status =
                document.getElementById(
                    'detailStatus'
                );


            if (house.status === 'available') {

                status.textContent =
                    'Available';

                status.className =
                    'mt-3 inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700';

            } else {

                status.textContent =
                    house.status ??
                    'Tidak dinyatakan';

                status.className =
                    'mt-3 inline-flex rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600';
            }


            // =====================================================
            // IMAGES
            // =====================================================

            currentHouseImages =
                Array.isArray(images)
                    ? images
                    : [];

            currentImageIndex = 0;

            updateHouseImage();


            // =====================================================
            // FAVOURITE BUTTON
            // =====================================================

            setFavouriteButton(true);


            // =====================================================
            // HIDE LIST
            // =====================================================

            document
                .getElementById('houseListing')
                .classList.add('hidden');


            // =====================================================
            // SHOW DETAIL
            // =====================================================

            houseDetail
                .classList.remove('hidden');


            // =====================================================
            // SCROLL TOP
            // =====================================================

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

        }


        // =========================================================
        // SET FAVOURITE BUTTON
        // =========================================================

        function setFavouriteButton(isFavourite) {

            const icon =
                document.getElementById(
                    'favouriteIcon'
                );

            const button =
                document.getElementById(
                    'favouriteButton'
                );


            if (isFavourite) {

                icon.textContent = '♥';

                button.classList.remove(
                    'text-gray-700',
                    'hover:bg-gray-50'
                );

                button.classList.add(
                    'text-red-500',
                    'bg-red-50'
                );

            } else {

                icon.textContent = '♡';

                button.classList.remove(
                    'text-red-500',
                    'bg-red-50'
                );

                button.classList.add(
                    'text-gray-700'
                );
            }

        }


        // =========================================================
        // UPDATE HOUSE IMAGE
        // =========================================================

        function updateHouseImage() {

            const image =
                document.getElementById(
                    'detailHouseImage'
                );

            const noImage =
                document.getElementById(
                    'detailNoImage'
                );

            const prevButton =
                document.getElementById(
                    'prevImageButton'
                );

            const nextButton =
                document.getElementById(
                    'nextImageButton'
                );

            const counter =
                document.getElementById(
                    'imageCounter'
                );


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
            // SHOW IMAGE
            // =====================================================

            image.src =
                '/storage/' +
                currentHouseImages[currentImageIndex];

            image.classList.remove('hidden');

            noImage.classList.add('hidden');


            // =====================================================
            // COUNTER
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


        // =========================================================
        // PREVIOUS IMAGE
        // =========================================================

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


        // =========================================================
        // NEXT IMAGE
        // =========================================================

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


        // =========================================================
        // REMOVE FAVOURITE DIRECTLY FROM CARD
        // =========================================================

        async function removeFavouriteFromCard(event, houseId) {

            // Jangan buka detail rumah
            event.stopPropagation();


            const button =
                event.currentTarget;


            try {

                const response =
                    await fetch(
                        `/favourite/${houseId}/toggle`,
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .getAttribute(
                                            'content'
                                        )
                            }
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Gagal membuang Favourite.'
                    );
                }


                // =================================================
                // KALAU BERJAYA UNFAVOURITE
                // =================================================

                if (!data.favourite) {

                    const card =
                        button.closest(
                            '.house-card'
                        );


                    if (card) {

                        // Animation

                        card.style.transition =
                            'opacity 0.25s ease, transform 0.25s ease';

                        card.style.opacity = '0';

                        card.style.transform =
                            'scale(0.95)';


                        setTimeout(() => {

                            card.remove();


                            checkFavouriteEmpty();

                        }, 250);

                    }

                }

            } catch (error) {

                console.error(
                    'Unfavourite Error:',
                    error
                );


                alert(
                    'Tidak dapat membuang rumah daripada Favourite.'
                );

            }

        }


        // =========================================================
        // CHECK EMPTY FAVOURITE
        // =========================================================

        function checkFavouriteEmpty() {

            const listing =
                document.getElementById(
                    'houseListing'
                );


            const cards =
                listing.querySelectorAll(
                    '.house-card'
                );


            if (cards.length === 0) {

                listing.innerHTML = `

                    <div
                        id="emptyFavourite"
                        class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center"
                    >

                        <div class="text-5xl">
                            ♡
                        </div>

                        <h3
                            class="mt-4 text-lg font-bold text-gray-700"
                        >
                            Belum ada rumah favourite
                        </h3>

                        <p
                            class="mt-2 text-sm text-gray-500"
                        >
                            Rumah yang anda favourite akan dipaparkan di sini.
                        </p>

                        <a
                            href="{{ route('student.dashboard') }}"
                            class="mt-6 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Cari Rumah
                        </a>

                    </div>

                `;
            }

        }


        // =========================================================
        // TOGGLE FAVOURITE FROM DETAIL
        // =========================================================

        async function toggleFavourite() {

            const houseDetail =
                document.getElementById(
                    'houseDetail'
                );


            const houseId =
                houseDetail.dataset.houseId;


            if (!houseId) {
                return;
            }


            try {

                const response =
                    await fetch(
                        `/favourite/${houseId}/toggle`,
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .getAttribute(
                                            'content'
                                        )
                            }
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Favourite gagal.'
                    );
                }


                // =================================================
                // UPDATE ICON
                // =================================================

                setFavouriteButton(
                    data.favourite
                );


                // =================================================
                // KALAU DIBUANG DARIPADA FAVOURITE
                // =================================================

                if (!data.favourite) {

                    const card =
                        document.querySelector(
                            `.house-card[data-house-id="${houseId}"]`
                        );


                    if (card) {

                        card.style.transition =
                            'opacity 0.25s ease, transform 0.25s ease';

                        card.style.opacity = '0';

                        card.style.transform =
                            'scale(0.95)';


                        setTimeout(() => {

                            card.remove();

                            checkFavouriteEmpty();

                            backToHouseList();

                        }, 250);

                    } else {

                        backToHouseList();

                    }

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


        // =========================================================
        // BACK TO FAVOURITE LIST
        // =========================================================

        function backToHouseList() {

            document
                .getElementById('houseDetail')
                .classList.add('hidden');


            document
                .getElementById('houseListing')
                .classList.remove('hidden');


            document
                .getElementById('houseDetail')
                .dataset.houseId = '';


            currentHouseImages = [];

            currentImageIndex = 0;


            setFavouriteButton(true);


            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

        }

    </script>

</x-app-layout>

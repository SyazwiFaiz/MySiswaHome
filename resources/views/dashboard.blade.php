@php
    // ------------------------------------------------------------
    // Tukar medan `image` (array / JSON / JSON dalam JSON / laluan tunggal)
    // kepada senarai URL penuh. Dibuat di server supaya JS tak perlu meneka.
    // ------------------------------------------------------------
    $flattenImages = function ($value, $depth = 0) use (&$flattenImages) {
        if ($depth > 4 || $value === null || $value === '') {
            return [];
        }

        if ($value instanceof \Illuminate\Contracts\Support\Arrayable) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            $out = [];
            foreach ($value as $item) {
                $out = array_merge($out, $flattenImages($item, $depth + 1));
            }
            return $out;
        }

        if (!is_string($value)) {
            return [];
        }

        $text = trim($value);

        if ($text === '') {
            return [];
        }

        if (preg_match('/^[\[{"]/', $text)) {
            $decoded = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $flattenImages($decoded, $depth + 1);
            }
        }

        return [$text];
    };

    $resolveImages = function ($raw) use ($flattenImages) {
        $urls = [];

        foreach ($flattenImages($raw) as $path) {
            if (preg_match('#^https?://#i', $path)) {
                $urls[] = $path;
                continue;
            }

            $path = ltrim(str_replace('\\', '/', $path), '/');
            $path = preg_replace('#^(app/)?public/#i', '', $path);
            $path = preg_replace('#^storage/#i', '', $path);

            $encoded = implode('/', array_map('rawurlencode', explode('/', $path)));
            // Guna laluan sahaja (tanpa domain/port) supaya sentiasa sepadan dengan alamat semasa
            $urls[] = parse_url(asset('storage/' . $encoded), PHP_URL_PATH) ?: '/storage/' . $encoded;
        }

        return array_values(array_unique($urls));
    };

    $toHouseArray = function ($house) use ($resolveImages) {
        $data = is_array($house)
            ? $house
            : (method_exists($house, 'toArray') ? $house->toArray() : (array) $house);

        $data['image_urls'] = $resolveImages($data['image'] ?? null);

        return $data;
    };

    $housesJson = collect($houses->items())->map($toHouseArray)->values();
    $selectedJson = (isset($selectedHouse) && $selectedHouse) ? $toHouseArray($selectedHouse) : null;

    $isSearching = request()->hasAny(['location', 'price', 'type']);
@endphp

<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
            <p class="mt-1 text-sm text-gray-500">
                Cari rumah sewa yang sesuai untuk kehidupan student anda.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            @if (request()->has('debug'))
                <div class="mb-4 break-all rounded-lg bg-yellow-200 p-3 text-xs text-black">
                    DEBUG | rumah: {{ $housesJson->count() }} |
                    URL gambar pertama: {{ $housesJson->first()['image_urls'][0] ?? 'KOSONG' }} |
                    host semasa: {{ request()->getSchemeAndHttpHost() }} |
                    APP_URL: {{ config('app.url') }}
                </div>
            @endif


            {{-- ===================================================== --}}
            {{-- SENARAI RUMAH (disembunyikan bila detail dibuka)      --}}
            {{-- ===================================================== --}}
            <div id="listingSection">

                <!-- Welcome -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Hai, {{ Auth::user()->name }}</h1>
                    <p class="mt-2 text-gray-500">
                        Jom cari rumah sewa yang sesuai dengan bajet dan lokasi anda.
                    </p>
                </div>

                <!-- Search Box -->
                <div class="mb-10 overflow-hidden rounded-2xl bg-indigo-600 shadow-lg">
                    <div class="p-6 sm:p-8">
                        <h2 class="text-xl font-bold text-white">Cari Rumah Sewa</h2>
                        <p class="mt-1 text-sm text-indigo-100">
                            Cari rumah berdasarkan lokasi, harga dan jenis kediaman.
                        </p>

                        <form action="{{ route('student.dashboard') }}" method="GET" class="mt-6">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-white">Lokasi</label>
                                    <input type="text" name="location" value="{{ request('location') }}"
                                        placeholder="Contoh: Semenyih"
                                        class="w-full rounded-xl border-0 px-4 py-3 text-sm text-gray-800 shadow-sm focus:ring-2 focus:ring-white">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-white">Bajet Maksimum</label>
                                    <select name="price"
                                        class="w-full rounded-xl border-0 px-4 py-3 text-sm text-gray-800 shadow-sm focus:ring-2 focus:ring-white">
                                        <option value="">Pilih bajet</option>
                                        @foreach ([300, 400, 500, 600, 1000] as $budget)
                                            <option value="{{ $budget }}"
                                                {{ request('price') == $budget ? 'selected' : '' }}>
                                                RM{{ $budget }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-white">Jenis</label>
                                    <select name="type"
                                        class="w-full rounded-xl border-0 px-4 py-3 text-sm text-gray-800 shadow-sm focus:ring-2 focus:ring-white">
                                        <option value="">Semua jenis</option>
                                        @foreach (['bilik' => 'Bilik', 'rumah' => 'Rumah', 'apartment' => 'Apartment', 'studio' => 'Studio'] as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ request('type') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

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

                <!-- Header senarai -->
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            {{ $isSearching ? 'Hasil Carian Rumah' : 'Rumah Disyorkan' }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $isSearching ? 'Rumah yang sepadan dengan carian anda.' : 'Pilihan rumah sewa yang mungkin sesuai untuk anda.' }}
                        </p>
                    </div>

                    @if ($isSearching)
                        <a href="{{ route('student.dashboard') }}"
                            class="shrink-0 rounded-lg border border-indigo-200 bg-white px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50">
                            Reset Carian
                        </a>
                    @endif
                </div>

                <!-- Senarai kad -->
                <div id="houseListing" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($houses as $house)
                        @php $houseImages = $resolveImages($house->image); @endphp

                        <div
                            class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                            <div class="relative h-48 bg-gray-200">
                                @if (!empty($houseImages))
                                    <img src="{{ $houseImages[0] }}"
                                        alt="{{ $house->title }}" loading="lazy"
                                        class="h-full w-full object-cover"
                                        onerror="console.warn('Gambar gagal dimuatkan:', this.src); this.replaceWith(Object.assign(document.createElement('div'), {className: 'flex h-full w-full items-center justify-center text-5xl', textContent: '🏠'}));">

                                    @if (count($houseImages) > 1)
                                        <div
                                            class="absolute bottom-3 right-3 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white">
                                            📷 {{ count($houseImages) }} gambar
                                        </div>
                                    @endif
                                @else
                                    <div class="flex h-full items-center justify-center text-5xl">🏠</div>
                                @endif

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

                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-800">{{ $house->title }}</h3>
                                <p class="mt-1 text-sm text-gray-500">📍 {{ $house->area }}</p>

                                <div class="mt-4 flex items-center justify-between">
                                    <div>
                                        <span class="text-xl font-bold text-indigo-600">
                                            RM{{ number_format($house->monthly_rent) }}
                                        </span>
                                        <span class="text-sm text-gray-400">/ bulan</span>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $house->bedrooms }} Bilik</div>
                                </div>

                                <button type="button" onclick="showHouseDetail({{ $house->id }})"
                                    class="mt-5 w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                    Lihat Butiran
                                </button>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center">
                            <div class="text-5xl">🏠</div>
                            <h3 class="mt-4 text-lg font-bold text-gray-700">
                                {{ $isSearching ? 'Tiada rumah dijumpai' : 'Belum ada rumah tersedia' }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ $isSearching ? 'Cuba ubah lokasi, bajet atau jenis rumah.' : 'Buat masa ini belum ada landlord yang menambah rumah sewa.' }}
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($houses->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $houses->links() }}
                    </div>
                @endif

                <!-- Tips -->
                <div class="mt-10 rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                    <div class="flex gap-4">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-xl">
                            💡
                        </div>
                        <div>
                            <h3 class="font-bold text-indigo-800">Tips mencari rumah sewa</h3>
                            <p class="mt-1 text-sm leading-6 text-indigo-600">
                                Pastikan anda menyemak lokasi, harga sewa, kemudahan dan syarat rumah
                                sebelum membuat permohonan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================================================== --}}
            {{-- DETAIL RUMAH (SATU SAHAJA - dikawal oleh JavaScript)  --}}
            {{-- ===================================================== --}}
            <div id="houseDetail" class="hidden" data-house-id="">

                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                    <!-- Header gradient -->
                    <div
                        class="relative overflow-hidden bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 px-5 py-7 text-white sm:px-8 sm:py-8">
                        <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-white/5"></div>

                        <div class="relative z-10">
                            <div
                                class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-indigo-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10.5L12 3l9 7.5V21a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1V10.5z" />
                                </svg>
                                MAKLUMAT HARTANAH
                            </div>

                            <h2 id="detailHouseTitle"
                                class="text-2xl font-extrabold tracking-tight sm:text-3xl"></h2>

                            <p class="mt-2 flex items-start gap-2 text-sm leading-6 text-indigo-100 sm:text-base">
                                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span id="detailHouseArea"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Kandungan -->
                    <div class="p-4 sm:p-6 lg:p-8">
                        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">

                            <!-- KIRI: Galeri + Penerangan -->
                            <div class="min-w-0 lg:col-span-7">

                                <div class="mb-4 flex items-center justify-between gap-3">
                                    <h3 class="text-lg font-bold text-slate-900 sm:text-xl">Galeri Rumah</h3>
                                    <span id="galleryBadge"
                                        class="hidden shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700"></span>
                                </div>

                                <!-- Gambar utama -->
                                <div id="galleryWrapper">
                                    <div class="relative h-64 overflow-hidden rounded-2xl bg-slate-100 sm:h-96">
                                        <img id="detailHouseImage" src="" alt="Gambar rumah"
                                            class="hidden h-full w-full object-cover">

                                        <div id="detailNoImage"
                                            class="flex h-full flex-col items-center justify-center px-4 text-center">
                                            <div
                                                class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 text-3xl">
                                                🏠
                                            </div>
                                            <p class="font-semibold text-slate-700">Tiada gambar rumah</p>
                                            <p class="mt-1 text-sm text-slate-500">
                                                Pemilik belum memuat naik gambar untuk rumah ini.
                                            </p>
                                        </div>

                                        <button id="prevImageButton" type="button" onclick="previousHouseImage()"
                                            aria-label="Gambar sebelumnya"
                                            class="absolute left-3 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-xl font-bold text-gray-700 shadow-md transition hover:bg-white">
                                            ←
                                        </button>

                                        <button id="nextImageButton" type="button" onclick="nextHouseImage()"
                                            aria-label="Gambar seterusnya"
                                            class="absolute right-3 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-xl font-bold text-gray-700 shadow-md transition hover:bg-white">
                                            →
                                        </button>

                                        <div id="imageCounter"
                                            class="absolute bottom-3 left-1/2 hidden -translate-x-1/2 rounded-full bg-black/60 px-4 py-1.5 text-sm font-semibold text-white">
                                            1 / 1
                                        </div>

                                        <button id="favouriteButton" type="button" onclick="toggleFavourite()"
                                            aria-label="Tambah ke favourite"
                                            class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full bg-white text-2xl text-gray-700 shadow-md transition hover:scale-110 hover:bg-red-50">
                                            <span id="favouriteIcon">♡</span>
                                        </button>
                                    </div>

                                    <!-- Thumbnail -->
                                    <div id="detailThumbnails" class="mt-3 hidden gap-2 overflow-x-auto pb-1"></div>
                                </div>

                                <!-- Penerangan -->
                                <div class="mt-6 rounded-2xl border border-slate-200 p-5 sm:p-6">
                                    <div class="mb-3 flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900">Penerangan Rumah</h3>
                                    </div>

                                    <p id="detailDescription"
                                        class="whitespace-pre-line break-words text-sm leading-7 text-slate-600 sm:text-base">
                                    </p>
                                </div>
                            </div>

                            <!-- KANAN: Maklumat -->
                            <div class="min-w-0 lg:col-span-5">

                                <!-- Harga -->
                                <div
                                    class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-violet-50 p-5 sm:p-6">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-medium text-slate-500">Harga sewa bulanan</p>
                                            <p id="detailHousePrice"
                                                class="mt-2 break-words text-3xl font-extrabold tracking-tight text-indigo-700 sm:text-4xl">
                                            </p>
                                            <p class="mt-1 text-sm text-slate-500">Ringgit Malaysia / bulan</p>
                                        </div>
                                        <div
                                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-indigo-600 shadow-sm">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 2v10m0 0v2m8-8a8 8 0 11-16 0 8 8 0 0116 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Butiran -->
                                <div class="mt-6">
                                    <h3 class="mb-4 text-lg font-bold text-slate-900">Butiran Hartanah</h3>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div
                                            class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                                            <div
                                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-lg">
                                                🛏️</div>
                                            <p class="text-xs text-slate-500 sm:text-sm">Bilik tidur</p>
                                            <p id="detailBedrooms" class="mt-1 text-lg font-bold text-slate-900"></p>
                                        </div>

                                        <div
                                            class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                                            <div
                                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-lg">
                                                🚿</div>
                                            <p class="text-xs text-slate-500 sm:text-sm">Bilik air</p>
                                            <p id="detailBathrooms" class="mt-1 text-lg font-bold text-slate-900"></p>
                                        </div>

                                        <div
                                            class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                                            <div
                                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-lg">
                                                🏢</div>
                                            <p class="text-xs text-slate-500 sm:text-sm">Jenis hartanah</p>
                                            <p id="detailPropertyType"
                                                class="mt-1 break-words text-base font-bold capitalize text-slate-900">
                                            </p>
                                        </div>

                                        <div
                                            class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                                            <div
                                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-lg">
                                                🛋️</div>
                                            <p class="text-xs text-slate-500 sm:text-sm">Perabot</p>
                                            <p id="detailFurnished"
                                                class="mt-1 break-words text-base font-bold capitalize text-slate-900">
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mt-6 flex items-center justify-between rounded-2xl border border-slate-200 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Status Rumah</p>
                                    <span id="detailStatus"
                                        class="inline-flex rounded-full bg-gray-100 px-4 py-1.5 text-sm font-semibold text-gray-600"></span>
                                </div>

                                <!-- Alamat -->
                                <div class="mt-6 rounded-2xl border border-slate-200 p-5">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-slate-900">Alamat Penuh</p>
                                            <p id="detailAddress"
                                                class="mt-1 break-words text-sm leading-6 text-slate-600"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pemilik -->
                                <div class="mt-6 rounded-2xl border border-slate-200 p-5 sm:p-6">
                                    <div class="mb-4 flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3l2 5-2 1a11 11 0 005 5l1-2 5 2v3a2 2 0 01-2 2A16 16 0 013 5z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900">Hubungi Pemilik</h3>
                                            <p class="text-xs text-slate-500">Berminat dengan rumah ini?</p>
                                        </div>
                                    </div>

                                    <div class="mb-5 space-y-4">
                                        <div>
                                            <p class="text-xs text-slate-500">Nama</p>
                                            <p id="detailLandlordName"
                                                class="mt-0.5 break-words font-semibold text-slate-800"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Telefon</p>
                                            <p id="detailLandlordPhone"
                                                class="mt-0.5 break-all font-semibold text-slate-800"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Email</p>
                                            <p id="detailLandlordEmail"
                                                class="mt-0.5 break-all font-semibold text-slate-800"></p>
                                        </div>
                                    </div>

                                    <a id="detailWhatsapp" href="#" target="_blank" rel="noopener noreferrer"
                                        class="hidden w-full items-center justify-center gap-2 rounded-xl bg-green-600 px-5 py-3.5 text-center text-sm font-bold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200 sm:text-base">
                                        <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M20.52 3.48A11.86 11.86 0 0012.06 0C5.48 0 .13 5.35.13 11.93c0 2.1.55 4.15 1.6 5.96L0 24l6.27-1.64a11.9 11.9 0 005.79 1.49h.01C18.65 23.85 24 18.5 24 11.92a11.85 11.85 0 00-3.48-8.44zM12.07 21.8a9.9 9.9 0 01-5.04-1.38l-.36-.21-3.72.97.99-3.62-.24-.37a9.85 9.85 0 01-1.52-5.26c0-5.46 4.44-9.9 9.9-9.9a9.83 9.83 0 017 2.9 9.83 9.83 0 012.9 7c0 5.46-4.44 9.9-9.91 9.9zm5.43-7.41c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.39-1.47-.88-.78-1.47-1.74-1.64-2.04-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.09 4.49.71.31 1.27.5 1.7.64.71.22 1.36.19 1.87.12.57-.09 1.76-.72 2.01-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35z" />
                                        </svg>
                                        Hubungi melalui WhatsApp
                                    </a>

                                    <div id="detailNoContact"
                                        class="hidden rounded-xl bg-slate-100 px-4 py-3 text-center text-sm font-medium text-slate-500">
                                        Maklumat hubungan belum tersedia.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Butang kembali -->
                        <div class="mt-8 border-t border-slate-100 pt-6">
                            <button type="button" onclick="backToHouseList()"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 sm:w-auto">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali ke senarai rumah
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================================================== --}}
            {{-- JAVASCRIPT                                            --}}
            {{-- ===================================================== --}}
            <script>
                // ------------------------------------------------------
                // DATA DARI LARAVEL
                // ------------------------------------------------------
                const houses = @json($housesJson);
                const selectedHouse = @json($selectedJson);
                const dashboardUrl = @json(route('student.dashboard'));
                const csrfToken = @json(csrf_token());

                let currentHouseImages = [];
                let currentImageIndex = 0;

                // ------------------------------------------------------
                // HELPER
                // ------------------------------------------------------
                // URL gambar sudah diselesaikan oleh server (image_urls)
                function imageUrl(url) {
                    return url;
                }

                function setText(id, value, fallback = 'Tidak dinyatakan') {
                    const el = document.getElementById(id);
                    if (!el) return;

                    const valid = value !== null && value !== undefined && String(value).trim() !== '';
                    el.textContent = valid ? value : fallback;
                }

                function setFavouriteUI(isFavourite) {
                    const icon = document.getElementById('favouriteIcon');
                    const button = document.getElementById('favouriteButton');
                    if (!icon || !button) return;

                    if (isFavourite) {
                        icon.textContent = '♥';
                        button.classList.remove('text-gray-700', 'hover:bg-red-50');
                        button.classList.add('text-red-500', 'bg-red-50');
                    } else {
                        icon.textContent = '♡';
                        button.classList.remove('text-red-500', 'bg-red-50');
                        button.classList.add('text-gray-700', 'hover:bg-red-50');
                    }
                }

                // ------------------------------------------------------
                // PAPAR DETAIL RUMAH
                // ------------------------------------------------------
                function showHouseDetail(houseId) {
                    let house = houses.find(h => Number(h.id) === Number(houseId));

                    // Datang dari peta: rumah mungkin tiada dalam senarai halaman ini
                    if (!house && selectedHouse && Number(selectedHouse.id) === Number(houseId)) {
                        house = selectedHouse;
                    }

                    if (!house) {
                        console.error('House not found:', houseId);
                        return;
                    }

                    const houseDetail = document.getElementById('houseDetail');
                    const listingSection = document.getElementById('listingSection');

                    if (!houseDetail || !listingSection) return;

                    houseDetail.dataset.houseId = house.id;

                    // Favourite
                    setFavouriteUI(false);
                    checkFavourite(house.id);

                    // Maklumat utama
                    setText('detailHouseTitle', house.title, 'Rumah Sewa');
                    setText('detailHouseArea', house.area, 'Lokasi tidak dinyatakan');

                    const rent = Number(house.monthly_rent ?? 0);
                    setText(
                        'detailHousePrice',
                        'RM' + new Intl.NumberFormat('en-MY', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(Number.isFinite(rent) ? rent : 0)
                    );

                    // Spesifikasi
                    setText('detailBedrooms', `${house.bedrooms ?? 0} bilik`);
                    setText('detailBathrooms', `${house.bathrooms ?? 0} bilik`);
                    setText('detailPropertyType', house.property_type);
                    setText('detailFurnished', house.furnished);

                    // Alamat & penerangan
                    setText('detailAddress', house.address, 'Alamat tidak dinyatakan');
                    setText('detailDescription', house.description, 'Tiada penerangan disediakan oleh pemilik.');

                    // Pemilik
                    const phone = house.phone || house.landlord?.phone || '';
                    setText('detailLandlordName', house.landlord?.name, 'Nama tidak tersedia');
                    setText('detailLandlordPhone', phone, 'Nombor telefon tidak tersedia');
                    setText('detailLandlordEmail', house.landlord?.email, 'E-mel tidak tersedia');

                    // WhatsApp
                    const wa = document.getElementById('detailWhatsapp');
                    const noContact = document.getElementById('detailNoContact');
                    const cleanPhone = String(phone).replace(/[^0-9]/g, '');

                    if (cleanPhone) {
                        wa.href = 'https://wa.me/' + cleanPhone;
                        wa.classList.remove('hidden');
                        wa.classList.add('inline-flex');
                        noContact.classList.add('hidden');
                    } else {
                        wa.classList.add('hidden');
                        wa.classList.remove('inline-flex');
                        noContact.classList.remove('hidden');
                    }

                    // Status
                    const status = document.getElementById('detailStatus');
                    const houseStatus = String(house.status ?? '').toLowerCase();
                    const base = 'inline-flex rounded-full px-4 py-1.5 text-sm font-semibold ';

                    if (houseStatus === 'available') {
                        status.textContent = 'Tersedia';
                        status.className = base + 'bg-emerald-50 text-emerald-700';
                    } else if (houseStatus === 'rented') {
                        status.textContent = 'Telah Disewa';
                        status.className = base + 'bg-rose-50 text-rose-700';
                    } else {
                        status.textContent = house.status || 'Status tidak dinyatakan';
                        status.className = base + 'bg-gray-100 text-gray-600';
                    }

                    // Galeri
                    currentHouseImages = Array.isArray(house.image_urls) ? house.image_urls : [];
                    currentImageIndex = 0;
                    updateHouseImage();

                    // Tukar paparan
                    listingSection.classList.add('hidden');
                    houseDetail.classList.remove('hidden');

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }

                // ------------------------------------------------------
                // GALERI GAMBAR
                // ------------------------------------------------------
                function updateHouseImage() {
                    const image = document.getElementById('detailHouseImage');
                    const noImage = document.getElementById('detailNoImage');
                    const prevButton = document.getElementById('prevImageButton');
                    const nextButton = document.getElementById('nextImageButton');
                    const counter = document.getElementById('imageCounter');
                    const badge = document.getElementById('galleryBadge');
                    const thumbs = document.getElementById('detailThumbnails');

                    const total = currentHouseImages.length;

                    // Tiada gambar
                    if (total === 0) {
                        image.removeAttribute('src');
                        image.classList.add('hidden');
                        noImage.classList.remove('hidden');
                        noImage.classList.add('flex');

                        [prevButton, nextButton].forEach(btn => {
                            btn.classList.add('hidden');
                            btn.classList.remove('flex');
                        });

                        counter.classList.add('hidden');
                        badge.classList.add('hidden');
                        thumbs.classList.add('hidden');
                        thumbs.classList.remove('flex');
                        thumbs.innerHTML = '';
                        return;
                    }

                    // Ada gambar
                    image.src = imageUrl(currentHouseImages[currentImageIndex]);
                    image.onerror = () => console.warn('Gambar gagal dimuatkan:', image.src);
                    image.classList.remove('hidden');
                    noImage.classList.add('hidden');
                    noImage.classList.remove('flex');

                    badge.textContent = total + ' gambar';
                    badge.classList.remove('hidden');

                    if (total > 1) {
                        [prevButton, nextButton].forEach(btn => {
                            btn.classList.remove('hidden');
                            btn.classList.add('flex');
                        });

                        counter.textContent = (currentImageIndex + 1) + ' / ' + total;
                        counter.classList.remove('hidden');

                        // Thumbnail
                        thumbs.innerHTML = '';
                        currentHouseImages.forEach((img, index) => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.setAttribute('aria-label', 'Gambar ' + (index + 1));
                            btn.className =
                                'h-16 w-20 shrink-0 overflow-hidden rounded-xl border-2 transition ' +
                                (index === currentImageIndex ?
                                    'border-indigo-600 opacity-100' :
                                    'border-transparent opacity-60 hover:opacity-100');

                            const thumb = document.createElement('img');
                            thumb.src = imageUrl(img);
                            thumb.alt = '';
                            thumb.className = 'h-full w-full object-cover';
                            thumb.onerror = () => {
                                thumb.style.display = 'none';
                            };

                            btn.appendChild(thumb);
                            btn.addEventListener('click', () => {
                                currentImageIndex = index;
                                updateHouseImage();
                            });

                            thumbs.appendChild(btn);
                        });

                        thumbs.classList.remove('hidden');
                        thumbs.classList.add('flex');
                    } else {
                        [prevButton, nextButton].forEach(btn => {
                            btn.classList.add('hidden');
                            btn.classList.remove('flex');
                        });

                        counter.classList.add('hidden');
                        thumbs.classList.add('hidden');
                        thumbs.classList.remove('flex');
                        thumbs.innerHTML = '';
                    }
                }

                function previousHouseImage() {
                    if (currentHouseImages.length <= 1) return;

                    currentImageIndex = (currentImageIndex - 1 + currentHouseImages.length) % currentHouseImages.length;
                    updateHouseImage();
                }

                function nextHouseImage() {
                    if (currentHouseImages.length <= 1) return;

                    currentImageIndex = (currentImageIndex + 1) % currentHouseImages.length;
                    updateHouseImage();
                }

                // ------------------------------------------------------
                // FAVOURITE
                // ------------------------------------------------------
                async function checkFavourite(houseId) {
                    try {
                        const response = await fetch(`/favourite/${houseId}/check`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) return;

                        const data = await response.json();

                        // Pastikan pengguna masih di rumah yang sama
                        const current = document.getElementById('houseDetail').dataset.houseId;
                        if (String(current) !== String(houseId)) return;

                        setFavouriteUI(Boolean(data.favourite));
                    } catch (error) {
                        console.error('Gagal check favourite:', error);
                    }
                }

                async function toggleFavourite() {
                    const houseId = document.getElementById('houseDetail').dataset.houseId;

                    if (!houseId) {
                        console.error('House ID tidak dijumpai.');
                        return;
                    }

                    try {
                        const response = await fetch(`/favourite/${houseId}/toggle`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Favourite gagal.');
                        }

                        setFavouriteUI(Boolean(data.favourite));
                    } catch (error) {
                        console.error('Favourite Error:', error);
                        alert('Tidak dapat mengemaskini Favourite.');
                    }
                }

                // ------------------------------------------------------
                // KEMBALI KE SENARAI
                // ------------------------------------------------------
                function backToHouseList() {
                    const houseDetail = document.getElementById('houseDetail');

                    houseDetail.classList.add('hidden');
                    houseDetail.dataset.houseId = '';

                    document.getElementById('listingSection').classList.remove('hidden');

                    setFavouriteUI(false);

                    currentHouseImages = [];
                    currentImageIndex = 0;

                    // Buang parameter peta supaya refresh tidak buka detail semula
                    history.replaceState({}, '', dashboardUrl);

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }

                // ------------------------------------------------------
                // KEYBOARD: ← → tukar gambar, Esc kembali
                // ------------------------------------------------------
                document.addEventListener('keydown', function(e) {
                    const detail = document.getElementById('houseDetail');
                    if (!detail || detail.classList.contains('hidden')) return;

                    if (e.key === 'ArrowLeft') previousHouseImage();
                    if (e.key === 'ArrowRight') nextHouseImage();
                    if (e.key === 'Escape') backToHouseList();
                });

                // ------------------------------------------------------
                // AUTO BUKA DETAIL BILA DATANG DARI PETA
                // ------------------------------------------------------
                document.addEventListener('DOMContentLoaded', function() {
                    if (selectedHouse && selectedHouse.id) {
                        showHouseDetail(selectedHouse.id);
                    }
                });
            </script>

        </div>
    </div>

</x-app-layout>
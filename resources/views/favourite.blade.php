@php
    // ------------------------------------------------------------
    // Tukar medan `image` kepada senarai URL penuh (dibuat di server)
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

    $houseList = ($houses ?? null) instanceof \Illuminate\Pagination\AbstractPaginator
        ? $houses->getCollection()
        : collect($houses ?? []);

    $housesJson = $houseList->map($toHouseArray)->values();
@endphp

<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Favourite</h2>
            <p class="mt-1 text-sm text-gray-500">Lihat rumah favourite anda di sini!</p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- ===================================================== --}}
            {{-- SENARAI FAVOURITE                                     --}}
            {{-- ===================================================== --}}
            <div id="listingSection">

                {{-- Hero --}}
                <div
                    class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 p-6 text-white shadow-lg sm:p-8">
                    <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-white/5"></div>

                    <div class="relative z-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                        <div>
                            <div
                                class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-indigo-50">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 21s-7.5-4.6-9.6-9.2C.9 8.5 2.6 5 6 5c2 0 3.3 1.1 4 2.2C10.700 6.100 12 5 14 5c3.400 0 5.100 3.500 3.600 6.800C19.500 16.400 12 21 12 21z" />
                                </svg>
                                SIMPANAN SAYA
                            </div>

                            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Rumah Favourite</h1>
                            <p class="mt-2 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">
                                Senarai rumah yang anda simpan. Bandingkan harga dan lokasi sebelum membuat keputusan.
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center gap-3 rounded-2xl bg-white/10 px-6 py-4 backdrop-blur">
                            <div class="text-4xl font-extrabold" id="favCount">{{ $houseList->count() }}</div>
                            <div class="text-sm leading-5 text-indigo-100">rumah<br>disimpan</div>
                        </div>
                    </div>
                </div>

                {{-- Toolbar --}}
                <div id="toolbar"
                    class="mb-6 grid grid-cols-1 gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:grid-cols-12 {{ $houseList->isEmpty() ? 'hidden' : '' }}">
                    <div class="relative sm:col-span-8">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                        <input id="favSearch" type="search" placeholder="Cari dalam favourite (nama atau kawasan)..."
                            class="w-full rounded-xl border-gray-200 py-3 pl-12 pr-4 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="sm:col-span-4">
                        <select id="favSort"
                            class="w-full rounded-xl border-gray-200 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="default">Susunan: Terkini disimpan</option>
                            <option value="cheapest">Harga: Termurah</option>
                            <option value="expensive">Harga: Termahal</option>
                            <option value="name">Nama: A - Z</option>
                        </select>
                    </div>
                </div>

                {{-- Senarai kad --}}
                <div id="houseListing" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                    @forelse ($houseList as $index => $house)
                        @php $images = $resolveImages($house->image ?? null); @endphp

                        <article
                            class="house-card group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                            data-house-id="{{ $house->id }}" data-index="{{ $index }}"
                            data-title="{{ \Illuminate\Support\Str::lower($house->title) }}"
                            data-area="{{ \Illuminate\Support\Str::lower($house->area . ' ' . $house->address) }}"
                            data-price="{{ (float) $house->monthly_rent }}">

                            {{-- Gambar --}}
                            <div class="relative h-52 overflow-hidden bg-gradient-to-br from-indigo-50 to-violet-50">
                                @if (!empty($images))
                                    <img src="{{ $images[0] }}" alt="{{ $house->title }}" loading="lazy"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        onerror="console.warn('Gambar gagal dimuatkan:', this.src); this.replaceWith(Object.assign(document.createElement('div'), {className: 'flex h-full w-full items-center justify-center text-5xl', textContent: '🏠'}));">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-5xl">🏠</div>
                                @endif

                                <div
                                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/10">
                                </div>

                                {{-- Status --}}
                                <div class="absolute left-3 top-3">
                                    @if ($house->status === 'available')
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-emerald-700 shadow-sm">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Tersedia
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-rose-700 shadow-sm">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                            {{ $house->status === 'rented' ? 'Telah Disewa' : ucfirst($house->status) }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Buang favourite --}}
                                <button type="button" onclick="removeFavouriteFromCard(event, {{ $house->id }})"
                                    title="Buang daripada Favourite" aria-label="Buang daripada Favourite"
                                    class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-white text-red-500 shadow-md transition hover:scale-110 hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 21s-7.5-4.6-9.6-9.2C.9 8.5 2.6 5 6 5c2 0 3.3 1.1 4 2.2C10.700 6.100 12 5 14 5c3.400 0 5.100 3.500 3.600 6.800C19.500 16.400 12 21 12 21z" />
                                    </svg>
                                </button>

                                {{-- Harga atas gambar --}}
                                <div class="absolute bottom-3 left-3 text-white">
                                    <span class="text-2xl font-extrabold drop-shadow">
                                        RM{{ number_format($house->monthly_rent) }}
                                    </span>
                                    <span class="text-sm text-white/80">/ bulan</span>
                                </div>

                                @if (count($images) > 1)
                                    <div
                                        class="absolute bottom-3 right-3 rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white">
                                        📷 {{ count($images) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Kandungan --}}
                            <div class="p-5">
                                <h3 class="truncate text-lg font-bold text-gray-800">{{ $house->title }}</h3>

                                <p class="mt-1 flex items-center gap-1.5 truncate text-sm text-gray-500">
                                    <svg class="h-4 w-4 shrink-0 text-rose-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $house->area ?: 'Lokasi tidak dinyatakan' }}</span>
                                </p>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                        🛏️ {{ $house->bedrooms ?? 0 }} bilik
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-lg bg-cyan-50 px-2.5 py-1 text-xs font-semibold text-cyan-700">
                                        🚿 {{ $house->bathrooms ?? 0 }} air
                                    </span>
                                    @if ($house->property_type)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-lg bg-violet-50 px-2.5 py-1 text-xs font-semibold capitalize text-violet-700">
                                            🏢 {{ $house->property_type }}
                                        </span>
                                    @endif
                                </div>

                                <button type="button" onclick="showHouseDetail({{ $house->id }})"
                                    class="mt-5 w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">
                                    Lihat Butiran
                                </button>
                            </div>
                        </article>

                    @empty
                        <div id="emptyFavourite"
                            class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white px-4 py-16 text-center">
                            <div
                                class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-rose-50 text-4xl text-rose-400">
                                ♡</div>
                            <h3 class="mt-5 text-xl font-bold text-gray-800">Belum ada rumah favourite</h3>
                            <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                                Tekan ikon hati pada mana-mana rumah untuk menyimpannya di sini dan bandingkan kemudian.
                            </p>
                            <a href="{{ route('student.dashboard') }}"
                                class="mt-6 inline-flex rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                Cari Rumah
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Tiada hasil carian --}}
                <div id="noResult"
                    class="hidden rounded-3xl border border-dashed border-gray-300 bg-white px-4 py-14 text-center">
                    <div class="text-4xl">🔎</div>
                    <h3 class="mt-4 text-lg font-bold text-gray-800">Tiada rumah dijumpai</h3>
                    <p class="mt-1 text-sm text-gray-500">Cuba kata kunci lain.</p>
                </div>
            </div>

            {{-- ===================================================== --}}
            {{-- DETAIL RUMAH                                          --}}
            {{-- ===================================================== --}}
            <div id="houseDetail" class="hidden" data-house-id="">

                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                    {{-- Header gradient --}}
                    <div
                        class="relative overflow-hidden bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 px-5 py-7 text-white sm:px-8 sm:py-8">
                        <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-white/5"></div>

                        <div class="relative z-10">
                            <button type="button" onclick="backToHouseList()"
                                class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-white/20">
                                ← Kembali ke Favourite
                            </button>

                            <h2 id="detailHouseTitle" class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                            </h2>
                            <p id="detailHouseArea" class="mt-2 text-sm leading-6 text-indigo-100 sm:text-base"></p>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 lg:p-8">
                        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">

                            {{-- Kiri: galeri + penerangan --}}
                            <div class="min-w-0 lg:col-span-7">
                                <div class="mb-4 flex items-center justify-between gap-3">
                                    <h3 class="text-lg font-bold text-slate-900 sm:text-xl">Galeri Rumah</h3>
                                    <span id="galleryBadge"
                                        class="hidden shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700"></span>
                                </div>

                                <div class="relative h-64 overflow-hidden rounded-2xl bg-slate-100 sm:h-96">
                                    <img id="detailHouseImage" src="" alt="Gambar rumah"
                                        class="hidden h-full w-full object-cover">

                                    <div id="detailNoImage"
                                        class="flex h-full flex-col items-center justify-center px-4 text-center">
                                        <div
                                            class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 text-3xl">
                                            🏠</div>
                                        <p class="font-semibold text-slate-700">Tiada gambar rumah</p>
                                        <p class="mt-1 text-sm text-slate-500">Pemilik belum memuat naik gambar.</p>
                                    </div>

                                    <button id="prevImageButton" type="button" onclick="previousHouseImage()"
                                        aria-label="Gambar sebelumnya"
                                        class="absolute left-3 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-xl font-bold text-gray-700 shadow-md transition hover:bg-white">←</button>

                                    <button id="nextImageButton" type="button" onclick="nextHouseImage()"
                                        aria-label="Gambar seterusnya"
                                        class="absolute right-3 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-xl font-bold text-gray-700 shadow-md transition hover:bg-white">→</button>

                                    <div id="imageCounter"
                                        class="absolute bottom-3 left-1/2 hidden -translate-x-1/2 rounded-full bg-black/60 px-4 py-1.5 text-sm font-semibold text-white">
                                        1 / 1</div>

                                    <button id="favouriteButton" type="button" onclick="toggleFavourite()"
                                        aria-label="Buang daripada Favourite"
                                        class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-2xl text-red-500 shadow-md transition hover:scale-110">
                                        <span id="favouriteIcon">♥</span>
                                    </button>
                                </div>

                                <div id="detailThumbnails" class="mt-3 hidden gap-2 overflow-x-auto pb-1"></div>

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

                            {{-- Kanan: maklumat --}}
                            <div class="min-w-0 lg:col-span-5">

                                <div
                                    class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-violet-50 p-5 sm:p-6">
                                    <p class="text-sm font-medium text-slate-500">Harga sewa bulanan</p>
                                    <p id="detailHousePrice"
                                        class="mt-2 break-words text-3xl font-extrabold tracking-tight text-indigo-700 sm:text-4xl">
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">Ringgit Malaysia / bulan</p>
                                </div>

                                <div class="mt-6">
                                    <h3 class="mb-4 text-lg font-bold text-slate-900">Butiran Hartanah</h3>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                            <div
                                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-lg">
                                                🛏️</div>
                                            <p class="text-xs text-slate-500 sm:text-sm">Bilik tidur</p>
                                            <p id="detailBedrooms" class="mt-1 text-lg font-bold text-slate-900"></p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                            <div
                                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-lg">
                                                🚿</div>
                                            <p class="text-xs text-slate-500 sm:text-sm">Bilik air</p>
                                            <p id="detailBathrooms" class="mt-1 text-lg font-bold text-slate-900"></p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                            <div
                                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-lg">
                                                🏢</div>
                                            <p class="text-xs text-slate-500 sm:text-sm">Jenis hartanah</p>
                                            <p id="detailPropertyType"
                                                class="mt-1 break-words text-base font-bold capitalize text-slate-900">
                                            </p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
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

                                <div
                                    class="mt-6 flex items-center justify-between rounded-2xl border border-slate-200 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Status Rumah</p>
                                    <span id="detailStatus"
                                        class="inline-flex rounded-full bg-gray-100 px-4 py-1.5 text-sm font-semibold text-gray-600"></span>
                                </div>

                                <div class="mt-6 rounded-2xl border border-slate-200 p-5">
                                    <p class="text-sm font-semibold text-slate-900">📍 Alamat Penuh</p>
                                    <p id="detailAddress" class="mt-1 break-words text-sm leading-6 text-slate-600">
                                    </p>
                                </div>

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
                                        Hubungi melalui WhatsApp
                                    </a>

                                    <div id="detailNoContact"
                                        class="hidden rounded-xl bg-slate-100 px-4 py-3 text-center text-sm font-medium text-slate-500">
                                        Maklumat hubungan belum tersedia.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 border-t border-slate-100 pt-6">
                            <button type="button" onclick="backToHouseList()"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-100 sm:w-auto">
                                ← Kembali ke Favourite
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" aria-live="polite"
        class="pointer-events-none fixed bottom-6 left-1/2 z-50 -translate-x-1/2 translate-y-4 rounded-xl bg-gray-900 px-5 py-3 text-sm font-medium text-white opacity-0 shadow-xl transition duration-300">
    </div>

    <script>
        // ======================================================
        // DATA
        // ======================================================
        const houses = @json($housesJson);
        const dashboardUrl = @json(route('student.dashboard'));
        const csrfToken = @json(csrf_token());

        let currentHouseImages = [];
        let currentImageIndex = 0;
        let toastTimer;

        // ======================================================
        // HELPER
        // ======================================================
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('opacity-0', 'translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2500);
        }

        function setText(id, value, fallback = 'Tidak dinyatakan') {
            const el = document.getElementById(id);
            if (!el) return;

            const valid = value !== null && value !== undefined && String(value).trim() !== '';
            el.textContent = valid ? value : fallback;
        }

        function getCards() {
            return Array.from(document.querySelectorAll('#houseListing .house-card'));
        }

        function updateCount() {
            const count = getCards().length;
            document.getElementById('favCount').textContent = count;
            return count;
        }

        function renderEmptyState() {
            const listing = document.getElementById('houseListing');
            document.getElementById('toolbar').classList.add('hidden');
            document.getElementById('noResult').classList.add('hidden');

            listing.innerHTML = `
                <div id="emptyFavourite"
                    class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white px-4 py-16 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-rose-50 text-4xl text-rose-400">♡</div>
                    <h3 class="mt-5 text-xl font-bold text-gray-800">Belum ada rumah favourite</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                        Tekan ikon hati pada mana-mana rumah untuk menyimpannya di sini dan bandingkan kemudian.
                    </p>
                    <a href="${dashboardUrl}"
                        class="mt-6 inline-flex rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Cari Rumah
                    </a>
                </div>`;
        }

        // ======================================================
        // CARI & SUSUN
        // ======================================================
        function applyFilters() {
            const search = document.getElementById('favSearch').value.trim().toLowerCase();
            const sort = document.getElementById('favSort').value;
            const listing = document.getElementById('houseListing');
            const cards = getCards();

            // Susun
            cards.sort((a, b) => {
                if (sort === 'cheapest') return Number(a.dataset.price) - Number(b.dataset.price);
                if (sort === 'expensive') return Number(b.dataset.price) - Number(a.dataset.price);
                if (sort === 'name') return a.dataset.title.localeCompare(b.dataset.title);
                return Number(a.dataset.index) - Number(b.dataset.index);
            });

            cards.forEach(card => listing.appendChild(card));

            // Tapis
            let visible = 0;
            cards.forEach(card => {
                const match = !search ||
                    card.dataset.title.includes(search) ||
                    card.dataset.area.includes(search);

                card.classList.toggle('hidden', !match);
                if (match) visible++;
            });

            document.getElementById('noResult').classList.toggle('hidden', visible > 0 || cards.length === 0);
        }

        document.getElementById('favSearch')?.addEventListener('input', applyFilters);
        document.getElementById('favSort')?.addEventListener('change', applyFilters);

        // ======================================================
        // PAPAR DETAIL
        // ======================================================
        function showHouseDetail(houseId) {
            const house = houses.find(h => Number(h.id) === Number(houseId));

            if (!house) {
                console.error('House not found:', houseId);
                return;
            }

            const houseDetail = document.getElementById('houseDetail');
            houseDetail.dataset.houseId = house.id;

            setText('detailHouseTitle', house.title, 'Rumah Sewa');
            setText('detailHouseArea', house.area ? '📍 ' + house.area : '📍 Lokasi tidak dinyatakan');

            const rent = Number(house.monthly_rent ?? 0);
            setText('detailHousePrice', 'RM' + new Intl.NumberFormat('en-MY', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(Number.isFinite(rent) ? rent : 0));

            setText('detailBedrooms', (house.bedrooms ?? 0) + ' bilik');
            setText('detailBathrooms', (house.bathrooms ?? 0) + ' bilik');
            setText('detailPropertyType', house.property_type);
            setText('detailFurnished', house.furnished);
            setText('detailAddress', house.address, 'Alamat tidak dinyatakan');
            setText('detailDescription', house.description, 'Tiada penerangan disediakan oleh pemilik.');

            // Pemilik
            const phone = house.phone || house.landlord?.phone || '';
            setText('detailLandlordName', house.landlord?.name, 'Nama tidak tersedia');
            setText('detailLandlordPhone', phone, 'Nombor telefon tidak tersedia');
            setText('detailLandlordEmail', house.landlord?.email, 'E-mel tidak tersedia');

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

            setFavouriteButton(true);

            document.getElementById('listingSection').classList.add('hidden');
            houseDetail.classList.remove('hidden');

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function setFavouriteButton(isFavourite) {
            const icon = document.getElementById('favouriteIcon');
            const button = document.getElementById('favouriteButton');

            if (isFavourite) {
                icon.textContent = '♥';
                button.classList.remove('text-gray-700', 'bg-white');
                button.classList.add('text-red-500', 'bg-red-50');
            } else {
                icon.textContent = '♡';
                button.classList.remove('text-red-500', 'bg-red-50');
                button.classList.add('text-gray-700', 'bg-white');
            }
        }

        // ======================================================
        // GALERI
        // ======================================================
        function updateHouseImage() {
            const image = document.getElementById('detailHouseImage');
            const noImage = document.getElementById('detailNoImage');
            const prevButton = document.getElementById('prevImageButton');
            const nextButton = document.getElementById('nextImageButton');
            const counter = document.getElementById('imageCounter');
            const badge = document.getElementById('galleryBadge');
            const thumbs = document.getElementById('detailThumbnails');

            const total = currentHouseImages.length;

            const hideNav = () => {
                [prevButton, nextButton].forEach(btn => {
                    btn.classList.add('hidden');
                    btn.classList.remove('flex');
                });
                counter.classList.add('hidden');
            };

            const hideThumbs = () => {
                thumbs.classList.add('hidden');
                thumbs.classList.remove('flex');
                thumbs.innerHTML = '';
            };

            if (total === 0) {
                image.removeAttribute('src');
                image.classList.add('hidden');
                noImage.classList.remove('hidden');
                noImage.classList.add('flex');
                badge.classList.add('hidden');
                hideNav();
                hideThumbs();
                return;
            }

            image.onerror = () => {
                console.warn('Gambar gagal dimuatkan:', image.src);
                image.classList.add('hidden');
                noImage.classList.remove('hidden');
                noImage.classList.add('flex');
            };

            image.onload = () => {
                image.classList.remove('hidden');
                noImage.classList.add('hidden');
                noImage.classList.remove('flex');
            };

            image.src = currentHouseImages[currentImageIndex];

            badge.textContent = total + ' gambar';
            badge.classList.remove('hidden');

            if (total === 1) {
                hideNav();
                hideThumbs();
                return;
            }

            [prevButton, nextButton].forEach(btn => {
                btn.classList.remove('hidden');
                btn.classList.add('flex');
            });

            counter.textContent = (currentImageIndex + 1) + ' / ' + total;
            counter.classList.remove('hidden');

            thumbs.innerHTML = '';
            currentHouseImages.forEach((url, index) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.setAttribute('aria-label', 'Gambar ' + (index + 1));
                btn.className =
                    'h-16 w-20 shrink-0 overflow-hidden rounded-xl border-2 transition ' +
                    (index === currentImageIndex ?
                        'border-indigo-600 opacity-100' :
                        'border-transparent opacity-60 hover:opacity-100');

                const thumb = document.createElement('img');
                thumb.src = url;
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

        // ======================================================
        // FAVOURITE
        // ======================================================
        async function sendToggle(houseId) {
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

            return data;
        }

        function removeCard(houseId, done) {
            const card = document.querySelector(`.house-card[data-house-id="${houseId}"]`);

            if (!card) {
                updateCount();
                if (done) done();
                return;
            }

            card.style.transition = 'opacity .25s ease, transform .25s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';

            setTimeout(() => {
                card.remove();

                if (updateCount() === 0) {
                    renderEmptyState();
                } else {
                    applyFilters();
                }

                if (done) done();
            }, 250);
        }

        async function removeFavouriteFromCard(event, houseId) {
            event.stopPropagation();

            const button = event.currentTarget;
            button.disabled = true;

            try {
                const data = await sendToggle(houseId);

                if (!data.favourite) {
                    removeCard(houseId);
                    showToast('Dibuang daripada Favourite');
                } else {
                    button.disabled = false;
                }
            } catch (error) {
                console.error('Unfavourite Error:', error);
                button.disabled = false;
                showToast('Tidak dapat membuang daripada Favourite');
            }
        }

        async function toggleFavourite() {
            const houseId = document.getElementById('houseDetail').dataset.houseId;
            if (!houseId) return;

            try {
                const data = await sendToggle(houseId);
                setFavouriteButton(data.favourite);

                if (!data.favourite) {
                    removeCard(houseId, backToHouseList);
                    showToast('Dibuang daripada Favourite');
                }
            } catch (error) {
                console.error('Favourite Error:', error);
                showToast('Tidak dapat mengemaskini Favourite');
            }
        }

        // ======================================================
        // KEMBALI
        // ======================================================
        function backToHouseList() {
            const houseDetail = document.getElementById('houseDetail');

            houseDetail.classList.add('hidden');
            houseDetail.dataset.houseId = '';

            document.getElementById('listingSection').classList.remove('hidden');

            currentHouseImages = [];
            currentImageIndex = 0;

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Papan kekunci: ← → tukar gambar, Esc kembali
        document.addEventListener('keydown', function(e) {
            const detail = document.getElementById('houseDetail');
            if (!detail || detail.classList.contains('hidden')) return;

            if (e.key === 'ArrowLeft') previousHouseImage();
            if (e.key === 'ArrowRight') nextHouseImage();
            if (e.key === 'Escape') backToHouseList();
        });
    </script>

</x-app-layout>
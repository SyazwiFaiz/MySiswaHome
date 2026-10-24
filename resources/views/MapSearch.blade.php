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

    $houseCollection = ($houses ?? null) instanceof \Illuminate\Pagination\AbstractPaginator
        ? $houses->getCollection()
        : collect($houses ?? []);

    // Jika controller peta tidak menghantar lajur `image`, ambil terus dari database
    $houseIds = $houseCollection
        ->map(fn ($h) => is_array($h) ? ($h['id'] ?? null) : ($h->id ?? null))
        ->filter()
        ->values()
        ->all();

    $imageMap = empty($houseIds)
        ? collect()
        : \App\Models\House::whereIn('id', $houseIds)->pluck('image', 'id');

    $housesJson = $houseCollection->map(function ($h) use ($toHouseArray, $resolveImages, $imageMap) {
        $data = $toHouseArray($h);

        if (empty($data['image_urls'])) {
            $data['image_urls'] = $resolveImages($imageMap[$data['id'] ?? 0] ?? null);
        }

        return $data;
    })->values();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Track Lokasi Rumah</h2>
            <p class="mt-1 text-sm text-gray-500">
                Cari rumah sewa, semak lokasi dan lihat maklumat lanjut.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">

            @if (request()->has('debug'))
                <div class="break-all rounded-lg bg-yellow-200 p-3 text-xs text-black">
                    DEBUG | rumah: {{ $housesJson->count() }} |
                    URL gambar pertama: {{ $housesJson->first()['image_urls'][0] ?? 'KOSONG' }} |
                    host semasa: {{ request()->getSchemeAndHttpHost() }} |
                    APP_URL: {{ config('app.url') }}
                </div>
            @endif


            {{-- Hero --}}
            <div
                class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 p-6 text-white shadow-lg sm:p-8">
                <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-white/5"></div>

                <div class="relative z-10 flex flex-col justify-between gap-5 md:flex-row md:items-center">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">
                            MySiswaHome Explorer
                        </p>
                        <h3 class="mt-3 text-2xl font-extrabold tracking-tight sm:text-3xl">
                            Cari rumah, cari keselesaan.
                        </h3>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100">
                            Gunakan peta untuk menyemak kedudukan rumah sewa. Tekan penanda untuk melihat
                            ringkasan dan maklumat lanjut.
                        </p>
                    </div>

                    <button id="locate-me" type="button"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 font-bold text-indigo-700 shadow transition hover:bg-indigo-50 focus:outline-none focus:ring-4 focus:ring-white/40 disabled:cursor-wait disabled:opacity-70">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8a4 4 0 100 8 4 4 0 000-8zM12 2v3m0 14v3M2 12h3m14 0h3" />
                        </svg>
                        <span id="locate-label">Gunakan Lokasi Saya</span>
                    </button>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xl">
                        🏠</div>
                    <div>
                        <p class="text-sm text-gray-500">Jumlah rumah tersedia</p>
                        <h3 id="total-houses" class="mt-0.5 text-3xl font-extrabold text-gray-800">0</h3>
                    </div>
                </div>

                <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-xl">
                        📍</div>
                    <div>
                        <p class="text-sm text-gray-500">Rumah pada peta</p>
                        <h3 id="mapped-houses" class="mt-0.5 text-3xl font-extrabold text-emerald-600">0</h3>
                    </div>
                </div>

                <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl">
                        🧭</div>
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500">Status lokasi anda</p>
                        <h3 id="gps-summary" class="mt-0.5 truncate text-lg font-bold text-gray-800">
                            Belum dikesan
                        </h3>
                        <p id="location-status" class="mt-0.5 text-xs text-gray-500">
                            Tekan butang lokasi untuk bermula.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Penapis --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-5">
                        <label for="search-house" class="mb-2 block text-sm font-semibold text-gray-700">
                            Cari rumah atau kawasan
                        </label>
                        <input id="search-house" type="search" placeholder="Contoh: Shah Alam, Bangi..."
                            class="w-full rounded-xl border-gray-200 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="md:col-span-3">
                        <label for="price-filter" class="mb-2 block text-sm font-semibold text-gray-700">
                            Bajet bulanan
                        </label>
                        <select id="price-filter"
                            class="w-full rounded-xl border-gray-200 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua harga</option>
                            <option value="300">Sehingga RM300</option>
                            <option value="500">Sehingga RM500</option>
                            <option value="800">Sehingga RM800</option>
                            <option value="1000">Sehingga RM1,000</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="sort-filter" class="mb-2 block text-sm font-semibold text-gray-700">
                            Susunan
                        </label>
                        <select id="sort-filter"
                            class="w-full rounded-xl border-gray-200 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="default">Terkini</option>
                            <option value="cheapest">Termurah</option>
                            <option value="expensive">Termahal</option>
                            <option value="nearest" disabled>Terdekat (aktifkan lokasi)</option>
                        </select>
                    </div>

                    <div class="flex items-end md:col-span-2">
                        <button id="reset-filter" type="button"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-100">
                            ↻ Reset
                        </button>
                    </div>
                </div>
            </div>

            {{-- Peta + Senarai --}}
            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-5">

                <div
                    class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm lg:sticky lg:top-6 lg:col-span-3">
                    <div class="border-b border-gray-100 p-5">
                        <h3 class="font-bold text-gray-800">Peta Rumah Sewa</h3>
                        <p class="mt-1 text-xs text-gray-500">
                            Tekan penanda untuk melihat lokasi dan pautan maklumat lanjut.
                        </p>
                    </div>

                    <div class="map-wrap">
                        <div id="map" class="h-[420px] w-full bg-indigo-50 sm:h-[520px] lg:h-[600px]"></div>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-gray-100 px-5 py-4 text-xs text-gray-500">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="inline-block h-3 w-3 rounded-full bg-indigo-600"></span> Rumah sewa
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="inline-block h-3 w-3 rounded-full bg-rose-500"></span> Dipilih
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="inline-block h-3 w-3 rounded-full bg-blue-500"></span> Lokasi anda
                        </span>
                        <span id="map-message" class="ml-auto font-medium text-gray-600">Memuatkan peta...</span>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between gap-3 border-b border-gray-100 p-5">
                        <div>
                            <h3 class="font-bold text-gray-800">Senarai Rumah</h3>
                            <p class="mt-1 text-sm text-gray-500">Pilih rumah untuk melihat kedudukannya.</p>
                        </div>
                        <span id="house-count"
                            class="shrink-0 rounded-lg bg-indigo-50 px-3 py-2 text-sm font-bold text-indigo-700">
                            0 rumah
                        </span>
                    </div>

                    <div id="house-list"
                        class="relative max-h-[560px] space-y-3 overflow-y-auto p-4 lg:max-h-[690px]">
                        <p class="py-6 text-center text-sm text-gray-500">Memuatkan senarai rumah...</p>
                    </div>
                </div>
            </div>

            {{-- Tip --}}
            <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                <div class="flex gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-xl">
                        💡</div>
                    <div>
                        <h4 class="font-bold text-indigo-800">Tip mencari rumah</h4>
                        <p class="mt-1 text-sm leading-6 text-indigo-600">
                            Rumah perlu mempunyai latitude dan longitude untuk muncul pada peta.
                            Butang maklumat lanjut akan membuka halaman Dashboard untuk rumah yang dipilih.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

        <style>
            /* Elak peta menutup dropdown navigasi (Leaflet guna z-index tinggi) */
            .map-wrap {
                position: relative;
                isolation: isolate;
            }

            #map {
                display: block;
                z-index: 0;
            }

            .leaflet-container {
                font-family: inherit;
            }

            .leaflet-popup-content-wrapper {
                border-radius: 16px;
                padding: 0;
                overflow: hidden;
            }

            .leaflet-popup-content {
                margin: 14px;
            }

            /* Penanda rumah */
            .house-pin {
                background: transparent;
                border: 0;
            }

            .house-pin .pin {
                position: relative;
                display: block;
                width: 30px;
                height: 30px;
                margin: 2px 2px 0;
                border: 3px solid #fff;
                border-radius: 50% 50% 50% 0;
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                box-shadow: 0 4px 10px rgba(79, 70, 229, .45);
                transform: rotate(-45deg);
                transition: transform .15s ease;
            }

            .house-pin .pin::after {
                content: "";
                position: absolute;
                inset: 0;
                margin: auto;
                width: 9px;
                height: 9px;
                border-radius: 50%;
                background: #fff;
            }

            .house-pin.active .pin {
                background: linear-gradient(135deg, #e11d48, #f43f5e);
                box-shadow: 0 4px 12px rgba(225, 29, 72, .5);
                transform: rotate(-45deg) scale(1.15);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // ======================================================
                // DATA
                // ======================================================
                const rawHouses = @json($housesJson);
                const storageBaseUrl = @json(asset('storage'));
                const detailPageUrl = @json(route('student.dashboard'));

                // Sokong array biasa, paginator ({data: [...]}) atau object berindeks
                const houseArray = Array.isArray(rawHouses) ?
                    rawHouses :
                    (Array.isArray(rawHouses?.data) ? rawHouses.data : Object.values(rawHouses || {}));

                // ======================================================
                // ELEMEN
                // ======================================================
                const mapElement = document.getElementById('map');
                const list = document.getElementById('house-list');
                const searchInput = document.getElementById('search-house');
                const priceFilter = document.getElementById('price-filter');
                const sortFilter = document.getElementById('sort-filter');
                const totalElement = document.getElementById('total-houses');
                const mappedElement = document.getElementById('mapped-houses');
                const countElement = document.getElementById('house-count');
                const mapMessage = document.getElementById('map-message');
                const gpsSummary = document.getElementById('gps-summary');
                const locationStatus = document.getElementById('location-status');
                const locateButton = document.getElementById('locate-me');
                const locateLabel = document.getElementById('locate-label');

                if (!mapElement || !list) {
                    console.error('Elemen peta atau senarai rumah tidak dijumpai.');
                    return;
                }

                if (typeof L === 'undefined') {
                    mapMessage.textContent = 'Leaflet gagal dimuatkan.';
                    list.textContent = 'Peta tidak dapat dimuatkan. Semak sambungan internet.';
                    return;
                }

                // ======================================================
                // HELPER
                // ======================================================
                const moneyFormat = new Intl.NumberFormat('en-MY', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                function escapeHtml(value) {
                    return String(value ?? '').replace(/[&<>"']/g, char => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    } [char]));
                }

                function parseCoordinates(house) {
                    const isBlank = v => v === null || v === undefined || String(v).trim() === '';

                    if (isBlank(house.latitude) || isBlank(house.longitude)) return null;

                    const lat = Number(house.latitude);
                    const lng = Number(house.longitude);

                    const valid = Number.isFinite(lat) && Number.isFinite(lng) &&
                        lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180 &&
                        !(lat === 0 && lng === 0);

                    return valid ? {
                        lat,
                        lng
                    } : null;
                }

                // Ambil semua laluan gambar daripada nilai apa pun:
                // array, JSON string, JSON dalam JSON, object, atau laluan tunggal
                function extractImages(value, depth = 0) {
                    if (depth > 4 || value === null || value === undefined) return [];

                    if (Array.isArray(value)) {
                        return value.flatMap(item => extractImages(item, depth + 1));
                    }

                    if (typeof value === 'object') {
                        return extractImages(Object.values(value), depth + 1);
                    }

                    if (typeof value !== 'string') return [];

                    const text = value.trim();
                    if (text === '') return [];

                    if (/^[\[{"]/.test(text)) {
                        try {
                            return extractImages(JSON.parse(text), depth + 1);
                        } catch (e) {
                            // bukan JSON sah, anggap laluan biasa
                        }
                    }

                    return [text];
                }

                function toImageUrl(image) {
                    if (/^https?:\/\//i.test(image)) return image;

                    const path = image.trim()
                        .replace(/\\/g, '/')
                        .replace(/^\/+/, '')
                        .replace(/^(app\/)?public\//i, '')
                        .replace(/^storage\//i, '');

                    return storageBaseUrl + '/' + path.split('/').map(encodeURIComponent).join('/');
                }

                function parseImageUrl(house) {
                    // URL sudah diselesaikan oleh server
                    if (Array.isArray(house.image_urls) && house.image_urls.length) {
                        return house.image_urls[0];
                    }

                    const images = extractImages(house.image);
                    return images.length ? toImageUrl(images[0]) : null;
                }

                // Bila gambar gagal dimuatkan, tunjuk ikon rumah (bukan kosong)
                window.__imgFallback = function(img) {
                    const placeholder = document.createElement('div');

                    if (img.dataset.fallback === 'popup') {
                        placeholder.style.cssText =
                            'height:100px;display:flex;align-items:center;justify-content:center;' +
                            'background:#eef2ff;border-radius:12px;margin-bottom:10px;font-size:32px;';
                    } else {
                        placeholder.className = 'flex h-full w-full items-center justify-center text-2xl';
                    }

                    console.warn('Gambar gagal dimuatkan:', img.src);
                    placeholder.textContent = '🏠';
                    img.replaceWith(placeholder);
                };

                function distanceKm(lat1, lon1, lat2, lon2) {
                    const rad = v => v * Math.PI / 180;
                    const dLat = rad(lat2 - lat1);
                    const dLon = rad(lon2 - lon1);
                    const a = Math.sin(dLat / 2) ** 2 +
                        Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(dLon / 2) ** 2;

                    return 6371 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                }

                function formatDistance(km) {
                    return km < 1 ? Math.round(km * 1000) + ' m' : km.toFixed(1) + ' km';
                }

                // Parameter `house_id` -> controller dashboard mesti baca `house_id`
                function getDetailUrl(house) {
                    const url = new URL(detailPageUrl, window.location.origin);
                    url.searchParams.set('house_id', house.id);
                    return url.toString();
                }

                // Sediakan data sekali sahaja; hanya rumah berstatus tersedia dipaparkan
                const houses = houseArray
                    .filter(h => !h.status || String(h.status).toLowerCase() === 'available')
                    .map(h => ({
                        ...h,
                        _coords: parseCoordinates(h),
                        _image: parseImageUrl(h),
                        _rent: Number(h.monthly_rent || 0),
                        _search: [h.title, h.address, h.area, h.property_type].join(' ').toLowerCase()
                    }));

                // ======================================================
                // PETA
                // ======================================================
                const map = L.map(mapElement, {
                    scrollWheelZoom: true
                }).setView([3.0738, 101.5183], 10);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const markersLayer = L.layerGroup().addTo(map);
                const markers = new Map(); // id rumah -> marker

                const pinIcon = L.divIcon({
                    className: 'house-pin',
                    html: '<span class="pin"></span>',
                    iconSize: [36, 44],
                    iconAnchor: [18, 42],
                    popupAnchor: [0, -38]
                });

                const pinIconActive = L.divIcon({
                    className: 'house-pin active',
                    html: '<span class="pin"></span>',
                    iconSize: [36, 44],
                    iconAnchor: [18, 42],
                    popupAnchor: [0, -38]
                });

                let userMarker = null;
                let userAccuracy = null;
                let userPosition = null;
                let activeId = null;

                // ======================================================
                // PENAPIS & SUSUNAN
                // ======================================================
                function getFilteredHouses() {
                    const search = searchInput.value.trim().toLowerCase();
                    const maxPrice = Number(priceFilter.value) || 0;
                    const sort = sortFilter.value;

                    let results = houses.filter(h =>
                        (!search || h._search.includes(search)) &&
                        (!maxPrice || h._rent <= maxPrice)
                    );

                    // Kira jarak jika lokasi pengguna ada
                    results = results.map(h => ({
                        ...h,
                        _distance: (userPosition && h._coords) ?
                            distanceKm(userPosition.lat, userPosition.lng, h._coords.lat, h._coords.lng) :
                            null
                    }));

                    if (sort === 'cheapest') {
                        results.sort((a, b) => a._rent - b._rent);
                    } else if (sort === 'expensive') {
                        results.sort((a, b) => b._rent - a._rent);
                    } else if (sort === 'nearest' && userPosition) {
                        results.sort((a, b) => {
                            if (a._distance === null) return 1;
                            if (b._distance === null) return -1;
                            return a._distance - b._distance;
                        });
                    }

                    return results;
                }

                // ======================================================
                // AKTIF / PILIH RUMAH
                // ======================================================
                function setActive(id, scrollList = false) {
                    // Reset penanda lama
                    if (activeId !== null && markers.has(activeId)) {
                        markers.get(activeId).setIcon(pinIcon);
                    }

                    list.querySelectorAll('[data-house-card]').forEach(card => {
                        card.classList.remove('ring-2', 'ring-indigo-500', 'border-indigo-300');
                    });

                    activeId = id;

                    if (id === null) return;

                    if (markers.has(id)) {
                        markers.get(id).setIcon(pinIconActive);
                    }

                    const card = list.querySelector(`[data-house-card="${id}"]`);

                    if (card) {
                        card.classList.add('ring-2', 'ring-indigo-500', 'border-indigo-300');

                        if (scrollList) {
                            list.scrollTo({
                                top: Math.max(card.offsetTop - 16, 0),
                                behavior: 'smooth'
                            });
                        }
                    }
                }

                function focusHouse(id) {
                    const marker = markers.get(id);
                    if (!marker) return;

                    map.setView(marker.getLatLng(), 16, {
                        animate: true
                    });
                    marker.openPopup();
                    setActive(id);

                    // Pada skrin kecil, bawa peta ke dalam pandangan
                    if (window.innerWidth < 1024) {
                        mapElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }

                // ======================================================
                // RENDER
                // ======================================================
                function popupHtml(house) {
                    const image = house._image ?
                        `<img src="${escapeHtml(house._image)}" alt="Gambar rumah"
                            data-fallback="popup"
                            style="width:100%;height:150px;object-fit:cover;border-radius:12px;margin-bottom:10px;"
                            onerror="window.__imgFallback(this)">` :
                        `<div style="height:100px;display:flex;align-items:center;justify-content:center;background:#eef2ff;border-radius:12px;margin-bottom:10px;font-size:32px;">🏠</div>`;

                    return `
                        <div style="width:240px;">
                            ${image}
                            <h3 style="font-weight:700;font-size:16px;margin:0 0 6px;color:#1f2937;">
                                ${escapeHtml(house.title || 'Rumah sewa')}
                            </h3>
                            <p style="font-size:13px;margin:0 0 8px;color:#6b7280;">
                                📍 ${escapeHtml(house.address || house.area || 'Alamat belum disediakan')}
                            </p>
                            <p style="font-size:17px;font-weight:800;margin:0;color:#4338ca;">
                                RM${moneyFormat.format(house._rent)}
                                <span style="font-size:12px;font-weight:400;color:#9ca3af;">/ bulan</span>
                            </p>
                            <a href="${escapeHtml(getDetailUrl(house))}"
                                style="display:block;text-align:center;background:#4f46e5;color:#fff;padding:10px;border-radius:10px;text-decoration:none;font-weight:700;margin-top:12px;">
                                Lihat Maklumat Lanjut →
                            </a>
                        </div>`;
                }

                function cardHtml(house) {
                    const located = Boolean(house._coords);

                    const thumb = house._image ?
                        `<img src="${escapeHtml(house._image)}" alt="" loading="lazy"
                            class="h-full w-full object-cover" onerror="window.__imgFallback(this)">` :
                        `<div class="flex h-full w-full items-center justify-center text-2xl">🏠</div>`;

                    const meta = [
                        house.property_type ? escapeHtml(house.property_type) : null,
                        (house.bedrooms !== null && house.bedrooms !== undefined) ?
                        escapeHtml(house.bedrooms) + ' bilik' : null
                    ].filter(Boolean).join(' · ');

                    const distanceBadge = house._distance !== null ?
                        `<span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                            ${formatDistance(house._distance)}
                        </span>` :
                        '';

                    const noMapBadge = located ? '' :
                        `<span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                            Tiada lokasi peta
                        </span>`;

                    return `
                        <div class="flex gap-3">
                            <div class="h-20 w-24 shrink-0 overflow-hidden rounded-xl bg-gray-100">${thumb}</div>
                            <div class="min-w-0 flex-1">
                                <h4 class="truncate font-bold text-gray-800">${escapeHtml(house.title || 'Rumah sewa')}</h4>
                                <p class="mt-1 truncate text-xs text-gray-500">
                                    📍 ${escapeHtml(house.area || house.address || 'Alamat belum disediakan')}
                                </p>
                                ${meta ? `<p class="mt-0.5 truncate text-xs capitalize text-gray-400">${meta}</p>` : ''}
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                            <p class="text-lg font-extrabold text-indigo-700">
                                RM${moneyFormat.format(house._rent)}
                                <span class="text-xs font-normal text-gray-500">/ bulan</span>
                            </p>
                            <div class="flex gap-1.5">${distanceBadge}${noMapBadge}</div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <button type="button" data-action="locate"
                                class="rounded-xl px-3 py-2.5 text-sm font-semibold transition ${
                                    located
                                        ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                        : 'cursor-not-allowed bg-gray-100 text-gray-400'
                                }"
                                ${located ? '' : 'disabled'}>
                                ${located ? 'Lihat di Peta' : 'Tiada lokasi'}
                            </button>
                            <a href="${escapeHtml(getDetailUrl(house))}"
                                class="block rounded-xl bg-indigo-600 px-3 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-indigo-700">
                                Maklumat →
                            </a>
                        </div>`;
                }

                function renderHouses() {
                    markersLayer.clearLayers();
                    markers.clear();
                    list.innerHTML = '';

                    const results = getFilteredHouses();
                    const located = results.filter(h => h._coords);
                    const bounds = [];

                    // Penanda
                    located.forEach(house => {
                        const marker = L.marker([house._coords.lat, house._coords.lng], {
                            icon: house.id === activeId ? pinIconActive : pinIcon,
                            title: house.title || 'Rumah sewa'
                        }).addTo(markersLayer);

                        marker.bindPopup(popupHtml(house), {
                            maxWidth: 280,
                            minWidth: 240
                        });

                        marker.on('click', () => setActive(house.id, true));
                        marker.on('popupclose', () => {
                            if (activeId === house.id) setActive(null);
                        });

                        markers.set(house.id, marker);
                        bounds.push([house._coords.lat, house._coords.lng]);
                    });

                    // Kad senarai
                    if (results.length === 0) {
                        list.innerHTML = `
                            <div class="py-10 text-center">
                                <div class="text-4xl">🔎</div>
                                <p class="mt-3 font-semibold text-gray-700">Tiada rumah ditemui</p>
                                <p class="mt-1 text-sm text-gray-500">Cuba ubah carian atau bajet anda.</p>
                            </div>`;
                    } else {
                        const fragment = document.createDocumentFragment();

                        results.forEach(house => {
                            const card = document.createElement('div');
                            card.dataset.houseCard = house.id;
                            card.className =
                                'rounded-2xl border border-gray-100 bg-white p-4 transition hover:border-indigo-200 hover:shadow-md';
                            card.innerHTML = cardHtml(house);
                            fragment.appendChild(card);
                        });

                        list.appendChild(fragment);
                    }

                    // Pulihkan penyerlahan jika rumah aktif masih ada
                    if (activeId !== null) {
                        if (markers.has(activeId)) {
                            setActive(activeId);
                        } else {
                            activeId = null;
                        }
                    }

                    // Statistik
                    totalElement.textContent = houses.length;
                    mappedElement.textContent = houses.filter(h => h._coords).length;
                    countElement.textContent = results.length + ' rumah';

                    mapMessage.textContent = located.length ?
                        located.length + ' lokasi dipaparkan' :
                        'Tiada koordinat rumah';

                    // Zum ke hasil, kecuali ketika pengguna sedang melihat lokasi sendiri
                    if (bounds.length > 0 && !userPosition) {
                        map.fitBounds(bounds, {
                            padding: [35, 35],
                            maxZoom: 14
                        });
                    }
                }

                // ======================================================
                // EVENT
                // ======================================================
                // Satu pendengar untuk semua butang "Lihat di Peta"
                list.addEventListener('click', function(e) {
                    const button = e.target.closest('[data-action="locate"]');
                    if (!button || button.disabled) return;

                    const card = button.closest('[data-house-card]');
                    if (card) focusHouse(Number(card.dataset.houseCard));
                });

                let searchTimer;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(renderHouses, 200);
                });

                priceFilter.addEventListener('change', renderHouses);
                sortFilter.addEventListener('change', renderHouses);

                document.getElementById('reset-filter').addEventListener('click', function() {
                    searchInput.value = '';
                    priceFilter.value = '';
                    sortFilter.value = userPosition ? 'nearest' : 'default';
                    renderHouses();
                });

                // ======================================================
                // LOKASI PENGGUNA
                // ======================================================
                function setLocateLoading(isLoading) {
                    locateButton.disabled = isLoading;
                    locateLabel.textContent = isLoading ? 'Mengesan...' : 'Kesan Semula Lokasi';
                }

                locateButton.addEventListener('click', function() {
                    if (!navigator.geolocation) {
                        gpsSummary.textContent = 'Tidak disokong';
                        locationStatus.textContent = 'Pelayar anda tidak menyokong GPS.';
                        return;
                    }

                    if (!window.isSecureContext) {
                        gpsSummary.textContent = 'Tidak tersedia';
                        locationStatus.textContent =
                            'GPS hanya berfungsi pada HTTPS atau localhost.';
                        return;
                    }

                    gpsSummary.textContent = 'Mengesan...';
                    locationStatus.textContent = 'Sila benarkan akses lokasi pada pelayar.';
                    setLocateLoading(true);

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            userPosition = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };

                            if (userMarker) map.removeLayer(userMarker);
                            if (userAccuracy) map.removeLayer(userAccuracy);

                            userAccuracy = L.circle([userPosition.lat, userPosition.lng], {
                                radius: position.coords.accuracy || 50,
                                color: '#3b82f6',
                                weight: 1,
                                fillColor: '#3b82f6',
                                fillOpacity: 0.1,
                                interactive: false
                            }).addTo(map);

                            userMarker = L.circleMarker([userPosition.lat, userPosition.lng], {
                                radius: 9,
                                color: '#ffffff',
                                weight: 3,
                                fillColor: '#2563eb',
                                fillOpacity: 1
                            }).addTo(map);

                            userMarker.bindPopup('Lokasi semasa anda');

                            map.setView([userPosition.lat, userPosition.lng], 13);

                            // Aktifkan susunan "Terdekat"
                            const nearestOption = sortFilter.querySelector('option[value="nearest"]');
                            nearestOption.disabled = false;
                            nearestOption.textContent = 'Terdekat';
                            sortFilter.value = 'nearest';

                            gpsSummary.textContent = 'Lokasi dikesan';
                            locationStatus.textContent = 'Senarai rumah disusun mengikut jarak.';
                            setLocateLoading(false);

                            renderHouses();
                        },
                        function(error) {
                            gpsSummary.textContent = 'Tidak tersedia';

                            if (error.code === 1) {
                                locationStatus.textContent =
                                    'Akses lokasi ditolak. Benarkan lokasi pada pelayar.';
                            } else if (error.code === 2) {
                                locationStatus.textContent =
                                    'Lokasi tidak dapat dikenal pasti. Cuba lagi.';
                            } else {
                                locationStatus.textContent =
                                    'Permintaan lokasi tamat masa. Cuba lagi.';
                            }

                            locateLabel.textContent = 'Cuba Lagi';
                            locateButton.disabled = false;
                        }, {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 60000
                        }
                    );
                });

                // ======================================================
                // MULA
                // ======================================================
                renderHouses();

                // Pastikan peta saiz betul bila susun atur berubah
                if ('ResizeObserver' in window) {
                    new ResizeObserver(() => map.invalidateSize()).observe(mapElement);
                } else {
                    setTimeout(() => map.invalidateSize(), 500);
                }
            });
        </script>
    @endpush
</x-app-layout>
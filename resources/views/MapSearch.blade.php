<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-800">
                Track Lokasi Rumah
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Cari rumah sewa mengikut lokasi dan bajet anda.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <div class="overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 p-6 text-white shadow-lg sm:p-8">
                <div class="flex flex-col justify-between gap-5 md:flex-row md:items-center">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-200">
                            MySiswaHome Explorer
                        </p>

                        <h3 class="mt-3 text-3xl font-extrabold">
                            Cari rumah, cari keselesaan.
                        </h3>

                        <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100">
                            Terokai lokasi rumah sewa, semak harga bulanan
                            dan cari penginapan yang sesuai dengan keperluan anda.
                        </p>
                    </div>

                    <button id="locate-me" type="button"
                        class="rounded-xl bg-white px-5 py-3 font-bold text-indigo-700 shadow transition hover:bg-indigo-50">
                        ◎ Gunakan Lokasi Saya
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Jumlah rumah tersedia</p>
                    <h3 id="total-houses" class="mt-2 text-3xl font-extrabold text-gray-800">0</h3>
                    <p class="mt-1 text-xs text-gray-400">Berdasarkan data sistem</p>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Lokasi pada peta</p>
                    <h3 id="mapped-houses" class="mt-2 text-3xl font-extrabold text-emerald-600">0</h3>
                    <p class="mt-1 text-xs text-gray-400">Rumah dengan koordinat lengkap</p>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Status lokasi anda</p>
                    <h3 id="gps-summary" class="mt-2 text-xl font-bold text-gray-800">
                        Belum dikesan
                    </h3>
                    <p id="location-status" class="mt-1 text-xs text-gray-500">
                        Tekan butang lokasi untuk bermula.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                    <div class="md:col-span-6">
                        <label for="search-house" class="mb-2 block text-sm font-semibold text-gray-700">
                            Cari rumah atau kawasan
                        </label>

                        <input id="search-house" type="search"
                            placeholder="Contoh: Shah Alam, Bangi..."
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

                    <div class="flex items-end md:col-span-3">
                        <button id="reset-filter" type="button"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                            ↻ Reset carian
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-5">

                <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm lg:col-span-3">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 p-5">
                        <div>
                            <h3 class="font-bold text-gray-800">Peta Rumah Sewa</h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Tekan penanda untuk melihat maklumat rumah.
                            </p>
                        </div>

                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                            Peta interaktif
                        </span>
                    </div>

                    <div id="map" class="w-full" style="height: 480px; background: #eef2ff;"></div>

                    <div class="flex flex-wrap items-center gap-4 border-t border-gray-100 px-5 py-4 text-xs text-gray-500">
                        <span>🟣 Rumah sewa</span>
                        <span>🔵 Lokasi anda</span>
                        <span id="map-message" class="ml-auto">Memuatkan peta...</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between border-b border-gray-100 p-5">
                        <div>
                            <h3 class="font-bold text-gray-800">Senarai Rumah</h3>
                            <p class="mt-1 text-sm text-gray-500">Rumah yang sepadan dengan carian anda.</p>
                        </div>

                        <span id="house-count"
                            class="rounded-lg bg-indigo-50 px-3 py-2 text-sm font-bold text-indigo-700">
                            0 rumah
                        </span>
                    </div>

                    <div id="house-list" class="max-h-[620px] space-y-3 overflow-y-auto p-4">
                        <p class="py-6 text-center text-sm text-gray-500">
                            Memuatkan senarai rumah...
                        </p>
                    </div>
                </div>

            </div>

            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-4">
                <h4 class="font-bold text-amber-900">💡 Tip mencari rumah</h4>
                <p class="mt-1 text-sm leading-6 text-amber-800">
                    Benarkan akses lokasi pada pelayar untuk mengira jarak garis lurus
                    dari kedudukan anda. Rumah perlu mempunyai latitude dan longitude
                    untuk muncul sebagai penanda pada peta.
                </p>
            </div>

        </div>
    </div>

    @push('styles')
        <link rel="stylesheet"
            href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

        <style>
            #map {
                z-index: 1;
                display: block;
                min-height: 400px;
            }

            .leaflet-container {
                font-family: inherit;
            }

            .leaflet-popup-content-wrapper {
                border-radius: 14px;
            }

            .leaflet-popup-content {
                margin: 14px 16px;
            }

            .house-marker {
                background: #4f46e5;
                border: 2px solid white;
                border-radius: 12px;
                color: white;
                font-weight: 800;
                padding: 6px 9px;
                white-space: nowrap;
                box-shadow: 0 3px 12px #0002;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const houses = @json($houses ?? []);

                const mapElement = document.getElementById('map');
                const list = document.getElementById('house-list');
                const searchInput = document.getElementById('search-house');
                const priceFilter = document.getElementById('price-filter');

                const totalElement = document.getElementById('total-houses');
                const mappedElement = document.getElementById('mapped-houses');
                const countElement = document.getElementById('house-count');
                const mapMessage = document.getElementById('map-message');
                const gpsSummary = document.getElementById('gps-summary');
                const locationStatus = document.getElementById('location-status');

                if (typeof L === 'undefined') {
                    mapMessage.textContent = 'Leaflet gagal dimuatkan.';
                    list.innerHTML = `
                        <div class="rounded-xl bg-red-50 p-4 text-sm text-red-700">
                            Peta gagal dimuatkan. Semak sambungan internet atau CDN Leaflet.
                            Cuba refresh halaman selepas beberapa saat.
                        </div>
                    `;
                    console.error('Leaflet JavaScript tidak tersedia.');
                    return;
                }

                const map = L.map(mapElement).setView([3.0738, 101.5183], 10);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const markersLayer = L.layerGroup().addTo(map);

                let userMarker = null;
                let userPosition = null;

                function escapeHtml(value) {
                    return String(value ?? '').replace(/[&<>"']/g, function (char) {
                        return {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#39;'
                        }[char];
                    });
                }

                function hasCoordinates(house) {
                    if (
                        house.latitude === null ||
                        house.latitude === undefined ||
                        house.latitude === '' ||
                        house.longitude === null ||
                        house.longitude === undefined ||
                        house.longitude === ''
                    ) {
                        return false;
                    }

                    const lat = Number(house.latitude);
                    const lng = Number(house.longitude);

                    return Number.isFinite(lat) &&
                        Number.isFinite(lng) &&
                        lat >= -90 && lat <= 90 &&
                        lng >= -180 && lng <= 180 &&
                        !(lat === 0 && lng === 0);
                }

                function distanceKm(lat1, lon1, lat2, lon2) {
                    const rad = value => value * Math.PI / 180;
                    const dLat = rad(lat2 - lat1);
                    const dLon = rad(lon2 - lon1);

                    const a =
                        Math.sin(dLat / 2) ** 2 +
                        Math.cos(rad(lat1)) *
                        Math.cos(rad(lat2)) *
                        Math.sin(dLon / 2) ** 2;

                    return 6371 * 2 * Math.atan2(
                        Math.sqrt(a),
                        Math.sqrt(1 - a)
                    );
                }

                function getFilteredHouses() {
                    const search = searchInput.value.trim().toLowerCase();
                    const maxPrice = priceFilter.value;

                    let results = houses.filter(function (house) {
                        const searchableText = [
                            house.title,
                            house.address,
                            house.area,
                            house.property_type
                        ].join(' ').toLowerCase();

                        const matchesSearch =
                            !search || searchableText.includes(search);

                        const matchesPrice =
                            !maxPrice ||
                            Number(house.monthly_rent) <= Number(maxPrice);

                        return matchesSearch && matchesPrice;
                    });

                    if (userPosition) {
                        results = results.map(function (house) {
                            if (!hasCoordinates(house)) {
                                return house;
                            }

                            return {
                                ...house,
                                distance: distanceKm(
                                    userPosition.lat,
                                    userPosition.lng,
                                    Number(house.latitude),
                                    Number(house.longitude)
                                )
                            };
                        });

                        results.sort(function (a, b) {
                            if (a.distance === undefined) return 1;
                            if (b.distance === undefined) return -1;
                            return a.distance - b.distance;
                        });
                    }

                    return results;
                }

                function renderHouses() {
                    markersLayer.clearLayers();
                    list.innerHTML = '';

                    const results = getFilteredHouses();
                    const locatedHouses = results.filter(hasCoordinates);
                    const bounds = [];

                    locatedHouses.forEach(function (house) {
                        const lat = Number(house.latitude);
                        const lng = Number(house.longitude);
                        const price = Number(house.monthly_rent || 0);

                        const marker = L.marker([lat, lng]).addTo(markersLayer);

                        marker.bindPopup(`
                            <div style="min-width:180px">
                                <strong>${escapeHtml(house.title || 'Rumah sewa')}</strong>
                                <p style="margin:6px 0;color:#64748b">
                                    ${escapeHtml(house.area || house.address || 'Lokasi rumah')}
                                </p>
                                <p style="color:#4f46e5;font-weight:800;font-size:16px">
                                    RM${price.toFixed(2)} / bulan
                                </p>
                                ${house.distance !== undefined
                                    ? `<p style="margin-top:5px">${house.distance.toFixed(2)} km dari anda</p>`
                                    : ''}
                            </div>
                        `);

                        house._marker = marker;
                        bounds.push([lat, lng]);
                    });

                    results.forEach(function (house) {
                        const located = hasCoordinates(house);
                        const price = Number(house.monthly_rent || 0);
                        const card = document.createElement('div');

                        card.className =
                            'rounded-2xl border border-gray-100 p-4 transition hover:border-indigo-200 hover:shadow-md';

                        card.innerHTML = `
                            <div class="flex items-start gap-3">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-2xl">
                                    🏠
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-gray-800">
                                        ${escapeHtml(house.title || 'Rumah sewa')}
                                    </h4>

                                    <p class="mt-2 text-xs leading-5 text-gray-500">
                                        📍 ${escapeHtml(house.area || house.address || 'Alamat belum disediakan')}
                                    </p>

                                    <p class="mt-3 text-lg font-extrabold text-indigo-700">
                                        RM${price.toFixed(2)}
                                        <span class="text-xs font-normal text-gray-500">/ bulan</span>
                                    </p>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        ${house.property_type
                                            ? `<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs text-gray-600">${escapeHtml(house.property_type)}</span>`
                                            : ''}

                                        ${house.distance !== undefined
                                            ? `<span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">${house.distance.toFixed(2)} km dari anda</span>`
                                            : ''}
                                    </div>

                                    <button type="button"
                                        class="view-house mt-4 w-full rounded-xl px-4 py-2.5 text-sm font-semibold transition ${
                                            located
                                                ? 'bg-indigo-600 text-white hover:bg-indigo-700'
                                                : 'cursor-not-allowed bg-gray-100 text-gray-500'
                                        }"
                                        ${located ? '' : 'disabled'}>
                                        ${located ? 'Lihat pada peta →' : 'Lokasi belum ditetapkan'}
                                    </button>
                                </div>
                            </div>
                        `;

                        if (located) {
                            card.querySelector('.view-house').addEventListener('click', function () {
                                map.setView([
                                    Number(house.latitude),
                                    Number(house.longitude)
                                ], 16);

                                house._marker.openPopup();

                                if (window.innerWidth < 1024) {
                                    mapElement.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'start'
                                    });
                                }
                            });
                        }

                        list.appendChild(card);
                    });

                    totalElement.textContent = houses.length;
                    mappedElement.textContent = locatedHouses.length;
                    countElement.textContent = results.length + ' rumah';

                    if (results.length === 0) {
                        list.innerHTML = `
                            <div class="py-10 text-center">
                                <div class="text-4xl">🔎</div>
                                <p class="mt-3 font-semibold text-gray-700">
                                    Tiada rumah ditemui
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Cuba ubah carian atau bajet anda.
                                </p>
                            </div>
                        `;
                    } else if (locatedHouses.length === 0) {
                        const notice = document.createElement('div');

                        notice.className =
                            'mb-3 rounded-xl bg-amber-50 p-3 text-xs leading-5 text-amber-800';

                        notice.textContent =
                            'Rumah tersedia, tetapi belum mempunyai koordinat. Tambah latitude dan longitude pada data rumah untuk memaparkan penanda pada peta.';

                        list.prepend(notice);
                    }

                    mapMessage.textContent = locatedHouses.length
                        ? locatedHouses.length + ' lokasi dipaparkan'
                        : 'Tiada koordinat rumah';

                    if (bounds.length > 0 && !userPosition) {
                        map.fitBounds(bounds, {
                            padding: [35, 35],
                            maxZoom: 14
                        });
                    }
                }

                searchInput.addEventListener('input', renderHouses);
                priceFilter.addEventListener('change', renderHouses);

                document.getElementById('reset-filter').addEventListener('click', function () {
                    searchInput.value = '';
                    priceFilter.value = '';
                    renderHouses();
                });

                document.getElementById('locate-me').addEventListener('click', function () {
                    if (!navigator.geolocation) {
                        gpsSummary.textContent = 'Tidak disokong';
                        locationStatus.textContent = 'Pelayar anda tidak menyokong GPS.';
                        return;
                    }

                    gpsSummary.textContent = 'Mengesan...';
                    locationStatus.textContent = 'Sila benarkan akses lokasi pada pelayar.';

                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            userPosition = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };

                            if (userMarker) {
                                map.removeLayer(userMarker);
                            }

                            userMarker = L.circleMarker([
                                userPosition.lat,
                                userPosition.lng
                            ], {
                                radius: 9,
                                color: '#ffffff',
                                weight: 3,
                                fillColor: '#2563eb',
                                fillOpacity: 1
                            }).addTo(map);

                            userMarker.bindPopup('Lokasi semasa anda');

                            map.setView([
                                userPosition.lat,
                                userPosition.lng
                            ], 13);

                            gpsSummary.textContent = 'Lokasi dikesan';
                            locationStatus.textContent = 'Senarai rumah disusun mengikut jarak.';

                            renderHouses();
                        },
                        function (error) {
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
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 60000
                        }
                    );
                });

                renderHouses();

                setTimeout(function () {
                    map.invalidateSize();
                }, 500);
            });
        </script>
    @endpush
</x-app-layout>

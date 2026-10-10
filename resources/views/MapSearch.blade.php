
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">
                Track Lokasi Rumah
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Cari rumah sewa, semak lokasi dan lihat maklumat lanjut.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <div class="rounded-3xl bg-gradient-to-r from-indigo-700 to-violet-600 p-6 text-white shadow-lg">
                <div class="flex flex-col justify-between gap-5 md:flex-row md:items-center">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">
                            MySiswaHome Explorer
                        </p>
                        <h3 class="mt-3 text-3xl font-extrabold">
                            Cari rumah, cari keselesaan.
                        </h3>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100">
                            Gunakan peta untuk menyemak kedudukan rumah sewa.
                            Tekan penanda untuk melihat ringkasan dan maklumat lanjut.
                        </p>
                    </div>

                    <button id="locate-me" type="button"
                        class="rounded-xl bg-white px-5 py-3 font-bold text-indigo-700 shadow hover:bg-indigo-50">
                        ◎ Gunakan Lokasi Saya
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Jumlah rumah tersedia</p>
                    <h3 id="total-houses" class="mt-2 text-3xl font-extrabold text-gray-800">0</h3>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Rumah pada peta</p>
                    <h3 id="mapped-houses" class="mt-2 text-3xl font-extrabold text-emerald-600">0</h3>
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
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            ↻ Reset carian
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-5">
                <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm lg:col-span-3">
                    <div class="border-b border-gray-100 p-5">
                        <h3 class="font-bold text-gray-800">Peta Rumah Sewa</h3>
                        <p class="mt-1 text-xs text-gray-500">
                            Tekan penanda untuk melihat lokasi dan pautan maklumat lanjut.
                        </p>
                    </div>

                    <div id="map" class="w-full" style="height:480px;background:#eef2ff"></div>

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
                            <p class="mt-1 text-sm text-gray-500">
                                Pilih rumah untuk melihat kedudukannya.
                            </p>
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
                    Rumah perlu mempunyai latitude dan longitude untuk muncul pada peta.
                    Butang maklumat lanjut akan membuka halaman Cari Rumah untuk rumah yang dipilih.
                </p>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet"
            href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

        <style>
            #map {
                display: block;
                min-height: 400px;
                z-index: 1;
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
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const houses = @json($houses ?? []);
                const storageBaseUrl = @json(asset('storage'));
                const detailPageUrl = @json(route('student.dashboard'));

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

                if (!mapElement || !list) {
                    console.error('Elemen peta atau senarai rumah tidak dijumpai.');
                    return;
                }

                if (typeof L === 'undefined') {
                    mapMessage.textContent = 'Leaflet gagal dimuatkan.';
                    list.textContent = 'Peta tidak dapat dimuatkan. Semak sambungan internet.';
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

                function getImageUrl(house) {
                    let images = house.image;

                    if (typeof images === 'string') {
                        try {
                            images = JSON.parse(images);
                        } catch (error) {
                            // Nilai bukan JSON; anggap sebagai laluan gambar tunggal.
                        }
                    }

                    if (!Array.isArray(images)) {
                        images = [images];
                    }

                    const image = images.find(item =>
                        typeof item === 'string' && item.trim() !== ''
                    );

                    if (!image) {
                        return null;
                    }

                    if (/^https?:\/\//i.test(image)) {
                        return image;
                    }

                    const filename = image.trim()
                        .replace(/\\/g, '/')
                        .replace(/^\/+/, '')
                        .replace(/^storage\//i, '')
                        .replace(/^public\//i, '');

                    return `${storageBaseUrl}/${filename
                        .split('/')
                        .map(segment => encodeURIComponent(segment))
                        .join('/')}`;
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

                function getDetailUrl(house) {
                    const url = new URL(detailPageUrl, window.location.origin);
                    url.searchParams.set('house_id', house.id);
                    return url.toString();
                }

                function getFilteredHouses() {
                    const search = searchInput.value.trim().toLowerCase();
                    const maxPrice = priceFilter.value;

                    let results = houses.filter(function (house) {
                        const text = [
                            house.title,
                            house.address,
                            house.area,
                            house.property_type
                        ].join(' ').toLowerCase();

                        const matchesSearch = !search || text.includes(search);
                        const matchesPrice = !maxPrice ||
                            Number(house.monthly_rent) <= Number(maxPrice);

                        return matchesSearch && matchesPrice;
                    });

                    if (userPosition) {
                        results = results.map(function (house) {
                            if (!hasCoordinates(house)) return house;

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
                        const imageUrl = getImageUrl(house);

                        const imageHtml = imageUrl
                            ? `<img
                                src="${escapeHtml(imageUrl)}"
                                alt="Gambar rumah"
                                style="width:100%;height:150px;object-fit:cover;border-radius:10px;margin-bottom:10px;"
                                onerror="this.style.display='none';"
                              >`
                            : `<div style="height:100px;display:flex;align-items:center;justify-content:center;background:#eef2ff;border-radius:10px;margin-bottom:10px;font-size:32px;">🏠</div>`;

                        const detailUrl = getDetailUrl(house);

                        marker.bindPopup(`
                            <div style="width:250px;">
                                ${imageHtml}
                                <h3 style="font-weight:bold;font-size:16px;margin-bottom:8px;">
                                    ${escapeHtml(house.title || 'Rumah sewa')}
                                </h3>
                                <p style="font-size:13px;margin-bottom:8px;">
                                    📍 ${escapeHtml(house.address || house.area || 'Alamat belum disediakan')}
                                </p>
                                <p style="font-size:16px;font-weight:bold;color:#4338ca;">
                                    RM${price.toFixed(2)} / bulan
                                </p>
                                <a href="${escapeHtml(detailUrl)}"
                                   style="display:block;text-align:center;background:#4f46e5;color:white;padding:10px;border-radius:8px;text-decoration:none;font-weight:bold;margin-top:12px;">
                                    Lihat Maklumat Lanjut →
                                </a>
                            </div>
                        `, {
                            maxWidth: 300,
                            minWidth: 250
                        });

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
                            <div class="mt-3 flex flex-col gap-2">
                                <button type="button"
                                    class="view-map rounded-xl px-4 py-2.5 text-sm font-semibold ${
                                        located
                                            ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                            : 'cursor-not-allowed bg-gray-100 text-gray-400'
                                    }"
                                    ${located ? '' : 'disabled'}>
                                    ${located ? 'Lihat Lokasi pada Peta' : 'Lokasi belum ditetapkan'}
                                </button>
                                <a href="${escapeHtml(getDetailUrl(house))}"
                                    class="block rounded-xl bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-indigo-700">
                                    Lihat Maklumat Lanjut →
                                </a>
                            </div>
                        `;

                        if (located) {
                            card.querySelector('.view-map').addEventListener('click', function () {
                                map.setView([
                                    Number(house.latitude),
                                    Number(house.longitude)
                                ], 16);

                                house._marker.openPopup();
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
                                <p class="mt-3 font-semibold text-gray-700">Tiada rumah ditemui</p>
                                <p class="mt-1 text-sm text-gray-500">Cuba ubah carian atau bajet anda.</p>
                            </div>
                        `;
                    } else if (locatedHouses.length === 0) {
                        const notice = document.createElement('p');
                        notice.className = 'text-sm text-amber-700';
                        notice.textContent =
                            'Rumah ini belum mempunyai koordinat untuk dipaparkan pada peta.';
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
                        locationStatus.textContent =
                            'Pelayar anda tidak menyokong GPS.';
                        return;
                    }

                    gpsSummary.textContent = 'Mengesan...';
                    locationStatus.textContent =
                        'Sila benarkan akses lokasi pada pelayar.';

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
                            locationStatus.textContent =
                                'Senarai rumah disusun mengikut jarak.';

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
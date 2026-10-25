@php
    $v = fn ($k) => old($k, $house->{$k});
    $field = 'mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500';
    $lbl = 'block text-sm font-semibold text-gray-700';
    $err = fn ($k) => $errors->first($k);
    $types = ['bilik' => 'Bilik', 'rumah' => 'Rumah', 'apartment' => 'Apartment', 'studio' => 'Studio'];
    $furn = ['yes' => 'Berperabot penuh', 'partial' => 'Separa berperabot', 'no' => 'Tanpa perabot'];
    $curType = $v('property_type');
    $curFurn = $v('furnished');
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">

<div class="grid gap-6 xl:grid-cols-3 xl:items-start">
  <div class="space-y-6 xl:col-span-2">
    {{-- Maklumat asas --}}
    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
        <h3 class="mb-4 text-lg font-bold text-gray-800">Maklumat rumah</h3>
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="title" class="{{ $lbl }}">Tajuk iklan</label>
                <input id="title" name="title" value="{{ $v('title') }}" required class="{{ $field }}" placeholder="cth. Bilik sewa dekat UNMC Semenyih">
                @if ($err('title'))<p class="mt-1 text-xs text-rose-600">{{ $err('title') }}</p>@endif
            </div>
            <div class="sm:col-span-2">
                <label for="description" class="{{ $lbl }}">Penerangan</label>
                <textarea id="description" name="description" rows="5" maxlength="5000" class="{{ $field }}" placeholder="Kemudahan, jarak ke kampus, syarat sewa, jiran...">{{ $v('description') }}</textarea>
                <p class="mt-1 text-xs text-gray-400">Penerangan sekurang-kurangnya 200 aksara menaikkan skor iklan anda.</p>
            </div>
            <div>
                <label for="area" class="{{ $lbl }}">Kawasan</label>
                <input id="area" name="area" value="{{ $v('area') }}" required class="{{ $field }}" placeholder="cth. Semenyih">
                @if ($err('area'))<p class="mt-1 text-xs text-rose-600">{{ $err('area') }}</p>@endif
            </div>
            <div>
                <label for="phone" class="{{ $lbl }}">Telefon untuk dihubungi</label>
                <input id="phone" name="phone" type="tel" value="{{ $v('phone') }}" required class="{{ $field }}" placeholder="01x-xxxxxxx">
                @if ($err('phone'))<p class="mt-1 text-xs text-rose-600">{{ $err('phone') }}</p>@endif
            </div>
            <div class="sm:col-span-2">
                <label for="address" class="{{ $lbl }}">Alamat penuh</label>
                <input id="address" name="address" value="{{ $v('address') }}" required class="{{ $field }}">
                @if ($err('address'))<p class="mt-1 text-xs text-rose-600">{{ $err('address') }}</p>@endif
            </div>
        </div>
    </section>

    {{-- Butiran & harga --}}
    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
        <h3 class="mb-4 text-lg font-bold text-gray-800">Butiran & harga</h3>
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label for="monthly_rent" class="{{ $lbl }}">Sewa bulanan (RM)</label>
                <input id="monthly_rent" name="monthly_rent" type="number" min="0" step="0.01" value="{{ $v('monthly_rent') }}" required class="{{ $field }}">
                @if ($err('monthly_rent'))<p class="mt-1 text-xs text-rose-600">{{ $err('monthly_rent') }}</p>@endif
            </div>
            <div>
                <label for="bedrooms" class="{{ $lbl }}">Bilik tidur</label>
                <input id="bedrooms" name="bedrooms" type="number" min="0" value="{{ $v('bedrooms') }}" required class="{{ $field }}">
            </div>
            <div>
                <label for="bathrooms" class="{{ $lbl }}">Bilik air</label>
                <input id="bathrooms" name="bathrooms" type="number" min="0" value="{{ $v('bathrooms') }}" required class="{{ $field }}">
            </div>
            <div>
                <label for="property_type" class="{{ $lbl }}">Jenis hartanah</label>
                <select id="property_type" name="property_type" class="{{ $field }}">
                    <option value="">Pilih jenis</option>
                    @foreach ($types as $k => $l)<option value="{{ $k }}" @selected($curType === $k)>{{ $l }}</option>@endforeach
                    @if ($curType && ! isset($types[$curType]))<option value="{{ $curType }}" selected>{{ $curType }}</option>@endif
                </select>
            </div>
            <div>
                <label for="furnished" class="{{ $lbl }}">Perabot</label>
                <select id="furnished" name="furnished" class="{{ $field }}">
                    <option value="">Pilih</option>
                    @foreach ($furn as $k => $l)<option value="{{ $k }}" @selected($curFurn === $k)>{{ $l }}</option>@endforeach
                    @if ($curFurn && ! isset($furn[$curFurn]))<option value="{{ $curFurn }}" selected>{{ $curFurn }}</option>@endif
                </select>
            </div>
            <div>
                <label for="status" class="{{ $lbl }}">Status</label>
                <select id="status" name="status" class="{{ $field }}">
                    <option value="available" @selected($v('status') === 'available')>Kosong</option>
                    <option value="rented" @selected($v('status') === 'rented')>Disewa</option>
                </select>
            </div>
        </div>
    </section>

  </div>

  <div class="space-y-6">
    {{-- Gambar --}}
    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
        <h3 class="text-lg font-bold text-gray-800">Gambar</h3>
        <p class="mb-4 text-sm text-gray-500">Iklan dengan sekurang-kurangnya 3 gambar lebih menarik minat pelajar. Setiap gambar maksimum 4 MB.</p>

        @if (count($house->image_urls))
            <p class="mb-2 text-xs font-semibold text-gray-500">Gambar sedia ada. Tanda kotak untuk membuang.</p>
            <div class="mb-4 grid grid-cols-3 gap-3 sm:grid-cols-5 xl:grid-cols-3">
                @foreach ($house->image_urls as $i => $url)
                    <label class="relative block cursor-pointer overflow-hidden rounded-xl border border-gray-200 bg-gray-100 has-[:checked]:opacity-40 has-[:checked]:ring-2 has-[:checked]:ring-rose-500">
                        <img src="{{ $url }}" alt="" class="aspect-square w-full object-cover" onerror="this.style.display='none'">
                        <input type="checkbox" name="remove_images[]" value="{{ $house->image_paths[$i] }}" class="absolute left-1.5 top-1.5 rounded border-gray-300" aria-label="Buang gambar {{ $i + 1 }}">
                    </label>
                @endforeach
            </div>
        @endif

        <input id="photos" name="photos[]" type="file" multiple accept="image/*"
               class="block w-full text-sm file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
        @if ($errors->has('photos') || $errors->has('photos.*'))<p class="mt-1 text-xs text-rose-600">{{ $errors->first('photos') ?: $errors->first('photos.*') }}</p>@endif
        <div id="photoPreview" class="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-5 xl:grid-cols-3"></div>
    </section>

    {{-- Lokasi --}}
    <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
        <h3 class="text-lg font-bold text-gray-800">Lokasi di peta</h3>
        <p class="mb-4 text-sm text-gray-500">Klik pada peta untuk letak pin tepat di rumah anda. Rumah yang ada lokasi muncul dalam carian peta pelajar dan dikira jaraknya ke kampus.</p>
        <div id="pickMap" class="relative isolate z-0 h-72 w-full overflow-hidden rounded-xl border border-gray-200 sm:h-96"></div>
        <div class="mt-3 grid gap-3 sm:grid-cols-3 xl:grid-cols-2">
            <div><label for="latitude" class="{{ $lbl }}">Latitud</label><input id="latitude" name="latitude" type="number" step="any" value="{{ $v('latitude') }}" class="{{ $field }}"></div>
            <div><label for="longitude" class="{{ $lbl }}">Longitud</label><input id="longitude" name="longitude" type="number" step="any" value="{{ $v('longitude') }}" class="{{ $field }}"></div>
            <div class="flex items-end xl:col-span-2"><button type="button" id="useMyLocation" class="w-full rounded-xl border border-indigo-200 bg-white px-4 py-2.5 text-sm font-semibold text-indigo-600 hover:bg-indigo-50">📍 Guna lokasi saya</button></div>
        </div>
        @if ($err('latitude') || $err('longitude'))<p class="mt-1 text-xs text-rose-600">{{ $err('latitude') ?: $err('longitude') }}</p>@endif
    </section>

  </div>

    <div class="sticky bottom-[4.5rem] z-10 -mx-4 flex justify-end gap-2 border-t border-gray-100 bg-gray-50/95 px-4 py-3 backdrop-blur sm:mx-0 sm:rounded-2xl sm:border lg:bottom-4 xl:col-span-3">
        <a href="{{ route('landlord.x.overview') }}" class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</a>
        <button class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">{{ $house->exists ? 'Simpan perubahan' : 'Terbitkan iklan' }}</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
(function () {
    const latEl = document.getElementById('latitude');
    const lngEl = document.getElementById('longitude');
    const lat = parseFloat(latEl.value), lng = parseFloat(lngEl.value);
    const has = !isNaN(lat) && !isNaN(lng);

    const map = L.map('pickMap').setView(has ? [lat, lng] : [3.0, 101.75], has ? 16 : 10);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap',
    }).addTo(map);

    let marker = has ? L.marker([lat, lng]).addTo(map) : null;

    function setPoint(la, ln, center) {
        latEl.value = la.toFixed(7);
        lngEl.value = ln.toFixed(7);
        if (marker) marker.setLatLng([la, ln]); else marker = L.marker([la, ln]).addTo(map);
        if (center) map.setView([la, ln], 17);
    }

    map.on('click', (e) => setPoint(e.latlng.lat, e.latlng.lng, false));

    [latEl, lngEl].forEach((el) => el.addEventListener('change', () => {
        const la = parseFloat(latEl.value), ln = parseFloat(lngEl.value);
        if (!isNaN(la) && !isNaN(ln)) setPoint(la, ln, true);
    }));

    document.getElementById('useMyLocation').addEventListener('click', () => {
        if (!navigator.geolocation) { alert('Pelayar anda tidak menyokong lokasi.'); return; }
        navigator.geolocation.getCurrentPosition(
            (p) => setPoint(p.coords.latitude, p.coords.longitude, true),
            () => alert('Tidak dapat akses lokasi. Klik pada peta untuk letak pin.'),
            { timeout: 8000 }
        );
    });

    // Pratonton gambar baharu
    document.getElementById('photos').addEventListener('change', (e) => {
        const box = document.getElementById('photoPreview');
        box.innerHTML = '';
        [...e.target.files].forEach((f) => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(f);
            img.alt = '';
            img.className = 'aspect-square w-full rounded-xl border border-gray-200 object-cover';
            box.appendChild(img);
        });
    });

    // Peta tersembunyi/berubah saiz: betulkan jubin
    setTimeout(() => map.invalidateSize(), 200);
})();
</script>

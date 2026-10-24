@php $v = fn ($k) => old($k, $house->{$k}); @endphp
<div class="grid gap-6 lg:grid-cols-3" >
    <div class="panel space-y-5 p-5 lg:col-span-2">
        <x-field name="title" label="Tajuk iklan">
            <input class="input" id="title" name="title" value="{{ $v('title') }}" required placeholder="cth. Bilik sewa dekat UiTM Shah Alam">
        </x-field>

        <x-field name="description" label="Penerangan">
            <textarea class="input" id="description" name="description" rows="5" placeholder="Kemudahan, syarat sewa, jarak ke kampus...">{{ $v('description') }}</textarea>
        </x-field>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-field name="area" label="Kawasan"><input class="input" id="area" name="area" value="{{ $v('area') }}" required></x-field>
            <x-field name="phone" label="Telefon untuk dihubungi"><input class="input" id="phone" name="phone" value="{{ $v('phone') }}" required inputmode="tel"></x-field>
        </div>

        <x-field name="address" label="Alamat penuh"><input class="input" id="address" name="address" value="{{ $v('address') }}" required></x-field>

        <div class="grid gap-5 sm:grid-cols-3">
            <x-field name="monthly_rent" label="Sewa bulanan (RM)"><input class="input" id="monthly_rent" name="monthly_rent" type="number" min="0" step="0.01" value="{{ $v('monthly_rent') }}" required></x-field>
            <x-field name="bedrooms" label="Bilik tidur"><input class="input" id="bedrooms" name="bedrooms" type="number" min="0" value="{{ $v('bedrooms') }}" required></x-field>
            <x-field name="bathrooms" label="Bilik air"><input class="input" id="bathrooms" name="bathrooms" type="number" min="0" value="{{ $v('bathrooms') }}" required></x-field>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-field name="property_type" label="Jenis hartanah">
                <input class="input" id="property_type" name="property_type" list="types" value="{{ $v('property_type') }}" placeholder="cth. Apartment">
                <datalist id="types">@foreach ($types as $t)<option value="{{ $t }}">@endforeach</datalist>
            </x-field>
            <x-field name="furnished" label="Perabot">
                <input class="input" id="furnished" name="furnished" list="furn" value="{{ $v('furnished') }}" placeholder="cth. Fully furnished">
                <datalist id="furn">@foreach ($furnishings as $t)<option value="{{ $t }}">@endforeach</datalist>
            </x-field>
        </div>
    </div>

    <div class="space-y-6">
        <div class="panel space-y-5 p-5">
            <x-field name="status" label="Status">
                <select class="input" id="status" name="status">
                    <option value="available" @selected($v('status') === 'available')>Kosong</option>
                    <option value="rented" @selected($v('status') === 'rented')>Disewa</option>
                </select>
            </x-field>
            <x-field name="landlord_id" label="Tuan rumah">
                <select class="input" id="landlord_id" name="landlord_id" required>
                    <option value="">Pilih tuan rumah</option>
                    @foreach ($landlords as $l)<option value="{{ $l->id }}" @selected($v('landlord_id') == $l->id)>{{ $l->name }}</option>@endforeach
                </select>
            </x-field>
        </div>

        <div class="panel p-5">
            <x-field name="photos" label="Gambar rumah" hint="Boleh pilih beberapa gambar. JPG, PNG atau WebP, maksimum 4 MB setiap satu.">
                @if (count($house->image_urls))
                    <p class="mb-2 text-xs text-mute">Tanda kotak pada gambar yang mahu dibuang.</p>
                    <div class="mb-3 grid grid-cols-3 gap-2">
                        @foreach ($house->image_urls as $i => $url)
                            <label class="relative block cursor-pointer overflow-hidden rounded-xl border border-gray-200 bg-gray-100 has-[:checked]:opacity-40 has-[:checked]:ring-2 has-[:checked]:ring-rose-500">
                                <img src="{{ $url }}" alt="" class="aspect-square w-full object-cover" onerror="this.style.display='none'">
                                <input type="checkbox" name="remove_images[]" value="{{ $house->image_paths[$i] }}" class="absolute left-1.5 top-1.5 rounded border-gray-300" aria-label="Buang gambar {{ $i + 1 }}">
                            </label>
                        @endforeach
                    </div>
                @endif
                <input id="photos" name="photos[]" type="file" multiple accept="image/*" class="block w-full text-sm file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                @error('photos.*')<p class="mt-1 text-xs text-rose-700">{{ $message }}</p>@enderror
            </x-field>
        </div>

        <div class="panel space-y-5 p-5">
            <div class="grid grid-cols-2 gap-3">
                <x-field name="latitude" label="Latitud"><input class="input" id="latitude" name="latitude" type="number" step="any" value="{{ $v('latitude') }}" placeholder="3.0738"></x-field>
                <x-field name="longitude" label="Longitud"><input class="input" id="longitude" name="longitude" type="number" step="any" value="{{ $v('longitude') }}" placeholder="101.5183"></x-field>
            </div>
            <p class="text-xs text-mute">Klik kanan lokasi di Google Maps, kemudian salin dua nombor koordinat.</p>
            @if ($house->map_url)<a href="{{ $house->map_url }}" target="_blank" class="link text-sm">Semak di Google Maps</a>@endif
        </div>
    </div>
</div>

<div class="sticky bottom-0 -mx-4 mt-6 flex justify-end gap-2 border-t border-line bg-paper/95 px-4 py-3 backdrop-blur sm:-mx-6 sm:px-6">
    <a href="{{ $house->exists ? route('admin.houses.show', $house) : route('admin.houses.index') }}" class="btn btn-ghost">Batal</a>
    <button class="btn btn-primary">Simpan rumah</button>
</div>

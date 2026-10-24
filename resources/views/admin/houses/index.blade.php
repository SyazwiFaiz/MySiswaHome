@extends('layouts.admin')
@section('title', 'Rumah')
@section('heading', 'Rumah')

@section('actions')
    <a href="{{ route('admin.houses.export', request()->query()) }}" class="btn btn-ghost btn-sm hidden sm:inline-flex">Eksport CSV</a>
    <a href="{{ route('admin.houses.create') }}" class="btn btn-primary btn-sm">Tambah rumah</a>
@endsection

@php
    $filtering = collect(request()->except(['page', 'per_page']))->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty();
    $sortUrl = fn ($col) => request()->fullUrlWithQuery(['sort' => $col, 'dir' => request('sort') === $col && request('dir') === 'asc' ? 'desc' : 'asc']);
@endphp

@section('content')
    {{-- Tapisan --}}
    <form method="GET" class="panel mb-4 p-4" x-data="{ more: {{ request()->hasAny(['area','type','landlord','min_rent','max_rent','missing','trashed','sort']) ? 'true' : 'false' }} }">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input class="input sm:flex-1" type="search" name="q" value="{{ request('q') }}" placeholder="Cari tajuk, alamat, kawasan atau tuan rumah" aria-label="Carian">
            <select class="input sm:w-40" name="status" aria-label="Status">
                <option value="">Semua status</option>
                <option value="available" @selected(request('status') === 'available')>Kosong</option>
                <option value="rented" @selected(request('status') === 'rented')>Disewa</option>
            </select>
            <button class="btn btn-primary">Cari</button>
            <button type="button" class="btn btn-ghost" @click="more = !more" :aria-expanded="more">Tapisan lain</button>
        </div>

        <div x-show="more" x-cloak class="mt-4 grid gap-3 border-t border-line pt-4 sm:grid-cols-2 lg:grid-cols-4">
            <select class="input" name="area" aria-label="Kawasan"><option value="">Semua kawasan</option>@foreach ($areas as $a)<option @selected(request('area') === $a)>{{ $a }}</option>@endforeach</select>
            <select class="input" name="type" aria-label="Jenis"><option value="">Semua jenis</option>@foreach ($types as $t)<option @selected(request('type') === $t)>{{ $t }}</option>@endforeach</select>
            <select class="input" name="landlord" aria-label="Tuan rumah"><option value="">Semua tuan rumah</option>@foreach ($landlords as $l)<option value="{{ $l->id }}" @selected(request('landlord') == $l->id)>{{ $l->name }}</option>@endforeach</select>
            <select class="input" name="trashed" aria-label="Tong sampah">
                <option value="">Tidak termasuk tong sampah</option>
                <option value="with" @selected(request('trashed') === 'with')>Termasuk tong sampah</option>
                <option value="only" @selected(request('trashed') === 'only')>Tong sampah sahaja</option>
            </select>
            <input class="input" type="number" min="0" name="min_rent" value="{{ request('min_rent') }}" placeholder="Sewa min (RM)" aria-label="Sewa minimum">
            <input class="input" type="number" min="0" name="max_rent" value="{{ request('max_rent') }}" placeholder="Sewa maks (RM)" aria-label="Sewa maksimum">
            <select class="input" name="missing" aria-label="Data tidak lengkap">
                <option value="">Data lengkap atau tidak</option>
                <option value="coords" @selected(request('missing') === 'coords')>Tiada koordinat</option>
                <option value="image" @selected(request('missing') === 'image')>Tiada gambar</option>
            </select>
            <select class="input" name="per_page" aria-label="Bilangan setiap halaman">
                @foreach ([10, 25, 50] as $n)<option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>{{ $n }} setiap halaman</option>@endforeach
            </select>
        </div>
        @if ($filtering)<a href="{{ route('admin.houses.index') }}" class="link mt-3 inline-block text-sm">Kosongkan tapisan</a>@endif
    </form>

    {{-- Jadual + tindakan pukal --}}
    <form method="POST" action="{{ route('admin.houses.bulk') }}" class="panel overflow-hidden"
          x-data="{ sel: [], ids: @js($houses->pluck('id')->map(fn ($i) => (string) $i)) }">
        @csrf

        <div class="flex min-h-[3rem] flex-wrap items-center gap-3 border-b border-line px-4 py-2">
            <template x-if="sel.length === 0">
                <p class="text-sm text-mute">{{ $houses->total() }} rumah ditemui</p>
            </template>
            <template x-if="sel.length > 0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium" x-text="sel.length + ' dipilih'"></span>
                    <select name="action" class="input !w-auto !py-1.5" required aria-label="Tindakan pukal">
                        <option value="">Pilih tindakan</option>
                        <option value="available">Tandakan kosong</option>
                        <option value="rented">Tandakan disewa</option>
                        <option value="delete">Alih ke tong sampah</option>
                        <option value="restore">Pulihkan</option>
                    </select>
                    <button class="btn btn-primary btn-sm">Jalankan</button>
                </div>
            </template>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-indigo-50/60">
                    <tr>
                        <th class="th w-10"><input type="checkbox" class="rounded border-line" aria-label="Pilih semua" :checked="sel.length === ids.length && ids.length" @change="sel = $event.target.checked ? [...ids] : []"></th>
                        <th class="th"><a href="{{ $sortUrl('title') }}">Rumah</a></th>
                        <th class="th hidden md:table-cell">Tuan rumah</th>
                        <th class="th"><a href="{{ $sortUrl('monthly_rent') }}">Sewa</a></th>
                        <th class="th hidden lg:table-cell">Bilik</th>
                        <th class="th">Status</th>
                        <th class="th hidden lg:table-cell"><a href="{{ $sortUrl('favourites_count') }}">Simpanan</a></th>
                        <th class="th text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($houses as $house)
                        <tr class="hover:bg-indigo-50/40 {{ $house->trashed() ? 'bg-rose-50/40' : '' }}">
                            <td class="td"><input type="checkbox" name="ids[]" value="{{ $house->id }}" x-model="sel" class="rounded border-line" aria-label="Pilih {{ $house->title }}"></td>
                            <td class="td">
                                <a href="{{ route('admin.houses.show', $house) }}" class="flex items-center gap-3">
                                    <span class="h-12 w-16 shrink-0 overflow-hidden rounded-md bg-indigo-50">
                                        @if ($house->image_url)<img src="{{ $house->image_url }}" alt="" loading="lazy" class="h-full w-full object-cover" onerror="this.style.display='none'">@endif
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block max-w-[14rem] truncate font-medium sm:max-w-xs">{{ $house->title }}</span>
                                        <span class="block truncate text-xs text-mute">{{ $house->area }}</span>
                                    </span>
                                </a>
                            </td>
                            <td class="td hidden md:table-cell">{{ $house->landlord?->name ?? '-' }}</td>
                            <td class="td whitespace-nowrap">RM {{ number_format($house->monthly_rent, 0) }}</td>
                            <td class="td hidden whitespace-nowrap lg:table-cell">{{ $house->bedrooms }} tidur, {{ $house->bathrooms }} air</td>
                            <td class="td">
                                @if ($house->trashed())
                                    <x-badge tone="rose">Dipadam</x-badge>
                                @else
                                    <button formnovalidate formaction="{{ route('admin.houses.toggle', $house) }}" title="Klik untuk tukar status" class="rounded-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500">
                                        <x-badge :tone="$house->status === 'available' ? 'green' : 'amber'">{{ $house->status === 'available' ? 'Kosong' : 'Disewa' }}</x-badge>
                                    </button>
                                @endif
                            </td>
                            <td class="td hidden lg:table-cell">{{ $house->favourites_count }}</td>
                            <td class="td whitespace-nowrap text-right">
                                @if ($house->trashed())
                                    <button formnovalidate formaction="{{ route('admin.houses.restore', $house) }}" class="link text-sm">Pulih</button>
                                    <button type="button" class="ml-2 text-sm font-medium text-rose-700 hover:underline"
                                            data-action="{{ route('admin.houses.destroy', $house) }}" data-label="{{ $house->title }}" data-note="Padam kekal. Tindakan ini tidak boleh dibatalkan."
                                            @click="$dispatch('confirm-delete', { action: $el.dataset.action, label: $el.dataset.label, note: $el.dataset.note })">Padam kekal</button>
                                @else
                                    <a href="{{ route('admin.houses.edit', $house) }}" class="link text-sm">Edit</a>
                                    <button type="button" class="ml-2 text-sm font-medium text-rose-700 hover:underline"
                                            data-action="{{ route('admin.houses.destroy', $house) }}" data-label="{{ $house->title }}" data-note="Rumah dialih ke tong sampah dan boleh dipulihkan."
                                            @click="$dispatch('confirm-delete', { action: $el.dataset.action, label: $el.dataset.label, note: $el.dataset.note })">Padam</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8">
                            <x-empty title="Tiada rumah sepadan" text="Cuba kosongkan tapisan, atau tambah rumah baharu.">
                                <a href="{{ route('admin.houses.create') }}" class="btn btn-primary">Tambah rumah</a>
                            </x-empty>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-4">{{ $houses->links() }}</div>
@endsection

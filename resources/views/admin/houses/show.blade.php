@extends('layouts.admin')
@section('title', $house->title)
@section('heading', 'Butiran rumah')

@section('actions')
    @unless ($house->trashed())
        <a href="{{ route('admin.houses.edit', $house) }}" class="btn btn-primary btn-sm">Edit</a>
    @endunless
@endsection

@section('content')
    <a href="{{ route('admin.houses.index') }}" class="link mb-4 inline-block text-sm">Kembali ke senarai rumah</a>

    @if ($house->trashed())
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
            <span>Rumah ini berada dalam tong sampah sejak {{ $house->deleted_at->format('d/m/Y') }}.</span>
            <form method="POST" action="{{ route('admin.houses.restore', $house) }}">@csrf<button class="btn btn-ghost btn-sm">Pulihkan</button></form>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="panel overflow-hidden">
                <div class="aspect-video bg-indigo-50">
                    @if ($house->image_url)<img src="{{ $house->image_url }}" alt="{{ $house->title }}" class="h-full w-full object-cover">
                    @else<div class="flex h-full items-center justify-center text-sm text-mute">Belum ada gambar. Muat naik melalui butang Edit.</div>@endif
                </div>
                @if (count($house->image_urls) > 1)
                    <div class="flex gap-2 overflow-x-auto border-t border-gray-100 p-3">
                        @foreach ($house->image_urls as $url)
                            <a href="{{ $url }}" target="_blank" class="shrink-0"><img src="{{ $url }}" alt="" class="h-16 w-20 rounded-xl object-cover" onerror="this.style.display='none'"></a>
                        @endforeach
                    </div>
                @endif
                <div class="p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="font-display text-2xl font-bold">{{ $house->title }}</h2>
                            <p class="mt-1 text-sm text-mute">{{ $house->address }}</p>
                        </div>
                        <x-badge :tone="$house->status === 'available' ? 'green' : 'amber'">{{ $house->status === 'available' ? 'Kosong' : 'Disewa' }}</x-badge>
                    </div>
                    <p class="mt-4 whitespace-pre-line text-sm leading-relaxed">{{ $house->description ?: 'Tiada penerangan.' }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="panel divide-y divide-line text-sm">
                @foreach ([
                    'Sewa bulanan' => 'RM ' . number_format($house->monthly_rent, 2),
                    'Kawasan' => $house->area,
                    'Bilik tidur' => $house->bedrooms,
                    'Bilik air' => $house->bathrooms,
                    'Jenis' => $house->property_type ?: '-',
                    'Perabot' => $house->furnished ?: '-',
                    'Telefon' => $house->phone,
                    'Disimpan oleh' => $house->favourites_count . ' pelajar',
                    'Dicipta' => $house->created_at?->format('d/m/Y'),
                ] as $k => $val)
                    <div class="flex justify-between gap-4 px-5 py-2.5"><span class="text-mute">{{ $k }}</span><span class="text-right font-medium">{{ $val }}</span></div>
                @endforeach
                <div class="flex justify-between gap-4 px-5 py-2.5">
                    <span class="text-mute">Tuan rumah</span>
                    @if ($house->landlord)<a class="link" href="{{ route('admin.users.show', $house->landlord) }}">{{ $house->landlord->name }}</a>@else<span>-</span>@endif
                </div>
                <div class="flex justify-between gap-4 px-5 py-2.5">
                    <span class="text-mute">Peta</span>
                    @if ($house->map_url)<a class="link" target="_blank" href="{{ $house->map_url }}">Buka Google Maps</a>@else<span class="text-amber-700">Koordinat belum diisi</span>@endif
                </div>
            </div>

            @unless ($house->trashed())
                <form method="POST" action="{{ route('admin.houses.toggle', $house) }}">@csrf
                    <button class="btn btn-ghost w-full">Tandakan sebagai {{ $house->status === 'available' ? 'disewa' : 'kosong' }}</button>
                </form>
            @endunless

            <div class="panel">
                <h3 class="border-b border-line px-5 py-3 font-display text-base font-bold">Disimpan oleh</h3>
                @forelse ($favouritedBy as $u)
                    <a href="{{ route('admin.users.show', $u) }}" class="flex items-center gap-3 border-b border-line px-5 py-2.5 last:border-0 hover:bg-indigo-50">
                        <x-avatar :user="$u" class="h-8 w-8" /><span class="truncate text-sm">{{ $u->name }}</span>
                    </a>
                @empty
                    <p class="px-5 py-6 text-sm text-mute">Belum ada pelajar menyimpan rumah ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

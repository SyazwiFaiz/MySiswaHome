@extends('layouts.admin')
@section('title', 'Kegemaran')
@section('heading', 'Kegemaran pelajar')

@section('content')
    <form method="GET" class="panel mb-4 flex flex-col gap-3 p-4 sm:flex-row">
        <input class="input sm:flex-1" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama pelajar, emel atau tajuk rumah" aria-label="Carian">
        <button class="btn btn-primary">Cari</button>
        @if (request('q'))<a href="{{ route('admin.favourites.index') }}" class="btn btn-ghost">Kosongkan</a>@endif
    </form>

    <form method="POST" action="{{ route('admin.favourites.bulk') }}" class="panel overflow-hidden" x-data="{ sel: [], ids: @js($favourites->pluck('id')->map(fn ($i) => (string) $i)) }">
        @csrf
        <div class="flex min-h-[3rem] items-center gap-3 border-b border-line px-4 py-2">
            <template x-if="sel.length === 0"><p class="text-sm text-mute">{{ $favourites->total() }} rekod</p></template>
            <template x-if="sel.length > 0">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium" x-text="sel.length + ' dipilih'"></span>
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Buang kegemaran terpilih?')">Buang</button>
                </div>
            </template>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-indigo-50/60"><tr>
                    <th class="th w-10"><input type="checkbox" class="rounded border-line" aria-label="Pilih semua" :checked="sel.length === ids.length && ids.length" @change="sel = $event.target.checked ? [...ids] : []"></th>
                    <th class="th">Pelajar</th><th class="th">Rumah</th><th class="th hidden md:table-cell">Disimpan</th><th class="th text-right">Tindakan</th>
                </tr></thead>
                <tbody class="divide-y divide-line">
                    @forelse ($favourites as $f)
                        <tr class="hover:bg-indigo-50/40">
                            <td class="td"><input type="checkbox" name="ids[]" value="{{ $f->id }}" x-model="sel" class="rounded border-line" aria-label="Pilih rekod {{ $f->id }}"></td>
                            <td class="td">@if ($f->user)<a class="link" href="{{ route('admin.users.show', $f->user) }}">{{ $f->user->name }}</a><span class="block text-xs text-mute">{{ $f->user->email }}</span>@else - @endif</td>
                            <td class="td">@if ($f->house)<a class="link" href="{{ route('admin.houses.show', $f->house) }}">{{ $f->house->title }}</a><span class="block text-xs text-mute">{{ $f->house->area }}{{ $f->house->trashed() ? ' (dipadam)' : '' }}</span>@else - @endif</td>
                            <td class="td hidden md:table-cell whitespace-nowrap">{{ $f->created_at?->format('d/m/Y') }}</td>
                            <td class="td text-right">
                                <button type="button" class="text-sm font-medium text-rose-700 hover:underline"
                                        data-action="{{ route('admin.favourites.destroy', $f) }}" data-label="{{ $f->user?->name }} dan {{ $f->house?->title }}" data-note="Hanya rekod simpanan dibuang. Pelajar dan rumah tidak terjejas."
                                        @click="$dispatch('confirm-delete', { action: $el.dataset.action, label: $el.dataset.label, note: $el.dataset.note })">Buang</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><x-empty title="Tiada rekod kegemaran" text="Rekod muncul apabila pelajar menyimpan rumah." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
    <div class="mt-4">{{ $favourites->links() }}</div>
@endsection

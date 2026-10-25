@extends('layouts.admin')
@section('title', 'Ulasan')
@section('heading', 'Ulasan penyewa')

@section('content')
    <div class="panel mb-4 flex flex-wrap items-center gap-4 p-4">
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-lg">⭐</span>
            <div><p class="text-2xl font-extrabold leading-none">{{ $total ? number_format($avg, 1) : '-' }}</p><p class="text-xs text-mute">Purata keseluruhan</p></div>
        </div>
        <div><p class="text-2xl font-extrabold leading-none">{{ $total }}</p><p class="text-xs text-mute">Jumlah ulasan</p></div>
    </div>

    <form method="GET" class="panel mb-4 flex flex-col gap-3 p-4 sm:flex-row">
        <input class="input sm:flex-1" type="search" name="q" value="{{ request('q') }}" placeholder="Cari ulasan, nama pelajar atau tajuk rumah" aria-label="Carian">
        <select class="input sm:w-40" name="rating" aria-label="Penilaian">
            <option value="">Semua bintang</option>
            @foreach ([5, 4, 3, 2, 1] as $n)<option value="{{ $n }}" @selected((int) request('rating') === $n)>{{ $n }} bintang</option>@endforeach
        </select>
        <button class="btn btn-primary">Cari</button>
    </form>

    <div class="panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-indigo-50/60"><tr>
                    <th class="th">Rumah</th><th class="th">Pelajar</th><th class="th">Penilaian</th>
                    <th class="th hidden md:table-cell">Ulasan</th><th class="th hidden lg:table-cell">Tarikh</th><th class="th text-right">Tindakan</th>
                </tr></thead>
                <tbody class="divide-y divide-line">
                    @forelse ($reviews as $r)
                        <tr class="hover:bg-indigo-50/40">
                            <td class="td">@if ($r->house)<a class="link" href="{{ route('admin.houses.show', $r->house) }}">{{ $r->house->title }}</a>@else - @endif</td>
                            <td class="td">@if ($r->user)<a class="link" href="{{ route('admin.users.show', $r->user) }}">{{ $r->user->name }}</a>@else - @endif</td>
                            <td class="td whitespace-nowrap"><span class="text-amber-400">{{ str_repeat('★', $r->rating) }}</span><span class="text-gray-300">{{ str_repeat('★', 5 - $r->rating) }}</span></td>
                            <td class="td hidden max-w-sm md:table-cell"><span class="line-clamp-2 text-mute" title="{{ $r->comment }}">{{ $r->comment ?: '-' }}</span></td>
                            <td class="td hidden whitespace-nowrap lg:table-cell">{{ $r->created_at->format('d/m/Y') }}</td>
                            <td class="td text-right">
                                <button type="button" class="text-sm font-semibold text-rose-700 hover:underline"
                                        data-action="{{ route('admin.reviews.destroy', $r) }}" data-label="Ulasan {{ $r->user?->name }} untuk {{ $r->house?->title }}" data-note="Gunakan untuk ulasan kasar atau tidak sesuai."
                                        @click="$dispatch('confirm-delete', { action: $el.dataset.action, label: $el.dataset.label, note: $el.dataset.note })">Padam</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-empty title="Belum ada ulasan" text="Ulasan muncul apabila pelajar menilai rumah." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $reviews->links() }}</div>
@endsection

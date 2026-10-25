@php
    $rm = fn ($n) => 'RM' . number_format((float) $n, 0);
    $q = fn ($s) => $s >= 80 ? ['bg-green-500', 'text-green-700'] : ($s >= 60 ? ['bg-amber-400', 'text-amber-700'] : ['bg-rose-500', 'text-rose-700']);
@endphp
<x-landlord-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Papan Pemuka Tuan Rumah</h2>
            <p class="mt-1 text-sm text-gray-500">Urus rumah, permintaan pelajar, ulasan dan sewaan di satu tempat.</p>
        </div>
    </x-slot>

    <div class="w-full">

            {{-- Banner --}}
            <section class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 px-6 py-7 text-white shadow-lg sm:px-8">
                <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-white/5"></div>
                <div class="relative z-10 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Hai, {{ auth()->user()->name }}</h1>
                        <p class="mt-2 max-w-xl text-sm text-indigo-100">
                            Anda ada {{ $stats['houses'] }} rumah: {{ $stats['available'] }} kosong dan {{ $stats['rented'] }} disewa.
                            {{ count($attention) ? 'Ada ' . count($attention) . ' perkara perlu tindakan.' : 'Semuanya terkawal.' }}
                        </p>
                    </div>
                    <a href="{{ route('landlord.x.tenancies.create') }}" class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-indigo-600 shadow-sm transition hover:bg-indigo-50">Rekod penyewa baharu</a>
                </div>
            </section>

            {{-- Statistik --}}
            <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
                @foreach ([
                    ['house', 'bg-blue-50', 'Rumah', $stats['houses'], $stats['available'] . ' kosong, ' . $stats['rented'] . ' disewa'],
                    ['money', 'bg-green-50', 'Sewa bulanan aktif', $rm($stats['income']), 'Daripada rekod sewaan aktif'],
                    ['heart', 'bg-rose-50', 'Disimpan pelajar', $stats['favourites'], 'Jumlah semua iklan anda'],
                    ['star', 'bg-amber-50', 'Penilaian', number_format($stats['rating'] ?? 0, 1), ($stats['reviews'] ?? 0) . ' ulasan'],
                ] as [$icon, $color, $label, $value, $note])
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl {{ $color }}">
                            @if ($icon === 'house')
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif ($icon === 'money')
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20m5-15H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif ($icon === 'heart')
                                <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
                        <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $value }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $note }}</p>
                    </div>
                @endforeach
            </div>
            </div>

            <div class="mb-6 grid gap-6 lg:grid-cols-3">
                {{-- Perlu tindakan --}}
                <section class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <h2 class="border-b border-gray-100 px-5 py-4 text-base font-bold text-gray-800">Perlu tindakan</h2>
                    @forelse ($attention as $a)
                        <a href="{{ $a['url'] }}" class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-3 text-sm last:border-0 hover:bg-indigo-50/50">
                            <span class="text-gray-700">{{ $a['label'] }}</span>
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $a['count'] }}</span>
                        </a>
                    @empty
                        <p class="px-5 py-10 text-center text-sm text-gray-500">Tiada tindakan tertunggak. Bagus!</p>
                    @endforelse
                </section>

                {{-- Bayaran lewat + sewaan hampir tamat --}}
                <section class="rounded-2xl border border-gray-100 bg-white shadow-sm lg:col-span-2">
                    <h2 class="border-b border-gray-100 px-5 py-4 text-base font-bold text-gray-800">Sewaan perlu perhatian</h2>
                    @php $rows = $overdue->merge($ending)->unique('id'); @endphp
                    @forelse ($rows as $t)
                        <a href="{{ route('landlord.x.tenancies.show', $t) }}" class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-3 last:border-0 hover:bg-indigo-50/50">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-800">{{ $t->tenant_name }}</p>
                                <p class="truncate text-xs text-gray-500">{{ $t->house?->title }}</p>
                            </div>
                            <div class="flex gap-2">
                                @if ($t->overdueCount() > 0)<span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">{{ $t->overdueCount() }} bulan lewat</span>@endif
                                @if ($t->daysLeft() !== null && $t->daysLeft() <= 30)<span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $t->daysLeft() < 0 ? 'Telah tamat tempoh' : 'Tamat dalam ' . $t->daysLeft() . ' hari' }}</span>@endif
                            </div>
                        </a>
                    @empty
                        <p class="px-5 py-10 text-center text-sm text-gray-500">Tiada bayaran lewat atau sewaan hampir tamat.</p>
                    @endforelse
                </section>
            </div>

            {{-- Prestasi iklan --}}
            <section id="prestasi" class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-base font-bold text-gray-800">Prestasi iklan</h2>
                    <p class="text-xs text-gray-500">Skor iklan menunjukkan sejauh mana iklan anda lengkap. Iklan yang lengkap lebih mudah dijumpai pelajar.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50"><tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Rumah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Sewa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">â™¥</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">â­</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Permintaan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Skor iklan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">Tindakan</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($houses as $h)
                                @php [$bar, $txt] = $q($h->quality['score']); @endphp
                                <tr class="align-top hover:bg-indigo-50/30">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="h-12 w-16 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                                @if ($h->image_url)<img src="{{ $h->image_url }}" alt="" loading="lazy" class="h-full w-full object-cover" onerror="this.style.display='none'">@endif
                                            </span>
                                            <div class="min-w-0"><p class="max-w-[14rem] truncate text-sm font-semibold text-gray-800">{{ $h->title }}</p><p class="truncate text-xs text-gray-500">{{ $h->area }}</p></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('landlord.x.houses.toggle', $h) }}">@csrf
                                            <button title="Klik untuk tukar status" class="rounded-full px-3 py-1 text-xs font-semibold {{ $h->status === 'available' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-amber-100 text-amber-700 hover:bg-amber-200' }}">
                                                {{ $h->status === 'available' ? 'Kosong' : 'Disewa' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">{{ $rm($h->monthly_rent) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $h->favourites_count }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700">{{ $h->rating_avg ? $h->rating_avg . ' (' . $h->rating_count . ')' : '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $h->inq_total }}
                                        @if ($h->inq_pending)<span class="ml-1 rounded-full bg-rose-100 px-2 py-0.5 text-xs font-semibold text-rose-700">{{ $h->inq_pending }} baharu</span>@endif
                                    </td>
                                    <td class="min-w-[11rem] px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-100"><div class="h-full {{ $bar }}" style="width: {{ $h->quality['score'] }}%"></div></div>
                                            <span class="text-sm font-bold {{ $txt }}">{{ $h->quality['score'] }}</span>
                                        </div>
                                        @if ($h->quality['tips'])
                                            <details class="mt-1 text-xs text-gray-500">
                                                <summary class="cursor-pointer font-semibold text-indigo-600">Cara tingkatkan</summary>
                                                <ul class="mt-1 list-disc space-y-0.5 pl-4">@foreach ($h->quality['tips'] as $tip)<li>{{ $tip }}</li>@endforeach</ul>
                                            </details>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <form method="POST" action="{{ route('landlord.x.houses.duplicate', $h) }}" onsubmit="return confirm('Gandakan iklan ini?')">@csrf
                                            <button class="rounded-xl border border-indigo-200 bg-white px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50">Gandakan</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-6 py-14 text-center text-sm text-gray-500">Anda belum menambah sebarang rumah.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
</x-landlord-layout>



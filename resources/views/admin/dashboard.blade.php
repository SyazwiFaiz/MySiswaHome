@extends('layouts.admin')
@section('title', 'Papan pemuka')
@section('heading', 'Papan pemuka')

@section('actions')
    <a href="{{ route('admin.houses.create') }}" class="btn btn-primary btn-sm">Tambah rumah</a>
@endsection

@php
    $occupancy = $stats['houses'] ? round($stats['rented'] / $stats['houses'] * 100) : 0;
    $actionLabels = ['created' => 'Tambah', 'updated' => 'Kemas kini', 'deleted' => 'Padam', 'force_deleted' => 'Padam kekal', 'restored' => 'Pulih', 'bulk' => 'Pukal', 'export' => 'Eksport'];
@endphp

@section('content')
    {{-- Banner sambutan (gaya sama seperti halaman student) --}}
    <section class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 px-6 py-7 text-white shadow-lg sm:px-8">
        <div class="absolute -right-10 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-white/5"></div>
        <div class="relative z-10 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Hai, {{ auth()->user()->name }}</h2>
                <p class="mt-2 max-w-xl text-sm text-indigo-100">
                    {{ $stats['available'] }} rumah masih kosong dan {{ $stats['users'] }} pengguna berdaftar.
                    @php $todo = collect($attention)->sum('count'); @endphp
                    {{ $todo ? "Ada {$todo} perkara perlu disemak." : 'Semua data kemas.' }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.houses.create') }}" class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-indigo-600 shadow-sm transition hover:bg-indigo-50">Tambah rumah</a>
                <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">Urus pengguna</a>
            </div>
        </div>
    </section>

    {{-- Ringkasan: satu panel, bukan empat kad seragam --}}
    <section class="panel grid divide-y divide-line sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x" aria-label="Ringkasan">
        <div class="p-5 sm:border-b sm:border-line lg:border-b-0">
            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-lg">🏠</span><p class="text-sm text-mute">Rumah disenaraikan</p></div>
            <p class="mt-1 font-display text-3xl font-bold">{{ number_format($stats['houses']) }}</p>
            <div class="mt-3 flex h-2 overflow-hidden rounded-full bg-indigo-100" role="img" aria-label="{{ $occupancy }}% disewa">
                <div class="bg-indigo-600" style="width: {{ $occupancy }}%"></div>
            </div>
            <p class="mt-2 text-xs text-mute">{{ $stats['available'] }} kosong, {{ $stats['rented'] }} disewa ({{ $occupancy }}%)</p>
        </div>
        <div class="p-5 sm:border-b sm:border-line lg:border-b-0">
            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-lg">👥</span><p class="text-sm text-mute">Pengguna berdaftar</p></div>
            <p class="mt-1 font-display text-3xl font-bold">{{ number_format($stats['users']) }}</p>
            <p class="mt-5 text-xs text-mute">{{ $stats['students'] }} pelajar, {{ $stats['landlords'] }} tuan rumah, {{ $stats['admins'] }} admin</p>
        </div>
        <div class="p-5">
            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-lg">💰</span><p class="text-sm text-mute">Sewa bulanan purata</p></div>
            <p class="mt-1 font-display text-3xl font-bold">RM {{ number_format($stats['avg_rent'], 0) }}</p>
            <p class="mt-5 text-xs text-mute">Dikira daripada semua rumah aktif</p>
        </div>
        <div class="p-5">
            <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-lg">♥</span><p class="text-sm text-mute">Rumah disimpan pelajar</p></div>
            <p class="mt-1 font-display text-3xl font-bold">{{ number_format($stats['favourites']) }}</p>
            <a href="{{ route('admin.favourites.index') }}" class="link mt-5 inline-block text-xs">Lihat senarai kegemaran</a>
        </div>
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Perlu perhatian --}}
        <section class="panel lg:col-span-1">
            <h2 class="border-b border-line px-5 py-3 font-display text-base font-bold">Perlu semakan</h2>
            <ul class="divide-y divide-line">
                @foreach ($attention as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="flex items-center justify-between gap-3 px-5 py-3 text-sm hover:bg-indigo-50 focus-visible:bg-indigo-50 focus-visible:outline-none">
                            <span>{{ $item['label'] }}</span>
                            <x-badge :tone="$item['count'] ? 'amber' : 'green'">{{ $item['count'] ?: 'Beres' }}</x-badge>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Trend --}}
        <section class="panel lg:col-span-2">
            <div class="flex items-center justify-between border-b border-line px-5 py-3">
                <h2 class="font-display text-base font-bold">Pertumbuhan 6 bulan</h2>
                <p class="text-xs text-mute">Pengguna dan iklan baharu</p>
            </div>
            <div class="h-64 p-4"><canvas id="trendChart" aria-label="Carta pertumbuhan" role="img"></canvas></div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="panel lg:col-span-2">
            <div class="flex items-center justify-between border-b border-line px-5 py-3">
                <h2 class="font-display text-base font-bold">Kawasan paling banyak iklan</h2>
                <p class="text-xs text-mute">Top 8</p>
            </div>
            @if ($areas->isEmpty())
                <x-empty title="Belum ada data kawasan" text="Carta muncul selepas rumah pertama ditambah." />
            @else
                <div class="p-4" style="height: {{ 60 + $areas->count() * 34 }}px"><canvas id="areaChart" aria-label="Carta kawasan" role="img"></canvas></div>
            @endif
        </section>

        <section class="panel">
            <h2 class="border-b border-line px-5 py-3 font-display text-base font-bold">Paling banyak disimpan</h2>
            @forelse ($topFavourites as $h)
                <a href="{{ route('admin.houses.show', $h) }}" class="flex items-center justify-between gap-3 border-b border-line px-5 py-3 last:border-0 hover:bg-indigo-50">
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-medium">{{ $h->title }}</span>
                        <span class="block truncate text-xs text-mute">{{ $h->area }}</span>
                    </span>
                    <x-badge tone="rose">{{ $h->favourites_count }} simpanan</x-badge>
                </a>
            @empty
                <x-empty title="Belum ada kegemaran" text="Rumah yang disimpan pelajar akan disenaraikan di sini." />
            @endforelse
        </section>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <section class="panel">
            <div class="flex items-center justify-between border-b border-line px-5 py-3">
                <h2 class="font-display text-base font-bold">Pengguna terbaharu</h2>
                <a href="{{ route('admin.users.index') }}" class="link text-xs">Semua pengguna</a>
            </div>
            @foreach ($newUsers as $u)
                <a href="{{ route('admin.users.show', $u) }}" class="flex items-center gap-3 border-b border-line px-5 py-3 last:border-0 hover:bg-indigo-50">
                    <x-avatar :user="$u" class="h-9 w-9" />
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm font-medium">{{ $u->name }}</span>
                        <span class="block truncate text-xs text-mute">{{ $u->email }}</span>
                    </span>
                    <span class="text-xs text-mute">{{ $u->created_at?->diffForHumans() }}</span>
                </a>
            @endforeach
        </section>

        <section class="panel">
            <div class="flex items-center justify-between border-b border-line px-5 py-3">
                <h2 class="font-display text-base font-bold">Aktiviti terkini</h2>
                <a href="{{ route('admin.activity.index') }}" class="link text-xs">Semua log</a>
            </div>
            @forelse ($recentLogs as $log)
                <div class="flex items-start gap-3 border-b border-line px-5 py-3 last:border-0">
                    <x-badge :tone="in_array($log->action, ['deleted','force_deleted']) ? 'rose' : 'blue'" class="mt-0.5">{{ $actionLabels[$log->action] ?? $log->action }}</x-badge>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm">{{ $log->description }}</p>
                        <p class="text-xs text-mute">{{ $log->user?->name ?? 'Sistem' }}, {{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <x-empty title="Belum ada aktiviti" text="Setiap tambah, kemas kini dan padam oleh admin direkodkan di sini." />
            @endforelse
        </section>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const green = '#4F46E5', gold = '#A78BFA', grid = '#F3F4F6';
    Chart.defaults.font.family = 'Figtree, system-ui, sans-serif';
    Chart.defaults.color = '#6B7280';

    new Chart(document.getElementById('trendChart'), {
        type: 'bar',
        data: {
            labels: @json($trend['labels']),
            datasets: [
                { label: 'Pengguna baharu', data: @json($trend['users']), backgroundColor: green, borderRadius: 4 },
                { label: 'Iklan baharu', data: @json($trend['houses']), backgroundColor: gold, borderRadius: 4 },
            ],
        },
        options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: grid } }, x: { grid: { display: false } } }, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } } } },
    });

    @if ($areas->isNotEmpty())
    new Chart(document.getElementById('areaChart'), {
        type: 'bar',
        data: { labels: @json($areas->pluck('area')), datasets: [{ label: 'Bilangan rumah', data: @json($areas->pluck('total')), backgroundColor: green, borderRadius: 4 }] },
        options: { indexAxis: 'y', maintainAspectRatio: false, scales: { x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: grid } }, y: { grid: { display: false } } }, plugins: { legend: { display: false }, tooltip: { callbacks: { afterLabel: (c) => 'Sewa purata: RM ' + Math.round(@json($areas->pluck('avg_rent'))[c.dataIndex]) } } } },
    });
    @endif
</script>
@endpush

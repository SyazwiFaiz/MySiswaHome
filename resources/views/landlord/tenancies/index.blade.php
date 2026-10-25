@php $rm = fn ($n) => 'RM' . number_format((float) $n, 0); @endphp
<x-landlord-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Sewaan & Bayaran</h2>
                <p class="mt-1 text-sm text-gray-500">Jejak penyewa, tempoh sewa dan bayaran bulanan.</p>
            </div>
            <a href="{{ route('landlord.x.tenancies.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">Rekod penyewa baharu</a>
        </div>
    </x-slot>

    <div class="w-full">

            <div class="mb-6 grid grid-cols-3 gap-4">
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><p class="text-xs text-gray-500">Sewa bulanan aktif</p><p class="mt-1 text-2xl font-extrabold text-gray-800">{{ $rm($income) }}</p></div>
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><p class="text-xs text-gray-500">Bayaran lewat</p><p class="mt-1 text-2xl font-extrabold {{ $overdue ? 'text-rose-600' : 'text-gray-800' }}">{{ $overdue }}</p></div>
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><p class="text-xs text-gray-500">Tamat dalam 30 hari</p><p class="mt-1 text-2xl font-extrabold {{ $ending ? 'text-amber-600' : 'text-gray-800' }}">{{ $ending }}</p></div>
            </div>

            <h3 class="mb-3 text-lg font-bold text-gray-800">Sewaan aktif</h3>
            <div class="space-y-3">
                @forelse ($active as $t)
                    <a href="{{ route('landlord.x.tenancies.show', $t) }}" class="block rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-lg font-bold text-gray-800">{{ $t->tenant_name }}</p>
                                <p class="truncate text-sm text-gray-500">{{ $t->house?->title }} &middot; {{ $t->house?->area }}</p>
                            </div>
                            <p class="text-xl font-extrabold text-indigo-600">{{ $rm($t->monthly_rent) }}<span class="text-xs font-medium text-gray-400"> / bulan</span></p>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600">Mula {{ $t->start_date->format('d/m/Y') }}</span>
                            @if ($t->end_date)<span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600">Tamat {{ $t->end_date->format('d/m/Y') }}</span>@endif
                            @if ($t->overdueCount())<span class="rounded-full bg-rose-100 px-3 py-1 text-rose-700">{{ $t->overdueCount() }} bulan lewat</span>@else<span class="rounded-full bg-green-100 px-3 py-1 text-green-700">Bayaran terkini</span>@endif
                            @if ($t->daysLeft() !== null && $t->daysLeft() <= 30)<span class="rounded-full bg-amber-100 px-3 py-1 text-amber-700">{{ $t->daysLeft() < 0 ? 'Lepas tarikh tamat' : 'Tamat dalam ' . $t->daysLeft() . ' hari' }}</span>@endif
                        </div>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-white py-14 text-center">
                        <div class="flex justify-center text-indigo-500"><svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 1 1 8 0v3m-4 5v2"/></svg></div>
                        <h3 class="mt-4 text-lg font-bold text-gray-700">Belum ada sewaan aktif</h3>
                        <p class="mt-2 text-sm text-gray-500">Rekod penyewa untuk jejak bayaran sewa dan terima peringatan tamat sewa.</p>
                        <a href="{{ route('landlord.x.tenancies.create') }}" class="mt-5 inline-block rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Rekod penyewa</a>
                    </div>
                @endforelse
            </div>

            @if ($ended->isNotEmpty())
                <details class="mt-8">
                    <summary class="cursor-pointer text-lg font-bold text-gray-800">Sewaan lepas ({{ $ended->count() }})</summary>
                    <div class="mt-3 space-y-3">
                        @foreach ($ended as $t)
                            <a href="{{ route('landlord.x.tenancies.show', $t) }}" class="flex flex-wrap items-center justify-between gap-2 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm hover:bg-indigo-50/40">
                                <div><p class="font-semibold text-gray-800">{{ $t->tenant_name }}</p><p class="text-xs text-gray-500">{{ $t->house?->title }}</p></div>
                                <p class="text-xs text-gray-500">{{ $t->start_date->format('d/m/Y') }} hingga {{ optional($t->ended_at ?? $t->end_date)->format('d/m/Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif
        </div>
</x-landlord-layout>


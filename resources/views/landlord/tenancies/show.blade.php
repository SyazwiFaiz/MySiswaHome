@php
    $rm = fn ($n) => 'RM' . number_format((float) $n, 0);
    $states = ['paid' => ['Telah dibayar', 'bg-green-100 text-green-700'], 'overdue' => ['Lewat', 'bg-rose-100 text-rose-700'], 'soon' => ['Hampir tarikh bayaran', 'bg-amber-100 text-amber-700'], 'upcoming' => ['Belum tiba', 'bg-gray-100 text-gray-600']];
    $phone = preg_replace('/\D/', '', (string) $tenancy->tenant_phone);
    $due = $schedule->firstWhere('state', 'overdue');
    $wa = $phone ? 'https://wa.me/' . preg_replace('/^0/', '60', $phone) . '?text=' . rawurlencode("Salam {$tenancy->tenant_name}, ini peringatan mesra untuk bayaran sewa {$tenancy->house?->title}. Terima kasih.") : null;
@endphp
<x-landlord-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $tenancy->tenant_name }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $tenancy->house?->title }}</p>
            </div>
            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tenancy->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $tenancy->status === 'active' ? 'Sewaan aktif' : 'Telah tamat' }}</span>
        </div>
    </x-slot>

    <div class="w-full">
            <a href="{{ route('landlord.x.tenancies.index') }}" class="mb-4 inline-block text-sm font-semibold text-indigo-600 hover:underline">Kembali ke senarai sewaan</a>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="space-y-6">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <dl class="space-y-3 text-sm">
                            @foreach ([
                                'Sewa bulanan' => $rm($tenancy->monthly_rent),
                                'Deposit' => $rm($tenancy->deposit),
                                'Mula' => $tenancy->start_date->format('d/m/Y'),
                                'Tamat' => $tenancy->end_date?->format('d/m/Y') ?? 'Tidak ditetapkan',
                                'Tarikh bayaran' => 'Hari ke-' . min($tenancy->start_date->day, 28) . ' setiap bulan',
                                'Telefon' => $tenancy->tenant_phone ?: '-',
                            ] as $k => $val)
                                <div class="flex justify-between gap-3"><dt class="text-gray-500">{{ $k }}</dt><dd class="text-right font-semibold text-gray-800">{{ $val }}</dd></div>
                            @endforeach
                        </dl>
                        @if ($tenancy->notes)<p class="mt-4 whitespace-pre-line rounded-xl bg-gray-50 p-3 text-sm text-gray-600">{{ $tenancy->notes }}</p>@endif
                        @if ($tenancy->daysLeft() !== null && $tenancy->daysLeft() <= 30)
                            <p class="mt-4 rounded-xl bg-amber-50 p-3 text-sm font-semibold text-amber-700">{{ $tenancy->daysLeft() < 0 ? 'Tempoh sewa telah tamat. Kemas kini atau tamatkan sewaan.' : 'Sewa tamat dalam ' . $tenancy->daysLeft() . ' hari. Bincang pembaharuan dengan penyewa.' }}</p>
                        @endif
                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('landlord.x.tenancies.edit', $tenancy) }}" class="rounded-xl border border-indigo-200 bg-white px-4 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50">Edit</a>
                            @if ($wa)<a href="{{ $wa }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">{{ $due ? 'Ingatkan bayaran' : 'WhatsApp' }}</a>@endif
                        </div>
                    </div>

                    @if ($tenancy->status === 'active')
                        <form method="POST" action="{{ route('landlord.x.tenancies.end', $tenancy) }}" class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm" onsubmit="return confirm('Tamatkan sewaan ini?')">
                            @csrf
                            <p class="text-sm font-bold text-gray-800">Tamatkan sewaan</p>
                            <label class="mt-3 flex items-start gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="free_house" value="1" checked class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                Tandakan rumah kosong semula supaya muncul kepada pelajar
                            </label>
                            <button class="mt-4 w-full rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Tamatkan sewaan</button>
                        </form>
                    @endif

                    <button type="button" form="delTenancy" class="w-full text-center text-xs font-semibold text-gray-400 hover:text-rose-600" onclick="if (confirm('Padam rekod sewaan dan semua rekod bayarannya?')) document.getElementById('delTenancy').submit()">Padam rekod ini</button>
                    <form id="delTenancy" method="POST" action="{{ route('landlord.x.tenancies.destroy', $tenancy) }}" class="hidden">@csrf @method('DELETE')</form>
                </div>

                <section class="rounded-2xl border border-gray-100 bg-white shadow-sm lg:col-span-2">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h3 class="text-base font-bold text-gray-800">Bayaran bulanan</h3>
                        <p class="text-xs text-gray-500">Tekan "Tandakan dibayar" apabila anda terima wang sewa.</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($schedule as $row)
                            @php [$label, $tone] = $states[$row['state']]; @endphp
                            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $row['period']->translatedFormat('F Y') }}</p>
                                    <p class="text-xs text-gray-500">
                                        Tarikh bayaran {{ $row['due']->format('d/m/Y') }}
                                        @if ($row['payment']) &middot; dibayar {{ $row['payment']->paid_at->format('d/m/Y') }} @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tone }}">{{ $label }}</span>
                                    @if ($row['payment'])
                                        <form method="POST" action="{{ route('landlord.x.tenancies.unpay', [$tenancy, $row['key']]) }}" onsubmit="return confirm('Batalkan rekod bayaran bulan ini?')">@csrf @method('DELETE')
                                            <button class="rounded-xl border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-500 hover:bg-gray-50">Batal</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('landlord.x.tenancies.pay', [$tenancy, $row['key']]) }}">@csrf
                                            <button class="rounded-xl bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700">Tandakan dibayar</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
</x-landlord-layout>


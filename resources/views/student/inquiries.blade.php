@php
    $labels = ['pending' => ['Menunggu', 'bg-amber-100 text-amber-700'], 'contacted' => ['Telah dihubungi', 'bg-green-100 text-green-700'], 'closed' => ['Ditutup', 'bg-gray-100 text-gray-600']];
@endphp
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Permintaan Saya</h2>
            <p class="mt-1 text-sm text-gray-500">Jejak permintaan lawatan yang anda hantar kepada tuan rumah.</p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
            @endif

            <div class="space-y-4">
                @forelse ($inquiries as $q)
                    @php [$label, $tone] = $labels[$q->status] ?? [$q->status, 'bg-gray-100 text-gray-600']; @endphp
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="truncate text-lg font-bold text-gray-800">{{ $q->house?->title ?? 'Rumah telah dipadam' }}</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    📍 {{ $q->house?->area }}
                                    @if ($q->house) &middot; RM{{ number_format($q->house->monthly_rent) }} / bulan @endif
                                </p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tone }}">{{ $label }}</span>
                        </div>

                        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-3">
                            <div><dt class="text-xs text-gray-400">Dihantar</dt><dd class="font-medium text-gray-700">{{ $q->created_at->format('d/m/Y') }}</dd></div>
                            <div><dt class="text-xs text-gray-400">Tarikh lawatan dicadangkan</dt><dd class="font-medium text-gray-700">{{ $q->preferred_date?->format('d/m/Y') ?? 'Tidak dinyatakan' }}</dd></div>
                            <div><dt class="text-xs text-gray-400">Telefon anda</dt><dd class="font-medium text-gray-700">{{ $q->phone ?: '-' }}</dd></div>
                        </dl>

                        @if ($q->message)
                            <p class="mt-4 whitespace-pre-line rounded-xl bg-gray-50 p-3 text-sm text-gray-600">{{ $q->message }}</p>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if ($q->house?->phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '60', preg_replace('/\D/', '', $q->house->phone)) }}" target="_blank" rel="noopener noreferrer"
                                   class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">WhatsApp tuan rumah</a>
                            @endif
                            @if ($q->status === 'pending')
                                <form method="POST" action="{{ route('student.x.inquiries.destroy', $q) }}" onsubmit="return confirm('Batalkan permintaan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batalkan permintaan</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center">
                        <div class="text-5xl">📅</div>
                        <h3 class="mt-4 text-lg font-bold text-gray-700">Belum ada permintaan lawatan</h3>
                        <p class="mt-2 text-sm text-gray-500">Buka butiran mana-mana rumah dan tekan "Mohon lawatan" untuk mula.</p>
                        <a href="{{ route('student.dashboard') }}" class="mt-5 inline-block rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Cari rumah</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $inquiries->links() }}</div>
        </div>
    </div>
</x-app-layout>

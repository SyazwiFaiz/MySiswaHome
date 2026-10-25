@php
    $labels = ['pending' => ['Menunggu', 'bg-amber-100 text-amber-700'], 'contacted' => ['Telah dihubungi', 'bg-green-100 text-green-700'], 'closed' => ['Ditutup', 'bg-gray-100 text-gray-600']];
    $wa = function ($phone, $text) { $d = preg_replace('/\D/', '', (string) $phone); return $d ? 'https://wa.me/' . preg_replace('/^0/', '60', $d) . '?text=' . rawurlencode($text) : null; };
@endphp
<x-landlord-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Permintaan Lawatan</h2>
            <p class="mt-1 text-sm text-gray-500">Pelajar yang berminat dengan rumah anda.</p>
        </div>
    </x-slot>

    <div class="w-full">

            <div class="mb-5 flex flex-wrap gap-2">
                @foreach (['' => 'Semua'] + \App\Models\Inquiry::STATUSES as $k => $label)
                    <a href="{{ route('landlord.x.inquiries', array_filter(['status' => $k])) }}"
                       class="rounded-full px-4 py-1.5 text-sm font-semibold {{ (string) request('status') === (string) $k ? 'bg-indigo-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-indigo-50' }}">
                        {{ $label }}@if ($k)<span class="ml-1 opacity-70">{{ $counts[$k] ?? 0 }}</span>@endif
                    </a>
                @endforeach
            </div>

            <div class="space-y-4">
                @forelse ($inquiries as $q)
                    @php
                        [$label, $tone] = $labels[$q->status] ?? [$q->status, 'bg-gray-100 text-gray-600'];
                        $phone = $q->phone ?: $q->user?->phone;
                        $link = $wa($phone, "Salam {$q->user?->name}, saya " . auth()->user()->name . " pemilik \"{$q->house?->title}\". Terima kasih berminat dengan rumah saya. " . ($q->preferred_date ? 'Boleh datang melawat pada ' . $q->preferred_date->format('d/m/Y') . '?' : 'Bila anda free untuk melawat?'));
                    @endphp
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">{{ strtoupper(mb_substr($q->user?->name ?? '?', 0, 1)) }}</span>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $q->user?->name ?? 'Pengguna dipadam' }}</p>
                                    <p class="text-xs text-gray-500">{{ $phone ?: $q->user?->email }} &middot; {{ $q->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $tone }}">{{ $label }}</span>
                        </div>

                        <p class="mt-4 text-sm text-gray-500">Berminat dengan <span class="font-semibold text-gray-800">{{ $q->house?->title }}</span> ({{ $q->house?->area }}, RM{{ number_format($q->house?->monthly_rent ?? 0) }})</p>
                        <p class="mt-1 text-sm text-gray-500">Cadangan lawatan: <span class="font-semibold text-gray-800">{{ $q->preferred_date?->format('d/m/Y') ?? 'Tidak dinyatakan' }}</span></p>
                        @if ($q->message)<p class="mt-3 whitespace-pre-line rounded-xl bg-gray-50 p-3 text-sm text-gray-600">{{ $q->message }}</p>@endif

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            @if ($link)<a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">Balas melalui WhatsApp</a>@endif
                            <form method="POST" action="{{ route('landlord.x.inquiries.update', $q) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <label for="st{{ $q->id }}" class="sr-only">Status</label>
                                <select id="st{{ $q->id }}" name="status" onchange="this.form.submit()" class="rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach (\App\Models\Inquiry::STATUSES as $k => $l)<option value="{{ $k }}" @selected($q->status === $k)>{{ $l }}</option>@endforeach
                                </select>
                            </form>
                            @if ($q->house)<a href="{{ route('landlord.x.tenancies.create', ['house' => $q->house_id]) }}" class="rounded-xl border border-indigo-200 bg-white px-4 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50">Rekod sebagai penyewa</a>@endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center">
                        <div class="flex justify-center text-indigo-500"><svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m-9 8 3.5-3H18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12Z"/></svg></div>
                        <h3 class="mt-4 text-lg font-bold text-gray-700">Belum ada permintaan</h3>
                        <p class="mt-2 text-sm text-gray-500">Permintaan akan muncul di sini apabila pelajar menekan "Mohon lawatan" pada iklan anda. Iklan yang lengkap dengan gambar menarik lebih banyak permintaan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $inquiries->links() }}</div>
        </div>
</x-landlord-layout>


<x-landlord-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Ulasan Penyewa</h2>
            <p class="mt-1 text-sm text-gray-500">Balas ulasan untuk bina kepercayaan pelajar. Balasan anda dipaparkan di bawah ulasan.</p>
        </div>
    </x-slot>

    <div class="w-full">
            @include('landlord.partials.nav')

            <div class="mb-5 flex flex-wrap items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-lg">⭐</span>
                    <div><p class="text-2xl font-extrabold leading-none text-gray-800">{{ $total ? number_format($avg, 1) : '-' }}</p><p class="text-xs text-gray-500">Purata penilaian</p></div></div>
                <div><p class="text-2xl font-extrabold leading-none text-gray-800">{{ $total }}</p><p class="text-xs text-gray-500">Jumlah ulasan</p></div>
                <div class="ml-auto flex gap-2">
                    <a href="{{ route('landlord.x.reviews') }}" class="rounded-full px-4 py-1.5 text-sm font-semibold {{ request('filter') ? 'border border-gray-200 bg-white text-gray-600 hover:bg-indigo-50' : 'bg-indigo-600 text-white' }}">Semua</a>
                    <a href="{{ route('landlord.x.reviews', ['filter' => 'unreplied']) }}" class="rounded-full px-4 py-1.5 text-sm font-semibold {{ request('filter') === 'unreplied' ? 'bg-indigo-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-indigo-50' }}">Belum dibalas <span class="opacity-70">{{ $unreplied }}</span></a>
                </div>
            </div>

            <div class="grid gap-4 xl:grid-cols-2">
                @forelse ($reviews as $r)
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div><p class="font-bold text-gray-800">{{ $r->user?->name ?? 'Pengguna' }}</p><p class="text-xs text-gray-500">{{ $r->house?->title }} &middot; {{ $r->created_at->diffForHumans() }}</p></div>
                            <span class="text-lg leading-none"><span class="text-amber-400">{{ str_repeat('★', $r->rating) }}</span><span class="text-gray-300">{{ str_repeat('★', 5 - $r->rating) }}</span></span>
                        </div>
                        @if ($r->comment)<p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-600">{{ $r->comment }}</p>@endif

                        <form method="POST" action="{{ route('landlord.x.reviews.reply', $r) }}" class="mt-4 rounded-xl bg-indigo-50/60 p-4">
                            @csrf
                            <label for="rp{{ $r->id }}" class="text-sm font-bold text-gray-800">{{ $r->landlord_reply ? 'Balasan anda' : 'Balas ulasan ini' }}</label>
                            <textarea id="rp{{ $r->id }}" name="reply" rows="2" maxlength="500" placeholder="Contoh: Terima kasih atas ulasan. Kami akan baiki kemudahan tersebut."
                                class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $r->landlord_reply }}</textarea>
                            <div class="mt-2 flex gap-2">
                                <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">{{ $r->landlord_reply ? 'Kemas kini balasan' : 'Hantar balasan' }}</button>
                                @if ($r->landlord_reply)
                                    <button type="submit" form="del{{ $r->id }}" class="rounded-xl border border-rose-200 bg-white px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50" onclick="return confirm('Padam balasan?')">Padam balasan</button>
                                @endif
                            </div>
                        </form>
                        @if ($r->landlord_reply)
                            <form id="del{{ $r->id }}" method="POST" action="{{ route('landlord.x.reviews.reply.destroy', $r) }}" class="hidden">@csrf @method('DELETE')</form>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-white py-16 text-center">
                        <div class="text-5xl">💬</div>
                        <h3 class="mt-4 text-lg font-bold text-gray-700">{{ request('filter') ? 'Semua ulasan sudah dibalas' : 'Belum ada ulasan' }}</h3>
                        <p class="mt-2 text-sm text-gray-500">Ulasan muncul apabila pelajar menilai rumah anda.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $reviews->links() }}</div>
        </div>
</x-landlord-layout>

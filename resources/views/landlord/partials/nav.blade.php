
@php
    $pending = 0;

    if (auth()->check() && class_exists(\App\Models\Inquiry::class)) {
        $pending = \App\Models\Inquiry::whereHas('house', function ($query) {
            $query->where('landlord_id', auth()->id());
        })->where('status', 'pending')->count();
    }

    $menus = [
        [
            'label' => 'Dashboard',
            'route' => 'landlord.create',
            'patterns' => ['landlord.create', 'landlord.x.overview'],
            'icon' => '🏠',
        ],
        [
            'label' => 'Create House',
            'route' => 'landlord.houses.create',
            'patterns' => ['landlord.houses.create'],
            'icon' => '➕',
        ],
        [
            'label' => 'Manage House',
            'route' => 'landlord.manage',
            'patterns' => ['landlord.manage', 'landlord.edit'],
            'icon' => '🏘️',
        ],
        [
            'label' => 'Permintaan',
            'route' => 'landlord.x.inquiries',
            'patterns' => ['landlord.x.inquiries'],
            'icon' => '📩',
        ],
        [
            'label' => 'Ulasan',
            'route' => 'landlord.x.reviews',
            'patterns' => ['landlord.x.reviews'],
            'icon' => '⭐',
        ],
        [
            'label' => 'Sewaan',
            'route' => 'landlord.x.tenancies.index',
            'patterns' => ['landlord.x.tenancies.*'],
            'icon' => '🔑',
        ],
    ];
@endphp

<aside class="w-full rounded-2xl border border-gray-200 bg-white p-4 shadow-sm lg:sticky lg:top-6">
    <div class="mb-5 border-b border-gray-100 pb-4">
        <h2 class="text-lg font-bold text-gray-900">Landlord Portal</h2>
        <p class="mt-1 text-sm text-gray-500">Pengurusan rumah sewa</p>
    </div>

    <nav class="space-y-2">
        @foreach ($menus as $menu)
            @if (\Illuminate\Support\Facades\Route::has($menu['route']))
                @php
                    $active = collect($menu['patterns'])
                        ->contains(fn ($pattern) => request()->routeIs($pattern));
                @endphp

                <a
                    href="{{ route($menu['route']) }}"
                    class="flex items-center justify-between rounded-xl px-3 py-3 text-sm font-medium transition
                    {{ $active
                        ? 'bg-indigo-600 text-white shadow-sm'
                        : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}"
                >
                    <span class="flex items-center gap-3">
                        <span>{{ $menu['icon'] }}</span>
                        <span>{{ $menu['label'] }}</span>
                    </span>

                    @if ($menu['label'] === 'Permintaan' && $pending > 0)
                        <span class="rounded-full bg-red-500 px-2 py-0.5 text-xs text-white">
                            {{ $pending }}
                        </span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>
</aside>

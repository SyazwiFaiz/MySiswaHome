@extends('layouts.admin')
@section('title', $user->name)
@section('heading', 'Profil pengguna')

@section('actions')
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
@endsection

@section('content')
    <a href="{{ route('admin.users.index') }}" class="link mb-4 inline-block text-sm">Kembali ke senarai pengguna</a>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="panel p-5 lg:col-span-1">
            <div class="flex items-center gap-4">
                <x-avatar :user="$user" class="h-16 w-16 text-lg" />
                <div class="min-w-0">
                    <h2 class="truncate font-display text-xl font-bold">{{ $user->name }}</h2>
                    <div class="mt-1 flex gap-2"><x-badge tone="blue">{{ $user->role_label }}</x-badge><x-badge :tone="$user->is_active ? 'green' : 'rose'">{{ $user->is_active ? 'Aktif' : 'Digantung' }}</x-badge></div>
                </div>
            </div>
            <dl class="mt-5 space-y-3 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-mute">Emel</dt><dd class="truncate font-medium">{{ $user->email }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-mute">Telefon</dt><dd class="font-medium">{{ $user->phone ?: '-' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-mute">Emel disahkan</dt><dd class="font-medium">{{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y') : 'Belum' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-mute">Daftar</dt><dd class="font-medium">{{ $user->created_at?->format('d/m/Y') }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-mute">Iklan rumah</dt><dd class="font-medium">{{ $user->houses_count }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-mute">Rumah disimpan</dt><dd class="font-medium">{{ $user->favourites_count }}</dd></div>
            </dl>
            @if ($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="mt-5">@csrf
                    <button class="btn btn-ghost w-full">{{ $user->is_active ? 'Gantung akaun' : 'Aktifkan akaun' }}</button>
                </form>
            @endif
        </div>

        <div class="space-y-6 lg:col-span-2">
            <section class="panel">
                <h3 class="border-b border-line px-5 py-3 font-display text-base font-bold">Iklan rumah</h3>
                @forelse ($houses as $h)
                    <a href="{{ route('admin.houses.show', $h) }}" class="flex items-center justify-between gap-3 border-b border-line px-5 py-3 last:border-0 hover:bg-indigo-50">
                        <span class="min-w-0"><span class="block truncate text-sm font-medium">{{ $h->title }}</span><span class="text-xs text-mute">{{ $h->area }}, RM {{ number_format($h->monthly_rent, 0) }}</span></span>
                        <x-badge :tone="$h->status === 'available' ? 'green' : 'amber'">{{ $h->status === 'available' ? 'Kosong' : 'Disewa' }}</x-badge>
                    </a>
                @empty
                    <x-empty title="Tiada iklan rumah" text="Pengguna ini belum menyenaraikan sebarang rumah." />
                @endforelse
            </section>

            <section class="panel">
                <h3 class="border-b border-line px-5 py-3 font-display text-base font-bold">Rumah yang disimpan</h3>
                @forelse ($favourites as $f)
                    <div class="flex items-center justify-between gap-3 border-b border-line px-5 py-3 last:border-0">
                        <span class="truncate text-sm">{{ $f->house?->title ?? 'Rumah telah dipadam' }}</span>
                        <span class="text-xs text-mute">{{ $f->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <x-empty title="Tiada rumah disimpan" />
                @endforelse
            </section>
        </div>
    </div>
@endsection

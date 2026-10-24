@extends('layouts.admin')
@section('title', 'Pengguna')
@section('heading', 'Pengguna')

@section('actions')
    <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-ghost btn-sm hidden sm:inline-flex">Eksport CSV</a>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Tambah pengguna</a>
@endsection

@php
    $filtering = collect(request()->except(['page']))->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty();
    $roleTone = ['admin' => 'blue', 'landlord' => 'amber', 'student' => 'gray'];
    $sortUrl = fn ($col) => request()->fullUrlWithQuery(['sort' => $col, 'dir' => request('sort') === $col && request('dir') === 'asc' ? 'desc' : 'asc']);
@endphp

@section('content')
    <form method="GET" class="panel mb-4 flex flex-col gap-3 p-4 sm:flex-row">
        <input class="input sm:flex-1" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, emel atau telefon" aria-label="Carian">
        <select class="input sm:w-40" name="role" aria-label="Peranan">
            <option value="">Semua peranan</option>
            @foreach (\App\Models\User::ROLES as $k => $l)<option value="{{ $k }}" @selected(request('role') === $k)>{{ $l }}</option>@endforeach
        </select>
        <select class="input sm:w-40" name="active" aria-label="Status akaun">
            <option value="">Semua akaun</option>
            <option value="1" @selected(request('active') === '1')>Aktif</option>
            <option value="0" @selected(request('active') === '0')>Digantung</option>
        </select>
        <button class="btn btn-primary">Cari</button>
        @if ($filtering)<a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Kosongkan</a>@endif
    </form>

    <form method="POST" action="{{ route('admin.users.bulk') }}" class="panel overflow-hidden"
          x-data="{ sel: [], ids: @js($users->pluck('id')->map(fn ($i) => (string) $i)) }">
        @csrf
        <div class="flex min-h-[3rem] flex-wrap items-center gap-3 border-b border-line px-4 py-2">
            <template x-if="sel.length === 0"><p class="text-sm text-mute">{{ $users->total() }} pengguna ditemui</p></template>
            <template x-if="sel.length > 0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium" x-text="sel.length + ' dipilih'"></span>
                    <select name="action" class="input !w-auto !py-1.5" required aria-label="Tindakan pukal">
                        <option value="">Pilih tindakan</option>
                        <option value="activate">Aktifkan akaun</option>
                        <option value="suspend">Gantung akaun</option>
                        <option value="delete">Padam pengguna</option>
                    </select>
                    <button class="btn btn-primary btn-sm" onclick="if (this.form.action.value === 'delete') return confirm('Padam pengguna terpilih? Iklan rumah milik mereka turut dipadam.')">Jalankan</button>
                </div>
            </template>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-indigo-50/60">
                    <tr>
                        <th class="th w-10"><input type="checkbox" class="rounded border-line" aria-label="Pilih semua" :checked="sel.length === ids.length && ids.length" @change="sel = $event.target.checked ? [...ids] : []"></th>
                        <th class="th"><a href="{{ $sortUrl('name') }}">Pengguna</a></th>
                        <th class="th">Peranan</th>
                        <th class="th hidden md:table-cell">Telefon</th>
                        <th class="th hidden lg:table-cell"><a href="{{ $sortUrl('houses_count') }}">Rumah</a></th>
                        <th class="th hidden lg:table-cell"><a href="{{ $sortUrl('created_at') }}">Daftar</a></th>
                        <th class="th">Akaun</th>
                        <th class="th text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($users as $user)
                        <tr class="hover:bg-indigo-50/40">
                            <td class="td"><input type="checkbox" name="ids[]" value="{{ $user->id }}" x-model="sel" class="rounded border-line" aria-label="Pilih {{ $user->name }}" @disabled($user->id === auth()->id())></td>
                            <td class="td">
                                <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-3">
                                    <x-avatar :user="$user" class="h-9 w-9" />
                                    <span class="min-w-0"><span class="block truncate font-medium">{{ $user->name }}{{ $user->id === auth()->id() ? ' (anda)' : '' }}</span><span class="block max-w-[12rem] truncate text-xs text-mute sm:max-w-xs">{{ $user->email }}</span></span>
                                </a>
                            </td>
                            <td class="td"><x-badge :tone="$roleTone[$user->role] ?? 'gray'">{{ $user->role_label }}</x-badge></td>
                            <td class="td hidden md:table-cell">{{ $user->phone ?: '-' }}</td>
                            <td class="td hidden lg:table-cell">{{ $user->houses_count }}</td>
                            <td class="td hidden whitespace-nowrap lg:table-cell">{{ $user->created_at?->format('d/m/Y') }}</td>
                            <td class="td">
                                @if ($user->id === auth()->id())
                                    <x-badge tone="green">Aktif</x-badge>
                                @else
                                    <button formnovalidate formaction="{{ route('admin.users.toggle', $user) }}" title="Klik untuk tukar status akaun" class="rounded-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500">
                                        <x-badge :tone="$user->is_active ? 'green' : 'rose'">{{ $user->is_active ? 'Aktif' : 'Digantung' }}</x-badge>
                                    </button>
                                @endif
                            </td>
                            <td class="td whitespace-nowrap text-right">
                                <a href="{{ route('admin.users.edit', $user) }}" class="link text-sm">Edit</a>
                                @if ($user->id !== auth()->id())
                                    <button type="button" class="ml-2 text-sm font-medium text-rose-700 hover:underline"
                                            data-action="{{ route('admin.users.destroy', $user) }}" data-label="{{ $user->name }}" data-note="{{ $user->houses_count ? 'Pengguna ini ada ' . $user->houses_count . ' iklan rumah. Semuanya turut dipadam.' : 'Akaun dipadam kekal.' }}"
                                            @click="$dispatch('confirm-delete', { action: $el.dataset.action, label: $el.dataset.label, note: $el.dataset.note })">Padam</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><x-empty title="Tiada pengguna sepadan" text="Cuba tukar carian atau tapisan."><a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah pengguna</a></x-empty></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
    <div class="mt-4">{{ $users->links() }}</div>
@endsection

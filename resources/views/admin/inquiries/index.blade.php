@extends('layouts.admin')
@section('title', 'Permintaan lawatan')
@section('heading', 'Permintaan lawatan')

@php
    $tone = ['pending' => 'amber', 'contacted' => 'green', 'closed' => 'gray'];
    $wa = fn ($p) => $p ? 'https://wa.me/' . preg_replace('/^0/', '60', preg_replace('/\D/', '', $p)) : null;
@endphp

@section('content')
    <div class="mb-4 flex flex-wrap gap-2">
        @foreach (['' => 'Semua'] + \App\Models\Inquiry::STATUSES as $k => $label)
            <a href="{{ route('admin.inquiries.index', array_filter(['status' => $k, 'q' => request('q')])) }}"
               class="rounded-full px-4 py-1.5 text-sm font-semibold {{ (string) request('status') === (string) $k ? 'bg-indigo-600 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-indigo-50' }}">
                {{ $label }}@if ($k) <span class="ml-1 opacity-70">{{ $counts[$k] ?? 0 }}</span>@endif
            </a>
        @endforeach
    </div>

    <form method="GET" class="panel mb-4 flex flex-col gap-3 p-4 sm:flex-row">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input class="input sm:flex-1" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama pelajar, emel atau tajuk rumah" aria-label="Carian">
        <button class="btn btn-primary">Cari</button>
    </form>

    <div class="panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-indigo-50/60"><tr>
                    <th class="th">Pelajar</th><th class="th">Rumah</th><th class="th hidden lg:table-cell">Lawatan</th>
                    <th class="th hidden md:table-cell">Mesej</th><th class="th">Status</th><th class="th text-right">Tindakan</th>
                </tr></thead>
                <tbody class="divide-y divide-line">
                    @forelse ($inquiries as $q)
                        @php $phone = $q->phone ?: $q->user?->phone; @endphp
                        <tr class="hover:bg-indigo-50/40">
                            <td class="td">
                                @if ($q->user)<a class="link" href="{{ route('admin.users.show', $q->user) }}">{{ $q->user->name }}</a>@else - @endif
                                <span class="block text-xs text-mute">{{ $phone ?: $q->user?->email }}</span>
                            </td>
                            <td class="td">
                                @if ($q->house)<a class="link" href="{{ route('admin.houses.show', $q->house) }}">{{ $q->house->title }}</a><span class="block text-xs text-mute">{{ $q->house->area }}</span>@else - @endif
                            </td>
                            <td class="td hidden whitespace-nowrap lg:table-cell">{{ $q->preferred_date?->format('d/m/Y') ?? '-' }}<span class="block text-xs text-mute">Hantar {{ $q->created_at->diffForHumans() }}</span></td>
                            <td class="td hidden max-w-xs md:table-cell"><span class="line-clamp-2 text-mute" title="{{ $q->message }}">{{ $q->message ?: '-' }}</span></td>
                            <td class="td">
                                <form method="POST" action="{{ route('admin.inquiries.update', $q) }}">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="input !w-auto !py-1.5" aria-label="Status">
                                        @foreach (\App\Models\Inquiry::STATUSES as $k => $label)<option value="{{ $k }}" @selected($q->status === $k)>{{ $label }}</option>@endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="td whitespace-nowrap text-right">
                                @if ($wa($phone))<a href="{{ $wa($phone) }}" target="_blank" rel="noopener noreferrer" class="link text-sm">WhatsApp</a>@endif
                                <button type="button" class="ml-2 text-sm font-semibold text-rose-700 hover:underline"
                                        data-action="{{ route('admin.inquiries.destroy', $q) }}" data-label="Permintaan {{ $q->user?->name }} untuk {{ $q->house?->title }}"
                                        @click="$dispatch('confirm-delete', { action: $el.dataset.action, label: $el.dataset.label, note: '' })">Padam</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-empty title="Tiada permintaan lawatan" text="Permintaan akan muncul apabila pelajar menekan Mohon lawatan pada butiran rumah." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $inquiries->links() }}</div>
@endsection

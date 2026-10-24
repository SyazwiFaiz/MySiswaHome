@extends('layouts.admin')
@section('title', 'Log aktiviti')
@section('heading', 'Log aktiviti')

@php $labels = ['created' => 'Tambah', 'updated' => 'Kemas kini', 'deleted' => 'Padam', 'force_deleted' => 'Padam kekal', 'restored' => 'Pulih', 'bulk' => 'Pukal', 'export' => 'Eksport']; @endphp

@section('content')
    <form method="GET" class="panel mb-4 flex flex-col gap-3 p-4 sm:flex-row">
        <input class="input sm:flex-1" type="search" name="q" value="{{ request('q') }}" placeholder="Cari penerangan atau nama admin" aria-label="Carian">
        <select class="input sm:w-44" name="action" aria-label="Jenis tindakan">
            <option value="">Semua tindakan</option>
            @foreach ($actions as $a)<option value="{{ $a }}" @selected(request('action') === $a)>{{ $labels[$a] ?? $a }}</option>@endforeach
        </select>
        <button class="btn btn-primary">Cari</button>
    </form>

    <div class="panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line">
                <thead class="bg-indigo-50/60"><tr><th class="th">Masa</th><th class="th">Admin</th><th class="th">Tindakan</th><th class="th">Penerangan</th><th class="th hidden lg:table-cell">IP</th></tr></thead>
                <tbody class="divide-y divide-line">
                    @forelse ($logs as $log)
                        <tr>
                            <td class="td whitespace-nowrap text-mute">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="td whitespace-nowrap">{{ $log->user?->name ?? 'Sistem' }}</td>
                            <td class="td"><x-badge :tone="in_array($log->action, ['deleted', 'force_deleted']) ? 'rose' : 'blue'">{{ $labels[$log->action] ?? $log->action }}</x-badge></td>
                            <td class="td">{{ $log->description }}</td>
                            <td class="td hidden text-mute lg:table-cell">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><x-empty title="Tiada log" text="Tindakan admin akan direkodkan secara automatik." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>

    <form method="POST" action="{{ route('admin.activity.prune') }}" class="mt-6 flex flex-wrap items-center gap-3 text-sm" onsubmit="return confirm('Bersihkan log lama?')">
        @csrf
        <span class="text-mute">Bersihkan log yang lebih lama daripada</span>
        <select name="days" class="input !w-auto !py-1.5" aria-label="Hari"><option value="30">30 hari</option><option value="90" selected>90 hari</option><option value="180">180 hari</option></select>
        <button class="btn btn-ghost btn-sm">Bersihkan</button>
    </form>
@endsection

@php
    $v = fn ($k, $d = null) => old($k, $tenancy->{$k} ?? $d);
    $date = fn ($k) => old($k, optional($tenancy->{$k})->format('Y-m-d'));
    $field = 'mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500';
    $lbl = 'block text-sm font-semibold text-gray-700';
    $err = fn ($k) => $errors->first($k);
@endphp

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="house_id" class="{{ $lbl }}">Rumah</label>
        @if ($tenancy->exists)
            <p class="mt-1 rounded-xl bg-gray-50 px-3 py-2.5 text-sm font-semibold text-gray-700">{{ $tenancy->house?->title }}</p>
        @else
            <select id="house_id" name="house_id" required class="{{ $field }}" onchange="const o=this.selectedOptions[0]; if (o.dataset.rent && !document.getElementById('monthly_rent').value) document.getElementById('monthly_rent').value = o.dataset.rent;">
                <option value="">Pilih rumah</option>
                @foreach ($houses as $h)
                    <option value="{{ $h->id }}" data-rent="{{ (int) $h->monthly_rent }}" @selected((string) $v('house_id') === (string) $h->id)>{{ $h->title }}{{ $h->status === 'rented' ? ' (ditanda disewa)' : '' }}</option>
                @endforeach
            </select>
            @if ($houses->isEmpty())<p class="mt-1 text-xs text-amber-600">Semua rumah anda sudah ada sewaan aktif.</p>@endif
        @endif
        @if ($err('house_id'))<p class="mt-1 text-xs text-rose-600">{{ $err('house_id') }}</p>@endif
    </div>

    <div>
        <label for="tenant_name" class="{{ $lbl }}">Nama penyewa</label>
        <input id="tenant_name" name="tenant_name" value="{{ $v('tenant_name') }}" required class="{{ $field }}">
        @if ($err('tenant_name'))<p class="mt-1 text-xs text-rose-600">{{ $err('tenant_name') }}</p>@endif
    </div>
    <div>
        <label for="tenant_phone" class="{{ $lbl }}">Telefon penyewa</label>
        <input id="tenant_phone" name="tenant_phone" type="tel" value="{{ $v('tenant_phone') }}" class="{{ $field }}" placeholder="01x-xxxxxxx">
    </div>

    <div>
        <label for="start_date" class="{{ $lbl }}">Tarikh mula sewa</label>
        <input id="start_date" name="start_date" type="date" value="{{ $date('start_date') }}" required class="{{ $field }}">
        <p class="mt-1 text-xs text-gray-400">Hari dalam bulan ini menjadi tarikh bayaran sewa setiap bulan.</p>
        @if ($err('start_date'))<p class="mt-1 text-xs text-rose-600">{{ $err('start_date') }}</p>@endif
    </div>
    <div>
        <label for="end_date" class="{{ $lbl }}">Tarikh tamat (pilihan)</label>
        <input id="end_date" name="end_date" type="date" value="{{ $date('end_date') }}" class="{{ $field }}">
        <p class="mt-1 text-xs text-gray-400">Anda akan diingatkan 30 hari sebelum tamat.</p>
        @if ($err('end_date'))<p class="mt-1 text-xs text-rose-600">{{ $err('end_date') }}</p>@endif
    </div>

    <div>
        <label for="monthly_rent" class="{{ $lbl }}">Sewa bulanan (RM)</label>
        <input id="monthly_rent" name="monthly_rent" type="number" min="0" step="0.01" value="{{ $v('monthly_rent') }}" required class="{{ $field }}">
        @if ($err('monthly_rent'))<p class="mt-1 text-xs text-rose-600">{{ $err('monthly_rent') }}</p>@endif
    </div>
    <div>
        <label for="deposit" class="{{ $lbl }}">Deposit diterima (RM)</label>
        <input id="deposit" name="deposit" type="number" min="0" step="0.01" value="{{ $v('deposit', 0) }}" class="{{ $field }}">
    </div>

    <div class="sm:col-span-2">
        <label for="notes" class="{{ $lbl }}">Nota (pilihan)</label>
        <textarea id="notes" name="notes" rows="3" maxlength="1000" class="{{ $field }}" placeholder="Contoh: Deposit utiliti RM200 sudah dibayar. Penyewa pelajar UNMC.">{{ $v('notes') }}</textarea>
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ $tenancy->exists ? route('landlord.x.tenancies.show', $tenancy) : route('landlord.x.tenancies.index') }}" class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</a>
    <button class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">Simpan sewaan</button>
</div>

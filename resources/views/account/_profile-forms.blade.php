@php
    $user = auth()->user();
    $input = 'mt-1 block w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20';
    $label = 'block text-sm font-semibold text-gray-700';
    $card = 'rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6';
    $btn = 'rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700';
    $pw = $errors->getBag('updatePassword');
@endphp

<div class="grid gap-6 xl:grid-cols-3 xl:items-start">

    {{-- Maklumat profil --}}
    <section class="{{ $card }} xl:col-span-2">
        <h3 class="text-lg font-bold text-gray-800">Maklumat profil</h3>
        <p class="mb-5 text-sm text-gray-500">Nama, emel dan nombor telefon anda.</p>

        <form method="POST" action="{{ route('profile.account.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="flex flex-wrap items-center gap-4">
                @if ($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-2xl object-cover">
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 text-2xl font-bold text-white">{{ $user->initials }}</div>
                @endif
                <div class="min-w-0 flex-1">
                    <label for="photo" class="{{ $label }}">Gambar profil</label>
                    <input id="photo" name="photo" type="file" accept="image/*"
                           class="mt-1 block w-full text-sm file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-400">JPG, PNG atau WebP, maksimum 2 MB.</p>
                    @error('photo')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="{{ $label }}">Nama</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="{{ $input }}">
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="{{ $label }}">Telefon</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" autocomplete="tel" class="{{ $input }}" placeholder="01x-xxxxxxx">
                    @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="{{ $label }}">Emel</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="{{ $input }}">
                    @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button class="{{ $btn }}">Simpan profil</button>
                @if ($user->avatar)
                    <button type="submit" form="removeAvatarForm" class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50"
                            onclick="return confirm('Buang gambar profil?')">Buang gambar</button>
                @endif
            </div>
        </form>

        @if ($user->avatar)
            <form id="removeAvatarForm" method="POST" action="{{ route('profile.account.avatar.destroy') }}" class="hidden">@csrf @method('DELETE')</form>
        @endif
    </section>

    <div class="space-y-6">
        {{-- Ringkasan akaun --}}
        <section class="{{ $card }}">
            <h3 class="text-lg font-bold text-gray-800">Akaun</h3>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-3"><dt class="text-gray-500">Peranan</dt><dd class="rounded-full bg-indigo-50 px-3 py-0.5 text-xs font-semibold text-indigo-700">{{ $user->role_label }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-gray-500">Ahli sejak</dt><dd class="font-semibold text-gray-800">{{ $user->created_at?->format('d/m/Y') }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-gray-500">Status</dt><dd class="font-semibold {{ $user->is_active ? 'text-green-700' : 'text-rose-700' }}">{{ $user->is_active ? 'Aktif' : 'Digantung' }}</dd></div>
            </dl>
        </section>

        {{-- Kata laluan --}}
        <section class="{{ $card }}">
            <h3 class="text-lg font-bold text-gray-800">Tukar kata laluan</h3>
            <p class="mb-4 text-sm text-gray-500">Gunakan kata laluan yang panjang dan sukar diteka.</p>

            <form method="POST" action="{{ route('profile.account.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="current_password" class="{{ $label }}">Kata laluan semasa</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="{{ $input }}">
                    @if ($pw->has('current_password'))<p class="mt-1 text-xs text-rose-600">{{ $pw->first('current_password') }}</p>@endif
                </div>
                <div>
                    <label for="password" class="{{ $label }}">Kata laluan baharu</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" class="{{ $input }}">
                    @if ($pw->has('password'))<p class="mt-1 text-xs text-rose-600">{{ $pw->first('password') }}</p>@endif
                </div>
                <div>
                    <label for="password_confirmation" class="{{ $label }}">Sahkan kata laluan baharu</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="{{ $input }}">
                </div>
                <button class="{{ $btn }} w-full">Tukar kata laluan</button>
            </form>
        </section>
    </div>
</div>

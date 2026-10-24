@php $v = fn ($k) => old($k, $user->{$k}); @endphp
<div class="grid gap-6 lg:grid-cols-3">
    <div class="panel space-y-5 p-5 lg:col-span-2">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-field name="name" label="Nama penuh"><input class="input" id="name" name="name" value="{{ $v('name') }}" required autocomplete="off"></x-field>
            <x-field name="phone" label="Telefon"><input class="input" id="phone" name="phone" value="{{ $v('phone') }}" inputmode="tel"></x-field>
        </div>
        <x-field name="email" label="Emel"><input class="input" id="email" name="email" type="email" value="{{ $v('email') }}" required autocomplete="off"></x-field>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-field name="password" label="Kata laluan" :hint="$user->exists ? 'Biarkan kosong jika tidak mahu tukar.' : 'Minimum 8 aksara.'">
                <input class="input" id="password" name="password" type="password" autocomplete="new-password" @required(! $user->exists)>
            </x-field>
            <x-field name="password_confirmation" label="Sahkan kata laluan">
                <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
            </x-field>
        </div>
    </div>

    <div class="space-y-6">
        <div class="panel space-y-5 p-5">
            <x-field name="role" label="Peranan">
                <select class="input" id="role" name="role" @disabled($user->id === auth()->id())>
                    @foreach (\App\Models\User::ROLES as $k => $label)<option value="{{ $k }}" @selected($v('role') === $k)>{{ $label }}</option>@endforeach
                </select>
                @if ($user->id === auth()->id())<input type="hidden" name="role" value="{{ $user->role }}"><p class="mt-1 text-xs text-mute">Anda tidak boleh menukar peranan akaun sendiri.</p>@endif
            </x-field>
            <label class="flex items-start gap-3 text-sm">
                <input type="checkbox" name="is_active" value="1" class="mt-0.5 rounded border-line" @checked(old('is_active', $user->is_active ?? true)) @disabled($user->id === auth()->id())>
                <span><span class="font-medium">Akaun aktif</span><br><span class="text-xs text-mute">Akaun yang digantung tidak boleh log masuk.</span></span>
            </label>
        </div>
        <div class="panel p-5">
            <x-field name="photo" label="Gambar profil" hint="Maksimum 2 MB.">
                <div class="mb-3 flex items-center gap-3">
                    @if ($user->exists)<x-avatar :user="$user" class="h-14 w-14 text-base" />@endif
                </div>
                <input id="photo" name="photo" type="file" accept="image/*" class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-indigo-800 hover:file:bg-indigo-200">
            </x-field>
        </div>
    </div>
</div>

<div class="sticky bottom-0 -mx-4 mt-6 flex justify-end gap-2 border-t border-line bg-paper/95 px-4 py-3 backdrop-blur sm:-mx-6 sm:px-6">
    <a href="{{ $user->exists ? route('admin.users.show', $user) : route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
    <button class="btn btn-primary">Simpan pengguna</button>
</div>

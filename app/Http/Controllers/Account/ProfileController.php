<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ], [
            'photo.image' => 'Fail mesti berupa gambar (JPG, PNG atau WebP).',
            'photo.max' => 'Gambar profil maksimum 2 MB.',
            'email.unique' => 'Emel ini sudah digunakan oleh akaun lain.',
        ]);

        if ($request->hasFile('photo')) {
            $this->deleteAvatar($user->avatar);
            $data['avatar'] = $request->file('photo')->store('avatars', 'public');
        }

        $user->fill(Arr::except($data, 'photo'))->save();

        return back()->with('success', 'Profil dikemas kini.');
    }

    public function password(Request $request)
    {
        $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata laluan semasa tidak betul.',
            'password.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
        ]);

        $request->user()->update(['password' => $request->input('password')]);

        return back()->with('success', 'Kata laluan ditukar.');
    }

    public function removeAvatar(Request $request)
    {
        $user = $request->user();
        $this->deleteAvatar($user->avatar);
        $user->update(['avatar' => null]);

        return back()->with('success', 'Gambar profil dibuang.');
    }

    private function deleteAvatar(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://', '/'])) {
            Storage::disk('public')->delete($path);
        }
    }
}

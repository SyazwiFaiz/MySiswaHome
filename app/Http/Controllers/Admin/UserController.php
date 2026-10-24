<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Support\Csv;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = $this->filtered($request)->paginate(in_array((int) $request->per_page, [10, 25, 50]) ? (int) $request->per_page : 10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create', ['user' => new User(['role' => 'student', 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['email_verified_at'] = now();
        $user = User::create($data);

        ActivityLog::record('created', "Menambah pengguna \"{$user->name}\" ({$user->role})", $user);

        return redirect()->route('admin.users.show', $user)->with('success', 'Pengguna berjaya ditambah.');
    }

    public function show(User $user)
    {
        $user->loadCount(['houses', 'favourites']);
        $houses = $user->houses()->latest()->limit(8)->get();
        $favourites = $user->favourites()->with('house')->latest()->limit(8)->get();

        return view('admin.users.show', compact('user', 'houses', 'favourites'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user);

        // Admin tidak boleh turunkan pangkat / gantung akaun sendiri
        if ($user->id === auth()->id()) {
            $data['role'] = $user->role;
            $data['is_active'] = true;
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        ActivityLog::record('updated', "Mengemas kini pengguna \"{$user->name}\"", $user);

        return redirect()->route('admin.users.show', $user)->with('success', 'Perubahan disimpan.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak boleh memadam akaun sendiri.');
        }

        $name = $user->name;
        if ($user->avatar && ! Str::startsWith($user->avatar, ['http://', 'https://', '/'])) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();

        ActivityLog::record('deleted', "Memadam pengguna \"{$name}\"");

        return redirect()->route('admin.users.index')->with('success', 'Pengguna dipadam.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak boleh menggantung akaun sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);
        ActivityLog::record('updated', ($user->is_active ? 'Mengaktifkan' : 'Menggantung') . " akaun \"{$user->name}\"", $user);

        return back()->with('success', $user->is_active ? 'Akaun diaktifkan.' : 'Akaun digantung.');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => ['required', 'in:activate,suspend,delete'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ], ['ids.required' => 'Pilih sekurang-kurangnya satu pengguna.']);

        $ids = array_values(array_diff($request->ids, [auth()->id()])); // jangan sentuh diri sendiri

        match ($request->action) {
            'activate' => User::whereIn('id', $ids)->update(['is_active' => true]),
            'suspend' => User::whereIn('id', $ids)->update(['is_active' => false]),
            'delete' => User::whereIn('id', $ids)->delete(),
        };

        ActivityLog::record('bulk', "Tindakan pukal '{$request->action}' pada " . count($ids) . ' pengguna');

        return back()->with('success', 'Tindakan siap untuk ' . count($ids) . ' pengguna. Akaun anda sendiri dilangkau.');
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->cursor()->map(fn (User $u) => [
            $u->id, $u->name, $u->email, $u->phone, $u->role_label,
            $u->is_active ? 'Aktif' : 'Digantung', $u->houses_count, $u->favourites_count, $u->created_at,
        ]);

        ActivityLog::record('export', 'Eksport senarai pengguna (CSV)');

        return Csv::download('pengguna-' . now()->format('Ymd-His') . '.csv', [
            'ID', 'Nama', 'Emel', 'Telefon', 'Peranan', 'Status', 'Bil. rumah', 'Bil. kegemaran', 'Daftar',
        ], $rows);
    }

    /* ---------------------------------------------------------------- */

    private function filtered(Request $r)
    {
        $query = User::query()->withCount(['houses', 'favourites']);

        $query
            ->when($r->q, fn ($qb, $s) => $qb->where(fn ($w) => $w->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")->orWhere('phone', 'like', "%{$s}%")))
            ->when($r->role, fn ($qb, $v) => $qb->where('role', $v))
            ->when($r->active !== null && $r->active !== '', fn ($qb) => $qb->where('is_active', (bool) $r->active))
            ->when($r->no_listing, fn ($qb) => $qb->doesntHave('houses'));

        $sort = in_array($r->sort, ['name', 'created_at', 'houses_count']) ? $r->sort : 'created_at';

        return $query->orderBy($sort, $r->dir === 'asc' ? 'asc' : 'desc')->orderByDesc('id');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'is_active' => ['nullable', 'boolean'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            if ($user?->avatar && ! Str::startsWith($user->avatar, ['http://', 'https://', '/'])) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('photo')->store('avatars', 'public');
        }

        return Arr::except($data, 'photo');
    }
}

<?php

namespace App\Http\Controllers\Landlord;

use App\Models\House;
use App\Models\RentPayment;
use App\Models\Tenancy;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TenancyController extends LandlordController
{
    public function index()
    {
        $all = Tenancy::with(['house:id,title,area', 'payments'])
            ->whereIn('house_id', $this->myHouseIds())->orderByDesc('start_date')->get();

        $active = $all->where('status', 'active')->values();

        return view('landlord.tenancies.index', [
            'active' => $active,
            'ended' => $all->where('status', 'ended')->values(),
            'income' => (float) $active->sum('monthly_rent'),
            'overdue' => $active->filter(fn ($t) => $t->overdueCount() > 0)->count(),
            'ending' => $active->filter(fn ($t) => $t->daysLeft() !== null && $t->daysLeft() <= 30)->count(),
        ]);
    }

    public function create(Request $request)
    {
        $tenancy = new Tenancy(['start_date' => today(), 'deposit' => 0, 'house_id' => $request->house]);

        return view('landlord.tenancies.create', ['tenancy' => $tenancy, 'houses' => $this->freeHouses()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);

        $house = House::findOrFail($data['house_id']);
        $this->authorizeHouse($house);

        if (Tenancy::where('house_id', $house->id)->where('status', 'active')->exists()) {
            return back()->withInput()->withErrors(['house_id' => 'Rumah ini sudah ada sewaan aktif.']);
        }

        $tenancy = Tenancy::create($data + ['status' => 'active']);
        $house->update(['status' => 'rented']);

        return redirect()->route('landlord.x.tenancies.show', $tenancy)->with('success', 'Sewaan direkodkan. Rumah ditandakan disewa.');
    }

    public function show(Tenancy $tenancy)
    {
        $this->authorizeHouse($tenancy->house);
        $tenancy->load('house', 'payments');

        return view('landlord.tenancies.show', ['tenancy' => $tenancy, 'schedule' => $tenancy->schedule()->reverse()->values()]);
    }

    public function edit(Tenancy $tenancy)
    {
        $this->authorizeHouse($tenancy->house);

        return view('landlord.tenancies.edit', ['tenancy' => $tenancy, 'houses' => collect([$tenancy->house])]);
    }

    public function update(Request $request, Tenancy $tenancy)
    {
        $this->authorizeHouse($tenancy->house);
        $tenancy->update($this->validated($request, false));

        return redirect()->route('landlord.x.tenancies.show', $tenancy)->with('success', 'Maklumat sewaan dikemas kini.');
    }

    public function end(Request $request, Tenancy $tenancy)
    {
        $this->authorizeHouse($tenancy->house);

        $tenancy->update([
            'status' => 'ended',
            'ended_at' => today(),
            'end_date' => $tenancy->end_date && $tenancy->end_date->lt(today()) ? $tenancy->end_date : today(),
        ]);

        if ($request->boolean('free_house')) {
            $tenancy->house->update(['status' => 'available']);
        }

        return redirect()->route('landlord.x.tenancies.index')->with('success', 'Sewaan ditamatkan' . ($request->boolean('free_house') ? ' dan rumah ditandakan kosong semula.' : '.'));
    }

    public function destroy(Tenancy $tenancy)
    {
        $this->authorizeHouse($tenancy->house);
        $tenancy->delete();

        return redirect()->route('landlord.x.tenancies.index')->with('success', 'Rekod sewaan dipadam.');
    }

    public function pay(Tenancy $tenancy, string $period)
    {
        $this->authorizeHouse($tenancy->house);
        $tenancy->load('payments');

        abort_unless($tenancy->schedule()->contains('key', $period), 404);

        RentPayment::firstOrCreate(
            ['tenancy_id' => $tenancy->id, 'period' => Carbon::createFromFormat('Y-m', $period)->startOfMonth()],
            ['amount' => $tenancy->monthly_rent, 'paid_at' => today()]
        );

        return back()->with('success', 'Bayaran bulan ' . Carbon::createFromFormat('Y-m', $period)->format('m/Y') . ' direkodkan.');
    }

    public function unpay(Tenancy $tenancy, string $period)
    {
        $this->authorizeHouse($tenancy->house);

        RentPayment::where('tenancy_id', $tenancy->id)
            ->whereDate('period', Carbon::createFromFormat('Y-m', $period)->startOfMonth())->delete();

        return back()->with('success', 'Rekod bayaran dibatalkan.');
    }

    /* ---------------------------------------------------------------- */

    private function freeHouses()
    {
        $busy = Tenancy::where('status', 'active')->pluck('house_id');

        return House::when(! $this->isAdmin(), fn ($q) => $q->where('landlord_id', auth()->id()))
            ->whereNotIn('id', $busy)->orderBy('title')->get(['id', 'title', 'monthly_rent', 'status']);
    }

    private function validated(Request $request, bool $withHouse): array
    {
        $data = $request->validate(array_filter([
            'house_id' => $withHouse ? ['required', 'exists:houses,id'] : null,
            'tenant_name' => ['required', 'string', 'max:255'],
            'tenant_phone' => ['nullable', 'string', 'max:30'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'monthly_rent' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'deposit' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]), [
            'end_date.after' => 'Tarikh tamat mesti selepas tarikh mula.',
        ]);
        $data['deposit'] = $data['deposit'] ?? 0;

        return $data;
    }
}

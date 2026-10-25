<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Tenancy extends Model
{
    protected $fillable = [
        'house_id', 'tenant_name', 'tenant_phone', 'start_date', 'end_date',
        'monthly_rent', 'deposit', 'notes', 'status', 'ended_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'ended_at' => 'date',
        'monthly_rent' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    public function house()
    {
        return $this->belongsTo(House::class)->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(RentPayment::class);
    }

    /**
     * Jadual bayaran bulanan dari bulan mula hingga bulan semasa (atau tarikh tamat).
     * Tarikh bayaran = hari yang sama dengan tarikh mula sewa (maksimum 28).
     * state: paid | overdue | soon (dalam 7 hari) | upcoming
     */
    public function schedule(): Collection
    {
        $paid = $this->payments->keyBy(fn ($p) => $p->period->format('Y-m'));

        $last = ($this->status === 'ended' && $this->ended_at ? $this->ended_at : now())->copy()->startOfMonth();
        if ($this->end_date && $this->end_date->copy()->startOfMonth()->lt($last)) {
            $last = $this->end_date->copy()->startOfMonth();
        }

        $dueDay = min($this->start_date->day, 28);
        $rows = collect();

        for ($m = $this->start_date->copy()->startOfMonth(); $m->lte($last); $m->addMonth()) {
            $due = $m->copy()->day($dueDay);
            $payment = $paid->get($m->format('Y-m'));
            $state = $payment ? 'paid'
                : ($due->lt(today()) ? 'overdue'
                : ($due->lte(today()->addDays(7)) ? 'soon' : 'upcoming'));

            $rows->push(['key' => $m->format('Y-m'), 'period' => $m->copy(), 'due' => $due, 'payment' => $payment, 'state' => $state]);
        }

        return $rows;
    }

    public function overdueCount(): int
    {
        return $this->status === 'active' ? $this->schedule()->where('state', 'overdue')->count() : 0;
    }

    public function daysLeft(): ?int
    {
        return $this->end_date && $this->status === 'active' ? (int) today()->diffInDays($this->end_date, false) : null;
    }
}

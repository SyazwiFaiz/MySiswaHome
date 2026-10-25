<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
    protected $fillable = ['tenancy_id', 'period', 'amount', 'paid_at', 'note'];

    protected $casts = ['period' => 'date', 'paid_at' => 'date', 'amount' => 'decimal:2'];

    public function tenancy()
    {
        return $this->belongsTo(Tenancy::class);
    }
}

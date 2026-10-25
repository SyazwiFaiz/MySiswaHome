<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    public const STATUSES = ['pending' => 'Menunggu', 'contacted' => 'Telah dihubungi', 'closed' => 'Ditutup'];

    protected $fillable = ['house_id', 'user_id', 'message', 'phone', 'preferred_date', 'status'];

    protected $casts = ['preferred_date' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class)->withTrashed();
    }
}

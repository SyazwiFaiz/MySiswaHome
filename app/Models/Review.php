<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['house_id', 'user_id', 'rating', 'comment', 'landlord_reply', 'replied_at'];

    protected $casts = ['replied_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class)->withTrashed();
    }
}

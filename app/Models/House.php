<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'title',
        'description',
        'address',
        'area',
        'phone',
        'monthly_rent',
        'bedrooms',
        'bathrooms',
        'property_type',
        'furnished',
        'image',
        'status',
    ];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    // favourite relationship
    public function favouritedBy()
    {
        return $this->belongsToMany(
            User::class,
            'favourites',
            'house_id',
            'user_id'
        )->withTimestamps();
    }
}

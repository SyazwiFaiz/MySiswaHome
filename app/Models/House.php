<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class House extends Model
{
    use HasFactory, SoftDeletes;

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
        'latitude',
        'longitude',
    ];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    // favourite relationship (sedia ada, dipakai laman pelajar)
    public function favouritedBy()
    {
        return $this->belongsToMany(
            User::class,
            'favourites',
            'house_id',
            'user_id'
        )->withTimestamps();
    }

    // Dipakai oleh admin dashboard
    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    /**
     * Senarai path gambar (sudah dinormalkan). Menyokong: path tunggal, array,
     * JSON, dan JSON dalam JSON - sama seperti halaman student.
     */
    public function getImagePathsAttribute(): array
    {
        $paths = array_map(function ($path) {
            if (preg_match('#^https?://#i', $path)) {
                return $path;
            }
            $path = ltrim(str_replace('\\', '/', $path), '/');
            $path = preg_replace('#^(app/)?public/#i', '', $path);

            return preg_replace('#^storage/#i', '', $path);
        }, $this->flattenImages($this->image));

        return array_values(array_unique(array_filter($paths)));
    }

    /** URL setiap gambar (laluan sahaja, tanpa domain/port). */
    public function getImageUrlsAttribute(): array
    {
        return array_map(function ($path) {
            if (preg_match('#^https?://#i', $path)) {
                return $path;
            }

            return '/storage/' . implode('/', array_map('rawurlencode', explode('/', $path)));
        }, $this->image_paths);
    }

    /** Gambar pertama (untuk thumbnail). */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_urls[0] ?? null;
    }

    public function getMapUrlAttribute(): ?string
    {
        return $this->latitude && $this->longitude
            ? "https://www.google.com/maps?q={$this->latitude},{$this->longitude}"
            : null;
    }

    private function flattenImages($value, int $depth = 0): array
    {
        if ($depth > 4 || $value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            $out = [];
            foreach ($value as $item) {
                $out = array_merge($out, $this->flattenImages($item, $depth + 1));
            }

            return $out;
        }

        if (! is_string($value)) {
            return [];
        }

        $text = trim($value);
        if ($text === '') {
            return [];
        }

        if (preg_match('/^[\[{"]/', $text)) {
            $decoded = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $this->flattenImages($decoded, $depth + 1);
            }
        }

        return [$text];
    }
}
<?php

namespace App\Support;

use App\Models\House;

class ListingQuality
{
    /** Skor 0-100 dan petua untuk tingkatkan iklan. */
    public static function evaluate(House $h): array
    {
        $score = 0;
        $tips = [];

        $images = count($h->image_paths);
        if ($images >= 3) {
            $score += 25;
        } elseif ($images >= 1) {
            $score += 10;
            $tips[] = 'Tambah sekurang-kurangnya 3 gambar.';
        } else {
            $tips[] = 'Muat naik gambar rumah.';
        }

        $len = mb_strlen(trim((string) $h->description));
        if ($len >= 200) {
            $score += 25;
        } elseif ($len >= 80) {
            $score += 15;
            $tips[] = 'Panjangkan penerangan (kemudahan, jarak ke kampus, syarat sewa).';
        } else {
            $tips[] = 'Tambah penerangan rumah.';
        }

        if ($h->latitude !== null && $h->longitude !== null) {
            $score += 20;
        } else {
            $tips[] = 'Tetapkan koordinat peta supaya rumah muncul dalam carian peta.';
        }

        foreach (['property_type' => 'jenis hartanah', 'furnished' => 'maklumat perabot', 'phone' => 'nombor telefon'] as $field => $label) {
            if (filled($h->{$field})) {
                $score += 10;
            } else {
                $tips[] = "Isi {$label}.";
            }
        }

        return ['score' => $score, 'tips' => $tips];
    }
}

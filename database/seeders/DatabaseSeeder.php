<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\House;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // LANDLORD
        // =========================

        $landlord1 = User::create([
            'name' => 'Ahmad Property',
            'email' => 'ahmad@mysiswahome.com',
            'phone' => '0123456789',
            'avatar' => null,
            'password' => Hash::make('password'),
            'role' => 'landlord',
        ]);

        $landlord2 = User::create([
            'name' => 'Siti Homestay',
            'email' => 'siti@mysiswahome.com',
            'phone' => '0134567890',
            'avatar' => null,
            'password' => Hash::make('password'),
            'role' => 'landlord',
        ]);

        $landlord3 = User::create([
            'name' => 'Maju Property',
            'email' => 'maju@mysiswahome.com',
            'phone' => '0145678901',
            'avatar' => null,
            'password' => Hash::make('password'),
            'role' => 'landlord',
        ]);


        // =========================
        // STUDENT
        // =========================

        $student1 = User::create([
            'name' => 'Ali Ahmad',
            'email' => 'ali@student.com',
            'phone' => '0111111111',
            'avatar' => null,
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $student2 = User::create([
            'name' => 'Aiman Hakim',
            'email' => 'aiman@student.com',
            'phone' => '0122222222',
            'avatar' => null,
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $student3 = User::create([
            'name' => 'Nur Aisyah',
            'email' => 'aisyah@student.com',
            'phone' => '0133333333',
            'avatar' => null,
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $student4 = User::create([
            'name' => 'Farhan Zulkifli',
            'email' => 'farhan@student.com',
            'phone' => '0144444444',
            'avatar' => null,
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $student5 = User::create([
            'name' => 'Siti Nur',
            'email' => 'siti@student.com',
            'phone' => '0155555555',
            'avatar' => null,
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);


        // =========================
        // HOUSES
        // =========================

        $house1 = House::create([
            'landlord_id' => $landlord1->id,
            'title' => 'Bilik Sewa Berdekatan UNISEL',
            'description' => 'Bilik selesa dan sesuai untuk pelajar. Berdekatan dengan kemudahan awam.',
            'address' => 'Bestari Jaya, Selangor',
            'area' => 'Bestari Jaya',
            'phone' => '0123456789',
            'monthly_rent' => 350,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'property_type' => 'bilik',
            'furnished' => 'Fully Furnished',
            'image' => null,
            'status' => 'available',
        ]);

        $house2 = House::create([
            'landlord_id' => $landlord1->id,
            'title' => 'Rumah Teres Untuk Pelajar',
            'description' => 'Rumah teres luas dengan kawasan parking yang besar.',
            'address' => 'Ijok, Selangor',
            'area' => 'Ijok',
            'phone' => '0123456789',
            'monthly_rent' => 900,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'property_type' => 'rumah',
            'furnished' => 'Semi Furnished',
            'image' => null,
            'status' => 'available',
        ]);

        $house3 = House::create([
            'landlord_id' => $landlord1->id,
            'title' => 'Bilik Single Dekat Kampus',
            'description' => 'Bilik single dengan meja belajar dan katil.',
            'address' => 'Saujana Utama, Selangor',
            'area' => 'Saujana Utama',
            'phone' => '0123456789',
            'monthly_rent' => 400,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'property_type' => 'bilik',
            'furnished' => 'Fully Furnished',
            'image' => null,
            'status' => 'rented',
        ]);

        $house4 = House::create([
            'landlord_id' => $landlord2->id,
            'title' => 'Apartment Murah Untuk Student',
            'description' => 'Apartment selesa dengan kemudahan asas.',
            'address' => 'Shah Alam, Selangor',
            'area' => 'Shah Alam',
            'phone' => '0134567890',
            'monthly_rent' => 600,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'property_type' => 'apartment',
            'furnished' => 'Fully Furnished',
            'image' => null,
            'status' => 'available',
        ]);

        $house5 = House::create([
            'landlord_id' => $landlord2->id,
            'title' => 'Studio Apartment Shah Alam',
            'description' => 'Studio moden dan sesuai untuk seorang atau dua orang pelajar.',
            'address' => 'Seksyen 7, Shah Alam',
            'area' => 'Seksyen 7',
            'phone' => '0134567890',
            'monthly_rent' => 700,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'property_type' => 'studio',
            'furnished' => 'Fully Furnished',
            'image' => null,
            'status' => 'available',
        ]);

        $house6 = House::create([
            'landlord_id' => $landlord2->id,
            'title' => 'Bilik Sewa Seksyen 13',
            'description' => 'Bilik sewa dengan akses mudah ke kedai makan dan pengangkutan.',
            'address' => 'Seksyen 13, Shah Alam',
            'area' => 'Seksyen 13',
            'phone' => '0134567890',
            'monthly_rent' => 450,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'property_type' => 'bilik',
            'furnished' => 'Fully Furnished',
            'image' => null,
            'status' => 'available',
        ]);

        $house7 = House::create([
            'landlord_id' => $landlord3->id,
            'title' => 'Rumah Sewa Untuk 4 Pelajar',
            'description' => 'Rumah luas dengan 4 bilik tidur. Sesuai untuk student sharing.',
            'address' => 'Puncak Alam, Selangor',
            'area' => 'Puncak Alam',
            'phone' => '0145678901',
            'monthly_rent' => 1200,
            'bedrooms' => 4,
            'bathrooms' => 2,
            'property_type' => 'rumah',
            'furnished' => 'Semi Furnished',
            'image' => null,
            'status' => 'available',
        ]);

        $house8 = House::create([
            'landlord_id' => $landlord3->id,
            'title' => 'Bilik Bajet Puncak Alam',
            'description' => 'Bilik bajet untuk student dengan kemudahan asas.',
            'address' => 'Puncak Alam, Selangor',
            'area' => 'Puncak Alam',
            'phone' => '0145678901',
            'monthly_rent' => 300,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'property_type' => 'bilik',
            'furnished' => 'Fully Furnished',
            'image' => null,
            'status' => 'available',
        ]);

        $house9 = House::create([
            'landlord_id' => $landlord3->id,
            'title' => 'Apartment Student Friendly',
            'description' => 'Apartment dengan ruang tamu yang luas dan kawasan parking.',
            'address' => 'Klang, Selangor',
            'area' => 'Klang',
            'phone' => '0145678901',
            'monthly_rent' => 800,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'property_type' => 'apartment',
            'furnished' => 'Semi Furnished',
            'image' => null,
            'status' => 'rented',
        ]);

        $house10 = House::create([
            'landlord_id' => $landlord1->id,
            'title' => 'Bilik Selesa Dekat Pengangkutan Awam',
            'description' => 'Bilik sesuai untuk student yang memerlukan akses pengangkutan awam.',
            'address' => 'Kuala Selangor, Selangor',
            'area' => 'Kuala Selangor',
            'phone' => '0123456789',
            'monthly_rent' => 500,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'property_type' => 'bilik',
            'furnished' => 'Fully Furnished',
            'image' => null,
            'status' => 'available',
        ]);


        // =========================
        // FAVOURITES
        // =========================

        $student1->favourites()->attach([
            $house1->id,
            $house4->id,
            $house7->id,
        ]);

        $student2->favourites()->attach([
            $house2->id,
            $house5->id,
        ]);

        $student3->favourites()->attach([
            $house1->id,
            $house6->id,
            $house8->id,
        ]);

        $student4->favourites()->attach([
            $house4->id,
            $house10->id,
        ]);

        $student5->favourites()->attach([
            $house5->id,
            $house7->id,
        ]);
    }
}


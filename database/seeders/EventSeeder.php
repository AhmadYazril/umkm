<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title'       => 'Grand Opening Nucomu Cafe',
                'description' => 'Rayakan bersama kami pembukaan resmi Nucomu Cafe! Dapatkan promo spesial dan berbagai diskon menarik di hari pertama kami hadir untuk kalian.',
                'date'        => now()->addDays(7),
                'image'       => null,
                'is_active'   => true,
            ],
            [
                'title'       => 'Nucomu Dessert Weekend',
                'description' => 'Setiap akhir pekan, nikmati promo spesial dessert pilihan dengan harga lebih terjangkau. Ajak teman-teman dan rasakan kelezatan bersama!',
                'date'        => now()->addDays(14),
                'image'       => null,
                'is_active'   => true,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                ['title' => $event['title']],
                $event
            );
        }
    }
}

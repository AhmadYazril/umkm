<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'cafe_name' => 'Nucomu Cafe',
            'cafe_tagline' => 'New, Unforgettable, Comfy, Musings',
            'cafe_subtitle' => 'Coffee & Dessert',
            'cafe_story' => 'Nucomu Cafe adalah tempat nongkrong yang nyaman di Tulungagung, Jawa Timur. Suasananya santai dan cocok untuk bersantai sendirian, ngobrol bareng teman, mengerjakan tugas, atau berkumpul bersama keluarga.',
            'cafe_story_p1' => 'Nucomu Cafe adalah tempat nongkrong yang nyaman di Tulungagung, Jawa Timur. Suasananya santai dan cocok untuk bersantai sendirian, ngobrol bareng teman, mengerjakan tugas, atau berkumpul bersama keluarga.',
            'cafe_story_p2' => 'Kafe ini menyajikan kopi, minuman non-kopi, makanan ringan, dan makanan berat dengan harga yang bersahabat. Tersedia area indoor/outdoor, Wi-Fi, colokan listrik, area parkir untuk menunjang kenyamanan pengunjung.',
            'cafe_story_p3' => 'Dengan suasana yang hangat dan pelayanan yang ramah, Nucomu Cafe menjadi pilihan menarik untuk menghabiskan waktu, baik di siang hari maupun malam hari.',
            'cafe_history' => 'Nucomu lahir dari "rindu rasa" pelanggan setia yang dulu mengenal kami sebagai Banoffea, bisnis dessert kecil dengan sistem pre-order sejak 2020. Setelah sempat berhenti di 2024–2025, kami kembali hadir dengan wajah baru — Nucomu Cafe — agar pelanggan lama dan baru bisa datang langsung menikmati dessert otentik kami dengan suasana yang nyaman.',
            'cafe_concept' => 'Monokrom minimalis — putih, hitam, abu-abu. Elegan, bersih, nyaman. Dirancang agar setiap pengunjung merasa betah untuk berlama-lama, entah untuk bekerja, bersantai, atau sekadar menikmati momen.',
            'cafe_address' => 'Jl. Mayor Sujadi, Manggisan, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221',
            'cafe_landmark' => '200 meter ke timur dari Bravo Swalayan (sisi utara jalan sebelum perempatan).',
            'cafe_maps_url' => 'https://www.google.com/maps/place/Nucomu+Cafe/@-8.0765095,111.9002579,15z/data=!4m8!3m7!1s0x2e78e3a9244a4fe7:0x516fcae98c42d18!8m2!3d-8.0765099!4d111.918712!9m1!1b1!16s%2Fg%2F11ycy6lc94?entry=ttu&g_ep=EgoyMDI2MTAyMC4wIKXMDSoASAFQAw%3D%3D',
            'cafe_whatsapp' => '6285934960910',
            'zendo_whatsapp' => '6285934960910',
            'cafe_opening_hours' => 'Senin – Sabtu: 11:00 – 21:00 WIB | Minggu: 11:00 – 22:00 WIB',
            'cafe_email' => 'hello@nucomu.com',
            'cafe_instagram' => 'https://www.instagram.com/nucomu.cafe',
            'cafe_tiktok' => 'https://www.tiktok.com/@nucomu.cafe',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::firstOrCreate(
            ['key' => Setting::THEME_KEY],
            ['value' => Setting::THEME_LIGHT]
        );
    }
}

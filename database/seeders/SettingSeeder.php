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
            'cafe_story' => 'Nucomu Cafe hadir sebagai tempat yang nyaman untuk menikmati dessert, makanan, snack, kopi, dan minuman lainnya. Dengan konsep monokrom elegan — perpaduan hitam, putih, dan abu-abu — kami ingin setiap sudut cafe terasa seperti rumah kedua. Banyak dipilih pelanggan sebagai tempat favorit untuk WFC (Work From Cafe).',
            'cafe_history' => 'Nucomu lahir dari "rindu rasa" pelanggan setia yang dulu mengenal kami sebagai Banoffea, bisnis dessert kecil dengan sistem pre-order sejak 2020. Setelah sempat berhenti di 2024–2025, kami kembali hadir dengan wajah baru — Nucomu Cafe — agar pelanggan lama dan baru bisa datang langsung menikmati dessert otentik kami dengan suasana yang nyaman.',
            'cafe_concept' => 'Monokrom minimalis — putih, hitam, abu-abu. Elegan, bersih, nyaman. Dirancang agar setiap pengunjung merasa betah untuk berlama-lama, entah untuk bekerja, bersantai, atau sekadar menikmati momen.',
            'cafe_address' => 'Jl. Mayor Sujadi, Manggisan, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221',
            'cafe_landmark' => '200 meter ke timur dari Bravo Swalayan (sisi utara jalan sebelum perempatan).',
            'cafe_maps_url' => 'https://www.google.com/maps/place/Nucomu+Cafe/@-8.0765095,111.9002579,15z/data=!4m8!3m7!1s0x2e78e3a9244a4fe7:0x516fcae98c42d18!8m2!3d-8.0765099!4d111.918712!9m1!1b1!16s%2Fg%2F11ycy6lc94?entry=ttu&g_ep=EgoyMDI2MTAyMC4wIKXMDSoASAFQAw%3D%3D',
            'cafe_whatsapp' => '+62 859-3496-0910',
            'cafe_email' => 'hello@nucomu.com',
            'cafe_instagram' => 'https://www.instagram.com/nucomu.cafe',
            'cafe_tiktok' => 'https://www.tiktok.com/@nucomu.cafe',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

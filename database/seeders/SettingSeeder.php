<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'cafe_name'     => 'Nucomu Cafe',
            'cafe_tagline'  => 'New, Unforgettable, Comfy, Musings',
            'cafe_subtitle' => 'Coffee & Dessert',
            'cafe_story'    => 'Nucomu Cafe hadir sebagai tempat yang nyaman untuk menikmati dessert, makanan, snack, kopi, dan minuman lainnya. Dengan konsep monokrom elegan — perpaduan hitam, putih, dan abu-abu — kami ingin setiap sudut cafe terasa seperti rumah kedua. Banyak dipilih pelanggan sebagai tempat favorit untuk WFC (Work From Cafe).',
            'cafe_history'  => 'Nucomu lahir dari "rindu rasa" pelanggan setia yang dulu mengenal kami sebagai Banoffea, bisnis dessert kecil dengan sistem pre-order sejak 2020. Setelah sempat berhenti di 2024–2025, kami kembali hadir dengan wajah baru — Nucomu Cafe — agar pelanggan lama dan baru bisa datang langsung menikmati dessert otentik kami dengan suasana yang nyaman.',
            'cafe_concept'  => 'Monokrom minimalis — putih, hitam, abu-abu. Elegan, bersih, nyaman. Dirancang agar setiap pengunjung merasa betah untuk berlama-lama, entah untuk bekerja, bersantai, atau sekadar menikmati momen.',
            'cafe_address'  => '[ISI: alamat lengkap Nucomu Cafe, Tulungagung]',
            'cafe_maps_url' => '[ISI: link Google Maps]',
            'cafe_whatsapp' => '[ISI: nomor WhatsApp, contoh: 6281234567890]',
            'cafe_email'    => '[ISI: email cafe]',
            'cafe_instagram'=> '[ISI: link Instagram]',
            'cafe_tiktok'   => '[ISI: link TikTok]',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

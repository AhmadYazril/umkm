<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'slug' => 'indoor',
                'tab_name' => 'INDOOR',
                'title' => 'Indoor Lounge Work Space',
                'label' => 'Indoor Lounge Work Space',
                'badge' => '01 / WORKSPACE & LOUNGE',
                'subtitle' => 'Workspace & Lounge',
                'icon' => 'laptop',
                'description' => 'Area dalam ruangan ber-AC yang tenang dengan meja ergonomis, stopkontak melimpah di setiap sudut, serta koneksi internet cepat. Ideal untuk WFC (Work From Cafe) maupun rapat santai.',
                'features' => [
                    'Suhu ruangan sejuk & sirkulasi udara bersih',
                    'Pencahayaan hangat bebas silau untuk kenyamanan mata',
                    'Stopkontak di setiap meja & Wi-Fi berkecepatan tinggi',
                ],
                'photos' => [
                    [
                        'image' => 'images/nucomu/nucomu_story_2.png',
                        'caption' => 'Meja Ergonomis & Suasana Asri',
                        'alt' => 'Indoor Lounge Nucomu Cafe — Meja Ergonomis & Monstera',
                    ],
                    [
                        'image' => 'images/nucomu/nucomu_story_1.png',
                        'caption' => 'Espresso Bar & Counter Seduh',
                        'alt' => 'Espresso Bar Nucomu Cafe — Counter Seduh & Mesin Kopi',
                    ],
                    [
                        'image' => 'images/nucomu/nucomu_story_4.png',
                        'caption' => 'Sudut Apparel & Cermin Estetis',
                        'alt' => 'Sudut Apparel Nucomu Cafe — Merchandise & Cermin Estetis',
                    ],
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'slug' => 'outdoor',
                'tab_name' => 'OUTDOOR',
                'title' => 'Outdoor Garden Terrace',
                'label' => 'Outdoor Garden Terrace',
                'badge' => '02 / OUTDOOR TERRACE',
                'subtitle' => 'Garden Terrace',
                'icon' => 'coffee',
                'description' => 'Taman terbuka bersuasana asri dengan naungan pepohonan rindang dan pencahayaan malam yang temaram romantis. Tempat favorit untuk menikmati senja, angin sepoi-sepoi, dan obrolan santai tanpa sekat kaku.',
                'features' => [
                    'Smoking area yang lega dan asri',
                    'Suasana hijau teduh dengan bangku taman nyaman',
                    'Atmosfer santai untuk bercengkerama sore hingga malam',
                ],
                'photos' => [
                    [
                        'image' => 'images/nucomu/nucomu_story_3.png',
                        'caption' => 'Bangku Kayu & Lampu Temaram',
                        'alt' => 'Outdoor Garden Terrace Nucomu Cafe — Kursi Kayu Alami & Lampu Hangat',
                    ],
                    [
                        'image' => 'images/nucomu/nucomu_outdoor_2.png',
                        'caption' => 'Area Terbuka Hijau & Sejuk',
                        'alt' => 'Outdoor Garden Nucomu Cafe — Area Terbuka Hijau & Sejuk',
                    ],
                    [
                        'image' => 'images/nucomu/nucomu_outdoor_3.png',
                        'caption' => 'Sudut Santai Sore Hari',
                        'alt' => 'Outdoor Nucomu Cafe — Sudut Santai Sore Hari',
                    ],
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'slug' => 'musholla',
                'tab_name' => 'MUSHOLLA',
                'title' => 'Musala Bersih & Terpisah',
                'label' => 'Musala Bersih & Terpisah',
                'badge' => '03 / KENYAMANAN IBADAH',
                'subtitle' => 'Kenyamanan Ibadah',
                'icon' => 'moon',
                'description' => 'Fasilitas musala khusus lengkap dengan sarana wudhu yang selalu terjaga kebersihannya, sajadah, mukena wangi, serta pendingin udara untuk kenyamanan dan ketenangan ibadah Anda.',
                'features' => [
                    'Sarana Wudhu Bersih',
                    'Mukena & Sajadah',
                    'Sejuk & Tenang',
                ],
                'photos' => [
                    [
                        'image' => 'images/nucomu/nucomu_story_1.png',
                        'caption' => 'Area Ibadah Bersih & Tenang',
                        'alt' => 'Mushola Nucomu Cafe — Bersih & Tenang',
                    ],
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Facility::updateOrCreate(
                ['slug' => $slide['slug']],
                $slide
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['caption' => 'Interior Nucomu Cafe — suasana monokrom elegan',   'category' => 'ambience', 'image' => 'images/nucomu/nucomu_story_2.png',     'sort_order' => 1],
            ['caption' => 'Area duduk nyaman dengan banyak colokan untuk WFC', 'category' => 'ambience', 'image' => 'images/nucomu/nucomu_story_4.png',     'sort_order' => 2],
            ['caption' => 'Banoffee — dessert ikonik Nucomu',                  'category' => 'menu',     'image' => 'images/nucomu/hero_flatlay_clean.jpg', 'sort_order' => 3],
            ['caption' => 'Cheesecake creamy favorit pelanggan',               'category' => 'menu',     'image' => 'images/nucomu/nucomu_story_1.png',     'sort_order' => 4],
            ['caption' => 'Nucomu Coffee — signature blend spesial',           'category' => 'menu',     'image' => 'images/nucomu/latte_grabfood.jpg',     'sort_order' => 5],
            ['caption' => 'Area outdoor teduh untuk bersantai sore',           'category' => 'ambience', 'image' => 'images/nucomu/nucomu_outdoor_2.png',   'sort_order' => 6],
            ['caption' => 'Sudut foto estetik di Nucomu Cafe',                 'category' => 'ambience', 'image' => 'images/nucomu/nucomu_story_3.png',     'sort_order' => 7],
            ['caption' => 'Suasana malam hangat di taman Nucomu',              'category' => 'ambience', 'image' => 'images/nucomu/nucomu_outdoor_3.png',   'sort_order' => 8],
        ];

        foreach ($items as $item) {
            GalleryItem::updateOrCreate(
                ['caption' => $item['caption']],
                [
                    'image' => $item['image'],
                    'category' => $item['category'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['caption' => 'Interior Nucomu Cafe — suasana monokrom elegan',   'category' => 'ambience', 'sort_order' => 1],
            ['caption' => 'Area duduk nyaman dengan banyak colokan untuk WFC', 'category' => 'ambience', 'sort_order' => 2],
            ['caption' => 'Banoffee — dessert ikonik Nucomu',                  'category' => 'menu',     'sort_order' => 3],
            ['caption' => 'Cheesecake creamy favorit pelanggan',               'category' => 'menu',     'sort_order' => 4],
            ['caption' => 'Nucomu Coffee — signature blend spesial',           'category' => 'menu',     'sort_order' => 5],
            ['caption' => 'Donut Choco Pistachio yang menggoda',               'category' => 'menu',     'sort_order' => 6],
            ['caption' => 'Sudut foto estetik di Nucomu Cafe',                 'category' => 'ambience', 'sort_order' => 7],
            ['caption' => 'Nasi Cakalang Suwir — makanan berat pilihan',       'category' => 'menu',     'sort_order' => 8],
        ];

        foreach ($items as $item) {
            GalleryItem::updateOrCreate(
                ['caption' => $item['caption']],
                [
                    'image'      => 'images/placeholder/gallery-' . $item['sort_order'] . '.jpg',
                    'category'   => $item['category'],
                    'sort_order' => $item['sort_order'],
                ]
            );
        }
    }
}

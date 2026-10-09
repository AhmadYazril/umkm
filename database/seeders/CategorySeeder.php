<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Dessert',   'slug' => 'dessert',   'sort_order' => 1],
            ['name' => 'Makanan',   'slug' => 'makanan',   'sort_order' => 2],
            ['name' => 'Snack',     'slug' => 'snack',     'sort_order' => 3],
            ['name' => 'Kopi',      'slug' => 'kopi',      'sort_order' => 4],
            ['name' => 'Non-Kopi',  'slug' => 'non-kopi',  'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'sort_order' => $cat['sort_order'], 'is_active' => true]
            );
        }
    }
}
